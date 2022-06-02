<?php
/**
 * 定时任务
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

set_time_limit(0); 
ignore_user_abort(true);

$hour = date('H', time());
$minute = date('i', time());

/*
 * 检查超期未支付订单
 */
if (time() > $setting['closelast'] + $setting['closespace'] * 60 && $setting['closespace'] != 0) {
	$time = time() - $setting['closespace'] * 60;

	/* 取消指定时间内未支付订单 */
	$nopay_order = pdo_fetchall("SELECT id FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND status=:status AND addtime<:addtime LIMIT 5000", array(':uniacid'=>$uniacid, ':status'=>0, ':addtime'=>$time));

	foreach ($nopay_order as $item) {
		$order = pdo_fetch("SELECT id,ordersn,uid,lessonid,coupon,deduct_integral,spec_id FROM " .tablename($this->table_order). " WHERE id=:id AND status=:status LIMIT 1", array(':id'=>$item['id'],':status'=>0));
		if(empty($order)) continue;

		if($setting['stock_config']==1){
			$site_common->updateLessonStock($order['lessonid'], $order['spec_id'], '1');
		}

		pdo_update($this->table_order, array('status' => '-1'), array('id' => $order['id']));
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
			mc_credit_update($order['uid'], 'credit1', $order['deduct_integral'], array(0, '取消微课堂订单，sn:'.$order['ordersn']));
		}
	}

	/* 更新执行时间 */
	pdo_update($this->table_setting, array('closelast' => time()), array('id' => $setting['id']));
}


/*
 * 检查超期未评价订单
 */
 $order_default_good = $common['evaluate_page']['default_good'] ? $common['evaluate_page']['default_good'] : "好评!";
if($setting['autogood']>0){
	$paytime = time()-$setting['autogood']*86400;
	$order = pdo_fetchall("SELECT id,ordersn,uid,lessonid,bookname,teacherid FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND status=:status AND paytime<:paytime LIMIT 5000", array(':uniacid'=>$uniacid,':status'=>1,':paytime'=>$paytime));

	foreach($order as $value){
		$data = array(
			'uniacid'			=> $uniacid,
			'orderid'			=> $value['id'],
			'ordersn'			=> $value['ordersn'],
			'lessonid'			=> $value['lessonid'],
			'bookname'			=> $value['bookname'],
			'teacherid'			=> $value['teacherid'],
			'uid'				=> $value['uid'],
			'grade'				=> 1,
			'global_score'		=> 5,
			'content_score'		=> 5,
			'understand_score'  => 5,
			'content'			=> $order_default_good,
			'type'				=> 0,
			'addtime'			=> time(),
		);

		if(pdo_insert($this->table_evaluate, $data)){
			/* 更新订单状态 */
			pdo_update($this->table_order, array('status'=>2), array('id'=>$value['id']));

			/* 订单评价 */
			$site_common->systemEvaluate($data);
		}
	}
}

/* 检查默认好评的评价，置为系统评价类型 */
$evaluate_list = pdo_fetchall("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uniacid=:uniacid AND content=:content LIMIT 5000", array(':uniacid'=>$uniacid,':content'=>$order_default_good));
foreach($evaluate_list as $item){
	pdo_update($this->table_evaluate, array('type'=>0), array('id'=>$item['id']));
}


/*
 * 检查已过期优惠券
 */
 $coupon_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND status=:status AND validity<=:validity LIMIT 5000", array(':uniacid'=>$uniacid,':status'=>0, ':validity'=>time()));
 foreach($coupon_list as $value){
	 pdo_update($this->table_member_coupon, array('status'=>-1, 'update_time'=>time()), array('id'=>$value['id']));
 }

/*
 * 检查课程章节定期上架
 */
 $section_list = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND status=:status AND auto_show=:auto_show AND show_time<=:show_time LIMIT 5000", array(':uniacid'=>$uniacid, ':status'=>0, ':auto_show'=>1, ':show_time'=>time()));
 foreach($section_list as $item){
   pdo_update($this->table_lesson_son, array('status'=>1,'auto_show'=>0,'show_time'=>''), array('id'=>$item['id']));
 }


/*
 * 检查过期会员
 */
 $vipmember = pdo_fetchall("SELECT uid FROM " .tablename($this->table_member). " WHERE vip=:vip", array(':vip'=>1));
 foreach($vipmember as $member){
	 $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity LIMIT 5000", array(':uid'=>$member['uid'], ':validity'=>time()));
	 if($total==0){
		$this->updateMemberVip($member['uid'], 0);
	 }
 }

/*
 * 课程总库存校准
 * 每天凌晨03:10~03:30执行
 */
if($setting['stock_config'] && $hour==3 && $minute>10 && $minute<30){
	$list = pdo_getall($this->table_lesson_parent, array('uniacid'=>$uniacid), array('id'));
	foreach($list as $item){
		$total_stock = pdo_fetchcolumn("SELECT SUM(spec_stock) FROM " .tablename($this->table_lesson_spec). " WHERE uniacid=:uniacid AND lessonid=:lessonid", array(':uniacid'=>$uniacid,':lessonid'=>$item['id']));
		pdo_update($this->table_lesson_parent, array('stock'=>$total_stock), array('uniacid'=>$uniacid,'id'=>$item['id']));
	}
}


/* 定时上架课程 */
$list = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND status=:status AND show_time>0 AND show_time<=:show_time", array(':uniacid'=>$uniacid,':status'=>0,':show_time'=>time()));
foreach($list as $item){
	pdo_update($this->table_lesson_parent, array('status'=>1,'show_time'=>''), array('uniacid'=>$uniacid,'id'=>$item['id']));
}


/* 临时功能 更新佣金记录表来源 START */
$manage_source = pdo_get($this->table_commission_log, array('grade'=>'-1','source'=>'0'));
if($manage_source){
	pdo_update($this->table_commission_log, array('source'=>4), array('grade'=>'-1','source'=>'0'));
}

$lesson_source = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_log). " WHERE grade>:grade AND source=:source AND remark LIKE :remark LIMIT 0,1", array(':grade'=>0,':source'=>0,':remark'=>'%订单号L%'));
if($lesson_source){
	pdo_query("UPDATE " .tablename($this->table_commission_log). " SET `source`= 1 WHERE `grade`>0 AND `source`=0 AND `remark` LIKE '%订单号L%';");	
}

$vip_source = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_log). " WHERE grade>:grade AND source=:source AND remark LIKE :remark LIMIT 0,1", array(':grade'=>0,':source'=>0,':remark'=>'%订单号V%'));
if($vip_source){
	pdo_query("UPDATE " .tablename($this->table_commission_log). " SET `source`= 2 WHERE `grade`>0 AND `source`=0 AND `remark` LIKE '%订单号V%';");	
}

$teacher_source = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_log). " WHERE grade>:grade AND source=:source AND remark LIKE :remark LIMIT 0,1", array(':grade'=>0,':source'=>0,':remark'=>'%订单号T%'));
if($teacher_source){
	pdo_query("UPDATE " .tablename($this->table_commission_log). " SET `source`= 3 WHERE `grade`>0 AND `source`=0 AND `remark` LIKE '%订单号T%';");	
}
/* 临时功能 更新佣金记录表来源 END */

echo "success:".date('Y-m-d H:i:s');

?>