<?php

include_once dirname(__FILE__).'/../../core/Payresult.php';
$pay_result = new Payresult();

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " .tablename($this->table_member_order). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$orderid));

if (!$order) {
	message('该订单不存在或已被删除!');
}

$lessonmember = pdo_fetch("SELECT * FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$order['uid']));

$data = array(
	'status'   => 1,
	'paytime'  => time(),
	'paytype'  => 'offline',
);

if(pdo_update($this->table_member_order, $data, array('id'=>$orderid))){
	/* 更新用户VIP有效期 */
	$pay_result->updateVipValidity($uniacid, $lessonmember, $order);

	/* 订单金额加入今日销售额汇总表 */
	$pay_result->staticAmount($uniacid, 1, $order['vipmoney']);

	/* 判断分销员状态变化 */
	$pay_result->checkAgentStatus($lessonmember, $comsetting, $order['vipmoney']);

	/* 一级佣金 */
	if ($order['member1'] > 0 && $order['commission1'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member1'], $lessonmember, 1, $setting, $order, $order['commission1'], $level=1, $comsetting);
	}
	
	/* 二级佣金 */
	if ($order['member2'] > 0 && $order['commission2'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member2'], $lessonmember, 1, $setting, $order, $order['commission2'], $level=2, $comsetting);
	}

	/* 三级佣金 */
	if ($order['member3'] > 0 && $order['commission3'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member3'], $lessonmember, 1, $setting, $order, $order['commission3'], $level=3, $comsetting);
	}

	/* 购买成功模版消息通知用户 */
	$pay_result->sendMessageToUser($uniacid, $setting, $order, 1, $validity);

	/* 新VIP订单提醒(管理员) */
	$pay_result->sendOrderMessageToAdmin($setting, $order, 1);

	/* 更新会员vip字段 */
	$pay_result->updateMemberVip($order['uid'], $vip=1);

	/* 赠送积分操作 */
	if ($order['integral'] > 0) {
		$pay_result->handleUserIntegral($type=1, $order['ordersn'], $order['uid'], $order['integral']);
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "VIP订单", "更改订单编号:{$order['ordersn']}的订单状态为已付款");
	message("确认付款成功", $this->createWebUrl('viporder', array('op'=>'detail','id'=>$orderid)), "success");
}