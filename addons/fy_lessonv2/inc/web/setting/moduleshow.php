<?php

$composing = array(
	array(
		'module_name'  => '搜索框',
		'module_ident' => 'search',
	),
	array(
		'module_name'  => '轮播图',
		'module_ident' => 'banner',
	),
	array(
		'module_name'  => '课程分类',
		'module_ident' => 'category',
	),
	array(
		'module_name'  => '文章公告',
		'module_ident' => 'article',
	),
	array(
		'module_name'  => '限时折扣广告',
		'module_ident' => 'discount',
	),
	array(
		'module_name'  => '名师风采',
		'module_ident' => 'teacher',
	),
	array(
		'module_name'  => '最新更新课程',
		'module_ident' => 'newlesson',
	),
	array(
		'module_name'  => '课程推荐板块',
		'module_ident' => 'recommend',
	),
	array(
		'module_name'  => '底部标语',
		'module_ident' => 'slogan',
	),
);

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_index_module). " WHERE uniacid=:uniacid ORDER BY displayorder DESC,index_id ASC", array('uniacid'=>$uniacid));

if($_GPC['initialize']){
	pdo_delete($this->table_index_module, array('uniacid'=>$uniacid));
	cache_delete('fy_lesson_'.$uniacid.'_index_html');
	message('初始化设置成功', $this->createWebUrl('setting', array('op'=>'moduleshow')), 'success');
}

if (checksubmit('submit')) {
	pdo_delete($this->table_index_module, array('uniacid'=>$uniacid));
	foreach($_GPC['index_module'] as $item){
		$data = array(
			'uniacid' => $uniacid,
			'module_ident' => $item['module_ident'],
			'module_name'  => $item['module_name'],
			'is_show' => $item['is_show'],
			'displayorder' => $item['displayorder'],
			'addtime' => time(),
			'update_time' => time(),
		);
		pdo_insert($this->table_index_module, $data);
	}

	/* 更新设置表缓存 */
	cache_delete('fy_lesson_'.$uniacid.'_index_html');
	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->手机端显示", "编辑首页模块排序");
	message('更新成功', $this->createWebUrl('setting', array('op' => 'moduleshow')), 'success');
}