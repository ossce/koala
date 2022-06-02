<?php

$template_arr =  $typeStatus->templateList();
$setting_pc   = pdo_get($this->table_setting_pc, array('uniacid'=>$uniacid));

$jump_setting	  = json_decode($setting_pc['jump_setting'], true);
$teacher_platform = json_decode($setting_pc['teacher_platform'], true);
$new_notice		  = json_decode($setting_pc['new_notice'], true);
$rec_teacher	  = json_decode($setting_pc['rec_teacher'], true);
$new_lesson		  = json_decode($setting_pc['new_lesson'], true);
$friendly_link	  = json_decode($setting_pc['friendly_link'], true);
$company_info     = json_decode($setting_pc['company_info'], true);
$login_register   = json_decode($setting_pc['login_register'], true);

if (checksubmit('submit')) {
	$data = array(
		'uniacid'			 => $uniacid,
		'sitename'			 => $_GPC['sitename'],
		'template'			 => $_GPC['template'],
		'logo'				 => trim($_GPC['logo']),
		'bottom_logo'		 => trim($_GPC['bottom_logo']),
		'mobile_qrcode'		 => trim($_GPC['mobile_qrcode']),
		'favicon_icon'		 => trim($_GPC['favicon_icon']),
		'video_watermark'	 => intval($_GPC['video_watermark']),
		'teacher_contact'	 => intval($_GPC['teacher_contact']),
		'site_root'			 => trim($_GPC['site_root']) ? trim($_GPC['site_root'], "/")."/" : '',
		'mobile_site_root'	 => trim($_GPC['mobile_site_root']) ? trim($_GPC['mobile_site_root'], "/")."/" : '',
		'jump_setting'		 => json_encode($_GPC['jump_setting']),
		'hot_search'		 => trim($_GPC['hot_search']),
		'site_icp'			 => trim($_GPC['site_icp']),
		'site_network'		 => trim($_GPC['site_network']),
		'site_culture'		 => trim($_GPC['site_culture']),
		'keywords'			 => trim($_GPC['keywords']),
		'description'		 => trim($_GPC['description']),
		'teacher_platform'	 => json_encode($_GPC['teacher_platform']),
		'service_right_pic'	 => trim($_GPC['service_right_pic']),
		'service_right_url'	 => trim($_GPC['service_right_url']),
		'new_notice'		 => json_encode($_GPC['new_notice']),
		'rec_teacher'		 => json_encode($_GPC['rec_teacher']),
		'new_lesson'		 => json_encode($_GPC['new_lesson']),
		'friendly_link'		 => json_encode($_GPC['friendly_link']),
		'company_info'		 => json_encode($_GPC['company_info']),
		'login_register'	 => json_encode($_GPC['login_register']),
		'addtime'			 => time(),
	);

	if($_GPC['jump_setting']['switch'] && !$_GPC['jump_setting']['url']){
		message('开启PC端跳转手机端后，请填写手机端链接', "", 'error');
	}

	if (empty($setting_pc)) {
		$result = pdo_insert($this->table_setting_pc, $data);
	} else {
		$result = pdo_update($this->table_setting_pc, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新设置表缓存 */
		cache_delete('fy_lesson_'.$uniacid.'_setting_pc');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "PC端管理->基本设置", "编辑基本设置");
		message('更新成功', $this->createWebUrl('pcmanage'), 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}