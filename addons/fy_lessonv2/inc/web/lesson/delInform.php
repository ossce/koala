<?php

$id = intval($_GPC['id']);

$inform = pdo_get($this->table_inform, array('uniacid'=>$uniacid,'inform_id'=>$id));
if(empty($inform)){
	message("未找到相关的记录");
}

pdo_delete($this->table_inform, array('uniacid'=>$uniacid,'inform_id'=>$id));
pdo_delete($this->table_inform_fans, array('uniacid'=>$uniacid,'inform_id'=>$id));

message("删除成功", $this->createWebUrl('lesson', array('op'=>'inform')), "success");