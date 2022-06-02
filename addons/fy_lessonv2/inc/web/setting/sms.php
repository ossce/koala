<?php

$sms = json_decode($setting['sms'], true);

if (checksubmit('submit')) {
	$data = array(
		'uniacid'  => $uniacid,
		'sms' => json_encode($_GPC['sms']),
		'addtime'  => time(),
	);

	if (empty($glo_setting)) {
		$result = pdo_insert($this->table_setting, $data);
	} else {
		$result = pdo_update($this->table_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新设置表缓存 */
		$this->updateCache('fy_lesson_'.$uniacid.'_setting');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->短信配置", "更新短信配置");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}