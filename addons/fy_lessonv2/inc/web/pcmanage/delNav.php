<?php

$nav_id = intval($_GPC['nav_id']);

$navigation = pdo_get($this->table_navigation, array('uniacid'=>$uniacid,'nav_id'=>$nav_id, 'is_pc'=>1));
if(empty($navigation)){
	message("该导航不存在", "", "error");
}


if(pdo_delete($this->table_navigation, array('nav_id'=>$nav_id))){
	cache_delete('fy_lesson_'.$uniacid.'_top_navigation_pc');
	cache_delete('fy_lesson_'.$uniacid.'_menu_navigation_pc');
	message("删除成功", $this->createWebUrl('pcmanage',array('op'=>'navigation')), "success");
}else{
	message("删除失败", "", "error");
}