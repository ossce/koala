<?php
/**
 * 课程优惠码转换
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$password = trim($_GPC['coupon_password']);
$coupon = pdo_get($this->table_coupon, array('uniacid'=>$uniacid, 'password'=>$password));

if(empty($coupon)){
	$jsonData = array(
		'code' => -1,
		'message' => '课程优惠码不存在',
	);
	$this->resultJson($jsonData);
}
if($coupon['is_use']==1){
	$jsonData = array(
		'code' => -1,
		'message' => '课程优惠码已被使用',
	);
	$this->resultJson($jsonData);
}
if($coupon['validity'] < time()){
	$jsonData = array(
		'code' => -1,
		'message' => '课程优惠码已过期',
	);
	$this->resultJson($jsonData);
}

$upcoupon = array(
	'is_use'	=> 1,
	'nickname'	=> $_SESSION['fy_lessonv2_'.$uniacid.'_nickname'],
	'uid'		=> $uid,
	'use_time'	=> time(),
);

if(pdo_update($this->table_coupon, $upcoupon, array('uniacid'=>$uniacid,'card_id'=>$coupon['card_id']))){
	$membeCoupon = array(
		'uniacid'		=> $uniacid,
		'uid'			=> $uid,
		'amount'		=> $coupon['amount'],
		'conditions'	=> $coupon['conditions'],
		'validity'		=> $coupon['validity'],
		'password'		=> $coupon['password'],
		'use_type'		=> $coupon['use_type'] ? $coupon['use_type'] : 1,
		'category_id'	=> $coupon['category_id'],
		'lesson_ids'	=> $coupon['lesson_ids'],
		'status'		=> 0,
		'source'		=> 1,
		'addtime'		=> time(),
	);

	if(pdo_insert($this->table_member_coupon, $membeCoupon)){
		$jsonData = array(
			'code' => 0,
			'message' => '转换成功',
		);
		$this->resultJson($jsonData);
	}else{
		$jsonData = array(
			'code' => -1,
			'message' => '转换失败，请稍候重试',
		);
		$this->resultJson($jsonData);
	}
}else{
	$jsonData = array(
		'code' => -1,
		'message' => '系统繁忙，请稍候重试',
	);
	$this->resultJson($jsonData);
}


?>