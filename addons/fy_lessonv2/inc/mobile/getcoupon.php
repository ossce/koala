<?php
/**
 * 优惠券中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if((!$userAgent && in_array('getcoupon', $login_visit)) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}

$title = "优惠券中心";
$uid = $_W['member']['uid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 10;

if($op=='display'){
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid AND status=:status AND is_exchange=:is_exchange ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid,':status'=>1, ':is_exchange'=>1));
	foreach($list as $k=>$v){
		$v['conditions'] = $v['conditions']>0 ? "满".$v['conditions']."可用" : "无金额门槛";
		$v['integral'] = $v['exchange_integral']>0 ? "需".$v['exchange_integral']."积分" : "免费";
		$v['already_per'] = round($v['already_exchange']/$v['total_exchange'], 2) * 100;
		$v['already_per'] = $v['already_per'] > 100 ? '100%' : $v['already_per'].'%';
		
		$v['is_end'] = 0;
		if($v['already_exchange']>=$v['total_exchange'] || $v['status']==0){
			$v['is_end'] = 1;
		}

		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "适用".$category['name']."分类的课程" : "适用全部分类的课程";
			$v['coupon_link'] = $this->createMobileUrl('search', array('cat_id'=>$v['category_id']));
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程可用";
			$v['coupon_link'] = $this->createMobileUrl('couponlesson', array('coupon_id'=>$v['id']));
		}

		$list[$k] = $v;		
		unset($category);
	}

	$market = pdo_fetch("SELECT coupon_desc FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	$coupon_desc = $market['coupon_desc'] ? explode("\n", $market['coupon_desc']) : "";

}elseif($op=='getcoupon'){
	checkauth();
	$member = pdo_fetch("SELECT credit1 FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$uid));

	$id = $_GPC['id'];
	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid AND id=:id AND status=:status AND is_exchange=:is_exchange", array(':uniacid'=>$uniacid,':id'=>$id,':status'=>1,':is_exchange'=>1));
	if(empty($coupon)){
		message("该优惠券不存在", $this->createMobileUrl('getcoupon'), "warning");
	}
	if($coupon['already_exchange']>=$coupon['total_exchange']){
		message("该优惠券已被抢光", $this->createMobileUrl('getcoupon'), "warning");
	}

	$already = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND coupon_id=:coupon_id AND uid=:uid", array(':uniacid'=>$uniacid,':coupon_id'=>$id,':uid'=>$uid));	
	if($already>=$coupon['max_exchange']){
		message("您已经兑换{$already}张了，留点给别人吧", $this->createMobileUrl('getcoupon'), "warning");
	}
	if($member['credit1']<$coupon['exchange_integral']){
		message("您的积分不足于兑换", $this->createMobileUrl('getcoupon'), "warning");
	}

	load()->model('mc');
	if(mc_credit_update($uid, 'credit1', '-'.$coupon['exchange_integral'], array(0, '兑换优惠券:'.$id))){
		$memberCoupon = array(
			'uniacid'		=> $uniacid,
			'uid'			=> $uid,
			'amount'		=> $coupon['amount'],
			'conditions'	=> $coupon['conditions'],
			'validity'		=> $coupon['validity_type']==1 ? $coupon['days1'] : time()+$coupon['days2']*86400,
			'use_type'		=> $coupon['use_type'],
			'category_id'	=> $coupon['category_id'],
			'lesson_ids'	=> $coupon['lesson_ids'],
			'status'		=> 0,
			'source'		=> 5,
			'coupon_id'		=> $id,
			'addtime'		=> time(),
		);
		if(pdo_insert($this->table_member_coupon, $memberCoupon)){
			pdo_update($this->table_mcoupon, array('already_exchange'=>$coupon['already_exchange']+1), array('id'=>$id));
			message("领取成功", $this->createMobileUrl('coupon'), "success");
		}else{
			message("领取优惠券失败", $this->createMobileUrl('getcoupon'), "warning");
		}
	}else{
		message("扣除用户积分失败", $this->createMobileUrl('getcoupon'), "warning");
	}

}elseif($op=='free'){
	checkauth();
	
	$id = $_GPC['id'];
	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid AND id=:id AND status=:status AND receive_link=:receive_link", array(':uniacid'=>$uniacid,':id'=>$id,':status'=>1,'receive_link'=>1));
	if(empty($coupon)){
		message("优惠券不支持该领取方式", $this->createMobileUrl('coupon'), "warning");
	}
	if($coupon['already_exchange']>$coupon['total_exchange']){
		message("该优惠券已被抢光", $this->createMobileUrl('coupon'), "warning");
	}

	$already = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND coupon_id=:coupon_id AND uid=:uid", array(':uniacid'=>$uniacid,':coupon_id'=>$id,':uid'=>$uid));	
	if($already>=$coupon['max_exchange']){
		message("您已经领取{$already}张了，留点给别人吧", $this->createMobileUrl('coupon'), "warning");
	}

	load()->model('mc');
	$memberCoupon = array(
		'uniacid'		=> $uniacid,
		'uid'			=> $uid,
		'amount'		=> $coupon['amount'],
		'conditions'	=> $coupon['conditions'],
		'validity'		=> $coupon['validity_type']==1 ? $coupon['days1'] : time()+$coupon['days2']*86400,
		'use_type'		=> $coupon['use_type'],
		'category_id'	=> $coupon['category_id'],
		'lesson_ids'	=> $coupon['lesson_ids'],
		'status'		=> 0,
		'source'		=> 8,
		'coupon_id'		=> $id,
		'addtime'		=> time(),
	);
	if(pdo_insert($this->table_member_coupon, $memberCoupon)){
		pdo_update($this->table_mcoupon, array('already_exchange'=>$coupon['already_exchange']+1), array('id'=>$id));
		message("领取成功", $this->createMobileUrl('coupon'), "success");
	}else{
		message("领取优惠券失败", $this->createMobileUrl('coupon'), "warning");
	}
	
}

if(!$_W['isajax']){
	include $this->template("../mobile/{$template}/getcoupon");
}
if($_W['isajax'] && $op=='display'){
	echo json_encode($list);
}

?>