<?php

$service = json_decode($setting['qun_service'], true);

if(checksubmit()){
	foreach ($_GPC['service'] as $k => $v) {
		$nickname = trim($v['nickname']);
		$avatar = trim($v['avatar']);
		$qrcode = trim($v['qrcode']);

		if (!$nickname || !$avatar || !$qrcode){
			continue;
		}
			
		$service_data[] = array(
			'nickname' => $nickname,
			'avatar' => $avatar,
			'qrcode' => $qrcode,
		);
	}

	$data = array(
		'uniacid'  => $uniacid,
		'qun_service'  => json_encode($service_data),
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

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->加群客服", "更新加群客服");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
		pdo_debug();
	}
}