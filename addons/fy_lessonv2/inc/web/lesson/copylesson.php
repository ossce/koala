<?php

$id = intval($_GPC['lessonid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error");
}
$lesson['bookname'] = '【复制】'.$lesson['bookname'];
$lesson['buynum'] = $lesson['score'] = $lesson['visit_number'] = 0;
$lesson['addtime'] = $lesson['update_time'] = time();
unset($lesson['id']);

$result = pdo_insert($this->table_lesson_parent, $lesson);
$new_id = pdo_insertid();
if(!$result){
	message('复制课程失败，请稍候重试', '', 'error');
}

$spec_list = pdo_getall($this->table_lesson_spec, array('lessonid'=>$id));
if(!empty($spec_list)){
	foreach($spec_list as $value){
		$value['lessonid'] = $new_id;
		$value['addtime'] = time();
		unset($value['spec_id']);
		pdo_insert($this->table_lesson_spec, $value);
	}
}

message('复制课程成功，请自行修改课程标题', $this->createWebUrl('lesson', array('op'=>'postlesson', 'id'=>$new_id)), 'success');