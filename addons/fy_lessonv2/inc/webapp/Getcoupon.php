<?php
/**
 * 优惠券
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$title = "优惠券中心";

if($op=='display'){
	$login_visit = json_decode($setting_pc['login_visit'], true);
	if(in_array('getcoupon', $login_visit) && empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
		header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
	}


	/* 顶部轮播图片 */
	$banner_list = cache_load('fy_lessonv2_'.$uniacid.'_getcoupon_bg_pc');
	if(!$banner_list){
		$banner_list = pdo_getall($this->table_banner, array('uniacid'=>$uniacid, 'banner_type'=>5, 'is_pc'=>1));
		if(!$banner_list){
			$banner_list[] = array(
				'picture' => MODULE_URL."static/webapp/{$template}/images/getcoupon_bg_pc.jpg",
			);
		}else{
			foreach($banner_list as $k=>$v){
				$banner_list[$k]['picture'] = $_W['attachurl'].$v['picture'];
			}
		}
		cache_write('fy_lessonv2_'.$uniacid.'_getcoupon_bg_pc', $banner_list);
	}


	$pindex = max(1, intval($_GPC['page']));
	$psize = 12;

	$condition = " uniacid=:uniacid AND status=:status AND is_exchange=:is_exchange";
	$params = array(
		':uniacid'		=>$uniacid,
		':status'		=>1,
		':is_exchange'	=>1
	);

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE {$condition} ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['already_per'] = round($v['already_exchange']/$v['total_exchange'], 2) * 100;
		$v['already_per'] = $v['already_per'] > 100 ? '100%' : $v['already_per'].'%';
		
		$v['is_end'] = 0;
		if($v['already_exchange']>=$v['total_exchange'] || $v['status']==0){
			$v['is_end'] = 1;
		}

		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "适用".$category['name']."分类的课程" : "适用全部分类的课程";
			$v['coupon_link'] = "/{$uniacid}/search.html?pid={$v[category_id]}";
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程可用";
			$v['coupon_link'] = "/{$uniacid}/couponLesson.html?coupon_id={$v[id]}";
		}
		unset($category);

		$list[$k] = $v;
	}

	$market = pdo_fetch("SELECT coupon_desc FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	$coupon_desc = $market['coupon_desc'] ? explode("\n", $market['coupon_desc']) : "";

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_mcoupon). " WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='ajaxExchange' && $_W['isajax']){
	if(!$uid){
		$data = array(
			'code' => -2,
			'message' => '您还没有登录',
		);
		$this->resultJson($data);
	}
	$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid), array('credit1'));
		
	$id = $_GPC['id'];
	$coupon = pdo_get($this->table_mcoupon, array('uniacid'=>$uniacid,'id'=>$id,'status'=>1,'is_exchange'=>1));

	if(empty($coupon)){
		$data = array(
			'code' => -1,
			'message' => '该优惠券不存在，请刷新页面重试',
		);
		$this->resultJson($data);
	}
	if($coupon['already_exchange']>=$coupon['total_exchange']){
		$data = array(
			'code' => -1,
			'message' => '该优惠券已被抢光,下次早点来吧',
		);
		$this->resultJson($data);
	}

	$already = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND coupon_id=:coupon_id AND uid=:uid", array(':uniacid'=>$uniacid,':coupon_id'=>$id,':uid'=>$uid));	
	if($already>=$coupon['max_exchange']){
		$data = array(
			'code' => -1,
			'message' => '您已经兑换'.$already.'张，留点给别人吧',
		);
		$this->resultJson($data);
	}
	if($member['credit1']<$coupon['exchange_integral']){
		$data = array(
			'code' => -1,
			'message' => '您的积分不足于兑换',
		);
		$this->resultJson($data);
	}

	load()->model('mc');
	$modules = pdo_get('modules', array('name'=>'fy_lessonv2'), array('title'));
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

			$data = array(
				'code' => 0,
				'message' => '领取成功，请到“我的优惠券”里查看',
			);
			$this->resultJson($data);
		}else{
			$data = array(
				'code' => -1,
				'message' => '领取失败，请稍候重试',
			);
			$this->resultJson($data);
		}
	}else{
		$data = array(
			'code' => -1,
			'message' => '系统繁忙，请稍候重试',
		);
		$this->resultJson($data);
	}

}


include $this->template("../webapp/{$template}/getCoupon");


?>