<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("课程不存在或已被删除", "", "error");
}

$title_id = intval($_GPC['title_id']);
if($title_id){
	$title = pdo_get($this->table_lesson_title, array('title_id'=>$title_id));
	if(empty($title)){
		message("课程目录不存在或已被删除", "", "error");
	}
}

if(checksubmit()){
	$data = array(
		'uniacid'		=> $uniacid,
		'title'			=> trim($_GPC['title']),
		'lesson_id'		=> $pid,
		'displayorder'	=> intval($_GPC['displayorder']),
		'update_time'	=> time(),
	);

	if(empty($data['title'])){
		message("目录名称不能为空", "", "error");
	}
	if($title_id){
		$res = pdo_update($this->table_lesson_title, $data, array('uniacid'=>$uniacid,'title_id'=>$title_id));
	}else{
		$data['addtime'] = time();
		$res = pdo_insert($this->table_lesson_title, $data);
	}
	
	if($res){
		message("操作成功", $this->createWebUrl('lesson', array('op'=>'sectionTitle','pid'=>$pid)), "success");
	}else{
		message("操作失败，请稍候重试", "", "error");
	}
}