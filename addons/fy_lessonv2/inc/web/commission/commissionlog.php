<?php

$nickname = trim($_GPC['nickname']);
$bookname = trim($_GPC['bookname']);
$grade	  = intval($_GPC['grade']);
$remark	  = trim($_GPC['remark']);

$condition = " a.uniacid='{$uniacid}' ";
if(!empty($nickname)){
	$condition .= " AND b.nickname LIKE :nickname ";
	$params[':nickname'] = "%".$nickname."%";
}

if(!empty($bookname)){
	$condition .= " AND a.bookname LIKE :bookname ";
	$params[':bookname'] = "%".$bookname."%";
}
if(!empty($grade)){
	$condition .= " AND a.grade = :grade ";
	$params[':grade'] = $grade;
}
if(!empty($remark)){
	$condition .= " AND a.remark LIKE :remark ";
	$params[':remark'] = '%'.$remark.'%';
}
if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
}elseif(!$_GPC['search']){
	$starttime = strtotime('-1 month');
	$endtime = strtotime('today') + 86399;
}
if($starttime && $endtime){
	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_commission_log). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ", $params);


if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " . tablename($this->table_commission_log) . " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);

	$change_total_num = pdo_fetchcolumn("SELECT SUM(change_num) FROM " . tablename($this->table_commission_log) . " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_commission_log). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC", $params);

	foreach($list as $key=>$value){
		$arr[$key]['id']			 = $value['id'];
		$arr[$key]['uid']			 = $value['uid'];
		$arr[$key]['nickname']       = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']		 = $value['realname'];
		$arr[$key]['mobile']		 = $value['mobile'];
		$arr[$key]['bookname']       = $value['bookname'];
		$arr[$key]['grade']			 = '';
		if($value['grade'] == '1'){
			$arr[$key]['grade'] = '一级分销';
		}elseif($value['grade'] == '2'){
			$arr[$key]['grade'] = '二级分销';
		}elseif($value['grade'] == '3'){
			$arr[$key]['grade'] = '三级分销';
		}
		$arr[$key]['change_num']	= $value['change_num'];
		$arr[$key]['remark']		= is_numeric($value['remark']) ? "'".$value['remark'] : $value['remark'];
		$arr[$key]['buyer_uid']		= $value['buyer_uid'];
		$arr[$key]['addtime']		= date('Y-m-d H:i:s',$value['addtime']);
	}


	$title = array('序号', '用户uid', '昵称', '姓名', '手机号码', '分销内容', '分销等级', '分销佣金(元)', '备注', '购买者uid', '分销时间');
	$site_common->exportCSV($arr, $title, $fileName="分销佣金明细");
}

?>