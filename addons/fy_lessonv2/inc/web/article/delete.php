<?php

$aid = intval($_GPC['aid']);
if($aid>0){
	$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$aid));
	if(empty($article)){
		message("该文章公告不存在！", "", "error");
	}
}

$res = pdo_delete($this->table_article, array('uniacid'=>$uniacid, 'id'=>$aid));
if($res){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "文章公告", "删除ID:{$aid}的文章公告");
}

message("删除成功", $refurl, "success");