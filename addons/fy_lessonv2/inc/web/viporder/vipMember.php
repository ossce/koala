<?php

$vip_level = pdo_getall($this->table_vip_level, array('uniacid'=>$uniacid));
foreach($vip_level as $item){
	$vipLevelList[$item['id']] = $item['level_name'];
}

$keyword  = trim($_GPC['keyword']);
$level_id = $_GPC['level_id'];
$status   = $_GPC['status'];

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['keyword'])) {
	$condition .= " AND ((b.nickname LIKE :keyword1) OR (b.realname LIKE :keyword1) OR (b.mobile=:keyword2) OR (b.uid=:keyword2)) ";
	$params[':keyword1'] = "%{$_GPC['keyword']}%";
	$params[':keyword2'] = $_GPC['keyword'];
}
if ($_GPC['level_id']) {
	$condition .= " AND a.level_id=:level_id ";
	$params[':level_id'] = $_GPC['level_id'];
}
if ($_GPC['status']!='') {
	if($_GPC['status']==1){
		$condition .= " AND a.validity >= :validity ";
	}
	if($_GPC['status']==-1){
		$condition .= " AND a.validity < :validity ";
	}
	$params[':validity'] = time();
}

if($_GPC['overdue']){
	$condition .= " AND a.validity>:nowtime AND a.validity<:validity ";
	$params[':nowtime'] = time();
	$params[':validity'] = time() + $_GPC['overdue']*86400;
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.id,a.uid,a.level_id,a.discount,a.validity,b.nickname,b.realname,b.mobile,b.uid AS mc_uid FROM " .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.uid desc, a.validity DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	
	$list  = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.uid desc, a.validity DESC ", $params);
	
	foreach ($list as $key => $value) {
		$arr[$key]['uid']		  = $value['uid'];
		$arr[$key]['nickname']    = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']    = $value['realname'];
		$arr[$key]['mobile']      = $value['mobile'];
		$arr[$key]['level_name']  = $vipLevelList[$value['level_id']];
		$arr[$key]['discount']    = $value['discount'];
		$arr[$key]['validity']	  = date('Y-m-d H:i', $value['validity']);
		if($value['validity'] >= time()){
			$arr[$key]['status']  = "生效中";
		}else{
			$arr[$key]['status']  = "已过期";
		}
		$arr[$key]['addtime']     = date('Y-m-d H:i', $value['addtime']);
		$arr[$key]['update_time'] = $value['update_time'] ? date('Y-m-d H:i', $value['update_time']) : '无';
	}

	$title = array('会员ID', '昵称', '姓名', '手机号码', '等级名称', '折扣(%)', '有效期', '状态', '首次开通时间', '上次续费时间');
	$site_common->exportCSV($arr, $title, $fileName="VIP会员");

}