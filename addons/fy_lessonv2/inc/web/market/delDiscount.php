<?php

$discount_id = intval($_GPC['discount_id']);
$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
if(empty($discount)){
	message('该限时折扣活动不存在');
}

pdo_begin();
try{
	pdo_delete($this->table_discount, array('discount_id'=>$discount_id));
	pdo_delete($this->table_discount_lesson, array('discount_id'=>$discount_id));
	pdo_commit();

	message("删除成功", $this->createWebUrl('market', array('op'=>'discount')), "success");
}catch(Exception $e){
	pdo_rollback();
	message("删除失败，错误信息:".print_r($e, true), "", "error");
}