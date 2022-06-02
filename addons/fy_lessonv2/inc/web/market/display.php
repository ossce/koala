<?php


$duration = json_decode($market['study_duration'], true);
$signin = json_decode($market['signin'], true);

if(checksubmit('submit')){
	$_GPC['duration']['exchange_credit1']    = intval($_GPC['duration']['exchange_credit1']);
	$_GPC['duration']['max_exchange_minute'] = intval($_GPC['duration']['max_exchange_minute']);
	
	$_GPC['signin']['switch']     = intval($_GPC['signin']['switch']);
	$_GPC['signin']['everyday']   = intval($_GPC['signin']['everyday']);
	$_GPC['signin']['continuity'] = intval($_GPC['signin']['continuity']);
	$_GPC['signin']['limitation'] = intval($_GPC['signin']['limitation']);
	if($_GPC['signin']['switch']){
		if(!$_GPC['signin']['everyday']){
			message('请输入周期首次签到奖励积分');
		}
		if($_GPC['signin']['continuity'] && !$_GPC['signin']['limitation']){
			message('请输入奖励上限积分');
		}
		if(!$_GPC['signin']['continuity'] && $_GPC['signin']['limitation']){
			message('请输入续签递增奖励积分');
		}
		if(($_GPC['signin']['continuity'] && $_GPC['signin']['limitation']) && ($_GPC['signin']['limitation']<$_GPC['signin']['everyday']+$_GPC['signin']['continuity'])){
			message('奖励上限不能小于'.($_GPC['signin']['everyday']+$_GPC['signin']['continuity']).'积分');
		}
	}

	$data = array(
		'uniacid' => $uniacid,
		'deduct_switch'  => $_GPC['deduct_switch'],
		'deduct_money'   => $_GPC['deduct_money'],
		'study_duration' => json_encode($_GPC['duration']),
		'signin'		 => json_encode($_GPC['signin']),
	);

	if($market){
		pdo_update($this->table_market, $data, array('uniacid'=>$uniacid));
	}else{
		$data['addtime'] = time();
		pdo_insert($this->table_market, $data);
	}

	cache_delete('fy_lesson_'.$uniacid.'_market');

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "营销管理->积分设置", "编辑积分设置");	
	message("操作成功", $this->createWebUrl('market'), "success");
}