<?php

$id = intval($_GPC['id']);
$file = pdo_fetch("SELECT * FROM " .tablename($this->table_aliyunoss_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
if(empty($file)){
	message("该文件不存在!", "", "error");
}

$ossClient = new AliyunOSS($aliyunoss['access_key_id'], $aliyunoss['access_key_secret'], $aliyunoss['endpoint']);
$ossClient->delObject($aliyunoss['bucket'], $file['com_name']);

pdo_delete($this->table_aliyunoss_upload, array('id'=>$id));
itoast("删除成功", $refurl, "success");
