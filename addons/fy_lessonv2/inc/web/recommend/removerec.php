<?php

$id = intval($_GPC['id']);
$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));

if(empty($lesson)){
	message("该课程不存在或已被删除！", "", "error");
}

pdo_update($this->table_lesson_parent, array('recommendid'=>0), array('uniacid'=>$uniacid, 'id'=>$id));

message("移除课程成功！", referer, "success");

?>