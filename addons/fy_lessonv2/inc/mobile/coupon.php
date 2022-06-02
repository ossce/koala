<?php
/**
 * 会员优惠券
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

$member = pdo_fetch("SELECT nickname FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$uid));

if($op=='display'){
	$title = "我的优惠券";
	$typeStatus = new TypeStatus();
	$source = $typeStatus->couponSource();

	$pindex =max(1,$_GPC['page']);
	$psize = 10;
	$status = trim($_GPC['status']);

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND uid=:uid AND status=:status ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid,':uid'=>$uid,':status'=>$status));
	foreach($list as $k=>$v){
		$v['startDate'] = date('Y.m.d', $v['addtime']);
		$v['endDate'] = date('Y.m.d', $v['validity']);
		$v['startTime'] = date(' H:i', $v['addtime']);
		$v['endTime'] = date(' H:i', $v['validity']);
		$v['classname'] = $status ==0 ? 'pepper-red' : '';

		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "".$category['name']."分类课程" : "全部分类课程";
			$v['url'] = $this->createMobileUrl('search', array('cat_id'=>$v['category_id']));
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程";
			$v['url'] = $this->createMobileUrl('couponlesson', array('member_coupon_id'=>$v['id']));
		}
		unset($category);
		
		$v['source_name'] = $source[$v['source']];
		$list[$k] = $v;

		if(time()>$v['validity'] && $v['status']==0){
			pdo_update($this->table_member_coupon, array('status'=>-1), array('id'=>$v['id']));
			unset($list[$k]);
		}
	}

	if($_W['isajax']){
		echo json_encode($list);
	}

}elseif($op=='addCoupon'){
	$title = "优惠码转换";

	if($_W['isajax']){
		$password = trim($_GPC['card_password']);
		$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_coupon). " WHERE uniacid=:uniacid AND password=:password", array(':uniacid'=>$uniacid, ':password'=>$password));
		if(empty($coupon)){
			$json_data = array(
				'code'	  => -1,
				'message' => "课程优惠码不存在",
			);
			$this->resultJson($json_data);
		}
		if($coupon['is_use']==1){
			$json_data = array(
				'code' 	  => -1,
				'message' => "课程优惠码已被使用",
			);
			$this->resultJson($json_data);
		}
		if($coupon['validity'] < time()){
			$json_data = array(
				'code'	  => -1,
				'message' => "课程优惠码已过期",
			);
			$this->resultJson($json_data);
		}

		$upcoupon = array(
			'is_use'	=> 1,
			'nickname'	=> $member['nickname'],
			'uid'		=> $uid,
			'use_time'	=> time(),
		);
		$res = pdo_update($this->table_coupon, $upcoupon, array('card_id'=>$coupon['card_id']));

		if($res){
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
				$json_data = array(
					'code'	  => 0,
					'message' => "转换成功",
				);
				$this->resultJson($json_data);
			}else{
				$json_data = array(
					'code'	  => -1,
					'message' => "写入会员优惠券失败，请稍后重试",
				);
				$this->resultJson($json_data);
			}
		}else{
			$json_data = array(
				'code'	  => -1,
				'message' => "写入课程优惠码失败，请稍后重试",
			);
			$this->resultJson($json_data);
		}
	}

}elseif($op=='give'){
	$title = "优惠券转赠";
	$studen_no = $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';

	$give_coupon = intval($common['give_coupon']);
	if(!$give_coupon){
		message("系统未开启转赠功能");
	}

	$member_coupon_id = intval($_GPC['member_coupon_id']);
	$member_coupon = pdo_get($this->table_member_coupon, array('uniacid'=>$uniacid,'id'=>$member_coupon_id, 'uid'=>$uid));
	if(empty($member_coupon)){
		message("优惠券不存在", "", "warning");
	}
	if($member_coupon['status'] != 0){
		message("优惠券已使用或已过期", "", "warning");
	}

	if($member_coupon['use_type']==1){
		$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$member_coupon['category_id']), array('name'));
		$member_coupon['category_name'] = $category['name'] ? "".$category['name']."分类课程" : "全部分类课程";
	}elseif($member_coupon['use_type']==2){
		$member_coupon['category_name'] = "指定部分课程";
	}

	if(checksubmit()){
		//提交操作
		$open_type = intval($_GPC['open_type']);
		$give_user = intval($_GPC['give_user']);
		if($open_type == 1){
			/* 学号验证方式 */
			if(!$give_user){
				message("请输入{$studen_no}(uid)", "", "warning");
			}
			$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$give_user));
			if(empty($user)){
				message("该{$studen_no}(uid)不存在", "", "warning");
			}
		}elseif($open_type == 2){
			/* 手机号码验证方式 */
			if(!$give_user){
				message("请输入手机号码", "", "warning");
			}
			$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$give_user));
			if(empty($user)){
				message("该手机号码不存在", "", "warning");
			}
			$give_user = $user['uid'];
		}

		if($give_user == $uid){
			message("不能转赠给自己", "", "warning");
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
			message("转赠成功", $this->createMobileUrl('coupon'), "success");
		}else{
			message("系统繁忙，请稍后重试", "", "error");
		}
	}
}

if(!$_W['isajax']){
	include $this->template("../mobile/{$template}/coupon");
}

?>