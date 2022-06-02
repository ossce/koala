<?php

$ids = $_GPC['ids'];
if(!empty($ids) && is_array($ids)){
	$total = 0;
	$coupons = "";
	foreach($ids as $id){
		$coupon = pdo_get($this->table_coupon, array('uniacid'=>$uniacid,'card_id'=>$id), array('password'));
		$coupons .= $coupon['password'].",";
		
		if(pdo_delete($this->table_coupon, array('uniacid'=>$uniacid,'card_id' => $id))){
			$total++;
		}
	}

	$coupon = trim($coupon, ",");
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "营销管理->课程优惠码", "批量删除{$total}个课程优惠码,[{$coupons}]");
	itoast("批量删除成功", "", "success");
}else{
	message("未选中任何课程优惠码", "", "error");
}

?>