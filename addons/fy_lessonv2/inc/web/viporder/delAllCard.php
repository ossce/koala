<?php

$ids = $_GPC['ids'];
if(!empty($ids) && is_array($ids)){
	$num = 0;
	$card = "";
	foreach($ids as $id){
		$vipCard = pdo_get($this->table_vipcard, array('uniacid'=>$uniacid,'id'=>$id), array('password'));
		$card .= $vipCard['password'].",";
		if(pdo_delete($this->table_vipcard, array('uniacid'=>$uniacid,'id' => $id))){
			$num++;
		}
	}

	$card = trim($card, ",");
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "VIP服务卡", "批量删除{$num}个VIP服务卡,[{$card}]");
	itoast("批量删除成功", "", "success");
}else{
	message("未选中任何服务卡", "", "error");
}