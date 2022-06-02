<?php

$ids = $_GPC['ids'];
if(!empty($ids) && is_array($ids)){
	$total = 0;
	$cards = "";
	foreach($ids as $id){
		$card = pdo_get($this->table_lessoncard, array('uniacid'=>$uniacid,'id'=>$id), array('password'));
		$cards .= $card['password'].",";
		
		if(pdo_delete($this->table_lessoncard, array('uniacid'=>$uniacid,'id'=>$id))){
			$total++;
		}
	}

	$card = trim($card, ",");
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "营销管理->课程卡密", "批量删除{$total}个课程卡密,[{$cards}]");
	itoast("批量删除成功", "", "success");
}else{
	message("未选中任何课程卡密", "", "error");
}

?>