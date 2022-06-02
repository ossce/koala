<?php

include_once dirname(__FILE__).'/../../core/Payresult.php';
$pay_result = new Payresult();

$teacherid = intval($_GPC['teacherid']);
$teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid, 'id'=>$teacherid));
if (!$teacher) {
	message('该讲师不存在', '', 'error');
}

$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$teacherid));
if (!$teacher_price) {
	message('该讲师未添加购买讲师价格，请先编辑讲师进行添加', '', 'error');
}

if(checksubmit('submit')){
	$uid = intval($_GPC['user_uid']);
	$income_switch = intval($_GPC['income_switch']); //讲师分成佣金
	$sale_switch   = intval($_GPC['sale_switch']);	 //分销佣金

	if(!$uid){
		message('请选择用户', '', 'error');
	}

	$member = pdo_get($this->table_member, array('uniacid'=>$uniacid, 'uid'=>$uid));
	if(!$member){
		message('您选择的用户不存在，请重新选择用户', $this->createWebUrl('teacher', array('op'=>'createOrder','teacherid'=>$teacherid)), 'error');
	}

	/* 构造订单信息 */
	$orderdata = array(
		'uniacid'		 => $uniacid,
		'ordersn'		 => 'T'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'			 => $uid,
		'ordertime'		 => $teacher_price['validity_time'],
		'price'			 => $teacher_price['price'],
		'integral'		 => $teacher_price['integral'],
		'paytype'		 => 'admin',
		'status'		 => 1,
		'paytime'		 => time(),
		'teacherid'		 => $teacherid,
		'teacher_name'	 => $teacher['teacher'],
		'teacher_income' => $income_switch ? $teacher_price['teacher_income'] : 0,
		'addtime'		 => time(),
		'update_time'    => time(),
	);

	/* 分销功能和购买讲师分销同时开启 */
	if ($comsetting['is_sale'] == 1 && $sale_switch==1) {
		$orderdata['commission1'] = 0;
		$orderdata['commission2'] = 0;
		$orderdata['commission3'] = 0;

		if($comsetting['self_sale']==1){
			/* 开启分销内购，一级佣金为购买者本人 */
			$orderdata['member1'] = $orderdata['uid'];
			$orderdata['member2'] = $site_common->getParentid($orderdata['uid']);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}else{
			/* 关闭分销内购 */
			$orderdata['member1'] = $site_common->getParentid($orderdata['uid']);;
			$orderdata['member2'] = $site_common->getParentid($orderdata['member1']);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}

		$teacher_com = json_decode($teacher['commission'], true); /* 购买讲师单独佣金比例 */
		$settingcom = unserialize($comsetting['commission']);	  /* 全局佣金比例 */
		if($orderdata['member1']>0){
			$orderdata['commission1'] = $site_common->getAgentCommission1($commission_type=0, $teacher_com['commission1'], $settingcom['commission1'], $orderdata['price'], $orderdata['member1']);
		}
		if($orderdata['member2']>0 && in_array($comsetting['level'], array('2','3'))){
			$orderdata['commission2'] = $site_common->getAgentCommission2($commission_type=0, $teacher_com['commission2'], $settingcom['commission2'], $orderdata['price'], $orderdata['member2']);
		}
		if($orderdata['member3']>0 && $comsetting['level']==3){
			$orderdata['commission3'] = $site_common->getAgentCommission3($commission_type=0, $teacher_com['commission3'], $settingcom['commission3'], $orderdata['price'], $orderdata['member3']);
		}
	}

	if(pdo_insert($this->table_teacher_order, $orderdata)){
		$orderid = pdo_insertid();

		/* 给学员添加讲师服务有效期 */
		$buyteacher = pdo_get($this->table_member_buyteacher, array('uniacid'=>$uniacid, 'uid'=>$orderdata['uid'], 'teacherid'=>$orderdata['teacherid']));
		$buyteacher_data = array(
			'uniacid'	  => $uniacid,
			'uid'		  => $orderdata['uid'],
			'teacherid'	  => $orderdata['teacherid'],
			'update_time' => time(),
		);
		if(!$buyteacher){
			$buyteacher_data['validity'] = time() + $orderdata['ordertime']*86400;
			$buyteacher_data['addtime'] = time();
			pdo_insert($this->table_member_buyteacher, $buyteacher_data);
		}else{
			$buyteacher_data['validity'] = $buyteacher['validity'] + $orderdata['ordertime']*86400;
			pdo_update($this->table_member_buyteacher, $buyteacher_data, array('id'=>$buyteacher['id']));
		}
		
		/* 统计数据表 */
		$pay_result->staticAmount($uniacid, $type=3, $orderdata['price']);
		
		/* 判断分销员状态变化 */
		$pay_result->checkAgentStatus($member, $comsetting, $orderdata['price']);

		/* 一级佣金 */
		if ($orderdata['member1'] > 0 && $orderdata['commission1'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $orderdata['member1'], $member, $type=3, $setting, $orderdata, $orderdata['commission1'], $level=1, $comsetting);
		}
		
		/* 二级佣金 */
		if ($orderdata['member2'] > 0 && $orderdata['commission2'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $orderdata['member2'], $member, $type=3, $setting, $orderdata, $orderdata['commission2'], $level=2, $comsetting);
		}
		
		/* 三级佣金 */
		if ($orderdata['member3'] > 0 && $orderdata['commission3'] > 0) {
			$pay_result->sendCommissionToUser($uniacid, $orderdata['member3'], $member, $type=3, $setting, $orderdata, $orderdata['commission3'], $level=3, $comsetting);
		}

		/* 讲师分成 */
		if ($orderdata['price'] > 0 && $orderdata['teacher_income'] > 0) {
			$pay_result->sendCommissionToTeacher($uniacid, $orderdata, $setting, $type=3);
		}
		
		/* 购买成功模版消息通知用户 */
		$pay_result->sendMessageToUser($uniacid, $setting, $orderdata, $type=3, $buyteacher_data['validity']);

		/* 赠送积分操作 */
		if ($orderdata['integral'] > 0) {
			$pay_result->handleUserIntegral($type=3, $orderdata['ordersn'], $orderdata['uid'], $orderdata['integral']);
		}

		/* 写入系统日志 */
		$site_common->addSysLog($_W['uid'], $_W['username'], 1, "讲师管理->创建订单", "给[uid:".$orderdata['uid']."]的用户开通[teacherid:".$orderdata['teacherid']."]讲师服务 - ".$orderdata['validity_time']."]天");
		message("创建讲师订单成功", $this->createWebUrl('teacher', array('op'=>'order','status'=>1)), "success");
	}

}