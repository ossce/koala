<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	$data = array(
		'code'	=> -1,
		'msg'	=> '课程不存在或已被删除',
	);
	$this->resultJson($data);
}

$ids = $_GPC['ids'];
if(empty($ids)){
	$data = array(
		'code'	=> -1,
		'msg'	=> '未选中任何选项',
	);
	$this->resultJson($data);
}

load()->func('file');
$document_ids = explode(',', $ids);
foreach($document_ids as $v){
	$document = pdo_get($this->table_document, array('uniacid'=>$uniacid,'lessonid'=>$pid,'id'=>$v));
	if(!empty($document)){
		pdo_delete($this->table_document, array('id'=>$v));		
		if (is_file(ATTACHMENT_ROOT.$document['filepath'])) {
			unlink(ATTACHMENT_ROOT.$document['filepath']);
		}
		if (!empty($_W['setting']['remote']['type'])) {
			file_remote_delete($document['filepath']);
		}
	}
}

$data = array(
	'code'	=> 0,
	'msg'	=> '批量删除成功',
);
$this->resultJson($data);
