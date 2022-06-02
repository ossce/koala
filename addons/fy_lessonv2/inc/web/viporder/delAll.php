<?php

$ids = explode(",", $_GPC['ids']);
foreach($ids as $id){
	pdo_delete($this->table_member_order, array('id'=>$id));
}

if(!empty($_GPC['ids'])){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "VIP订单", "批量删除VIP订单ID:{$_GPC['ids']}");
}

$data = array(
	'code' => 0,
	'msg'  => '批量删除成功'
);
$this->resultJson($data);