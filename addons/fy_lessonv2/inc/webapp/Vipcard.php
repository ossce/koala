<?php
/**
 * 卡密开通VIP
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if(!$uid){
	$data = array(
		'code' => '-1',
		'message' => '您还没有登录',
	);
	$this->resultJson($data);
}

$password = trim($_GPC['card_password']);
$vipcard = pdo_fetch("SELECT * FROM " .tablename($this->table_vipcard). " WHERE uniacid=:uniacid AND password=:password AND is_use=0 AND validity>:time ", array(':uniacid'=>$uniacid, ':password'=>$password, ':time'=>time()));
if(!$vipcard){
	$data = array(
		'code' => '-1',
		'message' => '该服务卡不存在或已被使用',
	);
	$this->resultJson($data);
}
if(!$vipcard['level_id']){
	$data = array(
		'code' => -1,
		'message' => '该服务卡已暂停使用',
	);
	$this->resultJson($data);
}

$memberVip = pdo_get($this->table_member_vip, array('uid'=>$uid,'level_id'=>$vipcard['level_id']));
$newLevel = $site_common->getLevelById($vipcard['level_id']);

if(!$newLevel){
	$data = array(
		'code' => -1,
		'message' => '该服务卡对应的VIP不存在',
	);
	$this->resultJson($data);
}

if(!empty($memberVip)){
	if(time()>=$memberVip['validity']){
		$vipData = array(
			'validity' => time()+$vipcard['viptime']*86400,
			'discount' => $newLevel['discount'],
			'update_time' => time(),
		);
	}else{
		$vipData = array(
			'validity' => $memberVip['validity']+$vipcard['viptime']*86400,
			'discount' => $newLevel['discount'],
			'update_time' => time(),
		);
	}
	$result = pdo_update($this->table_member_vip, $vipData, array('id'=>$memberVip['id']));
}else{
	$vipData = array(
		'uniacid' => $uniacid,
		'uid'	  => $uid,
		'level_id'=> $vipcard['level_id'],
		'validity'=> time()+$vipcard['viptime']*86400,
		'discount'=> $newLevel['discount'],
		'addtime' => time(),
	);
	$result = pdo_insert($this->table_member_vip, $vipData);
}

if($result){
	/* 构造购买会员订单信息 */
	$orderdata = array(
		'acid'		=> $_W['account']['acid'],
		'uniacid'	=> $uniacid,
		'ordersn'	=> 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'		=> $uid,
		'level_id'  => $newLevel['id'],
		'level_name'=> $newLevel['level_name'],
		'viptime'	=> $vipcard['viptime'],
		'vipmoney'	=> '0.00',
		'paytype'	=> 'vipcard',
		'status'	=> 1,
		'paytime'	=> time(),
		'refer_id'	=> $vipcard['id'],
		'addtime'	=> time(),
	);
	pdo_insert($this->table_member_order, $orderdata);

	$vipcardData = array(
		'is_use'	=> 1,
		'nickname'	=> $_SESSION['fy_lessonv2_'.$uniacid.'_nickname'],
		'uid'		=> $uid,
		'ordersn'	=> $orderdata['ordersn'],
		'use_time'	=> $orderdata['addtime'],
	);
	pdo_update($this->table_vipcard, $vipcardData, array('uniacid'=>$uniacid,'id'=>$vipcard['id']));
	
	/* 更新会员vip字段 */
	$site_common->updateMemberVip($uid, 1);
	
	$fans = pdo_get($this->table_fans, array('uid'=>$uid), array('openid'));
	$tplmessage = pdo_get($this->table_tplmessage, array('uniacid'=>$uniacid), array('buysucc'));

	/* 发送模版消息 */
	$sendmessage = array(
		'touser'      => $fans['openid'],
		'template_id' => $tplmessage['buysucc'],
		'url'         => $_W['siteroot'] ."app/index.php?i={$uniacid}&c=entry&do=vip&m=fy_lessonv2",
		'topcolor'    => "",
		'data'        => array(
			 'name'	  => array(
				 'value' => "开通/续费：[".$newLevel['level_name']."]-".$vipcard['viptime']."天",
				 'color' => "",
			 ),
			 'remark' => array(
				 'value' => "\n有效期至：".date('Y-m-d H:i:s', $vipData['validity']),
				 'color' => "",
			 ),
	 
		  )
	);
	$site_common->send_template_message($sendmessage);

	$data = array(
		'code' => 0,
		'message' => "开通【{$newLevel['level_name']}-{$vipcard['viptime']}天】成功",
	);
}else{
	$data = array(
		'code' => -1,
		'message' => "开通失败，请稍候再试",
	);
}

$this->resultJson($data);


?>