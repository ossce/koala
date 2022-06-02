<?php

$filename = trim($_GPC['filename']);
$suffix = substr(strrchr($filename, '.'), 1);
$title = str_replace(".".$suffix, "", $filename);

$response = $aliyunVod->create_upload_video($title, $filename, $aliyun['no_template_groupid']);

$this->resultJson($response);