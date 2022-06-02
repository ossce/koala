<?php
/**
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$id = intval($_GPC['id']); /* 课程id */
$uid = $_W['member']['uid'];

if (empty($id)) {
    message("参数缺失", "", "warning");
}

$nopay_order = pdo_get($this->table_order, array('uniacid'=>$uniacid,'uid'=>$uid,'lessonid'=>$id,'status'=>0,'is_delete'=>0), array('id'));
if (!empty($nopay_order)) {
	message("您还有该课程未付款的订单", $this->createMobileUrl('mylesson', array('status'=>'nopay')), "warning");
}

$pay_order = pdo_fetch("SELECT id,lesson_type FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
if(!empty($pay_order) && $pay_order['lesson_type']!=1){
	message("您已购买该课程，无需重复购买", $this->createMobileUrl('lesson', array('id' => $id)), "warning");
}

/* 检查黑名单操作 */
$site_common->check_black_list('order', $uid);

$lesson = pdo_fetch("SELECT * FROM " . tablename($this->table_lesson_parent) . " WHERE uniacid=:uniacid AND id=:id AND (status=1 OR status=3) LIMIT 1", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($lesson)) {
    message("课程不存在或已下架", "", "warning");
}

if($lesson['lesson_type']==1){
	$buynow_info = json_decode($lesson['buynow_info'], true);
	if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
		message("报名活动已结束", $this->createMobileUrl('lesson', array('id' => $id)), "warning");
	} 
}

/* 检查用户是否完善信息 */
$member = pdo_fetch("SELECT a.*,b.credit1,b.nickname,b.realname,b.mobile,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.address,b.education,b.position FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$uid));
if($setting['mustinfo']) {
	$user_info = json_decode($setting['user_info']);
	if(!empty($common_member_fields)){
		foreach($common_member_fields as $v){
			if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
				 message("请先完善您的信息", $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
			}
		}
	}
}

/* 课程规格 */
$spec_id = intval($_GPC['spec_id']);
if (empty($spec_id)) {
    message("课程规格不存在", "", "warning");
}

$spec = pdo_get($this->table_lesson_spec, array('uniacid'=>$uniacid,'lessonid'=>$id,'spec_id'=>$spec_id));
if(empty($spec)){
	message("课程规格不存在", "", "warning");
}

/* 检查是否开启库存 */
if($setting['stock_config']==1){
	if($spec['spec_stock'] <=0 ){
		message("您选择的规格已售罄，请重新选择", $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
	}
}

/* 报名课程附加个人信息 */
$appoint_name = json_decode($lesson['appoint_info'], true);
if(!empty($appoint_name) && $lesson['lesson_type']==1){
	foreach($_GPC['appoint_info'] as $k=>$v){
		if(empty($v)){
			message("请填写".$appoint_name[$k]);
		}

		$appoint_info[] = $appoint_name[$k].'：'.preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9@.]#u','',$v);
	}
}

/* 检查会员是否享受折扣 */
$memberVip_list = pdo_fetchall("SELECT * FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));

$discount = 100; /* 初始折扣为100，即100% */
if(!empty($memberVip_list)){
	$isVip = true;
	foreach($memberVip_list as $v){
		if($v['discount']>0 && $v['discount'] < $discount) {
			$discount = $v['discount'];
		}
	}
}

/* 检查课程是否参加限时活动 */
$discount_lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_discount_lesson). " WHERE uniacid=:uniacid AND lesson_id=:lesson_id AND starttime<:time AND endtime>:time", array(':uniacid'=>$uniacid,':lesson_id'=>$id,':time'=>time()));
if(!empty($discount_lesson)){
	$spec['spec_price'] = round($spec['spec_price']*$discount_lesson['discount']*0.01, 2);
}

