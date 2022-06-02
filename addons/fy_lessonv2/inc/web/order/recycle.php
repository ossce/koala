<?php

$ids = explode(",", $_GPC['ids']);
foreach($ids as $id){
	pdo_update($this->table_order, array('is_delete'=>1), array('uniacid'=>$uniacid,'id' => $id));
}

if(!empty($_GPC['ids'])){
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程订单", "批量加入回收站，课程订单ID:{$_GPC['ids']}");
}

$data = array(
	'code' => 0,
	'msg'  => '批量加入回收站成功'
);
$this->resultJson($data);

?>