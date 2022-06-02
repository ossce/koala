<?php
/**
 * 我的优惠券
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$title = "我的优惠券";
$typeStatus = new TypeStatus();
$source = $typeStatus->couponSource();

$studen_no = $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';

if($op=='display'){
	$pindex =max(1,$_GPC['page']);
	$psize = 12;
	$status = trim($_GPC['status']);

	$condition = "uniacid=:uniacid AND uid=:uid AND status=:status ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;
	$params[':status'] = $status;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){

		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "仅限[".$category['name']."]分类的课程使用" : "适用于全部课程分类";
			$v['url'] = "/{$uniacid}/search.html?pid={$v['category_id']}";
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程";
			$v['url'] = "/{$uniacid}/couponlesson.html?member_coupon_id={$v['id']}";
		}
		unset($category);

		$list[$k] = $v;
		
		if(time()>$v['validity'] && $v['status']==0){
			pdo_update($this->table_member_coupon, array('status'=>-1), array('id'=>$v['id']));
			unset($list[$k]);
		}
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member_coupon) . " WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='give'){
	$member_coupon_id = intval($_GPC['member_coupon_id']);
	$open_type = intval($_GPC['open_type']);
	$give_user = intval($_GPC['give_user']);

	$give_coupon = intval($common['give_coupon']);
	if(!$give_coupon){
		$json_data = array(
			'code'	  => -1,
			'message' => "系统未开启转赠功能",
		);
		$this->resultJson($json_data);
	}

	$member_coupon = pdo_get($this->table_member_coupon, array('uniacid'=>$uniacid,'id'=>$member_coupon_id, 'uid'=>$uid));
	if(empty($member_coupon)){
		$json_data = array(
			'code'	  => -1,
			'message' => "优惠券不存在",
		);
		$this->resultJson($json_data);
	}
	if($member_coupon['status'] != 0){
		$json_data = array(
			'code'	  => -1,
			'message' => "优惠券已使用或已过期",
		);
		$this->resultJson($json_data);
	}

	if($open_type == 1){
		/* 学号验证方式 */
		if(!$give_user){
			$json_data = array(
				'code'	  => -1,
				'message' => "请输入{$studen_no}(uid)",
			);
			$this->resultJson($json_data);
		}
		$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$give_user));
		if(empty($user)){
			$json_data = array(
				'code'	  => -1,
				'message' => "该{$studen_no}(uid)不存在",
			);
			$this->resultJson($json_data);
		}
	}elseif($open_type == 2){
		/* 手机号码验证方式 */
		if(!$give_user){
			$json_data = array(
				'code'	  => -1,
				'message' => "请输入手机号码",
			);
			$this->resultJson($json_data);
		}
		$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$give_user));
		if(empty($user)){
			$json_data = array(
				'code'	  => -1,
				'message' => "该手机号码不存在",
			);
			$this->resultJson($json_data);
		}
		$give_user = $user['uid'];
	}

	if($give_user == $uid){
		$json_data = array(
			'code'	  => -1,
			'message' => "不能转赠给自己",
		);
		$this->resultJson($json_data);
	}

	$new_data = array(
		'uniacid'		=> $uniacid,
		'uid'			=> $give_user,
		'amount'		=> $member_coupon['amount'],
		'conditions'	=> $member_coupon['conditions'],
		'validity'		=> $member_coupon['validity'],
		'use_type'		=> $member_coupon['use_type'],
		'category_id'	=> $member_coupon['category_id'],
		'lesson_ids'	=> $member_coupon['lesson_ids'],
		'status'		=> 0,
		'source'		=> 9,
		'coupon_id'		=> $member_coupon['coupon_id'],
		'addtime'		=> time(),
		'update_time'	=> time(),
	);

	if(pdo_insert($this->table_member_coupon, $new_data)){
		pdo_update($this->table_member_coupon, array('status'=>1), array('uniacid'=>$uniacid,'id'=>$member_coupon_id));
		
		$json_data = array(
			'code'	  => 0,
			'message' => "转赠成功",
		);
		$this->resultJson($json_data);
	}else{
		$json_data = array(
			'code'	  => -1,
			'message' => "系统繁忙，请稍后重试",
		);
		$this->resultJson($json_data);
	}
}


include $this->template("../webapp/{$template}/coupon");


?>