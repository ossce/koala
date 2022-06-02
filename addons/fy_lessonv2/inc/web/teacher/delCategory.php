<?php

$id = intval($_GPC['id']);

$category = pdo_get($this->table_teacher_category, array('uniacid'=>$uniacid,'id'=>$id));
if(empty($category)){
	message("该讲师分类不存在", "", "error");
}

if(pdo_delete($this->table_teacher_category, array('uniacid'=>$uniacid,'id'=>$id))){
	cache_delete('fy_lesson_'.$uniacid.'_teacher_categorylist');
	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理", "删除ID:{$id}的讲师分类");
	message("删除分类成功", $this->createWebUrl('teacher', array('op'=>'category')), "success");

}else{

	message("删除分类失败，请稍后重试", "", "error");
}

?>