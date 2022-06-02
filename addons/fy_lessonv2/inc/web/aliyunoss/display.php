<?php


$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid AND teacherid=:teacherid ";
$params[':uniacid'] = $uniacid;
$params[':teacherid'] = intval($_GPC['teacherid']);
if(!empty($_GPC['keyword'])){
	$condition .= " AND name LIKE :name ";
	$params[':name'] = "%".trim($_GPC['keyword'])."%";
}
if($_GPC['pid']){
	$condition .= " AND pid=:pid ";
	$params[':pid'] = $_GPC['pid'];

	if($_GPC['cid']){
		$condition .= " AND cid=:cid ";
		$params[':cid'] = $_GPC['cid'];

		if($_GPC['ccid']){
			$condition .= " AND ccid=:ccid ";
			$params[':ccid'] = $_GPC['ccid'];
		}
	}
}
if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_aliyunoss_upload). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach($list as $k=>$v){
	if($v['pid']){
		$p_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['pid'],'teacherid'=>0), array('name'));
		$v['pname'] = $p_category['name'];
	}
	if($v['cid']){
		$c_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['cid'],'teacherid'=>0), array('name'));
		$v['cname'] = $c_category['name'];
	}
	if($v['ccid']){
		$cc_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['ccid'],'teacherid'=>0), array('name'));
		$v['ccname'] = $cc_category['name'];
	}
	$list[$k] = $v;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_aliyunoss_upload). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);