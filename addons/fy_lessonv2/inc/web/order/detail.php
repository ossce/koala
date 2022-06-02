<?php

$id = intval($_GPC['id']);

$order = pdo_fetch("SELECT a.*,b.nickname,b.realname,b.mobile,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.address,b.avatar FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($order)) {
	message('该订单不存在或已被删除!');
}

/* 报名课程信息 */
$appoint_info = json_decode($order['appoint_info'], true);

/* 商户订单号 */
$order_first = substr($order['ordersn'],0,1);
$tid = $order_first=='L' ? $order['ordersn'] : $order['id'];
$pay_log = $site_common->getWechatPayNo($tid);
$pay_log['transaction'] = unserialize($pay_log['tag']);


if(empty($order['avatar'])){
	$avatar = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($order['avatar'], "http://") || strstr($order['avatar'], "https://");
	$avatar = $inc ? $order['avatar'] : $_W['attachurl'].$order['avatar'];
}

$evaluate = pdo_fetch("SELECT content FROM " .tablename($this->table_evaluate). " WHERE uniacid=:uniacid AND orderid=:orderid", array(':uniacid'=>$uniacid,':orderid'=>$order['id']));

if($order['member1']>0){
	$member1 = pdo_fetch("SELECT nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$order['member1']));
	if(empty($member1['avatar'])){
		$avatar1 = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc1 = strstr($member1['avatar'], "http://") || strstr($member1['avatar'], "https://");
		$avatar1 = $inc1 ? $member1['avatar'] : $_W['attachurl'].$member1['avatar'];
	}
}
if($order['member2']>0){
	$member2 = pdo_fetch("SELECT nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$order['member2']));
	if(empty($member2['avatar'])){
		$avatar2 = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc2 = strstr($member2['avatar'], "http://") || strstr($member2['avatar'], "https://");
		$avatar2 = $inc2 ? $member2['avatar'] : $_W['attachurl'].$member2['avatar'];
	}
}
if($order['member3']>0){
	$member3 = pdo_fetch("SELECT nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$order['member3']));
	if(empty($member3['avatar'])){
		$avatar3 = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc3 = strstr($member3['avatar'], "http://") || strstr($member3['avatar'], "https://");
		$avatar3 = $inc3 ? $member3['avatar'] : $_W['attachurl'].$member3['avatar'];
	}
}

/* 核销订单 */
$verify_log = $site_common->getOrderVerifyLog($id);
if($_GPC['verify_order']){
	if($order['lesson_type']!=1){
		message('订单不是报名课程类型');
	}
	if($order['status']<=0){
		message('订单状态错误');
	}

	if($verify_log['count'] && $verify_log['count'] >= $order['verify_number']){
		message("该订单核销次数已用完");
	}

	$verify_data = array(
		'uniacid'	  => $uniacid,
		'orderid'	  => $id,
		'verify_type' => 1,
		'verify_uid'  => $_W['uid'],
		'verify_name' => $_W['username'],
		'addtime'	  => time(),
	);
	if(!$order['is_verify']){
		$order_data['is_verify'] = 1;
	}
	if($verify_log['count'] && $verify_log['count']+1 >= $order['verify_number']){
		$order_data['is_verify'] = 2;
	}
	if($order_data){
		pdo_update($this->table_order, $order_data, array('id'=>$id));
	}

	if(pdo_insert($this->table_order_verify, $verify_data)){
		message("核销成功", $refurl, "success");
	}else{
		message("核销失败，请稍候重试", '', "error");
	}
}


/* 修改订单有效期、备注信息 */
if(checksubmit('submit_order')){
	$data = array();
	$validity = strtotime($_GPC['validity']);
	if($validity != $order['validity']){
		$data['validity'] = $validity;
	}

	if($_GPC['remark'] != $order['remark'] || $_GPC['admin_img'] != $order['admin_img']){
		$data['remark']		= $_GPC['remark'];
		$data['admin_img']	= $_GPC['admin_img'];
	}

	pdo_update($this->table_order, $data, array('uniacid'=>$uniacid,'id'=>$id));
	message("更新订单成功", $refurl, "success");
}


?>