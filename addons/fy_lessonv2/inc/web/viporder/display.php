<?php

/* VIP等级列表 */
$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort", array(':uniacid'=>$uniacid));
$level_name_list = array();
foreach($level_list as $item){
	$level_name_list[$item['id']] = $item['level_name'];
}

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['ordersn'])) {
	$condition .= " AND a.ordersn = :ordersn ";
	$params[':ordersn'] = trim($_GPC['ordersn']);
}
if (!empty($_GPC['nickname'])) {
	$condition .= " AND ((b.nickname LIKE :nickname1) OR (b.realname LIKE :nickname1) OR (b.mobile = :nickname2)) ";
	$params[':nickname1'] = "%".trim($_GPC['nickname'])."%";
	$params[':nickname2'] = trim($_GPC['nickname']);
}
if ($_GPC['status']!='') {
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}
if (!empty($_GPC['paytype'])) {
	$condition .= " AND a.paytype = :paytype ";
	$params[':paytype'] = $_GPC['paytype'];
}
if ($_GPC['level_id']) {
	$condition .= " AND a.level_id=:level_id ";
	$params[':level_id'] = $_GPC['level_id'];
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

$statis = pdo_fetchall('SELECT COUNT(*) AS total, SUM(vipmoney) AS vipmoney FROM ' .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
$total = $statis[0]['total'];

if(!$_GPC['export']){

	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		$list[$k]['level'] = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$v['level_id']));
	}
	
	$pager = pagination($total, $pindex, $psize);
}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC", $params);

	foreach($list as $key=>$value){			
		$arr[$key]['ordersn']         = $value['ordersn'];
		$arr[$key]['uid']			  = $value['uid'];
		$arr[$key]['nickname']		  = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']        = $value['realname'];
		$arr[$key]['mobile']          = $value['mobile'];
		$arr[$key]['viptime']         = $level_name_list[$value['level_id']] ."-". $value['viptime']."天";
		$arr[$key]['vipmoney']        = $value['vipmoney'];
		$arr[$key]['member1']		  = $value['member1'];
		$arr[$key]['commission1']     = $value['commission1'];
		$arr[$key]['member2']		  = $value['member2'];
		$arr[$key]['commission2']     = $value['commission2'];
		$arr[$key]['member3']		  = $value['member3'];
		$arr[$key]['commission3']     = $value['commission3'];
		$arr[$key]['status']		  = $orderStatus[$value['status']];
		$arr[$key]['paytype']		  = $orderPayType[$value['paytype']];
		$arr[$key]['paytime']		  = $value['paytime'] ? date('Y-m-d H:i:s', $value['paytime']) : '';
		$arr[$key]['addtime']		  = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('订单编号', '用户uid', '昵称', '姓名', '手机号码', '服务时长', '服务价格(元)', '一级推荐人(uid)', '一级佣金(元)', '二级推荐人(uid)', '二级佣金(元)', '三级推荐人(uid)', '三级佣金(元)', '订单状态', '付款方式', '付款时间', '下单时间');
	$site_common->exportCSV($arr, $title, $fileName="VIP订单");
}