<?php
/**
 * 支付方式选择
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

load()->model('payment');

/* 支付方式 */
$payment = $_W['account']['setting']['payment'];
$pc_payment = json_decode($setting_pc['payment'], true);
if(empty($pc_payment)){
	$pc_payment = array(
		'wechat' => array(
			'pay_switch' => $payment['wechat']['pay_switch'] ? 1 : 0,
		),
		'credit' => array(
			'pay_switch' => $payment['credit']['pay_switch'] ? 1 : 0,
		),
		'alipay' => array(
			'pay_switch' => $payment['alipay']['pay_switch'] ? 1 : 0,
		),
	);
	pdo_update($this->table_setting_pc, array('payment'=>json_encode($pc_payment)), array('uniacid'=>$uniacid));
}

/* 会员信息 */
$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid, 'uid'=>$uid), array('credit2'));

$orderid = intval($_GPC['orderid']);
if($_GPC['ordertype'] == "buyvip"){
	$order = pdo_get($this->table_member_order, array('uniacid'=>$uniacid,'uid'=>$uid,'id'=>$orderid));
	if(!$order){
		message('订单不存在');
	}

	$redirect = $_W['siteroot']."{$uniacid}/myvip.html?op=orderlist";
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $redirect, 'error');
	}
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$order['level_id']));

	$params['tid']     = $order['ordersn'];
	$params['fee']     = $order['vipmoney'];
	$params['title']   = '购买['.$level['level_name'].']'.$order['viptime'].'天服务';
	$params['goodsid'] = $order['level_id'];
	$params['ordertype'] = 'buyvip';

}elseif($_GPC['ordertype'] == "buylesson"){
	$order = pdo_get($this->table_order, array('uniacid'=>$uniacid,'uid'=>$uid,'id'=>$orderid));
	if(!$order){
		message('订单不存在');
	}

	$redirect = $_W['siteroot']."{$uniacid}/mylesson.html?lessonid=".$order['lessonid'];
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $redirect, 'error');
	}

	$params['tid']     = $order['ordersn'];
	$params['fee']     = $order['price'];
	$params['title']   = '购买['.$order['bookname'].']课程';
	$params['goodsid'] = $order['lessonid'];
	$params['ordertype'] = 'buylesson';

}elseif($_GPC['ordertype'] == "buyteacher"){
	$order = pdo_get($this->table_teacher_order, array('uniacid'=>$uniacid,'uid'=>$uid,'id' => $orderid));
	if(!$order){
		message('订单不存在');
	}

	$redirect = $_W['siteroot']."{$uniacid}/myteacher.html?op=orderlist";
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $redirect, 'error');
	}
	$teacher = pdo_get($this->table_teacher, array('id'=>$order['teacherid']));

	$params['tid']     = $order['ordersn'];
	$params['fee']     = $order['price'];
	$params['title']   = '购买['.$teacher['teacher'].']讲师'.$order['ordertime'].'天服务';
	$params['goodsid'] = $order['teacherid'];
	$params['ordertype'] = 'buyteacher';

}elseif($_GPC['ordertype'] == "liveaward"){
	$order = pdo_get($this->table_live_award, array('uniacid'=>$uniacid,'uid'=>$uid,'id'=>$orderid));
	if(!$order){
		message('订单不存在');
	}

	$redirect = $_W['siteroot']."{$uniacid}/lesson.html?id={$order['lessonid']}&play=1";
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $redirect, 'error');
	}

	$params['tid']     = $order['ordersn'];
	$params['fee']     = $order['price'];
	$params['title']   = '打赏['.$order['bookname'].']课程';
	$params['goodsid'] = $order['lessonid'];
	$params['ordertype'] = 'liveaward';
}

$params['pay_way']  = 'web';
$params['user'] = $params['openid'] = $uid;
$params['module']   = $module_name;
$params['card_fee'] = $params['fee'];

