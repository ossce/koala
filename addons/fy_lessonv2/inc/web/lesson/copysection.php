<?php

$pid = intval($_GPC['pid']); /* 课程id */
$lesson = pdo_fetch("SELECT id,bookname FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("当前课程不存在或已被删除", "", "error");
}

$id = intval($_GPC['id']); /* 章节id */
$sections = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($sections)){
	message("该章节不存在或已被删除", "", "error");
}


$sections['title'] = '【复制】'.$sections['title'];
$sections['addtime'] = time();
unset($sections['id']);

$result = pdo_insert($this->table_lesson_son, $sections);
$new_id = pdo_insertid();

if($result){
	pdo_update($this->table_lesson_parent, array('update_time'=>time()), array('uniacid'=>$uniacid,'id'=>$lesson['id']));
	message('复制章节成功，正在前往编辑页面...', $this->createWebUrl('lesson', array('op'=>'postsection', 'id'=>$new_id,'pid'=>$pid)), 'success');
}else{
	message('复制章节失败，请稍候重试', '', 'error');
}