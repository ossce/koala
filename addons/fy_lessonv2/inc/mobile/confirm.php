<?php
/**
 * 确认下单
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();

$title = "确认下单";
$id = intval($_GPC['id']); /* 课程id */
$lessonurl = $this->createMobileUrl('lesson', array('id'=>$id));
$uid = $_W['member']['uid'];
$mc_member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid), array('credit1'));


$nopay_order = pdo_get($this->table_order, array('uniacid'=>$uniacid,'uid'=>$uid,'lessonid'=>$id,'status'=>0,'is_delete'=>0), array('id'));
if (!empty($nopay_order)) {
	message("您还有该课程未付款的订单", $this->createMobileUrl('mylesson', array('status'=>'nopay')), "warning");
}

$pay_order = pdo_fetch("SELECT id,lesson_type FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
if(!empty($pay_order) && $pay_order['lesson_type']!=1){
	message("您已购买该课程，无需重复购买", $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
}

/* 检查黑名单操作 */
$site_common->check_black_list('order', $uid);

$lesson = pdo_fetch("SELECT a.*,b.teacher,b.teacherphoto FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND (a.status=1 OR a.status=3) LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));

if (empty($lesson)) {
    message("课程不存在或已下架", "", "warning");
}

if($lesson['lesson_type']==1){
	$buynow_info = json_decode($lesson['buynow_info'], true);
	if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
		message("报名活动已结束");
	} 
}

/* 课程规格 */
$spec_id = intval($_GPC['spec_id']);
if (empty($spec_id)) {
    message("课程规格不存在！", "", "warning");
}
$spec = pdo_get($this->table_lesson_spec, array('uniacid'=>$uniacid,'lessonid'=>$id,'spec_id'=>$spec_id));
if(empty($spec)){
	message("课程规格不存在！", "", "warning");
}

if(!empty($lesson['teacherphoto'])){
	$teacherphoto = $_W['attachurl'].$lesson['teacherphoto'];
}else{
	$teacherphoto = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
}

/* 检查是否开启库存 */
if($setting['stock_config']==1){
	if($spec['spec_stock'] <=0 ){
		message("您选择的规格已售罄，请重新选择", "", "warning");
	}
}

/* 检查用户是否完善信息 */
$member = pdo_fetch("SELECT a.*,b.avatar,b.realname,b.mobile,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.address,b.education,b.position,b.credit1 FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$uid));

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

/*检查积分抵扣开关和课程是否支持积分抵扣*/
$market = pdo_fetch("SELECT * FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
if($market['deduct_switch']==1 && $market['deduct_money']>0 && $lesson['deduct_integral']>0 && $member['credit1']>0){
	$deduct_switch = 1;
	$deduct_integral = $lesson['deduct_integral'] >= $member['credit1'] ? $member['credit1'] : $lesson['deduct_integral'];
	$deduct_money = $deduct_integral*$market['deduct_money'];
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

				} else {
					/* 使用VIP等级最低折扣 */
					$price = round($spec['spec_price'] * $discount * 0.01, 2);
				}
			}
        }
    } else {
        $price = 0;
    }
}

$vipCoupon = $spec['spec_price'] - $price;

/*判断可用优惠券*/
if($lesson['support_coupon']==1){
	$coupon_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND uid=:uid AND conditions<=:conditions AND validity>=:validity AND status=:status ORDER BY amount DESC,validity ASC", array(':uniacid'=>$uniacid,':uid'=>$uid, ':conditions'=>$price, ':validity'=>time(), ':status'=>0));
	foreach($coupon_list as $k=>$v){
		if($v['use_type']==1){
			if($v['category_id'] && $lesson['pid']!=$v['category_id']){
				unset($coupon_list[$k]);
				continue;
			}

			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "仅限[".$category['name']."]分类的课程" : "全部课程分类";
			unset($category);
		}elseif($v['use_type']==2){
			$lesson_ids = json_decode($v['lesson_ids'], true);
			if(!in_array($id, $lesson_ids)){
				unset($coupon_list[$k]);
				continue;
			}

			$v['category_name'] = "指定部分课程可用";
		}

		$coupon_list[$k] = $v;
	}
}

/* 报名课程附加个人信息 */
$appoint_info = json_decode($lesson['appoint_info'], true);

/* 报名课程且会员为VIP免费时，报名价格为0 */
if(!empty($memberVip_list) && $lesson['lesson_type']==1){
	foreach($memberVip_list as $v){
		if(in_array($v['level_id'], json_decode($lesson['vipview']))){
			$apply_price = $price;
			$price = 0;
			break;
		}
	}
}


include $this->template("../mobile/{$template}/confirm");


?>