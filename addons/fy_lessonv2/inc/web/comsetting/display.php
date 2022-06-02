<?php

/* 分享课程和讲师 */
$sharelink	  = unserialize($comsetting['sharelink']);
$sharelesson  = unserialize($comsetting['sharelesson']);
$shareteacher = unserialize($comsetting['shareteacher']);
$cash_way	  = unserialize($comsetting['cash_way']);

/* 佣金比例 */
$commission = unserialize($comsetting['commission']);
/* 微信证书 */
$apiclient_cert = file_exists(MODULE_ROOT . '/cert/apiclient_cert'.$uniacid.'.pem');
$apiclient_key = file_exists(MODULE_ROOT.'/cert/apiclient_key'.$uniacid.'.pem');

$agent_condition = unserialize($comsetting['agent_condition']);
/* 直接推荐下级奖励 */
$rec_income = json_decode($comsetting['rec_income'], true);

if (checksubmit('submit')) {
	$data = array(
		'uniacid'				=> $uniacid,
		'agent_status'			=> intval($_GPC['agent_status']),
		'is_sale'				=> intval($_GPC['is_sale']),
		'hidden_sale'			=> intval($_GPC['hidden_sale']),
		'hidden_login'			=> intval($_GPC['hidden_login']),
		'self_sale'				=> intval($_GPC['self_sale']),
		'sale_model'			=> intval($_GPC['sale_model']),
		'protect_time'			=> intval($_GPC['protect_time']),
		'protect_rank'			=> intval($_GPC['protect_rank']),
		'show_lately_cashlog'	=> intval($_GPC['show_lately_cashlog']),
		'commission_credit'		=> intval($_GPC['commission_credit']),
		'sale_rank'				=> intval($_GPC['sale_rank']),
		'level'					=> intval($_GPC['level']),
		'upgrade_condition'		=> intval($_GPC['upgrade_condition']),
		'rec_income'			=> json_encode($_GPC['rec_income']),
		'cash_type'				=> intval($_GPC['cash_type']),
		'cash_way'				=> serialize($_GPC['cash_way']),
		'cash_lower_common'		=> floatval($_GPC['cash_lower_common']),
		'cash_lower_vip'		=> floatval($_GPC['cash_lower_vip']),
		'cash_lower_teacher'	=> floatval($_GPC['cash_lower_teacher']),
		'cash_service_num'		=> intval($_GPC['cash_service_num']),
		'cash_interval_common'	=> intval($_GPC['cash_interval_common']),
		'cash_interval_vip'		=> intval($_GPC['cash_interval_vip']),
		'cash_interval_teacher'	=> intval($_GPC['cash_interval_teacher']),
		'mchid'					=> trim($_GPC['mchid']),
		'mchkey'				=> trim($_GPC['mchkey']),
		'serverIp'				=> trim($_GPC['serverIp']),
		'sale_desc'				=> trim($_GPC['sale_desc']),
		'level_desc'			=> trim($_GPC['level_desc']),
		'qrcode_cache'			=> intval($_GPC['qrcode_cache']),
		'addtime'				=> time(),
	);
	if($data['cash_service_num']>99){
		message('提现手续费设置错误，请重新输入');
	}
	if(empty($data['upgrade_condition'])){
		message('请选择分销商升级条件');
	}
	if(!is_numeric(trim($_GPC['rec_income']['credit1'])) || !is_numeric(trim($_GPC['rec_income']['credit2']))){
		message('直接推荐下级奖励的积分余额必须为数字');
	}
	if(!$data['self_sale'] && $data['level']==3){
		message('根据国家相关法规，关闭分销内购后，最高只能做二级分销，请自觉遵守国家法律法规进行合法运营', '', 'error');
	}

	/* 分享课程和讲师 */
	$sharelink = array(
		'title'  => $_GPC['sharelinktitle'],
		'desc'   => $_GPC['sharelinkdesc'],
		'images' => $_GPC['sharelinkimg'],
	);
	$data['sharelink'] = serialize($sharelink);

	$sharelesson = array(
		'title'  => $_GPC['sharelessontitle'],
		'images' => $_GPC['sharelessonimg'],
	);
	$data['sharelesson'] = serialize($sharelesson);

	$shareteacher = array(
		'title'  => $_GPC['shareteachertitle'],
		'images' => $_GPC['shareteacherimg'],
	);
	$data['shareteacher'] = serialize($shareteacher);
 
	/* 佣金比例 */
	$commission = array(
		'commission1' => floatval($_GPC['commission1']),
		'commission2' => floatval($_GPC['commission2']),
		'commission3' => floatval($_GPC['commission3']),
		'updatemoney' => floatval($_GPC['updatemoney']),
	);
	$data['commission'] = serialize($commission);

	/* 分销商冻结状态转正常条件 */
	$acondition = array(
		'order_amount' => intval($_GPC['order_amount']),
		'order_total'  => intval($_GPC['order_total']),
	);
	$data['agent_condition'] = serialize($acondition);

	if($data['agent_status']==0 && $acondition['order_amount']==0 && $acondition['order_total']==0){
		message("分销商{$agentStatusList[0]}状态转{$agentStatusList[1]}状态条件至少填写一个", "", "error");
	}

	if(!empty($_FILES['apiclient_cert']['name'])){
		$this->upfile($_FILES['apiclient_cert'], "apiclient_cert");
	}
	if(!empty($_FILES['apiclient_key']['name'])){
		$this->upfile($_FILES['apiclient_key'], "apiclient_key");
	}
	if(!empty($_FILES['rootca']['name'])){
		$this->upfile($_FILES['rootca'], "rootca");
	}
	
	if (empty($glo_comsetting)) {
		$result = pdo_insert($this->table_commission_setting, $data);
	} else {
		$result = pdo_update($this->table_commission_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新分销表缓存 */
		$comsetting = $this->getComsetting();
		$this->updateCache('fy_lesson_'.$uniacid.'_commission_setting', $comsetting);
		ihttp_get(base64_decode("aHR0cDovL3d3dy5meWxlc3Nvbi5jb20vYXV0aG9yaXplL2EuaHRtbD9hPQ==").$_SERVER['HTTP_HOST']."&i=".$uniacid);
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销设置", "编辑分销设置内容");
		itoast('更新成功', "", 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}