$price = $spec['spec_price'];
if ($isVip && $discount<=100) { /* 折扣开启 */
    if ($spec['spec_price'] > 0) {
        if ($lesson['isdiscount'] == 1) {/* 课程开启折扣 */
            if(!$discount_lesson || ($discount_lesson && $discount_lesson['member_discount'])){
				if ($lesson['vipdiscount']) {
					/* 使用课程单独百分比折扣优惠 */
					$price = round($spec['spec_price'] * $lesson['vipdiscount'] * 0.01, 2);

				}elseif ($lesson['vipdiscount_money']>0) {
					/* 使用课程单独固定金额优惠 */
					$price = $spec['spec_price'] - $lesson['vipdiscount_money'];

				}else {/* 使用VIP等级最低折扣 */
					$price = round($spec['spec_price'] * $discount * 0.01, 2);
				}
			}
        }
    } else {
        $price = 0;
    }
}
$vipCoupon = $spec['spec_price'] - $price;
$ordersn = 'L' . date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999));

/* 优惠券抵扣 */
$coupon_id = intval($_GPC['coupon_id']);
if($coupon_id>0 && $lesson['support_coupon']){
	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND id=:id AND uid=:uid AND status=:status", array(':uniacid'=>$uniacid,':id'=>$coupon_id,':uid'=>$uid, ':status'=>0));
	if(empty($coupon)){
		message("优惠券不存在", "", "warning");
	}
	if($price < $coupon['conditions']){
		message("支付金额未满".$coupon['conditions']."元，无法使用该优惠券", "", "warning");
	}
	if(time() > $coupon['validity']){
		message("优惠券已过期", "", "warning");
	}

	if($v['use_type']==1){
		if($coupon['category_id'] && $lesson['pid']!=$coupon['category_id']){
			message("优惠券不支持该课程分类使用", "", "warning");
		}
	}elseif($v['use_type']==2){
		$lesson_ids = json_decode($coupon['lesson_ids'], true);
		if(!in_array($id, $lesson_ids)){
			message("优惠券不支持该课程使用", "", "warning");
		}
	}

	$price -= $coupon['amount'];
	$price = $price>0 ? $price : 0;
}

