<?php

$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['ordersn'])) {
	$condition .= " AND a.ordersn = :ordersn ";
	$params[':ordersn'] = trim($_GPC['ordersn']);
}
if ($_GPC['status']!='') {
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}
if (!empty($_GPC['paytype'])) {
	$condition .= " AND a.paytype = :paytype ";
	$params[':paytype'] = $_GPC['paytype'];
}
if (!empty($_GPC['nickname'])) {
	$condition .= " AND b.nickname LIKE :nickname ";
	$params[':nickname'] = "%{$_GPC['nickname']}%";
}

$timetype = intval($_GPC['timetype']) ? intval($_GPC['timetype']) : 1;
if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
}elseif(!$_GPC['search']){
	$starttime = strtotime('-1 month');
	$endtime = strtotime('today') + 86399;
}
if($starttime && $endtime){
	if($timetype==1){
		$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	}elseif($timetype==2){
		$condition .= " AND a.paytime>=:starttime AND a.paytime<=:endtime";
	}
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC", $params);

	foreach($list as $key=>$value){			
		$arr[$key]['ordersn']         = $value['ordersn'];
		$arr[$key]['uid']			  = $value['uid'];
		$arr[$key]['nickname']		  = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']        = $value['realname'];
		$arr[$key]['mobile']          = $value['mobile'];
		$arr[$key]['ordertime']         = "[".$value['teacher_name'] ."]????????????-". $value['ordertime']."???";
		$arr[$key]['vipmoney']        = $value['price'];
		$arr[$key]['member1']		  = $value['member1'];
		$arr[$key]['commission1']     = $value['commission1'];
		$arr[$key]['member2']		  = $value['member2'];
		$arr[$key]['commission2']     = $value['commission2'];
		$arr[$key]['member3']		  = $value['member3'];
		$arr[$key]['commission3']     = $value['commission3'];
		if($value['status'] == '0'){
			$arr[$key]['status'] = "?????????";
		}elseif($value['status'] == '1'){
			$arr[$key]['status'] = "?????????";
		}
		if($value['paytype'] == 'credit'){
			$arr[$key]['paytype'] = "????????????";
		}elseif($value['paytype'] == 'wechat'){
			$arr[$key]['paytype'] = "????????????";
		}elseif($value['paytype'] == 'alipay'){
			$arr[$key]['paytype'] = "???????????????";
		}elseif($value['paytype'] == 'offline'){
			$arr[$key]['paytype'] = "????????????";
		}elseif($value['paytype'] == 'admin'){
			$arr[$key]['paytype'] = "????????????";
		}elseif($value['paytype'] == 'vipcard'){
			$arr[$key]['paytype'] = "???????????????";
		}elseif($value['paytype'] == 'wxapp'){
			$arr[$key]['paytype'] = "???????????????";
		}else{
			$arr[$key]['paytype'] = "???";
		}
		$arr[$key]['paytime']	 = $value['paytime'] ? date('Y-m-d H:i:s', $value['paytime']) : '';
		$arr[$key]['addtime']	 = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('????????????', '??????uid', '??????', '??????', '????????????', '????????????', '????????????(???)', '???????????????(uid)', '????????????(???)', '???????????????(uid)', '????????????(???)', '???????????????(uid)', '????????????(???)', '????????????', '????????????', '????????????', '????????????');
	$site_common->exportCSV($arr, $title, $fileName="??????????????????");
}