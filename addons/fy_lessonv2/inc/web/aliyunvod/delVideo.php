<?php

$id = intval($_GPC['id']);
$file = pdo_fetch("SELECT * FROM " .tablename($this->table_aliyun_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
if(empty($file)){
	message("该文件不存在", "", "error");
}

pdo_delete($this->table_aliyun_upload, array('id'=>$id));

$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('aliyunvod');
try {
	$aliyunVod->delete_videos($file['videoid']);

	itoast("删除成功", "", "success");

} catch (Exception $e) {
	itoast("删除成功", "", "success");
}