<?php
/**
 * 会员VIP
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 访问权限 */
$site_common->check_black_list('visit', $uid);

if($op=='display'){
	/* 头部图片 */
	$vip_bg = cache_load('fy_lessonv2_'.$uniacid.'_vip_bg_pc');
	if(!$vip_bg){
		$vip_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid, 'banner_type'=>8, 'is_pc'=>1), array('picture'));
		$vip_bg = $vip_bg_data ? $_W['attachurl'].$vip_bg_data['picture'] : MODULE_URL."static/webapp/{$template}/images/vip_bg_pc.jpg";
		cache_write('fy_lessonv2_'.$uniacid.'_vip_bg_pc', $vip_bg);
	}

	/* VIP等级列表 */
	$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND is_show=:is_show ORDER BY sort DESC", array(':uniacid'=>$uniacid,':is_show'=>1));
	foreach($level_list as $k=>$v){
		$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND level_id=:level_id AND validity>:validity", array(':uid'=>$uid,':level_id'=>$v['id'],':validity'=>time()));
		
		$v['market_price'] = 0;
		if(empty($memberVip)){
			/* 开通VIP */
			$v['renew'] = 0;
			if($v['open_discount']>0 && $v['open_discount']<100){
				$v['market_price'] = $v['level_price'];
				$v['level_price'] = round($v['level_price'] * $v['open_discount'] * 0.01, 2);
			}
		}else{
			/* 续费VIP */
			$v['renew'] = 1;
			if($v['renew_discount']>0 && $v['renew_discount']<100){
				$v['market_price'] = $v['level_price'];
				$v['level_price'] = round($v['level_price'] * $v['renew_discount'] * 0.01, 2);
			}
		}
		$v['level_icon'] = $v['level_icon'] ? $_W['attachurl'].$v['level_icon'] : MODULE_URL.'static/mobile/default/images/vip-buyicon.png';

		$level_list[$k] = $v;
	}

	/* VIP等级介绍 */
	$comsetting['vipdesc'] = htmlspecialchars_decode($comsetting['vipdesc']);

}elseif($op=='buyvip'){
	$level_id = intval($_GPC['level_id']);
	
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$level_id));
	if(empty($level)){
		message("该VIP等级不存在", "", "error");
	}

	if ($setting['mustinfo']) {
		$user_info = json_decode($setting['user_info']);
		$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid));
		$jumpurl = $_W['siteroot']."{$uniacid}/memberInfo.html?type=vip&level_id={$level_id}";

		if(!empty($common_member_fields)){
			foreach($common_member_fields as $v){
				if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
					 message("请完善您的".$v['field_name'], $jumpurl, "warning");
				}
			}
		}
	}

	/* 构造购买会员订单信息 */
	$orderdata = array(
		'acid'	    => $_W['account']['acid'],
		'uniacid'   => $uniacid,
		'ordersn'   => 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'	    => $uid,
		'level_id'  => $level_id,
		'level_name'=> $level['level_name'],
		'viptime'   => $level['level_validity'],
		'vipmoney'  => $level['level_price'],
		'integral'	=> $level['integral'],
		'addtime'   => time(),
	);

	/* 计算开通和续费会员的折扣价格 */
	$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND level_id=:level_id AND validity>:validity", array(':uid'=>$uid,':level_id'=>$level_id,':validity'=>time()));
	if(empty($memberVip) && $level['open_discount']>0 && $level['open_discount']<100){
		$orderdata['vipmoney'] = round($level['level_price'] * $level['open_discount'] * 0.01, 2);
		$orderdata['discount_money'] = $level['level_price'] - $orderdata['vipmoney'];
	}
	if(!empty($memberVip) && $level['renew_discount']>0 && $level['renew_discount']<100){
		$orderdata['vipmoney'] = round($level['level_price'] * $level['renew_discount'] * 0.01, 2);
		$orderdata['discount_money'] = $level['level_price'] - $orderdata['vipmoney'];
	}

	/* 检查当前分销功能是否开启 */
	if($comsetting['is_sale']==1 && $comsetting['vip_sale']==1){
		$orderdata['commission1'] = 0;
		$orderdata['commission2'] = 0;
		$orderdata['commission3'] = 0;

		if($comsetting['self_sale']==1){
			/* 开启分销内购，一级佣金为购买者本人 */
			$orderdata['member1'] = $uid;
			$orderdata['member2'] = $site_common->getParentid($uid);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}else{
			/* 关闭分销内购 */
			$orderdata['member1'] = $site_common->getParentid($uid);;
			$orderdata['member2'] = $site_common->getParentid($orderdata['member1']);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}

		$vipordercom = json_decode($level['commission'], true); /* VIP单独佣金比例 */
		$settingcom = unserialize($comsetting['commission']);	/* 全局佣金比例 */
		if($orderdata['member1']>0){
			$orderdata['commission1'] = $site_common->getAgentCommission1($vipordercom['commission_type'], $vipordercom['commission1'], $settingcom['commission1'], $orderdata['vipmoney'], $orderdata['member1']);
		}
		if($orderdata['member2']>0 && in_array($comsetting['level'], array('2','3'))){
			$orderdata['commission2'] = $site_common->getAgentCommission2($vipordercom['commission_type'], $vipordercom['commission2'], $settingcom['commission2'], $orderdata['vipmoney'], $orderdata['member2']);
		}
		if($orderdata['member3']>0 && $comsetting['level']==3){
			$orderdata['commission3'] = $site_common->getAgentCommission3($vipordercom['commission_type'], $vipordercom['commission3'], $settingcom['commission3'], $orderdata['vipmoney'], $orderdata['member3']);
		}
	}

	if($uid>0){
		$result = pdo_insert($this->table_member_order, $orderdata);
		$orderid = pdo_insertid();
	}
	
	if($result){
		header("Location:".$_W['siteroot']."{$uniacid}/payment.html?orderid={$orderid}&ordertype=buyvip");
	}else{
		message("写入订单信息失败，请重试！", $_W['siteroot']."{$uniacid}/vip.html", "error");
	}
}



include $this->template("../webapp/{$template}/vip");



?>