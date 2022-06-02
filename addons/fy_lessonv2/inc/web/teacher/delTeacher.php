<?php

$id = intval($_GPC['id']);
$teacher = pdo_fetch("SELECT id FROM " . tablename($this->table_teacher) . " WHERE uniacid=:uniacid AND id=:id ", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($teacher)) {
	message("抱歉，讲师不存在或是已经被删除！", $this->createWebUrl('teacher', array('op' => 'display')), "error");
}

$lesson = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND teacherid=:teacherid", array(':uniacid'=>$uniacid,':teacherid'=>$id));
if($lesson){
	message("该讲师还存在课程，请删除或转移课程后重试！", "", "error");
}

$res = pdo_delete($this->table_teacher, array('uniacid'=>$uniacid,'id' => $id));

if($res){
	pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'ctype' => 2, 'outid'=>$id));
	pdo_delete($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$id));
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "讲师管理", "删除ID:{$id}的讲师");

	cache_delete('fy_lesson_'.$uniacid.'_recommend_teacher');
	cache_delete('fy_lessonv2_'.$uniacid.'_teacher_bg_'.$id);
		cache_delete('fy_lessonv2_'.$uniacid.'_teacher_bg_pc_'.$id);
}

itoast('删除讲师成功', "", "success");
