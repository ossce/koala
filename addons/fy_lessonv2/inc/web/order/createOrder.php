<?php

/* 课程分类 */
$category_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>0));
foreach($category_list as $k=>$v){
	$v['child'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>$v['id']));

	$category_list[$k] = $v;
}

if(checksubmit('submit')){
	$lessonid = intval($_GPC['lessonid']);
	$price = floatval($_GPC['price']);
	$teacher_income = intval($_GPC['teacher_income']);
	$validity = intval($_GPC['validity']);
	$uid = intval($_GPC['uid']);
	$income_switch = intval($_GPC['income_switch']);
	$sale_switch = intval($_GPC['sale_switch']);

	if(empty($lessonid)){
		message("请选择课程");
	}
	if(empty($uid)){
		message("请选择用户");
	}
	$lesson = pdo_fetch("SELECT id,bookname,lesson_type,price,teacherid,teacher_income,integral,commission,stock,buynum FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$lessonid));
	if(empty($lesson)){
		message("指定课程不存在，请重新选择");
	}
	if($lesson['lesson_type'] == 1){
		message("报名课程不支持创建订单");
	}

	$member = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uid=:uid", array(':uid'=>$uid));
	if(empty($member)){
		message("指定用户不存在，请重新选择");
	}

	$order = array(
		'acid'	   => $_W['acid'],
		'uniacid'  => $uniacid,
		'ordersn'  => 'L'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'	   => $member['uid'],
		'lessonid' => $lesson['id'],
		'bookname' => $lesson['bookname'],
		'marketprice' => $lesson['price'],
		'price'	   => $price,
		'spec_day' => $validity,
		'teacherid'=> $lesson['teacherid'],
		'teacher_income' => $teacher_income,
		'integral' => $lesson['integral'],
		'paytype'  => 'admin',
		'paytime'  => time(),
		'validity' => $validity>0 ? time()+$validity*86400 : 0,
		'status'   => 1,
		'addtime'  => time(),
	);
	/* 检查当前分销功能是否开启且课程价格大于0 */
	if ($comsetting['is_sale'] == 1 && $price > 0 && $sale_switch==1) {
		$order['commission1'] = 0;
		$order['commission2'] = 0;
		$order['commission3'] = 0;

		if ($comsetting['self_sale'] == 1) {
			/* 开启分销内购，一级佣金为购买者本人 */
			$order['member1'] = $uid;
			$order['member2'] = $site_common->getParentid($uid);
			$order['member3'] = $site_common->getParentid($order['member2']);
		} else {
			/* 关闭分销内购 */
			$order['member1'] = $site_common->getParentid($uid);
			$order['member2'] = $site_common->getParentid($order['member1']);
			$order['member3'] = $site_common->getParentid($order['member2']);
		}

		$lessoncom = unserialize($lesson['commission']); /* 本课程佣金比例 */
		$settingcom = unserialize($comsetting['commission']); /* 全局佣金比例 */
		if ($order['member1'] > 0) {
			$order['commission1'] = $site_common->getAgentCommission1($lessoncom['commission_type'], $lessoncom['commission1'], $settingcom['commission1'], $price, $order['member1']);
		}
		
		if ($order['member2'] > 0 && in_array($comsetting['level'], array('2', '3'))) {
			$order['commission2'] = $site_common->getAgentCommission2($lessoncom['commission_type'], $lessoncom['commission2'], $settingcom['commission2'], $price, $order['member2']);
		}
		
		if ($order['member3'] > 0 && $comsetting['level'] == 3) {
			$order['commission3'] = $site_common->getAgentCommission3($lessoncom['commission_type'], $lessoncom['commission3'], $settingcom['commission3'], $price, $order['member3']);
		}
	}

	if(pdo_insert($this->table_order, $order)){
		$orderid = pdo_insertid();

		/* 增加课程购买人数 */
		$pay_result->updateLessonNumber($order['lessonid']);
		
		/* 统计数据表 */
		$pay_result->staticAmount($uniacid, $type=2, $order['price']);
		
		/* 判断分销员状态变化 */
		$pay_result->checkAgentStatus($member, $comsetting, $order['price']);

		/* 一级佣金 */
		if ($order['member1'] > 0 && $order['commission1'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $order['member1'], $member, $type=2, $setting, $order, $order['commission1'], $level=1, $comsetting);
		}
		
		/* 二级佣金 */
		if ($order['member2'] > 0 && $order['commission2'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $order['member2'], $member, $type=2, $setting, $order, $order['commission2'], $level=2, $comsetting);
		}
		
		/* 三级佣金 */
		if ($order['member3'] > 0 && $order['commission3'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $order['member3'], $member, $type=2, $setting, $order, $order['commission3'], $level=3, $comsetting);
		}

		/* 讲师分成 */
		if ($price > 0 && $order['teacher_income'] > 0 && $income_switch==1) {
			$pay_result->sendCommissionToTeacher($uniacid, $order, $setting, $type=2);
		}
		
		/* 购买成功模版消息通知用户 */
		$pay_result->sendMessageToUser($uniacid, $setting, $order, $type=2, $validity='');

		/* 赠送积分操作 */
		if ($order['integral'] > 0) {
			$pay_result->handleUserIntegral($type=2, $order['ordersn'], $order['uid'], $order['integral']);
		}

		message("创建课程订单成功", $this->createWebUrl('order'), "success");
	}
}

?>