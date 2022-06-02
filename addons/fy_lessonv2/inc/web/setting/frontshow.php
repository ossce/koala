<?php

$lesson_config = json_decode($setting['lesson_config'], true);
$user_info = json_decode($setting['user_info'], true);
$template_arr =  $typeStatus->templateList();
$self_item = $common['self_item'];

if (checksubmit('submit')) {
	$common['self_item']			= $_GPC['self_item'];
	$common['small_index']			= $_GPC['small_index'];
	$common['right_bar']			= $_GPC['right_bar'];
	$common['article_page']			= $_GPC['article_page'];
	$common['article_copy']			= $_GPC['article_copy'];
	$common['virtual_buy']			= $_GPC['virtual_buy'];
	$common['video_watermark']		= $_GPC['video_watermark'];
	$common['rotate_mirror']		= $_GPC['rotate_mirror'];
	$common['lately_commission']	= $_GPC['lately_commission'];
	$common['give_coupon']			= $_GPC['give_coupon'];
	$common['study_show']			= $_GPC['study_show'];
	$common['category_row_number']	= $_GPC['category_row_number'];
	$common['category_row']			= $_GPC['category_row'];
	$common['article_ico']			= $_GPC['article_ico'];
	$common['teacherlist_ico']		= $_GPC['teacherlist_ico'];
	$common['newlesson_ico']		= $_GPC['newlesson_ico'];
	$common['teacher_home_show']	= $_GPC['teacher_home_show'];
	
	$data = array(
		'uniacid'			=> $uniacid,
		'sitename'			=> trim($_GPC['sitename']),
		'copyright'			=> trim($_GPC['copyright']),
		'site_icp'			=> trim($_GPC['site_icp']),
		'template'			=> trim($_GPC['template']),
		'show_newlesson'	=> intval($_GPC['show_newlesson']),
		'lesson_show'		=> intval($_GPC['lesson_show']),
		'lesson_config'		=> json_encode($_GPC['lesson_config']),
		'lesson_poster_status'	=> intval($_GPC['lesson_poster_status']),
		'lesson_vip_status'	=> intval($_GPC['lesson_vip_status']),
		'mustinfo'			=> intval($_GPC['mustinfo']),
		'user_info'			=> json_encode($_GPC['user_info']),
		'category_ico'		=> $_GPC['category_ico'],
		'common'			=> json_encode($common),
		'front_color'		=> $_GPC['front_color'],
		'lesson_agreement'	=> $_GPC['lesson_agreement'],
		'privacy_agreement' => $_GPC['privacy_agreement'],
		'teacher_agreement' => $_GPC['teacher_agreement'],
		'addtime'			=> time(),
	);

	if (empty($glo_setting)) {
		$result = pdo_insert($this->table_setting, $data);
	} else {
		$result = pdo_update($this->table_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新设置表缓存 */
		$this->updateCache('fy_lesson_'.$uniacid.'_setting');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->手机端显示", "编辑手机端显示");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}

if($_GPC['loadcss'] && $_W['isajax']){
	$front_color = file_get_contents(MODULE_ROOT."/static/mobile/{$template}/css/diy.css");
	$this->resultJson($front_color);
}