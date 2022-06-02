<?php

$pid = intval($_GPC['pid']); //课程id
$cid = intval($_GPC['cid']); //章节id
if($pid>0){
	$lesson = pdo_fetch("SELECT id,lesson_type FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$pid));
	if(empty($lesson)){
		message("该课程不存在或已被删除", "", "error");
	}
	pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'ctype' => 1, 'outid'=>$pid));
	pdo_delete($this->table_lesson_title, array('uniacid'=>$uniacid, 'lesson_id'=>$pid));
	pdo_delete($this->table_lesson_spec, array('uniacid'=>$uniacid, 'lessonid'=>$pid));
	pdo_delete($this->table_lesson_son, array('uniacid'=>$uniacid, 'parentid'=>$pid));
	pdo_delete($this->table_lesson_parent, array('uniacid'=>$uniacid, 'id'=>$pid));
	pdo_delete($this->table_playrecord, array('uniacid'=>$uniacid,'lessonid'=>$pid));
	pdo_delete($this->table_lesson_history, array('uniacid'=>$uniacid,'lessonid'=>$pid));

	//直播课程
	if($lesson['lesson_type'] == 3){
		pdo_delete($this->table_live_chatroom, array('uniacid'=>$uniacid, 'lessonid'=>$pid));
		pdo_delete($this->table_live_chatrecord, array('uniacid'=>$uniacid, 'lessonid'=>$pid));
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程管理", "删除ID:{$pid}的课程及所有章节");
	itoast("删除课程成功", "", "success");
}

if($cid>0){
	$section = pdo_fetch("SELECT id,parentid FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND id=:id ", array(':uniacid'=>$uniacid,':id'=>$cid));
	if(empty($section)){
		message("该章节不存在或已被删除", "", "error");
	}

	$res = pdo_delete($this->table_lesson_son, array('uniacid'=>$uniacid, 'id'=>$cid));
	if($res){
		pdo_delete($this->table_playrecord, array('uniacid'=>$uniacid,'sectionid'=>$cid));
		$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程管理", "删除ID:{$section['parentid']}的课程下ID:{$cid}的章节");
	}

	itoast("删除章节成功", "", "success");
}