<?php
/**
 * 课程订单
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 订单状态集 */
$typeStatus = new TypeStatus();
$order_status = $typeStatus->orderStatus();

$title = "课程订单";

if($op == 'display'){

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$status = $_GPC['status'];
	$ordersType = $_GPC['ordersType'];
	$keyword = trim($_GPC['keyword']);

	$condition = " a.uniacid=:uniacid AND a.uid=:uid AND a.is_delete=:is_delete ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;
	$params[':is_delete'] = 0;

	if($status=='nopay'){
		/* 待付款 */
		$condition .= " AND a.status=:status ";
		$params[':status'] = 0;
	}elseif($status=='payed'){
		/* 已付款 */
		$condition .= " AND a.status>:status ";
		$params[':status'] = 0;
	}elseif($status=='allow_verify'){
		/* 可核销 */
		$condition .= " AND a.is_verify<=:is_verify AND a.lesson_type=:lesson_type AND a.status>:status";
		$params[':is_verify'] = 1;
		$params[':lesson_type'] = 1;
		$params[':status'] = 0;
	}elseif($status=='no_evaluate'){
		/* 待评价 */
		$condition .= " AND a.status=:status";
		$params[':status'] = 1;
	}

	if($ordersType != ''){
		$condition .= " AND a.lesson_type=:lesson_type";
		$params[':lesson_type'] = $ordersType;
	}
	if($keyword){
		$condition .= " AND (a.bookname LIKE :keyword1 OR a.ordersn = :keyword2)";
		$params[':keyword1'] = '%'.$keyword.'%';
		$params[':keyword2'] = $keyword;
	}

	$list = pdo_fetchall("SELECT a.*,b.images FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['status']>0 && $v['lesson_type']==1){
			$verify_log = $site_common->getOrderVerifyLog($v['id']);
			if(!$verify_log['count'] || $verify_log['count'] < $v['verify_number']){
				$v['show_qrcode'] = 1;
			}
			$v['verify_num'] = $verify_log['count'];
		}

		if($v['status']==-1 || $v['status']==2){
			$v['classname'] = 'ftc-414141';
		}elseif($v['status']==1){
			$v['classname'] = 'ftc-0e9a0c';
		}
		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_order) . "  a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

	/* 随机获取客服列表 */
	if($_GPC['ispay']==1){
		if($_GPC['lessonid']){
			$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$_GPC['lessonid']), array('service'));
			$service = json_decode($lesson['service'], true);
		}

		if(empty($service)){
			$service = json_decode($setting['qun_service'], true);
		}
		
		if(!empty($service)){
			$rand = rand(0, count($service)-1);
			$now_service = $service[$rand];
		}
	}

}elseif($op=='cancel'){
	$is_delete = intval($_GPC['is_delete']);
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE id=:id AND uid=:uid ", array(':id'=>$orderid, ':uid'=>$uid));
	if(!$order){
		message("该课程不存在");
	}

	/* 删除订单 */
	if($is_delete){
		if($order['status'] != '-1'){
			message("该课程订单状态不允许删除");
		}
		if(pdo_update($this->table_order, array('is_delete'=>'1'), array('id'=>$orderid))){
			message("删除成功", $_W['siteroot']."{$uniacid}/mylesson.html?status={$_GPC['status']}", "success");
		}else{
			message("删除失败", "", "error");
		}
	}

	if($order['status'] != '0'){
		message("该课程状态不允许取消");
	}

	if(pdo_update($this->table_order, array('status'=>'-1'), array('id'=>$orderid))){
		if($setting['stock_config']==1){
			$site_common->updateLessonStock($order['lessonid'], $order['spec_id'], '1');
		}
		if($order['coupon']>0){
			$upcoupon = array(
				'status'	  => 0,
				'ordersn'	  => '',
				'update_time' => '',
			);
			pdo_update($this->table_member_coupon, $upcoupon, array('id'=>$order['coupon']));
		}
		if($order['deduct_integral']>0){
			load()->model('mc');
			mc_credit_update($order['uid'], 'credit1', $order['deduct_integral'], array(0, '取消课程订单，sn:'.$order['ordersn']));
		}

		message("取消成功", $_W['siteroot']."{$uniacid}/mylesson.html?status={$_GPC['status']}", "success");
	}else{
		message("取消失败", "", "error");
	}
}


include $this->template("../webapp/{$template}/myLesson");


?>