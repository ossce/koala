<?php

$ids = $_GPC['ids'];

$t = 0;
if(!empty($ids) && is_array($ids)){
	foreach($ids as $id){
		if(pdo_delete($this->table_mcoupon, array('uniacid'=>$uniacid,'id' => $id))){
			$t++;
		}
	}
}
message("批量删除{$t}个优惠券活动", $this->createWebUrl('market', array('op'=>'coupon')), "success");