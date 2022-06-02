<?php

$videoSaveList = $typeStatus->videoSaveType();

/* 存储方式 */
$qiniu = unserialize($setting['qiniu']);
$qcloud = unserialize($setting['qcloud']);
$qcloudvod = unserialize($setting['qcloudvod']);
$aliyunvod = unserialize($setting['aliyunvod']);
$aliyunoss = unserialize($setting['aliyunoss']);

if (checksubmit('submit')) {
	$data = array(
		'uniacid'  => $uniacid,
		'savetype' => intval($_GPC['savetype']),
		'addtime'  => time(),
	);

	/* 七牛云存储 */
	$qiniutype = array(
		'bucket'	 => trim($_GPC['qiniu']['bucket']),
		'access_key' => trim($_GPC['qiniu']['access_key']),
		'secret_key' => trim($_GPC['qiniu']['secret_key']),
		'qiniu_area' => intval($_GPC['qiniu']['qiniu_area']),
		'url'		 => str_replace("http://","",trim($_GPC['qiniu']['url'])),
		'https'		 => intval($_GPC['qiniu']['https'])
	);
	$data['qiniu'] = serialize($qiniutype);

	/* 腾讯云存储 */
	$qcloudtype = array(
		'appid'		  => trim($_GPC['qcloud']['appid']),
		'bucket'	  => trim($_GPC['qcloud']['bucket']),
		'secretid'    => trim($_GPC['qcloud']['secretid']),
		'secretkey'   => trim($_GPC['qcloud']['secretkey']),
		'qcloud_area' => $_GPC['qcloud']['qcloud_area'],
		'qcloud_type' => $_GPC['qcloud']['qcloud_type'] ? $_GPC['qcloud']['qcloud_type'] : 'json',
		'url'		  => str_replace("http://","",trim($_GPC['qcloud']['url'])),
		'https'		  => intval($_GPC['qcloud']['https'])
	);
	$data['qcloud'] = serialize($qcloudtype);

	/* 腾讯云点播 */
	$qcloudvodtype = array(
		'appid'		 => trim($_GPC['qcloudvod']['appid']),
		'secret_id'	 => trim($_GPC['qcloudvod']['secret_id']),
		'secret_key' => trim($_GPC['qcloudvod']['secret_key']),
		'safety_key' => trim($_GPC['qcloudvod']['safety_key']),
		'player_name'=> trim($_GPC['qcloudvod']['player_name']),
	);
	$data['qcloudvod'] = serialize($qcloudvodtype);

	/* 阿里云点播 */
	$aliyunvodtype = array(
		'region_id'			 => trim($_GPC['aliyunvod']['region_id']),
		'access_key_id'		 => trim($_GPC['aliyunvod']['access_key_id']),
		'access_key_secret'  => trim($_GPC['aliyunvod']['access_key_secret']),
		'url_key'			 => trim($_GPC['aliyunvod']['url_key']),
		'no_template_groupid'=> trim($_GPC['aliyunvod']['no_template_groupid']),
		'play_domain'		 => trim($_GPC['aliyunvod']['play_domain']),
	);
	$data['aliyunvod'] = serialize($aliyunvodtype);

	/* 阿里云OSS */
	$aliyunosstype = array(
		'bucket'			 => trim($_GPC['aliyunoss']['bucket']),
		'access_key_id'		 => trim($_GPC['aliyunoss']['access_key_id']),
		'access_key_secret'  => trim($_GPC['aliyunoss']['access_key_secret']),
		'endpoint'			 => trim($_GPC['aliyunoss']['endpoint']),
		'bucket_url'		 => trim($_GPC['aliyunoss']['bucket_url']),
		'https'				 => intval($_GPC['aliyunoss']['https']),
	);
	$data['aliyunoss'] = serialize($aliyunosstype);	

	if (empty($glo_setting)) {
		$result = pdo_insert($this->table_setting, $data);
	} else {
		$result = pdo_update($this->table_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新设置表缓存 */
		$this->updateCache('fy_lesson_'.$uniacid.'_setting');

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->存储方式", "编辑存储方式");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}