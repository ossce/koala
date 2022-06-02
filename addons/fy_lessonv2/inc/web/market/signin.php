<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if(intval($_GPC['uid'])) {
	$condition .= " AND a.uid = :uid ";
	$params[':uid'] = intval($_GPC['uid']);
}

if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$condition .= " AND a.sign_date>=:starttime AND a.sign_date<=:endtime";
	$params[':starttime'] = strtotime($_GPC['time']['start']);
	$params[':endtime'] = strtotime($_GPC['time']['end']) + 86399;
}


$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_signin). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_signin). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	
	$pager = pagination($total, $pindex, $psize);
}else{
	set_time_limit(0);
	
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_signin). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC", $params);
	foreach ($list as $key => $value) {
		$arr[$key]['uid']		= $value['uid'];
		$arr[$key]['nickname']	= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']	= $value['realname'];
		$arr[$key]['mobile']	= $value['mobile'];
		$arr[$key]['award']		= $value['award'];
		$arr[$key]['timer']		= $value['timer'];
		$arr[$key]['sign_date']	= $value['sign_date'];
	}

	$title = array('用户uid', '昵称', '姓名', '手机号码', '签到奖励(积分)', '连续签到(天)', '签到日期');
	$site_common->exportCSV($arr, $title, $fileName="积分签到明细");
}