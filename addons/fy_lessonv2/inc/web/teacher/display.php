<?php
 
$teacher = $_GPC['teacher'];
$is_recommend  = $_GPC['is_recommend'];
$status  = $_GPC['status'];
$teachertype  = $_GPC['teachertype'];
$is_distribution = $_GPC['is_distribution'];

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if(!empty($teacher)){
	$condition .= " AND teacher LIKE :teacher ";
	$params[':teacher'] = "%".$teacher."%";
}
if($is_recommend !=''){
	$condition .= " AND is_recommend = :is_recommend ";
	$params[':is_recommend'] = $is_recommend;
}
if($status != ''){
	$condition .= " AND status = :status ";
	$params[':status'] = $status;
}
if($teachertype==1){
	$condition .= " AND uid = :uid ";
	$params[':uid'] = 0;
}elseif($teachertype==2){
	$condition .= " AND uid != :uid";
	$params[':uid'] = 0;
}
if($is_distribution != ''){
	$condition .= " AND is_distribution = :is_distribution ";
	$params[':is_distribution'] = $is_distribution;
}


$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_teacher) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach($list as $key=>$value){
	$list[$key]['member'] = pdo_fetch("SELECT nopay_lesson,pay_lesson FROM " .tablename($this->table_member). " WHERE uid=:uid", array(':uid'=>$value['uid']));
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher) . " WHERE {$condition} ", $params);
$pager = pagination($total, $pindex, $psize);