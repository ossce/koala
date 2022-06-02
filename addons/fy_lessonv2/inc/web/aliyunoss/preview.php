<?php

$id = intval($_GPC['id']);
$file = pdo_fetch("SELECT * FROM " .tablename($this->table_aliyunoss_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
if(empty($file)){
	message("该文件不存在!", "", "error");
}

$suffix = substr(strrchr($file['name'], '.'), 1);
$audio = strtolower($suffix)=='mp3' ? true : false;

$ossClient = new AliyunOSS($aliyunoss['access_key_id'], $aliyunoss['access_key_secret'], $aliyunoss['endpoint']);
$default_url = $ossClient->getSignUrl($aliyunoss['bucket'], $file['com_name'], $timeout=7200);

$play_url = $site_common->aliyunOssPlayUrl($default_url, $aliyunoss);