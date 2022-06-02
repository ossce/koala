<?php


$typeStatus = new TypeStatus();
$saveList = $typeStatus->sectionSaveType();

$pid = intval($_GPC['pid']); /* 课程id */
$lesson = pdo_fetch("SELECT id,bookname FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("当前课程不存在或已被删除", "", "error");
}

$id = intval($_GPC['id']); /* 章节id */
if(!empty($id)){
	$sections = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($sections)){
		message("该章节不存在或已被删除", "", "error");
	}
}

/* 课程目录列表 */
$title_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_title)." WHERE lesson_id=:lesson_id ORDER BY displayorder DESC,title_id ASC", array('lesson_id'=>$pid));

/* 存储方式 */
if($setting['savetype']==1){
	$qiniu	   = unserialize($setting['qiniu']);
	if(!empty($qiniu['url'])){
		$qiniu['url'] = "http://".str_replace("http://","",$qiniu['url'])."/";
	}

	/* 引入七牛云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qiniu/autoload.php');
	$auth = new Qiniu\Auth($qiniu['access_key'], $qiniu['secret_key']);
	$policy = array(
		'insertOnly' => 1,
	);
	$token = $auth->uploadToken($qiniu['bucket'], null, 3600, $policy, true);

	$uploadConfig = array(
		'token'			=> $token,
		'saveVideoUrl'	=> $this->createWebUrl('video', array('op'=>'saveQiniuUrl')),
		'qiniu_url'		=> $qiniu['url'],
	);
}elseif($setting['savetype']==2){
	$qcloud	   = unserialize($setting['qcloud']);

	/* 引入腾讯云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qcloud/include.php');
	
	$auth = new QcloudCos\Auth($qcloud['appid'], $qcloud['secretid'], $qcloud['secretkey']);
	$expired = time() + 3600;
	$signature = $auth->createReusableSignature($expired, $qcloud['bucket']);

	$uploadConfig = array(
		'saveVideoUrl'	 => $this->createWebUrl('video', array('op'=>'saveQcloudUrl')),
		'verifyVideoUrl' => $this->createWebUrl('video', array('op'=>'verifyQcloud')),
		'bucket'		 => $qcloud['bucket'],
		'appid'			 => $qcloud['appid'],
		'qcloud_area'	 => $qcloud['qcloud_area'],
		'qcloud_url'	 => $qcloud['url'],
		'signature'		 => $signature,
	);
}elseif($setting['savetype']==3){
	$aliyunvod = unserialize($setting['aliyunvod']);
	$uploadConfig = array(
		'saveVideoUrl' => $this->createWebUrl('aliyunvod', array('op'=>'saveVideo')),
		'getTokenUrl'  => $this->createWebUrl('aliyunvod', array('op'=>'getUploadInfo')),
		'refTokenUrl'  => $this->createWebUrl('aliyunvod', array('op'=>'refreshUploadInfo')),
	);
}elseif($setting['savetype']==4){
	$qcloudvod = unserialize($setting['qcloudvod']);
	$uploadConfig = array(
		'saveVideoUrl' => $this->createWebUrl('qcloudvod', array('op'=>'saveVideo')),
		'getTokenUrl'  => $this->createWebUrl('qcloudvod', array('op'=>'getUploadInfo')),
	);
}elseif($setting['savetype']==5){
	$aliyunoss = unserialize($setting['aliyunoss']);
	$uploadConfig = array(
		'serverUrl'	   => $this->createWebUrl('aliyunoss', array('op'=>'getUploadInfo')),
		'saveVideoUrl' => $this->createWebUrl('aliyunoss', array('op'=>'saveVideo')),
		'bucket_url'   => $aliyunoss['bucket_url'],
		'sectionUpload'=> true,
	);
}
$uploadConfig['dirname']	= 'admin';
$uploadConfig['upload_max'] = 5120;

if(checksubmit('submit')){
	$data = array();
	$data['uniacid']		= $uniacid;
	$data['parentid']		= $pid;
	$data['title']			= $_GPC['title'];
	$data['title_id']		= intval($_GPC['title_id']);
	$data['images']			= trim($_GPC['images']);
	$data['sectiontype']	= intval($_GPC['sectiontype']);
	$data['savetype']		= trim($_GPC['savetype']);
	$data['is_live']		= intval($_GPC['is_live']);
	$data['videourl']		= trim($_GPC['videourl']);
	$data['videotime']		= str_replace("：",":",trim($_GPC['videotime']));
	$data['content']		= $_GPC['content'];
	$data['displayorder']	= intval($_GPC['displayorder']);
	$data['is_free']	    = intval($_GPC['is_free']);
	$data['status']			= intval($_GPC['status']);
	$data['auto_show']		= intval($_GPC['auto_show']);
	$data['show_time']		= strtotime($_GPC['show_time']);
	$data['test_time']		= intval($_GPC['test_time']);
	$data['password']		= trim($_GPC['password']);
	$data['addtime']		= time();

	if(empty($data['parentid'])){
		message("课程不存在或已被删除");
	}
	if(empty($data['title'])){
		message("请填写章节名称");
	}
	if(empty($data['sectiontype'])){
		message("请选择章节类型");
	}
	if($data['sectiontype']==1 && empty($data['videourl'])){
		message("请填写章节视频URL");
	}
	if(!in_array($data['is_free'], array('0','1'))){
		message("请选择是否为试听章节");
	}
	if($data['auto_show']==1 && empty($data['show_time'])){
		message("请选择定时上架日期时间");
	}

	if($data['savetype']==2){//内嵌代码存储方式保留内容的空格
		$data['videourl'] = $_GPC['videourl'];
	}
	if($data['sectiontype']==4){//外链章节的url保存在videourl里
		$data['videourl'] = $_GPC['linkurl'];
	}

	if(empty($id)){
		pdo_insert($this->table_lesson_son, $data);
		$id = pdo_insertid();
		pdo_update($this->table_lesson_parent, array('update_time'=>time()), array('id'=>$pid));
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "课程管理->章节管理", "新增ID:{$pid}的课程下ID:{$id}的章节");
		}

		message("添加章节成功", $this->createWebUrl('lesson',array('op'=>'viewsection','pid'=>$pid)), "success");
	}else{
		unset($data['addtime']);
		$res = pdo_update($this->table_lesson_son, $data, array('uniacid'=>$uniacid, 'id'=>$id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程管理->章节管理", "编辑ID:{$pid}的课程下ID:{$id}的章节");
		}

		message("编辑章节成功", $refurl, "success");
	}
}

//获取阿里云和腾讯云点播音视频信息
if($_GPC['getMediaTime']){
	$videoid = trim($_GPC['videoid']);
	if($_GPC['savetype']==4){
		$res = $aliyunVod->get_video_info($_GPC['videoid']);
		if(!empty($res->Video)){
			$data = array(
				'code' => 0,
				'duration' => $res->Video->Duration >= 3600 ? gmdate('H:i:s', $res->Video->Duration) : gmdate('i:s', $res->Video->Duration),
			);
			$this->resultJson($data);
		}else{
			$data = array(
				'code' => -1,
				'msg' => '获取视频信息失败，请手动填写',
			);
			$this->resultJson($data);
		}

	}elseif($_GPC['savetype']==5){
		$res = $newqcloudVod->getBasicInfo($_GPC['videoid'], $type='basicInfo');
		if($res['duration']>0){
			$data = array(
				'code' => 0,
				'duration' => $res['duration']>=3600 ? gmdate('H:i:s', $res['duration']) : gmdate('i:s', $res['duration']),
			);
			$this->resultJson($data);
		}else{
			$data = array(
				'code' => -1,
				'msg' => $res,
			);
			$this->resultJson($data);
		}
	}
}