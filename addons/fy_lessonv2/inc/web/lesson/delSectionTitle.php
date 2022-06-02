<?php

$pid = intval($_GPC['pid']);
$title_id = intval($_GPC['title_id']);
if($title_id){
	$title = pdo_get($this->table_lesson_title, array('title_id'=>$title_id));
	if(empty($title)){
		message("课程目录不存在或已被删除", "", "error");
	}
}

$res = pdo_delete($this->table_lesson_title, array('uniacid'=>$uniacid, 'title_id'=>$title_id));
if($res){
	if($pid){
		pdo_update($this->table_lesson_son, array('title_id'=>0), array('uniacid'=>$uniacid,'parentid'=>$pid,'title_id'=>$title_id));
	}
	
	message("删除成功", $this->createWebUrl('lesson', array('op'=>'sectionTitle','pid'=>$pid)), "success");
}else{
	message("删除失败，请稍候重试", "", "error");
}