<?php

$videoId = trim($_GPC['videoId']);
$response = $aliyunVod->refresh_upload_video($videoId);
$this->resultJson($response);