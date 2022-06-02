<?php

$today = strtotime("today");
$orderid = $_GPC['orderid'];
$order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$orderid));
if (empty($order)) {
	message('该订单不存在或已被删除');
}
$lessonmember = pdo_fetch("SELECT * FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$order['uid']));

$data = array(
	'status'   => 1,
	'paytime'  => time(),
	'paytype'  => 'offline',
	'validity' => $order['validity']>0 ? time()+86400*$order['validity'] : 0,
);

if(pdo_update($this->table_order, $data, array('id'=>$orderid))){
	/* 增加课程购买人数 */
	$pay_result->updateLessonNumber($order['lessonid']);

	/* 统计数据表 */
	$pay_result->staticAmount($uniacid, $type=2, $order['price']);
	
	/* 判断分销员状态变化 */
	$pay_result->checkAgentStatus($lessonmember, $comsetting, $order['price']);

	/* 一级佣金 */
	if ($order['member1'] > 0 && $order['commission1'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member1'], $lessonmember, $type=2, $setting, $order, $order['commission1'], $level=1, $comsetting);
	}
	
	/* 二级佣金 */
	if ($order['member2'] > 0 && $order['commission2'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member2'], $lessonmember, $type=2, $setting, $order, $order['commission2'], $level=2, $comsetting);
	}
	
	/* 三级佣金 */
	if ($order['member3'] > 0 && $order['commission3'] > 0) {
		$pay_result->sendCommissionToUser($uniacid, $order['member3'], $lessonmember, $type=2, $setting, $order, $order['commission3'], $level=3, $comsetting);
	}

	/* 讲师分成 */
	if ($order['price'] > 0 && $order['teacher_income'] > 0) {
		$pay_result->sendCommissionToTeacher($uniacid, $order, $setting, $type=2);
	}
	
	/* 购买成功模版消息通知用户 */
	$pay_result->sendMessageToUser($uniacid, $setting, $order, $type=2, $validity='');
	
	/* 赠送积分操作 */
	if ($order['integral'] > 0) {
		$pay_result->handleUserIntegral($type=1, $order['ordersn'], $order['uid'], $order['integral']);
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程订单", "更改订单编号:{$order['ordersn']}的课程状态为已付款");
	message("确认付款成功", $refurl, "success");
}

?>