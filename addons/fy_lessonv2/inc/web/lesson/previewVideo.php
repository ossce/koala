<?php

$id = intval($_GPC['id']); /* 章节id */
$sections = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($sections)){
	message("该章节不存在或已被删除", "", "error");
}

//七牛云对象存储
if($sections['savetype']==1){
	$qiniu = unserialize($setting['qiniu']);
	$playurl = $site_common->privateDownloadUrl($qiniu['access_key'], $qiniu['secret_key'], $sections['videourl']);
	if($qiniu['https']){
		$playurl = str_replace("http://", "https://", $playurl);
	}
}

//腾讯云对象存储
if($sections['savetype']==3){
	$qcloud = unserialize($setting['qcloud']);
	$playurl = $site_common->tencentDownloadUrl($qcloud, $sections['videourl']);
	if($qcloud['https']){
		$playurl = str_replace("http://", "https://", $playurl);
	}
}

//阿里云点播
if($sections['savetype']==4){

	if($sections['sectiontype']==3){/* 音频章节 */
		try {
			$playurl = $aliyunVod->get_play_info($sections['videourl']);
		} catch (Exception $e) {
			message("播放失败，错误原因:".$e->getMessage(), "", "error");
		}

		if(empty($sections['videourl'])){
			message("获取播放地址失败，请联系管理员", "", "error");
		}

	}else{/* 视频章节 */
		$file = pdo_get($this->table_aliyun_upload, array('uniacid'=>$uniacid,'videoid'=>$sections['videourl']), array('name'));
		$suffix = substr(strrchr($file['name'], '.'), 1);
		$audio = strtolower($suffix)=='mp3' ? true : false;

		try {
			$response = $aliyunVod->getVideoPlayAuth($sections['videourl']);
			$playAuth = $response->PlayAuth;
		} catch (Exception $e) {
			message("播放失败，错误原因:".$e->getMessage(), "", "error");
		}
	}
}

//腾讯云点播
if($sections['savetype']==5){

	if($sections['sectiontype']==3){/* 音频章节 */
		$playurl = $newqcloudVod->getUrlPlaySign($qcloudvod['safety_key'],$sections,$exper);
		if(empty($sections['videourl'])){
			message("获取播放地址失败，请联系管理员", "", "error");
		}

	}else{/* 视频章节 */
		try {
			$res = $newqcloudVod->getPlaySign($qcloudvod['safety_key'], $qcloudvod['appid'], $sections['videourl'], $qcloudvod['player_name']);
		} catch (Exception $e) {
			message("播放失败，错误原因:".$e->getMessage(), "", "error");
		}
	}
}

//阿里云OSS
if($sections['savetype']==6){
	include_once dirname(__FILE__).'/../../common/AliyunOSS.php';

	$aliyunoss = unserialize($setting['aliyunoss']);
	
	$params = parse_url($sections['videourl']);
	$com_name = trim($params['path'], '/');
	if(!$com_name){
		mesage('章节音视频链接有误，请检查填写是否正确');
	}

	$ossClient = new AliyunOSS($aliyunoss['access_key_id'], $aliyunoss['access_key_secret'], $aliyunoss['endpoint']);
	$default_url = $ossClient->getSignUrl($aliyunoss['bucket'], $com_name, $timeout=7200);
	$playurl = $site_common->aliyunOssPlayUrl($default_url, $aliyunoss);
}