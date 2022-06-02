<?php

$level_id = intval($_GPC['level_id']);
if($level_id>0){
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$level_id));
	if(empty($level)){
		message("该VIP等级不存在或已被删除", "", "error");
	}
}
$commission = json_decode($level['commission'], true);

if(checksubmit('submit')){
	if(!empty($_GPC['commission']['commission1']) && !is_numeric($_GPC['commission']['commission1'])){
		message('一级佣金比例必须为数字', '', 'error');
	}
	if(!empty($_GPC['commission']['commission2']) && !is_numeric($_GPC['commission']['commission2'])){
		message('二级佣金比例必须为数字', '', 'error');
	}
	if(!empty($_GPC['commission']['commission3']) && !is_numeric($_GPC['commission']['commission3'])){
		message('三级佣金比例必须为数字', '', 'error');
	}

	$data = array(
		'uniacid'         => $uniacid,
		'level_name'      => trim($_GPC['level_name']),
		'level_icon'      => trim($_GPC['level_icon']),
		'level_validity'  => intval($_GPC['level_validity']),
		'level_price'     => floatval($_GPC['level_price']),
		'integral'		  => intval($_GPC['integral']),
		'discount'        => intval($_GPC['discount']),
		'open_discount'	  => intval($_GPC['open_discount']),
		'renew_discount'  => intval($_GPC['renew_discount']),
		'sort'            => intval($_GPC['sort']),
		'is_show'		  => intval($_GPC['is_show']),
		'commission'	  => json_encode($_GPC['commission']),
		'update_time'	  => time(),
	);

	if(empty($data['level_name'])){
		message("VIP等级名称不能为空", "", "error");
	}
	if(empty($data['level_validity'])){
		message("VIP等级有效期不能为空", "", "error");
	}
	if(empty($data['level_price'])){
		message("VIP等级价格不能为空", "", "error");
	}
	if($_GPC['discount']<1 || $_GPC['discount']>100){
		message("购买课程折扣输入有误，范围：1%~100%", "", "error");
	}
	if($_GPC['open_discount']<1 || $_GPC['open_discount']>100){
		message("开通会员折扣输入有误，范围：1%~100%", "", "error");
	}
	if($_GPC['renew_discount']<1 || $_GPC['renew_discount']>100){
		message("续费会员折扣输入有误，范围：1%~100%", "", "error");
	}
	
	if($level_id>0){
		$result = pdo_update($this->table_vip_level, $data, array('id'=>$level_id));
	}else{
		$data['addtime'] = time();
		$result = pdo_insert($this->table_vip_level, $data);
	}

	if($result){
		message("更新成功", $this->createWebUrl('viporder',array('op'=>'vipservice')), "success");
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}