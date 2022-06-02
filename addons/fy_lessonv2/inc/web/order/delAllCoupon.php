<?php

$ids = $_GPC['ids'];
if(!empty($ids) && is_array($ids)){
	$total = 0;
	$coupon = "";
	foreach($ids as $id){
		$coupon = pdo_get($this->table_coupon, array('uniacid'=>$uniacid,'card_id'=>$id), array('password'));
		$coupon .= $coupon['password'].",";
		
		if(pdo_delete($this->table_coupon, array('uniacid'=>$uniacid,'card_id' => $id))){
			$total++;
		}
	}

	$coupon = trim($coupon, ",");
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程优惠码", "批量删除{$total}个课程优惠码,[{$coupon}]");
	message("批量删除成功", $this->createWebUrl('order', array('op'=>'couponCode')), "success");
}else{
	message("未选中任何课程优惠码", "", "error");
}

?>