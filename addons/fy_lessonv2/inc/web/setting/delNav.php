<?php

$nav_id = intval($_GPC['nav_id']);

$navigation = pdo_get($this->table_navigation, array('uniacid'=>$uniacid,'nav_id'=>$nav_id, 'is_pc'=>0));
if(empty($navigation)){
	message("该导航不存在", "", "error");
}


if(pdo_delete($this->table_navigation, array('nav_id'=>$nav_id))){
	cache_delete('fy_lesson_'.$uniacid.'_navigation');
	cache_delete('fy_lesson_'.$uniacid.'_navigation_rightBar');
	cache_delete('fy_lesson_'.$uniacid.'_navigation_self');
	message("删除成功", $this->createWebUrl('setting',array('op'=>'navigation')), "success");
}else{
	message("删除失败", "", "error");
}