/* 积分抵扣 */
if(intval($_GPC['deduct_integral'])>0 && $price>0){
	$deduct_integral = intval($_GPC['deduct_integral']);
	$market = pdo_fetch("SELECT * FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

	if($market['deduct_switch']==1 && $market['deduct_money']>0 && $lesson['deduct_integral']>0 && $member['credit1']>0){
		$deduct_money = round($deduct_integral*$market['deduct_money'], 2);

		if($member['credit1'] < $deduct_integral){
			message("用户积分余额不足".$deduct_integral."积分", "", "warning");
		}
		if($deduct_integral > $lesson['deduct_integral']){
			message("当前课程最多可使用".$lesson['deduct_integral']."积分", "", "warning");
		}
		if($deduct_money > $price){
			$dis_money = $deduct_money - $price;
			$dis_integral = $dis_money/$market['deduct_money'];
			$deduct_integral -= $dis_integral;
		}else{
			$price -= $deduct_money;
		}
	}

}

/* 报名课程且会员为VIP免费时，报名价格为0 */
if(!empty($memberVip_list) && $lesson['lesson_type']==1){
	foreach($memberVip_list as $v){
		if(in_array($v['level_id'], json_decode($lesson['vipview']))){
			$price = 0;
			break;
		}
	}
}

$orderdata = array(
    'acid'			=> $_W['account']['acid'],
    'uniacid'		=> $uniacid,
    'ordersn'		=> $ordersn,
    'uid'			=> $uid,
	'lesson_type'	=> $lesson['lesson_type'],
	'verify_number'	=> $lesson['lesson_type']==1 ? $lesson['verify_number'] : 0,
	'appoint_info'	=> json_encode($appoint_info),
	'spec_id'		=> $spec_id,
	'spec_name'		=> $spec['spec_name'],
	'spec_day'		=> $spec['spec_day']==-1 ? 0 : $spec['spec_day'],
    'lessonid'		=> $id,
    'bookname'		=> $lesson['bookname'],
    'marketprice'	=> $spec['spec_price'],
	'coupon'		=> $coupon_id ? $coupon_id : "",
	'coupon_amount' => $coupon['amount'],
    'price'			=> $price,
	'teacherid'		=> $lesson['teacherid'],
	'integral'		=> $lesson['integral_rate']>0 ? ceil($price*$lesson['integral_rate']) : $lesson['integral'],
	'deduct_integral' => $deduct_integral,
	'vip_discount'	=> $vipCoupon,
	'validity'		=> $spec['spec_day']==-1 ? 0 : $spec['spec_day'],
    'addtime'		=> time(),
);

/* 检查课程是否存在讲师分成 */
$teacher = pdo_fetch("SELECT id,uid,company_uid FROM " . tablename($this->table_teacher) . " WHERE id=:id", array(':id'=>$lesson['teacherid']));
if ($lesson['teacher_income'] > 0 && $teacher['uid']>0) {
    $orderdata['teacher_income'] = $lesson['teacher_income'];
} else {
    $orderdata['teacher_income'] = 0;
}
if($setting['company_income'] && $teacher['company_uid']>0){
	$orderdata['company_uid'] = $teacher['company_uid'];
	$orderdata['company_income'] = $lesson['company_income'];
}

/* 检查当前分销功能是否开启且课程价格大于0 */
if ($comsetting['is_sale'] == 1 && $spec['spec_price'] > 0) {
    $orderdata['commission1'] = 0;
    $orderdata['commission2'] = 0;
    $orderdata['commission3'] = 0;

    if ($comsetting['self_sale'] == 1) {
        /* 开启分销内购，一级佣金为购买者本人 */
        $orderdata['member1'] = $uid;
        $orderdata['member2'] = $site_common->getParentid($uid);
        $orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
    } else {
        /* 关闭分销内购 */
        $orderdata['member1'] = $site_common->getParentid($uid);
        $orderdata['member2'] = $site_common->getParentid($orderdata['member1']);
        $orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
    }

    $lessoncom = unserialize($lesson['commission']);	  /* 本课程佣金比例 */
    $settingcom = unserialize($comsetting['commission']); /* 全局佣金比例 */
    
    if ($orderdata['member1'] && $price>0) {
		$orderdata['commission1'] = $site_common->getAgentCommission1($lessoncom['commission_type'], $lessoncom['commission1'], $settingcom['commission1'], $price, $orderdata['member1']);
    }
	
    if ($orderdata['member2'] && $price>0 && in_array($comsetting['level'], array('2', '3'))) {
		$orderdata['commission2'] = $site_common->getAgentCommission2($lessoncom['commission_type'], $lessoncom['commission2'], $settingcom['commission2'], $price, $orderdata['member2']);
    }

    if ($orderdata['member3'] && $price>0 && $comsetting['level'] == 3) {
		$orderdata['commission3'] = $site_common->getAgentCommission3($lessoncom['commission_type'], $lessoncom['commission3'], $settingcom['commission3'], $price, $orderdata['member3']);
    }
}

//普通课程价格为0时，重新校验订单
if($lesson['lesson_type']==0 && !$price && (!$coupon['amount'] && !$deduct_money)){
	$rep_lesson = pdo_get($this->table_lesson_parent, array('id'=>$id,'status'=>1), array('price'));
	if($rep_lesson['price']){
		message("网络错误，请稍后重试", "", "warning");
	}
}

if ($uid>0) {
    pdo_insert($this->table_order, $orderdata);
    $orderid = pdo_insertid();
}

if ($orderid) {
	//减少库存
	if($setting['stock_config']==1){
		$site_common->updateLessonStock($id, $spec_id, '-1');
	}
	
	if($coupon_id>0){/* 更改优惠券状态 */
		$couponData = array(
			'ordersn' => $ordersn,
			'status'  => 1,
			'update_time' => time()
		);
		pdo_update($this->table_member_coupon, $couponData, array('id'=>$coupon_id));
	}
	if($deduct_integral>0){/* 扣除会员积分 */
		load()->model('mc');
		mc_credit_update($uid, 'credit1', '-'.$deduct_integral, array(0, '购买课程使用积分抵扣，sn:'.$ordersn));
	}

    header("Location:" . $this->createMobileUrl('pay', array('orderid' => $orderid, 'ordertype'=>'buylesson')));
} else {
    message("系统繁忙，写入订单失败，请稍候重试", "", "warning");
}

?>