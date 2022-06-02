<?php

$id = intval($_GPC['id']);
$recommend = pdo_fetch("SELECT id FROM " . tablename($this->table_recommend) . " WHERE uniacid=:uniacid AND id=:id ", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($recommend)) {
	message("抱歉，板块不存在或是已经被删除！", $this->createWebUrl('recommend', array('op' => 'display')), "error");
}

$res = pdo_delete($this->table_recommend, array('uniacid' => $uniacid, 'id' => $id));
if($res){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "推荐板块", "删除ID:{$id}的课程推荐板块");
}

message("板块删除成功！", $this->createWebUrl('recommend', array('op' => 'display')), "success");

?>