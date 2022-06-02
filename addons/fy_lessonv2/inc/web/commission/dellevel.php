<?php

$id = intval($_GPC['id']);
$level = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));

if(empty($level)){
	message("该分销商等级不存在或已被删除", "", "error");
}

$res = pdo_delete($this->table_commission_level, array('uniacid'=>$uniacid, 'id'=>$id));
if($res){
	if($res){
		$site_common->addSysLog($_W['uid'], $_W['username'], 2, "分销管理->分销商等级", "删除ID:{$res}的分销商等级");
	}
}

message("删除成功", $this->createWebUrl("commission", array('op'=>'level')), "success");

?>