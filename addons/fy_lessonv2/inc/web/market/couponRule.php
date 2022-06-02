<?php

$coupon_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

$regGive = json_decode($market['reg_give'], true);
$recommend = json_decode($market['recommend'], true);
$buyLesson = json_decode($market['buy_lesson'], true);
$shareLesson = json_decode($market['share_lesson'], true);

if(checksubmit('submit')){
	$data = array(
		'uniacid'			=> $uniacid,
		'reg_give'			=> json_encode($_GPC['reg_give']),
		'reg_coupon_image'  => $_GPC['reg_coupon_image'],
		'recommend'			=> json_encode($_GPC['recommend']),
		'recommend_time'	=> intval($_GPC['recommend_time']),
		'buy_lesson'		=> json_encode($_GPC['buy_lesson']),
		'buy_lesson_time'	=> intval($_GPC['buy_lesson_time']),
		'share_lesson'		=> json_encode($_GPC['share_lesson']),
		'share_lesson_time' => intval($_GPC['share_lesson_time']),
		'coupon_desc'		=> trim($_GPC['coupon_desc']),
	);

	if($market){
		pdo_update($this->table_market, $data, array('uniacid'=>$uniacid));
	}else{
		$data['addtime'] = time();
		pdo_insert($this->table_market, $data);
	}

	message("操作成功", $this->createWebUrl('market', array('op'=>'couponRule')), "success");
}