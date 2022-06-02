<?php
/**
 * 课程订单
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

//订单状态集
$typeStatus = new TypeStatus();
$order_status = $typeStatus->orderStatus();

$site_common->updateOrderVerifyLog(); //更新旧的核销订单记录

$mylesson_bg = cache_load('fy_lessonv2_'.$uniacid.'_mylesson_bg');
if(!$mylesson_bg){
	$mylesson_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>6,'is_pc'=>0,'is_show'=>1), array('picture'));
	$mylesson_bg = $mylesson_bg_data ? $_W['attachurl'].$mylesson_bg_data['picture'] : MODULE_URL."static/mobile/{$template}/images/agency-top.jpg?v=2";
	cache_write('fy_lessonv2_'.$uniacid.'_mylesson_bg', $mylesson_bg);
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$title = $common['page_title']['mylesson'] ? $common['page_title']['mylesson'] : '课程订单';
$uid = $_W['member']['uid'];
$status = $_GPC['status'];

$condition = " a.uniacid=:uniacid AND a.uid=:uid AND a.is_delete=:is_delete ";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;
$params[':is_delete'] = 0;

if($status=='nopay'){
	//待付款
	$condition .= " AND a.status=:status ";
	$params[':status'] = 0;
}elseif($status=='payed'){
	//已付款
	$condition .= " AND a.status>:status ";
	$params[':status'] = 0;
}elseif($status=='allow_verify'){
	//可核销
	$condition .= " AND a.is_verify<=:is_verify AND a.lesson_type=:lesson_type AND a.status>:status";
	$params[':is_verify'] = 1;
	$params[':lesson_type'] = 1;
	$params[':status'] = 0;
}elseif($status=='no_evaluate'){
	//待评价
	$condition .= " AND a.status=:status";
	$params[':status'] = 1;
}

$mylessonlist = pdo_fetchall("SELECT a.*,b.images FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

foreach($mylessonlist as $key=>$value){
	$value['statusname'] = $order_status[$value['status']];
	if($value['status']>=1 && $value['validity']>0){
		$value['validity'] = date('Y-m-d H:i', $value['validity']);
	}
	$value['addtime'] = date('Y-m-d H:i', $value['addtime']);
	
	if($value['status']<0){
		$value['status_class'] = 'grey-color';
	}elseif($value['status']==0){
		$value['status_class'] = 'red-color';
	}elseif($value['status']>0){
		$value['status_class'] = 'green-color';
	}

	if($value['status'] && $value['lesson_type']==1){
		$verify_log = $site_common->getOrderVerifyLog($value['id']);
		if(!$verify_log['count'] || $verify_log['count'] < $value['verify_number']){
			$value['show_qrcode'] = 1;
		}
		$value['verify_num'] = $verify_log['count'];
	}

	$mylessonlist[$key] = $value;
}

/* 检查超时未评价课程 */
if($setting['autogood']>0){
	$paytime = time()-$setting['autogood']*86400;
	$order = pdo_fetchall("SELECT a.id,a.ordersn,a.uid,a.openid,a.lessonid,a.bookname,b.nickname FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.status=:status AND a.uid=:uid AND a.paytime<:paytime", array(':status'=>1,':uid'=>$uid,':paytime'=>$paytime));

	foreach($order as $value){
		$evaluate = array(
			'uniacid'  => $uniacid,
			'orderid'  => $value['id'],
			'ordersn'  => $value['ordersn'],
			'lessonid' => $value['lessonid'],
			'bookname' => $value['bookname'],
			'uid'      => $value['uid'],
			'nickname' => $value['nickname'],
			'grade'    => 1,
			'content'  => $common['evaluate_page']['default_good'] ? $common['evaluate_page']['default_good'] : "好评",
			'type'	   => 0,
			'addtime'  => time(),
		);
		if(pdo_insert($this->table_evaluate, $evaluate)){
			/* 更新订单状态 */
			pdo_update($this->table_order, array('status'=>2), array('id'=>$value['id']));

			/* 课程总评论数 */
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE lessonid=:lessonid", array(':lessonid'=>$value['lessonid']));
			/* 课程好评数 */
			$good = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE lessonid=:lessonid AND grade=:grade", array(':lessonid'=>$value['lessonid'], ':grade'=>1));
			/* 更新课程好评率 */
			pdo_update($this->table_lesson_parent, array('score'=>round($good/$total,2)), array('id'=>$value['lessonid']));
		}
	}
}

if($op=='display'){
	/* 检查是否在微信中访问 */
	$agent = $this->checkUserAgent();

	$memberinfo = pdo_get($this->table_mc_members, array('uid'=>$uid), array('nickname','avatar'));
	if(empty($memberinfo['avatar'])){
		$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($memberinfo['avatar'], "http://") || strstr($memberinfo['avatar'], "https://");
		$avatar = $inc ? $memberinfo['avatar'] : $_W['attachurl'].$memberinfo['avatar'];
	}

}elseif($op=='cancle'){
	$is_delete = intval($_GPC['is_delete']);
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE id=:id AND uid=:uid ", array(':id'=>$orderid, ':uid'=>$uid));
	if(empty($order)){
		message("该订单不存在");
	}

	/* 删除订单 */
	if($is_delete){
		if($order['status'] != '-1'){
			message("该订单状态不允许删除");
		}
		if(pdo_update($this->table_order, array('is_delete'=>'1'), array('id'=>$orderid))){
			message("删除成功", $this->createMobileUrl('mylesson'), "success");
		}else{
			message("删除失败", "", "warning");
		}
	}

	if($order['status'] != '0'){
		message("该订单状态不允许取消");
	}

	if(pdo_update($this->table_order, array('status'=>'-1'), array('id'=>$orderid))){
		if($setting['stock_config']==1){
			$site_common->updateLessonStock($order['lessonid'], $order['spec_id'],'1');
		}
		if($order['coupon']>0){
			$upcoupon = array(
				'status'	=> 0,
				'ordersn'	=> "",
				'update_time' => "",
			);
			pdo_update($this->table_member_coupon, $upcoupon, array('id'=>$order['coupon']));
		}
		if($order['deduct_integral']>0){
			load()->model('mc');
			mc_credit_update($order['uid'], 'credit1', $order['deduct_integral'], array(0, '取消课程订单，sn:'.$order['ordersn']));
		}

		message("取消成功", $this->createMobileUrl('mylesson'), "success");
	}else{
		message("取消失败", "", "warning");
	}
}elseif($op=='ajaxgetlist'){
	$this->resultJson($mylessonlist);
}


include $this->template("../mobile/{$template}/mylesson");