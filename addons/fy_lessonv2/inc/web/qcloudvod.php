<?php
/**
 * 腾讯云点播
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$we7_version = intval(IMS_VERSION) ? intval(IMS_VERSION) : 2;
$qcloudvod = unserialize($setting['qcloudvod']);
$newqcloudVod = new QcloudVod($qcloudvod['secret_id'], $qcloudvod['secret_key']);
$qcloud_array = array('upQcloudVod','preview');

/* 视频分类 */
$category_list = $site_common->getVideoCategoryList(0);

/* 上一步URL */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('qcloudvod');

if($op=='display'){

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

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_qcloudvod_upload). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
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

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_qcloudvod_upload). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

}elseif($op=='getUploadInfo'){
	$filename = trim($_GPC['filename']);
	$suffix = substr(strrchr($filename, '.'), 1);

	$signature = $newqcloudVod->getUploadSign($suffix);
	$data = array(
		'signature' => $signature
	);

	$this->resultJson($data);

}elseif($op=='saveVideo'){
	$data = array(
		'uniacid'	=> $uniacid,
		'uid'		=> '',
		'teacherid'	=> '',
		'name'		=> trim($_GPC['filename']),
		'pid'		=> intval($_GPC['pid']),
		'cid'		=> intval($_GPC['cid']),
		'ccid'		=> intval($_GPC['ccid']),
		'videoid'	=> trim($_GPC['videoid']),
		'videourl'	=> trim($_GPC['videourl']),
		'size'		=> $_GPC['size'],
		'addtime'	=> time(),
	);
	$res = pdo_insert($this->table_qcloudvod_upload, $data);
	if($res){
		echo '写入数据库成功';
	}else{
		pdo_debug();
	}
	exit();

}elseif($op=='delVideo'){
	$id = intval($_GPC['id']);
	$file = pdo_fetch("SELECT * FROM " .tablename($this->table_qcloudvod_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	$paramArray = array(
		'Action' => 'DeleteVodFile',
		'fileId' => $file['videoid'],
		'priority' => 0,
	);

	$deleteUrl = $newqcloudVod->generateUrl($paramArray, 'GET', 'vod.api.qcloud.com', '/v2/index.php');
	$response = ihttp_request($deleteUrl);

	pdo_delete($this->table_qcloudvod_upload, array('id'=>$id));
	itoast("删除成功", "", "success");

}elseif($op=='preview'){
	$id = intval($_GPC['id']);
	$file = pdo_fetch("SELECT * FROM " .tablename($this->table_qcloudvod_upload). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($file)){
		message("该文件不存在!", "", "error");
	}

	$name_array = explode('.',$file['name']);
	$suffix = strtolower(array_pop($name_array));

	try {
		if($suffix == 'mp3'){
			$file['videourl'] = $file['videoid'];
			$res = $newqcloudVod->getUrlPlaySign($qcloudvod['safety_key'], $file, $exper);
		}else{
			$res = $newqcloudVod->getPlaySign($qcloudvod['safety_key'], $qcloudvod['appid'], $file['videoid'], $qcloudvod['player_name']);
		}
	} catch (Exception $e) {
		message("播放失败，错误原因:".$e->getMessage(), "", "error");
	}
	
}


include $this->template('web/qcloudVod');