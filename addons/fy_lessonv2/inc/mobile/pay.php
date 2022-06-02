<?php
/**
 * 支付方式选择页面
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();

$orderid = intval($_GPC['orderid']);
$params = array(
	'virtual' => false,
);

if($_GPC['ordertype'] == "buyvip"){
	$order = pdo_get($this->table_member_order, array('uniacid'=>$uniacid,'id'=>$orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $this->createMobileUrl('vip'), 'warning');
	}
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$order['level_id']));

	$params['tid']     = $order['ordersn'];
	$params['user']    = $_W['openid'] ? $_W['openid'] : $order['uid'];
	$params['fee']     = $order['vipmoney'];
	$params['title']   = '购买['.$level['level_name'].']'.$order['viptime'].'天服务';
	$params['ordersn'] = $order['ordersn'];

}elseif($_GPC['ordertype'] == "buylesson"){
	$order = pdo_get($this->table_order, array('uniacid'=>$uniacid,'id'=>$orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $this->createMobileUrl('mylesson'), 'warning');
	}

	$params['tid']     = $order['ordersn'];
	$params['user']    = $_W['openid'] ? $_W['openid'] : $order['uid'];
	$params['fee']     = $order['price'];
	$params['title']   = '购买['.$order['bookname'].']课程';
	$params['ordersn'] = $order['ordersn'];

}elseif($_GPC['ordertype'] == "buyteacher"){
	$order = pdo_get($this->table_teacher_order, array('uniacid'=>$uniacid,'id'=>$orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $this->createMobileUrl('teacher', array('teacherid'=>$order['teacherid'])), 'warning');
	}
	$teacher = pdo_get($this->table_teacher, array('id'=>$order['teacherid']));

	$params['tid']     = $order['ordersn'];
	$params['user']    = $_W['openid'] ? $_W['openid'] : $order['uid'];
	$params['fee']     = $order['price'];
	$params['title']   = '购买['.$teacher['teacher'].']讲师'.$order['ordertime'].'天服务';
	$params['ordersn'] = $order['ordersn'];

}elseif($_GPC['ordertype'] == "liveaward"){
	$order = pdo_get($this->table_live_award, array('uniacid'=>$uniacid,'id'=>$orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', $this->createMobileUrl('lesson', array('id'=>$order['lessonid'],'play'=>1)), 'warning');
	}

	$params['tid']     = $order['ordersn'];
	$params['user']    = $_W['openid'] ? $_W['openid'] : $order['uid'];
	$params['fee']     = $order['price'];
	$params['title']   = '打赏['.$order['bookname'].']课程';
	$params['ordersn'] = $order['ordersn'];

}elseif($_GPC['ordertype'] == "buygoods"){
	$order = pdo_get($this->table_shop_order, array('uniacid'=>$uniacid,'id'=>$orderid));
	if ($order['status'] != '0') {
		message('抱歉，您的订单已经付款或是被关闭，请重新进入付款', str_replace('fy_lessonv2','fy_lessonv2_plugin_shop',$this->createMobileUrl('shoporder')), 'warning');
	}

	if($order['is_virtual']){
		$goods_list = pdo_fetchall("SELECT a.total,b.id,b.goods_type,b.stock FROM " .tablename($this->table_shop_order_goods). " a LEFT JOIN " .tablename($this->table_shop_goods). " b ON a.goods_id=b.id WHERE a.uniacid=:uniacid AND a.orderid=:orderid", array(':uniacid'=>$uniacid,':orderid'=>$orderid));
		foreach($goods_list as $v){
			if($v['goods_type'] == 2 && $v['total'] > $v['stock']){
				message('商品库存不足，请取消订单', '', 'warning');
			}
		}
	}

	$order['title'] = trim($order['title'],'；');
	$titl_arr = explode('；', $order['title']);

	$params['tid']     = $order['ordersn'];
	$params['user']    = $_W['openid'] ? $_W['openid'] : $order['uid'];
	$params['fee']     = $order['total_amount'];
	$params['title']   = count($titl_arr) == 1 ? $order['title'] : $order['title']. " 等" . count($titl_arr). "件商品";
	$params['ordersn'] = $order['ordersn'];

}

$paylog = pdo_get($this->table_core_paylog, array('tid' => $order['ordersn'], 'status'=>0));
if(!empty($paylog)){
	pdo_delete($this->table_core_paylog, array('tid' => $order['ordersn']));
}

include $this->template("../mobile/{$template}/pay");

?>