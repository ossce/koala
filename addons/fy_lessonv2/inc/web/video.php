<?php
/**
 * 对象存储视频管理 / 七牛云对象存储 Vs 腾讯云对象存储
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$qiniu = unserialize($setting['qiniu']);
if(!empty($qiniu['url'])){
	$qiniu['url'] = "http://".str_replace("http://","",$qiniu['url'])."/";
}

$qcloud = unserialize($setting['qcloud']);
if(!empty($qcloud['url'])){
	$qcloud['url'] = "http://".$qcloud['url'];
}

/* 视频分类 */
$category_list = $site_common->getVideoCategoryList(0);

if($op=='display'){
	if(!$setting['savetype'] || $setting['savetype']==1){
		header("Location:" .$this->createWebUrl('video', array('op'=>'qiniu')));
	}elseif($setting['savetype']==2){
		header("Location:" .$this->createWebUrl('video', array('op'=>'qcloud')));
	}elseif($setting['savetype']==3){
		header("Location:" .$this->createWebUrl('aliyunvod'));
	}elseif($setting['savetype']==4){
		header("Location:" .$this->createWebUrl('qcloudvod'));
	}elseif($setting['savetype']==5){
		header("Location:" .$this->createWebUrl('aliyunoss'));
	}

}elseif($op=='qiniu'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid=:uniacid AND teacher=:teacher ";
	$params[':uniacid'] = $uniacid;
	$params[':teacher'] = intval($_GPC['teacherid']);
	if(!empty($_GPC['keyword'])){
		$condition .= " AND name LIKE :name ";
		$params[':name'] = "%".trim($_GPC['keyword'])."%";
	}

	if($_GPC['pid']){
		$condition .= " AND pid=:pid ";
		$params[':pid'] = $_GPC['pid'];

		if($_GPC['cid']){
			$condition .= " AND cid=:cid ";
			$params[':cid'] = $_GPC['cid'];

			if($_GPC['ccid']){
				$condition .= " AND ccid=:ccid ";
				$params[':ccid'] = $_GPC['ccid'];
			}
		}
	}

	if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;

		$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_qiniu_upload). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['pid']){
			$p_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['pid'],'teacherid'=>0), array('name'));
			$v['pname'] = $p_category['name'];
		}
		if($v['cid']){
			$c_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['cid'],'teacherid'=>0), array('name'));
			$v['cname'] = $c_category['name'];
		}
		if($v['ccid']){
			$cc_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['ccid'],'teacherid'=>0), array('name'));
			$v['ccname'] = $cc_category['name'];
		}
		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_qiniu_upload). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

	include $this->template('web/qiniu');

}elseif($op=='upqiniu'){
	/* 引入七牛云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qiniu/autoload.php');

	$auth = new Qiniu\Auth($qiniu['access_key'], $qiniu['secret_key']);

	$policy = array(
		'insertOnly' => 1,
	);
	$token = $auth->uploadToken($qiniu['bucket'], null, 3600, $policy, true);

	include $this->template('web/qiniu');

}elseif($op=='saveQiniuUrl'){
	$file = pdo_get($this->table_qiniu_upload, array('uniacid'=>$uniacid,'uid'=>0,'name'=>$_GPC['name'],'com_name'=>$_GPC['com_name']));
	if(!empty($file)){
		$output = array(
			'code'    => -1,
			'message' => '同名文件已存在',
		);
		$this->resultJson($output);
	}

	$data = array(
		'uniacid'	=> $uniacid,
		'uid'		=> '',
		'teacher'	=> '',
		'name'		=> trim($_GPC['name']),
		'pid'		=> intval($_GPC['pid']),
		'cid'		=> intval($_GPC['cid']),
		'ccid'		=> intval($_GPC['ccid']),
		'com_name'	=> trim($_GPC['com_name']),
		'qiniu_url' => $qiniu['url'].trim($_GPC['com_name']),
		'size'		=> intval($_GPC['size']),
		'addtime'	=> time(),
	);
	pdo_insert($this->table_qiniu_upload, $data);

}elseif($op=='delQiniu'){
	$id = intval($_GPC['id']);
	$file = pdo_fetch("SELECT * FROM " .tablename($this->table_qiniu_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	/* 引入七牛云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qiniu/autoload.php');

	$auth = new Qiniu\Auth($qiniu['access_key'], $qiniu['secret_key']);
	$config = new Qiniu\Config();
	$bucketManager = new Qiniu\Storage\BucketManager($auth, $config);
	$bucketManager->delete($qiniu['bucket'], $file['com_name']);

	pdo_delete($this->table_qiniu_upload, array('id'=>$id));

	$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('video', array('op'=>'qiniu'));
	itoast("删除成功!", "", "success");

}elseif($op=='qiniuPreview'){
	$id = intval($_GPC['id']);
	$file = pdo_get($this->table_qiniu_upload, array('uniacid'=>$uniacid, 'id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	if(!empty($qiniu['url'])){
		$videourl = $qiniu['url'].$file['com_name'];
	}

	$playurl = $site_common->privateDownloadUrl($qiniu['access_key'], $qiniu['secret_key'], $videourl);
	if($qiniu['https']){
		$playurl = str_replace("http://", "https://", $playurl);
	}

	include $this->template('web/qiniu');

/* 腾讯云对象存储 */
}elseif($op=='qcloud'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid=:uniacid AND teacherid=:teacherid ";
	$params[':uniacid'] = $uniacid;
	$params[':teacherid'] = intval($_GPC['teacherid']);
	if(!empty($_GPC['keyword'])){
		$condition .= " AND name LIKE :name ";
		$params[':name'] = "%".trim($_GPC['keyword'])."%";
	}
	if($_GPC['pid']){
		$condition .= " AND pid=:pid ";
		$params[':pid'] = $_GPC['pid'];

		if($_GPC['cid']){
			$condition .= " AND cid=:cid ";
			$params[':cid'] = $_GPC['cid'];

			if($_GPC['ccid']){
				$condition .= " AND ccid=:ccid ";
				$params[':ccid'] = $_GPC['ccid'];
			}
		}
	}
	if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;

		$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_qcloud_upload). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if(!empty($qcloud['url'])){
			$tmp_url = explode("myqcloud.com", $v['sys_link']);
			$v['sys_link'] = $qcloud['url'].$tmp_url[1];
		}

		if($v['pid']){
			$p_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['pid'],'teacherid'=>0), array('name'));
			$v['pname'] = $p_category['name'];
		}
		if($v['cid']){
			$c_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['cid'],'teacherid'=>0), array('name'));
			$v['cname'] = $c_category['name'];
		}
		if($v['ccid']){
			$cc_category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$v['ccid'],'teacherid'=>0), array('name'));
			$v['ccname'] = $cc_category['name'];
		}
		$list[$k] = $v;
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_qcloud_upload). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

	include $this->template('web/qcloud');

}elseif($op=='upqcloud'){
	/* 引入腾讯云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qcloud/include.php');
	
	$auth = new QcloudCos\Auth($qcloud['appid'], $qcloud['secretid'], $qcloud['secretkey']);
	$expired = time() + 3600;
	$signature = $auth->createReusableSignature($expired, $qcloud['bucket']);

	include $this->template('web/qcloud');

}elseif($op=='verifyQcloud'){
	/* 引入腾讯云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qcloud/include.php');

	if(empty($_GPC['filename'])){
		$output = array(
			'code' => -1,
		);
		$this->resultJson($output);
	}
	
	$config = array(
		'app_id'	 => $qcloud['appid'],
		'secret_id'	 => $qcloud['secretid'],
		'secret_key' => $qcloud['secretkey'],
		'region'	 => $qcloud['qcloud_area'],
		'timeout'	 => 60
	);
	$cosApi = new QcloudCos\Api($config);
	$res = $cosApi->stat($qcloud['bucket'],'admin/'.$_GPC['filename']);

	$output = array(
		'code' => $res['code'],	
	);
	$this->resultJson($output);

}elseif($op=='saveQcloudUrl'){
	$com_name = urldecode($_GPC['com_name']);
	$sys_link = trim($_GPC['sys_link']);
	$size = trim($_GPC['size']);

	$data = array(
		'uniacid'	=> $uniacid,
		'uid'		=> '',
		'teacherid'	=> '',
		'name'		=> str_replace("/admin/", "", $com_name),
		'pid'		=> intval($_GPC['pid']),
		'cid'		=> intval($_GPC['cid']),
		'ccid'		=> intval($_GPC['ccid']),
		'com_name'	=> $_GPC['com_name'],
		'sys_link'  => $sys_link,
		'size'		=> $size,
		'addtime'	=> time(),
	);
	pdo_insert($this->table_qcloud_upload, $data);

}elseif($op=='delQcloud'){
	$id = intval($_GPC['id']);
	$file = pdo_fetch("SELECT * FROM " .tablename($this->table_qcloud_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	/* 引入腾讯云存储API接口 */
	require_once(MODULE_ROOT.'/library/Qcloud/include.php');

	$config = array(
		'app_id'	 => $qcloud['appid'],
		'secret_id'	 => $qcloud['secretid'],
		'secret_key' => $qcloud['secretkey'],
		'region'	 => $qcloud['qcloud_area'],
		'timeout'	 => 60
	);
	$cosApi = new QcloudCos\Api($config);
	$cosApi->delFile($qcloud['bucket'], urldecode($file['com_name']));

	pdo_delete($this->table_qcloud_upload, array('id'=>$id));

	$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('video', array('op'=>'qcloud'));
	itoast("删除成功!", "", "success");

}elseif($op=='qcloudPreview'){
	$id = intval($_GPC['id']);
	$file = pdo_fetch("SELECT * FROM " .tablename($this->table_qcloud_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	if(!empty($qcloud['url'])){
		$tmp_url = explode("myqcloud.com", $file['sys_link']);
		$sys_link = $qcloud['url'].$tmp_url[1];
	}

	$playurl = $site_common->tencentDownloadUrl($qcloud, $sys_link);
	if($qcloud['https']){
		$playurl = str_replace("http://", "https://", $playurl);
	}

	include $this->template('web/qcloud');

}elseif($op=='category'){
	if (checksubmit('submit')) {
		if (is_array($_GPC['cate1'])) {
			foreach ($_GPC['cate1'] as $k=>$v) {
				$data = array('displayorder' => intval($_GPC['cate1'][$k]));
				pdo_update($this->table_video_category, $data, array('id'=>$k));
			}
		}
		if (is_array($_GPC['cate2'])) {
			foreach ($_GPC['cate2'] as $k=>$v) {
				$data = array('displayorder' => intval($_GPC['cate2'][$k]));
				pdo_update($this->table_video_category, $data, array('id'=>$k));
			}
		}
		if (is_array($_GPC['cate3'])) {
			foreach ($_GPC['cate3'] as $k=>$v) {
				$data = array('displayorder' => intval($_GPC['cate3'][$k]));
				pdo_update($this->table_video_category, $data, array('id'=>$k));
			}
		}
		itoast("批量排序成功", "", "success");
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid=:uniacid AND parentid=:parentid AND teacherid=:teacherid ";
	$params[':uniacid'] = $uniacid;
	$params[':parentid'] = 0;
	$params[':teacherid'] = 0;

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_video_category) . " WHERE {$condition} ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k1=>$v1){
		$v1['second'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_video_category) . " WHERE uniacid=:uniacid AND parentid=:parentid AND teacherid=:teacherid ORDER BY displayorder DESC, id ASC", array(':uniacid'=>$uniacid,':parentid'=>$v1['id'],':teacherid'=>0));

		foreach($v1['second'] as $k2=>$v2){
			$v2['third'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_video_category) . " WHERE uniacid=:uniacid AND parentid=:parentid AND teacherid=:teacherid ORDER BY displayorder DESC, id ASC", array(':uniacid'=>$uniacid,':parentid'=>$v2['id'],':teacherid'=>0));

			$v1['second'][$k2] = $v2;
		}

		$list[$k1] = $v1;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_video_category) . " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);
	

	include $this->template('web/video/category');

}elseif($op=='addCategory'){
	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'teacherid'=>0,'id'=>$id));
		if(empty($category)){
			message("该分类不存在");
		}
	}else{
		$category['parentid'] = intval($_GPC['parentid']);
	}

	if (checksubmit('submit')) {
		$data = array(
			'uniacid'      => $uniacid,
			'parentid'     => $category['parentid'],
			'name'		   => trim($_GPC['name']),
			'displayorder' => intval($_GPC['displayorder']),
			'addtime'      => time(),
		);

		if(empty($data['name'])) {
			message("请输入分类名称");
		}
		if($data['parentid']){
			$parent_cate = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'id'=>$data['parentid'],'teacherid'=>0));
			if(!$parent_cate){
				message("父分类不存在，请返回重新添加"); 
			}
		}

		if (!empty($id)) {
			$res = pdo_update($this->table_video_category, $data, array('uniacid'=>$uniacid,'id'=>$id));
			if($res){
				$site_common->addSysLog($_W['uid'], $_W['username'], 3, "视频管理->视频分类", "编辑ID:{$id}的视频分类");
			}
		} else {
			pdo_insert($this->table_video_category, $data);
			$new_id = pdo_insertid();
			if($new_id){
				$site_common->addSysLog($_W['uid'], $_W['username'], 1, "视频管理->视频分类", "新增ID:{$new_id}的视频分类");
			}
		}

		cache_delete('fy_lesson_'.$uniacid.'_video_category_0');
		itoast("保存成功", $this->createWebUrl('video', array('op'=>'category')), "success");
	}
	

	include $this->template('web/video/addCategory');

}elseif($op=='deleteCategory'){
	$id = intval($_GPC['id']);
	$category = pdo_get($this->table_video_category, array('uniacid'=>$uniacid,'teacherid'=>0,'id'=>$id));
	if (empty($category)) {
		message("该分类不存在", "", "error");
	}

	$second = pdo_getall($this->table_video_category, array('uniacid'=>$uniacid,'teacherid'=>0,'parentid'=>$id));
	foreach($second as $v){
		/* 删除三级分类 */
		pdo_delete($this->table_video_category, array('uniacid'=>$uniacid, 'parentid'=>$v['id']));
	}

	/* 删除二级分类 */
	pdo_delete($this->table_video_category, array('uniacid'=>$uniacid, 'parentid'=>$id));

	/* 删除该分类 */
	pdo_delete($this->table_video_category, array('uniacid'=>$uniacid, 'id'=>$id));

	cache_delete('fy_lesson_'.$uniacid.'_video_category_0');
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "视频管理->视频分类", "删除ID:{$id}的视频分类");
	itoast("删除分类成功", "", "success");

}elseif($op=='batchCategory'){
	$savetype = trim($_GPC['savetype']);
	$videoids = trim($_GPC['videoids']);

	$new_data = array(
		'pid' => intval($_GPC['batch_pid']),
		'cid' => intval($_GPC['batch_cid']),
		'ccid' => intval($_GPC['batch_ccid']),
	);

	$video_arr = explode(",", $videoids);

	if(empty($video_arr)){
		$data = array(
			'code' => -1,
			'msg'  => '没有选中任何音视频',
		);
		$this->resultJson($data);
	}

	foreach($video_arr as $id){
		if($savetype == 'qiniu'){
			pdo_update($this->table_qiniu_upload, $new_data, array('uniacid'=>$uniacid,'id'=>$id,'teacher'=>0));
		}elseif($savetype == 'qcloud'){
			pdo_update($this->table_qcloud_upload, $new_data, array('uniacid'=>$uniacid,'id'=>$id,'teacherid'=>0));
		}elseif($savetype == 'aliyunvod'){
			pdo_update($this->table_aliyun_upload, $new_data, array('uniacid'=>$uniacid,'id'=>$id,'teacherid'=>0));
		}elseif($savetype == 'qcloudvod'){
			pdo_update($this->table_qcloudvod_upload, $new_data, array('uniacid'=>$uniacid,'id'=>$id,'teacherid'=>0));
		}elseif($savetype == 'aliyunoss'){
			pdo_update($this->table_aliyunoss_upload, $new_data, array('uniacid'=>$uniacid,'id'=>$id,'teacherid'=>0));
		}
	}

	$data = array(
		'code' => 0,
		'msg'  => '操作成功',
	);
	$this->resultJson($data);
}



