<?php

$id = $_GPC['id'];
$card = pdo_fetch("SELECT password FROM " .tablename($this->table_vipcard). " WHERE uniacid=:uniacid AND id=:id LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));
if(empty($card)){
	message("该VIP服务卡不存在或已被删除", "", "error");
}
$res = pdo_delete($this->table_vipcard, array('uniacid'=>$uniacid,'id' => $id));
if($res){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "VIP服务卡", "删除服务卡密:{$card['password']}的VIP服务卡");
}

echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('viporder', array('op' => 'vipcard', 'page' => $_GPC['page']))."';</script>";