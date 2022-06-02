<?php

$today = strtotime("today");
	
$exit = pdo_fetch("SELECT * FROM " .tablename($this->table_static). " WHERE uniacid=:uniacid AND static_time=:static_time", array(':uniacid'=>$uniacid,':static_time'=>$today));
$yestoday = pdo_fetch("SELECT * FROM " .tablename($this->table_static). " WHERE uniacid=:uniacid AND static_time=:static_time", array(':uniacid'=>$uniacid,':static_time'=>$today-86400));

$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_static). " WHERE uniacid=:uniacid AND static_time>=:starttime AND static_time<=:endtime ORDER BY static_time ASC", array(':uniacid'=>$uniacid, ':starttime'=>$starttime, ':endtime'=>$endtime));

$day = $amount = array();
if (!empty($list)) {
	$incomeTotal = 0;
	foreach ($list as $row) {
		$day[] = date('m-d', $row['static_time']);
		$lessonOrder_amount[] = intval($row['lessonOrder_amount']);
		$vipOrder_amount[] = intval($row['vipOrder_amount']);
		$teacherOrder_amount[] = intval($row['teacherOrder_amount']);
		$incomeTotal += intval($row['lessonOrder_amount']) + intval($row['vipOrder_amount']) + intval($row['teacherOrder_amount']);
	}
}

?>