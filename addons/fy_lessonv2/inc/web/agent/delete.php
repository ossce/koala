<?php

$uid = intval($_GPC['uid']);
$member = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uid=:uid LIMIT 1", array(':uid'=>$uid));

if(empty($member)){
	message("该分销成员不存在!", "", "error");
}

if(pdo_delete($this->table_member, array('uid'=>$uid))){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "分销管理->分销商管理", "删除用户ID:[{$uid}]，昵称:[{$member['nickname']}]的分销商");

	itoast("删除成功", "", "success");
}else{
	message("删除失败", "", "error");
}