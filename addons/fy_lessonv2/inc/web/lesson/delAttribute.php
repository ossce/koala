<?php

$id = intval($_GPC['id']);
$attr_type = $_GPC['type'];

if($attr_type){
	pdo_delete($this->table_attribute, array('uniacid'=>$uniacid, 'attr_type'=>$attr_type));
	$res = true;
}else{
	$attribute = pdo_get($this->table_attribute, array('uniacid'=>$uniacid, 'id'=>$id));
	if(empty($attribute)){
		message("该课程属性值不存在", "", "error");
	}
	$res = pdo_delete($this->table_attribute, array('uniacid'=>$uniacid, 'id'=>$id));
}

if($res){
	cache_delete('fy_lesson_'.$uniacid.'_attribute1');
	cache_delete('fy_lesson_'.$uniacid.'_attribute2');
	message("删除成功", $this->createWebUrl('lesson', array('op'=>'attribute')), "success");
}else{
	message("删除失败，请稍候重试", "", "error");
}