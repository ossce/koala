<?php

$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort DESC", array(':uniacid'=>$uniacid));
if(checksubmit('submit')){
	$prefix = trim($_GPC['prefix']);
	$level_id = intval($_GPC['level_id']);
	$number = intval($_GPC['number']);
	$days = floatval($_GPC['days']);
	$validity = strtotime($_GPC['validity']);
	
	$level = $site_common->getLevelById($level_id);

	if(strlen($prefix) != 2){
		message("请输入服务卡的两位前缀", "", "error");
	}
	if(empty($level_id)){
		message("请选择的VIP等级", "", "error");
	}
	if(empty($level)){
		message("选择的VIP等级不存在", "", "error");
	}
	if($number < 1){
		message("请输入正确的服务卡数量", "", "error");
	}
	if($number > 10000){
		message("单次生成服务卡不要超过10000张", "", "error");
	}
	if($validity < time()){
		message("有效期必须大于当前时间", "", "error");
	}

	set_time_limit(120);
	ob_end_clean();
	ob_implicit_flush(true);
	str_pad(" ", 256);

	$total = 0;
	for($i=1;$i<=$number;$i++){
		$rand = mt_rand(0, 9999).mt_rand(0, 99999);

		$seek=mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999);
		$start=mt_rand(0,16);
		$str=strtoupper(substr(md5($seek),$start,16));
		$str=str_replace("O",chr(mt_rand(65,78)),$str);
		$str=str_replace("0",chr(mt_rand(65,78)),$str);

		$vipData = array(
			'uniacid'	=> $uniacid,
			'password'	=> $prefix.$str,
			'level_id'  => $level_id,
			'viptime'	=> $days,
			'validity'	=> $validity,
			'addtime'   => time()
		);
		if(pdo_insert($this->table_vipcard, $vipData)){
			$total++;
			unset($vipData);
		}
	}

	if($total){
		$site_common->addSysLog($_W['uid'], $_W['username'], 1, "VIP订单->VIP服务卡", "成功生成{$total}个有效期为{$days}天的[{$level['level_name']}]服务卡");
	}
	message("成功生成{$total}个服务卡", $this->createWebUrl('viporder', array('op'=>'vipcard')), "success");
}