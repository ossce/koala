<?php
/**
 * 退款管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

load()->model('mc');

if($op=='display'){
	$id = intval($_GPC['id']);
	$amount = $_GPC['refund_amount'];
	$ordertype = $_GPC['ordertype'];
	$reason = trim($_GPC['reason']);
	
	if(!is_numeric($amount)){
		message("退款金额必须为数字", "", "error");
	}
	if($ordertype=='lesson'){
		$order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
		$price = $order['price'];
		$return_url = $this->createWebUrl('order', array('op'=>'detail','id'=>$id));
		$paylog = pdo_get('core_paylog', array('uniacid'=>$uniacid, 'tid'=>$order['ordersn'], 'module'=>$this->module['name']));
		$content = $order['bookname'];
	
	}elseif($ordertype=='vip'){
		$order = pdo_fetch("SELECT * FROM " .tablename($this->table_member_order). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
		$price = $order['vipmoney'];
		$return_url = $this->createWebUrl('viporder', array('op'=>'detail','id'=>$id));
		$paylog = pdo_get('core_paylog', array('uniacid'=>$uniacid, 'tid'=>$order['ordersn'], 'module'=>$this->module['name']));
		$content = "VIP订单退款";
	}

	if(empty($order)){
		message("订单不存在", "", "error");
	}
	if($order['status']==-2){
		message("订单已退款，请勿重复提交", "", "error");
	}
	if($amount > $price){
		message("退款金额不可超过订单金额", "", "error");
	}
	
	load()->model('refund');
	$refund_id = refund_create_order($order['ordersn'], $this->module['name'], $amount, $reason);
	if (is_error($refund_id)) {
		return $refund_id;
	}
	$res = refund($refund_id);

	if (($paylog['type']=='wechat' && $res['result_code']=='SUCCESS') || ($paylog['type']=='alipay' && $res['code']==10000)) {
		$refund_status = true;

	}elseif(($paylog['type']=='wxapp' && $paylog['status'])){
		$refundlog = pdo_get('core_refundlog', array('id' => $refund_id));
		$refund_param = reufnd_wechat_build($refund_id, $refundlog['is_wish']);
		if (is_error($refund_param)) {
			return $refund_param;
		}
		if ($refundlog['is_wish'] == 1) {
			$module = 'store';
			$cert_file = ATTACHMENT_ROOT . 'store_wechat_refund_all.pem';
		} else {
			$module = '';
			$cert_file = ATTACHMENT_ROOT . $_W['uniacid'] . '_wechat_refund_all.pem';
		}

		$wechat = Pay::create('wechat', $module);
		$response = $wechat->refund($refund_param, $module);
		unlink($cert_file);

		if($response['result_code']=='SUCCESS'){
			$refund_status = true;
		}else{
			pdo_update('core_refundlog', array('status' => '-1'), array('id' => $refund_id));
			message("退款失败，支付方返回信息：".$response['errno']."，".$response['message'], "", "error");
		}
	}else{
		message("退款失败，支付方返回信息：".$res['errno']."，".$res['message'], "", "error");
	}

	if($refund_status){
		if($ordertype=='lesson'){
			/* 更改订单状态 */
			pdo_update($this->table_order, array('status'=>-2), array('id'=>$id));

			/* 订单总统计 */
			$static_data = array(
				'lessonOrder_num -='    => 1,
				'lessonOrder_amount -=' => $amount,
			);
		}elseif($ordertype=='vip'){
			/* 更改订单状态 */
			pdo_update($this->table_member_order, array('status'=>-2), array('id'=>$id));

			/* 订单总统计 */
			$static_data = array(
				'vipOrder_num -='	 => 1,
				'vipOrder_amount -=' => $amount,
			);
		}

		/* 更改订单总统计 */
		$static_time = strtotime(date('Y-m-d', $order['paytime']));
		pdo_update($this->table_static, $static_data, array('uniacid'=>$uniacid, 'static_time'=>$static_time));

		/* 扣除已发放分销佣金 */
		$commission_log = array(
			'uniacid'	 => $uniacid,
			'orderid'	 => $order['id'],
			'bookname'	 => $content,
			'buyer_uid'  => $order['uid'],
			'addtime'	 => time(),
		);
		if($order['commission1']>0){
			pdo_update($this->table_member, array('nopay_commission -='=>$order['commission1']), array('uid'=>$order['member1']));

			$commission_log['uid'] = $order['member1'];
			$commission_log['change_num'] = '-'.$order['commission1'];
			$commission_log['grade'] = 1;
			$commission_log['remark'] = '1级佣金:订单已退款，订单号'.$order['ordersn'];
			pdo_insert($this->table_commission_log, $commission_log);

			if($comsetting['commission_credit']){
				mc_credit_update($order['member1'], 'credit1', '-'.intval($order['commission1']), array(0, $commission_log['remark']));
			}
		}
		if($order['commission2']>0){
			pdo_update($this->table_member, array('nopay_commission -='=>$order['commission2']), array('uid'=>$order['member2']));

			$commission_log['uid'] = $order['member2'];
			$commission_log['change_num'] = '-'.$order['commission2'];
			$commission_log['grade'] = 2;
			$commission_log['remark'] = '2级佣金:订单已退款，订单号'.$order['ordersn'];
			pdo_insert($this->table_commission_log, $commission_log);

			if($comsetting['commission_credit']){
				mc_credit_update($order['member2'], 'credit1', '-'.intval($order['commission2']), array(0, $commission_log['remark']));
			}
		}
		if($order['commission3']>0){
			pdo_update($this->table_member, array('nopay_commission -='=>$order['commission3']), array('uid'=>$order['member3']));

			$commission_log['uid'] = $order['member3'];
			$commission_log['change_num'] = '-'.$order['commission3'];
			$commission_log['grade'] = 3;
			$commission_log['remark'] = '3级佣金:订单已退款，订单号'.$order['ordersn'];
			pdo_insert($this->table_commission_log, $commission_log);

			if($comsetting['commission_credit']){
				mc_credit_update($order['member3'], 'credit1', '-'.intval($order['commission3']), array(0, $commission_log['remark']));
			}
		}

		/* 减少用户购买订单额和订单笔数 */
		$memupdate = array(
			'payment_amount -=' => $amount,
			'payment_order -='  => 1,
		);
		pdo_update($this->table_member, $memupdate, array('uid'=>$order['uid']));

		if($ordertype=='lesson'){
			/* 退款减少讲师收入 */
			$teacher_income = pdo_get($this->table_teacher_income, array('uniacid'=>$uniacid, 'ordersn'=>$order['ordersn']));
			if($teacher_income['income_amount']>0){
				pdo_update($this->table_member, array('nopay_lesson -='=>$teacher_income['income_amount']), array('uid'=>$teacher_income['uid']));

				$teacher_income_log = array(
					'uniacid' => $uniacid,
					'uid'	  => $teacher_income['uid'],
					'teacher' => $teacher_income['teacher'],
					'ordersn' => $teacher_income['ordersn'],
					'bookname'	 => '用户申请退款，课程:'.$teacher_income['bookname'],
					'orderprice' => $teacher_income['orderprice'],
					'teacher_income' => $teacher_income['teacher_income'],
					'income_amount'	 => '-'.$teacher_income['income_amount'],
					'addtime'	     => time(),
				);
				pdo_insert($this->table_teacher_income, $teacher_income_log);
			}

		}elseif($ordertype=='vip'){
			/* 退款变更会员VIP有效期 */
			$member_vip = pdo_get($this->table_member_vip, array('uniacid'=>$uniacid,'level_id'=>$order['level_id'],'uid'=>$order['uid']));
			if(!empty($member_vip)){
				$new_validity = array(
					'validity' => $member_vip['validity'] - $order['viptime']*86400,
				);
				pdo_update($this->table_member_vip, $new_validity, array('uniacid'=>$uniacid,'id'=>$member_vip['id']));
			}
		}

		message("退款成功", $return_url, "success");
	}

}

?>