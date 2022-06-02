<?php

$level_id = intval($_GPC['level_id']);
if($level_id>0){
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$level_id));
	if(empty($level)){
		message("该VIP等级不存在或已被删除", "", "error");
	}
}

if(pdo_delete($this->table_vip_level, array('id'=>$level_id))){
	message("删除成功", $this->createWebUrl('viporder',array('op'=>'vipservice')), "success");
}else{
	message("删除失败", "", "error");
}