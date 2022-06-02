<?php

$setting_pc   = pdo_get($this->table_setting_pc, array('uniacid'=>$uniacid));
$about_title  = json_decode($setting_pc['about_title'], true);

if (checksubmit('submit')) {
	$data = array(
		'uniacid'			=> $uniacid,
		'about_title'		=> json_encode($_GPC['about_title']),
		'aboutus_desc'		=> $_GPC['aboutus_desc'],
		'culture_desc'		=> $_GPC['culture_desc'],
		'environment_desc'	=> $_GPC['environment_desc'],
		'contact_desc'		=> $_GPC['contact_desc'],
		'addtime'			=> time(),
	);

	if (empty($setting_pc)) {
		$result = pdo_insert($this->table_setting_pc, $data);
	} else {
		$result = pdo_update($this->table_setting_pc, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新设置表缓存 */
		cache_delete('fy_lesson_'.$uniacid.'_setting_pc');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "PC端管理->基本设置", "编辑关于我们");
		message('更新成功', $this->createWebUrl('pcmanage',array('op'=>'about')), 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}