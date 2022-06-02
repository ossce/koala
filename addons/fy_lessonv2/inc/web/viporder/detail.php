<?php

$id = intval($_GPC['id']);
$order = pdo_fetch("SELECT a.*,b.nickname,b.realname,b.mobile,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.address,b.avatar FROM " .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($order)) {
	message('该订单不存在或已被删除!');
}

/* 商户订单号 */
$order_first = substr($order['ordersn'],0,1);
$tid = $order_first=='V' ? $order['ordersn'] : $order['id'];
$pay_log = $site_common->getWechatPayNo($tid);
$pay_log['transaction'] = unserialize($pay_log['tag']);


if(empty($order['avatar'])){
	$avatar = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($order['avatar'], "http://") || strstr($order['avatar'], "https://");
	$avatar = $inc ? $order['avatar'] : $_W['attachurl'].$order['avatar'];
}

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