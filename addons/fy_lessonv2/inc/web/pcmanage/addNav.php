<?php


$nav_id = intval($_GPC['nav_id']);
if($nav_id){
	$navigation = pdo_get($this->table_navigation, array('uniacid'=>$uniacid, 'nav_id'=>$nav_id, 'is_pc'=>1));
	if(empty($navigation)){
		message('该导航不存在', '', 'error');
	}
}

if(checksubmit()){
	$data = array(
		'uniacid'		  => $uniacid,
		'location'		  => intval($_GPC['location']),
		'nav_ident'		  => 'pc',
		'nav_name'		  => trim($_GPC['nav_name']),
		'displayorder'	  => intval($_GPC['displayorder']),
		'is_pc'			  => 1,
		'icon'			  => trim($_GPC['icon']),
		'url_link'		  => trim($_GPC['url_link']),
		'displayorder'	  => intval($_GPC['displayorder']),
		'update_time'	  => time(),
	);

	if(!$data['location']){
		message('请选择导航栏位置', '', 'error');
	}
	if(!$data['nav_name']){
		message('请填写导航名称', '', 'error');
	}
	if(!$data['url_link']){
		message('请填写导航链接', '', 'error');
	}

	if($nav_id){
		$res = pdo_update($this->table_navigation, $data, array('nav_id'=>$nav_id));
	}else{
		$data['addtime'] = time();
		$res = pdo_insert($this->table_navigation, $data);
	}

	if($res){
		cache_delete('fy_lesson_'.$uniacid.'_top_navigation_pc');
		cache_delete('fy_lesson_'.$uniacid.'_menu_navigation_pc');
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "PC端管理->导航栏管理", "添加/修改导航栏");
		message('操作成功', $this->createWebUrl('pcmanage', array('op'=>'navigation')), 'success');
	}else{
		message('操作失败，请稍候重试', '', 'error');
	}
		
}



