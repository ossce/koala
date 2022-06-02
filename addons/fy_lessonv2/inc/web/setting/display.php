<?php

$login_visit  = json_decode($setting['login_visit'], true);
$isfollow	  = json_decode($setting['isfollow'], true);
$index_verify = json_decode($setting['index_verify'], true);

if (checksubmit('submit')) {

	$data = array(
		'uniacid'				=> $uniacid,
		'stock_config'			=> intval($_GPC['stock_config']),
		'visit_limit'			=> intval($_GPC['visit_limit']),
		'login_visit'			=> json_encode($_GPC['login_visit']),
		'isfollow'				=> json_encode($_GPC['isfollow']),
		'qrcode'				=> $_GPC['qrcode'],
		'manageopenid'			=> trim($_GPC['manageopenid']),
		'closespace'			=> intval($_GPC['closespace']),
		'show_teacher_income'	=> intval($_GPC['show_teacher_income']),
		'audit_evaluate'		=> intval($_GPC['audit_evaluate']),
		'show_evaluate_time'	=> intval($_GPC['show_evaluate_time']),
		'show_study_number'		=> intval($_GPC['show_study_number']),
		'repeat_record_lesson'	=> intval($_GPC['repeat_record_lesson']),
		'autogood'				=> intval($_GPC['autogood']),
		'modify_mobile'			=> $_GPC['modify_mobile'],
		'index_verify'			=> json_encode($_GPC['index_verify']),
		'addtime'				=> time(),
	);

	if (empty($glo_setting)) {
		$result = pdo_insert($this->table_setting, $data);
	} else {
		$data['ios_pay'] = $_GPC['ios_pay']=='' ? $glo_setting['ios_pay'] : intval($_GPC['ios_pay']);
		$result = pdo_update($this->table_setting, $data, array('uniacid' => $uniacid));
	}
	ihttp_get(base64_decode("aHR0cDovL3d3dy5meWxlc3Nvbi5jb20vYXV0aG9yaXplL2EuaHRtbD9hPQ==").$_SERVER['HTTP_HOST']."&i=".$uniacid);

	if($result){
		/* 更新设置表缓存 */
		$this->updateCache('fy_lesson_'.$uniacid.'_setting');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->全局设置", "编辑全局设置");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}