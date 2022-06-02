<?php
/**
 * 直播录制回调
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$type = intval($_GPC['type']); /* 1.腾讯云直播 2.阿里云直播 */

if($type==1){
	$stream		= $_GPC['__input']['stream_id'];
	$videoid	= $_GPC['__input']['record_file_id'];
	$video_url	= $_GPC['__input']['video_url'];
	$duration	= intval($_GPC['__input']['duration']);
	$file_size	= round(($_GPC['__input']['file_size']/1024)/1024, 2);
	if(empty($stream) || empty($videoid)){
		return;
	}

	$explode = explode("_", $stream);
	$uniacid = $explode[1];
	$lessonid = $explode[2];
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid));
	if(empty($lesson)){
		return;
	}

	//添加章节
	$data = array();
	$data['uniacid']		= $uniacid;
	$data['parentid']		= $lessonid;
	$data['title']			= "直播回放".date('Y-m-d-H-i', time());
	$data['sectiontype']	= 1;
	$data['savetype']		= 5;
	$data['is_live']		= 0;
	$data['videourl']		= $videoid;
	$data['videotime']		= $site_common->secToTime($duration, false);
	$data['is_free']	    = 0;
	$data['status']			= 1;
	$data['auto_show']		= 0;
	$data['addtime']		= time();
	pdo_insert($this->table_lesson_son, $data);

	//添加到腾讯云点播存储表
	$qcloud = array();
	$qcloud['uniacid']		= $uniacid;
	$qcloud['uid']			= 0;
	$qcloud['teacherid']	= 0;
	$qcloud['name']			= $data['title'];
	$qcloud['videoid']		= $videoid;
	$qcloud['videourl']		= $video_url;
	$qcloud['size']			= $file_size;
	$qcloud['addtime']		= time();
	pdo_insert($this->table_qcloudvod_upload, $qcloud);

}elseif($type==2){
	$record = $_GPC['record'] ? $_GPC['record'] : 'oss';
	$title  = "直播回放".date('Y-m-d-H-i', time());
	if($record == 'oss'){
		$aliyunoss = unserialize($setting['aliyunoss']);
		$stream	  = $_GPC['__input']['stream'];
		$uri	  = $_GPC['__input']['uri'];
		$duration = intval($_GPC['__input']['duration']);
		if(empty($stream) || empty($uri)){
			return;
		}
		$explode = explode("_", $stream);
		$savetype = 6; /* 章节存储方式为oss */
		$videourl = 'http://'.$aliyunoss['bucket_url'].'/'.$uri;

		//添加到阿里云oss存储表
		$oss = array();
		$oss['uniacid']		= $uniacid;
		$oss['uid']			= 0;
		$oss['teacherid']	= 0;
		$oss['name']		= $title;
		$oss['com_name']	= $uri;
		$oss['addtime']		= time();
		pdo_insert($this->table_aliyunoss_upload, $oss);
		
	}elseif($record == 'vod'){
		$aliyunvod = unserialize($setting['aliyunvod']);
		$stream	  = $_GPC['__input']['StreamName'];
		$videourl = $_GPC['__input']['VideoId'];
		$duration = intval(strtotime($_GPC['__input']['RecordEndTime']) - strtotime($_GPC['__input']['RecordStartTime']));

		$explode = explode("_", $stream);
		$savetype = 4; /* 章节存储方式为vod */

		//添加到阿里云vod存储表
		$aliyun_vod = new AliyunVod($aliyunvod);
		$res_aliyunvod = $aliyun_vod->get_video_info($videourl);

		$vod = array();
		$vod['uniacid']		= $uniacid;
		$vod['uid']			= 0;
		$vod['teacherid']	= 0;
		$vod['name']		= $title;
		$vod['videoid']		= $videourl;
		$vod['size']		= round(($res_aliyunvod->Video->Size/1024)/1024, 2);
		$vod['addtime']		= time();
		pdo_insert($this->table_aliyun_upload, $vod);
	}

	$uniacid = $explode[1];
	$lessonid = $explode[2];	
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid));
	if(empty($lesson)){
		return;
	}

	//添加章节
	$data = array();
	$data['uniacid']		= $uniacid;
	$data['parentid']		= $lessonid;
	$data['title']			= $title;
	$data['sectiontype']	= 1;
	$data['savetype']		= $savetype;
	$data['is_live']		= 0;
	$data['videourl']		= $videourl;
	$data['videotime']		= $site_common->secToTime($duration, false);
	$data['is_free']	    = 0;
	$data['status']			= 1;
	$data['auto_show']		= 0;
	$data['addtime']		= time();
	pdo_insert($this->table_lesson_son, $data);

}