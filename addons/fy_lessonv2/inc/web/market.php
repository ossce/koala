<?php
/**
 * 营销管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */
 
$market = pdo_fetch("SELECT * FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

$typeStatus = new TypeStatus();
/* 优惠券来源 */
$source = $typeStatus->couponSource();

/* 课程状态 */
$lessonStatusList = $typeStatus->lessonStatus();

/* 课程分类 */
$category_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>0));
foreach($category_list as $k=>$v){
	$v['child'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>$v['id']));

	$category_list[$k] = $v;
}

$pindex = max(1, intval($_GPC['page']));
$psize = 15;


if($op=='display'){
	/* 抵扣设置 */
	include_once 'market/display.php';

}elseif($op=='coupon'){
	/* 优惠券列表 */
	include_once 'market/coupon.php';

}elseif($op=='addCoupon'){
	/* 添加优惠券 */
	include_once 'market/addCoupon.php';

}elseif($op=='delAllCoupon'){
	/* 删除优惠券 */
	include_once 'market/delAllCoupon.php';

}elseif($op=='couponRule'){
	/* 优惠券规则 */
	include_once 'market/couponRule.php';

}elseif($op=='sendCoupon'){
	/* 发放优惠券 */
	include_once 'market/sendCoupon.php';

}elseif($op=='couponLog'){
	/* 优惠券记录 */
	include_once 'market/couponLog.php';

}elseif($op=='couponDetail'){
	/* 优惠券记录详情 */
	include_once 'market/couponDetail.php';

}elseif($op=='lessonCard'){
	/* 课程卡密 */
	include_once 'market/lessonCard.php';

}elseif($op=='addLessonCard'){
	/* 添加课程卡密 */
	include_once 'market/addLessonCard.php';

}elseif($op=='delLessonCard'){
	/* 删除课程卡密 */
	include_once 'market/delLessonCard.php';

}elseif($op=='couponCode'){
	/* 课程优惠码 */
	include_once "market/couponCode.php";

}elseif($op=='addCouponCode'){
	/* 添加课程优惠码 */
	include_once "market/addCouponCode.php";

}elseif($op=='delAllCouponCode'){
	/* 删除课程优惠码 */
	include_once "market/delAllCouponCode.php";

}elseif($op=='discount'){
	/* 限时折扣 */
	include_once 'market/discount.php';

}elseif($op=='addDiscount'){
	/* 添加限时折扣活动 */
	include_once 'market/addDiscount.php';

}elseif($op=='delDiscount'){
	/* 删除限时折扣活动 */
	include_once 'market/delDiscount.php';

}elseif($op=='discountLesson'){
	/* 限时折扣活动课程列表 */
	include_once 'market/discountLesson.php';

}elseif($op=='addDiscountLesson'){
	/* 添加课程到折扣活动 */
	include_once 'market/addDiscountLesson.php';

}elseif($op=='discountLessonPost'){
	/* 在限时活动里添加或移除课程 */
	include_once 'market/discountLessonPost.php';

}elseif($op=='signin'){
	/* 积分签到明细 */
	include_once 'market/signin.php';

}

include $this->template('web/market');

?>