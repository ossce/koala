<?php

$ids = explode(",", $_GPC['ids']);
foreach($ids as $id){
	pdo_delete($this->table_teacher_order, array('id'=>$id));
}

if(!empty($_GPC['ids'])){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "购买讲师订单", "批量删除购买讲师订单ID:{$_GPC['ids']}");
}

$data = array(
	'code' => 0,
	'msg'  => '批量删除成功'
);
$this->resultJson($data);