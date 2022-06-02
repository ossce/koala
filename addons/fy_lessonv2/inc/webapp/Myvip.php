<?php
/**
 * 我的VIP
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

 /* 支付方式集合 */
 $typeStatus = new TypeStatus();
 $paytype = $typeStatus->orderPayType();

if($op == 'display'){

	$title = "我的VIP";
	
	/* 我的VIP等级列表 */
	$memberVip_list = pdo_fetchall("SELECT a.*,b.level_name,b.level_icon FROM " .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_vip_level). " b ON a.level_id=b.id WHERE a.uid=:uid AND a.validity>:validity", array(':uid'=>$uid,':validity'=>time()));
	foreach($memberVip_list as $k=>$v){
		if($v['level_icon']){
			$v['level_icon'] = $_W['attachurl'].$v['level_icon'];
		}else{
			$v['level_icon'] = MODULE_URL."static/webapp/{$template}/images/icon-vip-default.png";
		}
		$memberVip_list[$k] = $v;
	}

}elseif($op=='orderlist'){

	$title = "VIP订单";

	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND uid=:uid AND status=:status";
	$params = array(
		':uniacid' => $uniacid,
		':uid'	   => $uid,
		':status'  => 1,
	);

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_order). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$level = pdo_get($this->table_vip_level, array('id'=>$v['level_id']), array('level_name'));
		$list[$k]['level_name'] = $level['level_name'] ? $level['level_name'] : "默认等级";
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member_order) . " WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

	/* 随机获取客服列表 */
	if($_GPC['ispay']==1){
		$service = json_decode($setting['qun_service'], true);
		if(!empty($service)){
			$rand = rand(0, count($service)-1);
			$now_service = $service[$rand];
		}
	}

}elseif($op=='cardlist'){

	$title = "VIP服务卡";

	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND own_uid=:own_uid ";
	$params = array(
		':uniacid' => $uniacid,
		':own_uid' => $uid
	);

	if($_GPC['card_status']!=''){
		if($_GPC['card_status']==0){
			$condition .= " AND is_use=:is_use AND validity>:validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = time();
		}elseif($_GPC['card_status']==1){
			$condition .= " AND is_use=:is_use ";
			$params[':is_use'] = 1;
		}elseif($_GPC['card_status']==2){
			$condition .= " AND is_use=:is_use AND validity<:validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = time();
		}
	}
	if(trim($_GPC['card_password'])){
		$condition .= " AND password=:password ";
		$params[':password'] = trim($_GPC['card_password']);
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition} ORDER BY is_use ASC,validity ASC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['is_use']==0 && time()<$v['validity']){
			$v['is_use_status'] = "未使用";
			$v['status'] = 0;
		}elseif($v['is_use']==1){
			$v['is_use_status'] = "已使用";
			$v['status'] = 1;
		}elseif($v['is_use']==0 && time()>$v['validity']){
			$v['is_use_status'] = "已过期";
			$v['status'] = 2;
		}
		$level = pdo_get($this->table_vip_level, array('uniacid'=>$uniacid,'id'=>$v['level_id']), array('level_name'));
		$v['level_name'] = $level['level_name'];

		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . " WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);
}


include $this->template("../webapp/{$template}/myVip");


?>