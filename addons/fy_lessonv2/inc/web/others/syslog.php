<?php

/* 所有操作员 */
$admin_list = pdo_fetchall("SELECT uid,username FROM " .tablename($this->table_users));
$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$condition = " uniacid=:uniacid ";
$params = array(':uniacid' => $uniacid);
if($_W['role']!='founder'){
	$condition .= " AND admin_uid=:admin_uid ";
	$params[':admin_uid'] = $_W['uid'];
}
if(!empty($_GPC['function'])){
	$condition .= " AND function LIKE :function ";
	$params[':function'] = "%".$_GPC['function']."%";
}
if($_GPC['log_type']>0){
	$condition .= " AND log_type=:log_type ";
	$params[':log_type'] = $_GPC['log_type'];
}
if($_W['role']=='founder' && $_GPC['admin_uid']>0){
	$condition .= " AND admin_uid=:admin_uid ";
	$params[':admin_uid'] = $_GPC['admin_uid'];
}

if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
}elseif(!$_GPC['search']){
	$starttime = strtotime('-1 month');
	$endtime = strtotime('today') + 86399;
}
if($starttime && $endtime){
	$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}


$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_syslog). " WHERE {$condition} ORDER BY addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_syslog). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);

?>