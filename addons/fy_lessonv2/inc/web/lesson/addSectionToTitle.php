<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("课程不存在或已被删除", "", "error");
}

$title_id = intval($_GPC['title_id']);
$title = pdo_get($this->table_lesson_title, array('title_id'=>$title_id));
if(empty($title)){
	message("课程目录不存在或已被删除", "", "error");
}

$ids = $_GPC['ids'];
foreach($ids as $sectionid){
	$section = pdo_get($this->table_lesson_son, array('parentid'=>$pid, 'id'=>$sectionid));
	if(empty($section)){
		continue;
	}
	pdo_update($this->table_lesson_son, array('title_id'=>$title_id), array('id'=>$sectionid));
}

message('设置成功', $this->createWebUrl('lesson', array('op'=>'viewsection','pid'=>$pid)), 'success');