$paylog = pdo_get($this->table_core_paylog, array('uniacid'=>$uniacid,'module'=>$module_name,'tid'=>$order['ordersn']));
if(!empty($paylog) && $paylog['status'] != '0') {
	message('这个订单已经支付成功, 无需重复支付', $redirect, "error");
}
if(!empty($paylog) && $paylog['status']==0){
	pdo_delete($this->table_core_paylog, array('uniacid'=>$uniacid,'module'=>$module_name,'tid'=>$order['ordersn']));
}


if($params['fee']==0){
	$site = WeUtility::createModuleSite($module_name);
	if(!is_error($site)) {
		$site->weid = $_W['weid'];
		$site->uniacid = $_W['uniacid'];
		$site->inMobile = false;
		$method = 'payResult';
		if (method_exists($site, $method)) {
			$ret = array();
			$ret['result'] = 'success';
			$ret['type'] = 'credit';
			$ret['from'] = 'notify';
			$ret['tid'] = $order['ordersn'];
			$ret['fee'] = $params['fee'];
			$res = $site->$method($ret);
		}
	}

	message("支付成功", $redirect."&ispay=1", "success");
}


if($op=='gopay'){
	$paytype = $_GPC['paytype'];
	if(!in_array($paytype, array('credit', 'alipay', 'wechat'))){
		message("请选择支付方式");
	}

	$params['type']	= $paytype;
	$new_paylog = $webAppCommon->createPayLog($params);
	$params['uniontid'] = $new_paylog['uniontid'];

	if($paytype=='credit'){

		load()->model('mc');
		if($params['fee']>0){
			if(!$payment['credit']['pay_switch']){
				message("系统未开启余额支付方式，如有疑问，请联系管理员");
			}

			if($member['credit2'] < $params['fee']) {
				message("余额不足以支付, 需要 {$params['fee']}元");
			}

			$creditlog = array(
				'0' => $uid,
				'1' => "消费，订单号：{$order['ordersn']}",
				'2' => $module_name,
			);
			$result = mc_credit_update($uid, 'credit2', -$params['fee'], $creditlog);
			if (is_error($result)) {
				message($result['message'], '', 'error');
			}
			pdo_update($this->table_core_paylog, array('status'=>'1'), array('plid'=>$new_paylog['plid']));
		}

		$site = WeUtility::createModuleSite($new_paylog['module']);
		if(!is_error($site)) {
			$site->weid = $_W['weid'];
			$site->uniacid = $_W['uniacid'];
			$site->inMobile = false;
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = $new_paylog['type'];
				$ret['from'] = 'notify';
				$ret['tid'] = $new_paylog['tid'];
				$ret['user'] = $new_paylog['openid'];
				$ret['fee'] = $new_paylog['fee'];
				$ret['weid'] = $new_paylog['weid'];
				$ret['uniacid'] = $new_paylog['uniacid'];
				$ret['acid'] = $new_paylog['acid'];
				$ret['is_usecard'] = $new_paylog['is_usecard'];
				$ret['card_type'] = $new_paylog['card_type'];
				$ret['card_fee'] = $new_paylog['card_fee'];
				$ret['card_id'] = $new_paylog['card_id'];
				$res = $site->$method($ret);
			}
		}

		message("支付成功", $redirect."&ispay=1", "success");
	
	}elseif($paytype=='alipay'){
		if(!$payment['alipay']['pay_switch']){
			message("系统未开启支付宝支付方式，如有疑问，请联系管理员");
		}

		$params['service'] = 'create_direct_pay_by_user';
		$result = $webAppCommon->createAlipayUrl($params, $payment['alipay']);
		header("Location: " .$result['url']);

	}elseif($paytype=='wechat'){
		if(!$payment['wechat']['pay_switch']){
			message("系统未开启微信支付方式，如有疑问，请联系管理员");
		}

		$wechat_setting = array(
			'appid'   => $_W['account']['key'],
			'mchid'   => $payment['wechat']['mchid'],
			'signkey' => $payment['wechat']['signkey'],
			'version' => 2,
		);
		$wechat_result = wechat_build($params, $wechat_setting);
		
		if (is_error($wechat_result)) {
			itoast($wechat_result['message'], $redirect."&ispay=1", 'info');
		}
	}
}

include $this->template("../webapp/{$template}/payment");


?>