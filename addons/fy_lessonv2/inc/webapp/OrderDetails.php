<?php
/**
 * 订单详情
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 订单状态名称 */
$typeStatus = new TypeStatus();
$orderStatusList = $typeStatus->orderStatus();
$orderPaytyoeList = $typeStatus->orderPayType();

$title = "订单详情";

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT a.*,b.images FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.id=:id AND a.uid=:uid ", array(':id'=>$orderid, ':uid'=>$uid));
if(empty($order)){
	message("订单不存在!");
}

/* 报名课程信息 */
$appoint_info = json_decode($order['appoint_info'], true);

/* 核销信息 */
$verify_info = json_decode($order['verify_info'], true);
if($verify_info['verify_uid']>0){
	$verify_user = pdo_get($this->table_mc_members, array('uid'=>$verify_info['verify_uid']), array('nickname'));
}

$verify_log = $site_common->getOrderVerifyLog($orderid);

/* 报名课程核销二维码 */
$verifyurl = $_W['siteroot'].'app/'.$this->createMobileUrl('verifyorder', array('orderid'=>$orderid));



include $this->template("../webapp/{$template}/orderDetails");


?>