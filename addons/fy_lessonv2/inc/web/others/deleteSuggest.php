<?php

$id = intval($_GPC['id']);
$suggest = pdo_get($this->table_suggest, array('uniacid'=>$uniacid,'id'=>$id));
if(empty($suggest)){
	message("该投诉记录不存在");
}

pdo_delete($this->table_suggest, array('uniacid'=>$uniacid,'id'=>$id));
$site_common->addSysLog($_W['uid'], $_W['username'], 2, "其他管理->投诉建议", "删除投诉建议状态，ID: [{$id}]");

$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('others');
itoast("操作成功", $refurl, "success");

?>