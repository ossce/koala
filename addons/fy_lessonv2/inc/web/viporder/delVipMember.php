<?php

$id = intval($_GPC['id']);
$vip_member = pdo_get($this->table_member_vip, array('uniacid'=>$uniacid,'id'=>$id));
if(empty($vip_member)){
	message("该条记录不存在");
}

$vip_level = pdo_get($this->table_vip_level, array('uniacid'=>$uniacid,'id'=>$vip_member['level_id']));
$mc_member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$vip_member['uid']));
if(!$vip_level || !$mc_member){
	if(pdo_delete($this->table_member_vip, array('uniacid'=>$uniacid,'id'=>$id))){
		itoast("删除成功", "", "success");
	}else{
		message("删除失败，请稍后重试", "", "error");
	}
}else{
	message("该会员记录不允许删除");
}

