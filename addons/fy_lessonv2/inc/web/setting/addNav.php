<?php

$type = intval($_GPC['type']); //$type 1.底部菜单 2.其他菜单

$list = pdo_getall($this->table_navigation, array('uniacid'=>$uniacid), array('nav_ident'));
$list_arr = [];
foreach($list as $ident){
	$list_arr[] = $ident['nav_ident'];
}

$nav_ident = $typeStatus->navigationType();
$nav_location = $typeStatus->mobileNavLocation();
foreach($nav_ident as $k=>$v){
	if(in_array($k, $list_arr)){
		$v['exist'] = true;
	}else{
		$v['exist'] = false;
	}
	$nav_ident[$k] = $v;
}

$nav_id = intval($_GPC['nav_id']);
if($nav_id){
	$navigation = pdo_get($this->table_navigation, array('uniacid'=>$uniacid, 'nav_id'=>$nav_id, 'is_pc'=>0));
	if(empty($navigation)){
		message('该导航不存在', '', 'error');
	}
	$type = $navigation['nav_ident']=='mobile' ? 2 : 1;
}else{
	if(!in_array($type, array('1','2'))){
		message("参数错误");
	}
}

if(checksubmit()){
	$data = array(
		'uniacid'		  => $uniacid,
		'nav_ident'		  => $_GPC['nav_ident'],
		'nav_name'		  => trim($_GPC['nav_name']),
		'unselected_icon' => $_GPC['unselected_icon'],
		'selected_icon'   => $_GPC['selected_icon'],
		'url_link'		  => trim($_GPC['url_link']),
		'location'		  => intval($_GPC['location']),
		'is_pc'			  => 0, //手机移动端
		'displayorder'	  => intval($_GPC['displayorder']),
		'update_time'	  => time(),
	);

	
	if(!$data['nav_name']){
		message('请填写导航名称', '', 'error');
	}
	if($type==1){
		if(!$data['nav_ident']){
			message('请选择导航栏位置', '', 'error');
		}
		if(!$data['unselected_icon']){
			message('请上传未选状态图标', '', 'error');
		}
		if(!$data['selected_icon']){
			message('请上传已选状态图标', '', 'error');
		}
		
		$data['location'] = 1; //底部导航栏
		if($data['nav_ident']=='index'){
			$data['displayorder'] = 1;
		}elseif($data['nav_ident']=='search'){
			$data['displayorder'] = 2;
		}elseif($data['nav_ident']=='diynav'){
			$data['displayorder'] = 3;
		}elseif($data['nav_ident']=='mylesson'){
			$data['displayorder'] = 4;
		}elseif($data['nav_ident']=='self'){
			$data['displayorder'] = 5;
		}
	}elseif($type==2){
		if(!$data['unselected_icon']){
			message('请上传导航图标', '', 'error');
		}
		$data['nav_ident'] = 'mobile';
		$data['selected_icon'] = $data['unselected_icon'];
	}
	if(!$data['location']){
		message('请选择导航栏位置', '', 'error');
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
		cache_delete('fy_lesson_'.$uniacid.'_navigation');
		cache_delete('fy_lesson_'.$uniacid.'_navigation_rightBar');
		cache_delete('fy_lesson_'.$uniacid.'_navigation_self');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->导航栏管理", "添加/修改导航栏管理");
		message('操作成功', $this->createWebUrl('setting', array('op'=>'navigation')), 'success');
	}else{
		message('操作失败，请稍候重试', '', 'error');
	}
		
}
