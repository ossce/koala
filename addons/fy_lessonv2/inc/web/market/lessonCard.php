<?php

$time = time();
$ordersn	= trim(($_GPC['ordersn']));
$use_uid	= trim(($_GPC['use_uid']));
$id1		= trim(($_GPC['id1']));
$id2		= trim(($_GPC['id2']));
$prefix		= trim(($_GPC['prefix']));
$password	= trim(($_GPC['password']));
$is_use		= trim(($_GPC['is_use']));
$lesson_id	= intval(($_GPC['lesson_id']));


$condition = " uniacid = :uniacid ";
$params['uniacid'] = $uniacid;

if (!empty($ordersn)) {
	$condition .= " AND ordersn=:ordersn ";
	$params[':ordersn'] = $ordersn;
}
if ($use_uid != '') {
	$condition .= " AND use_uid=:use_uid ";
	$params[':use_uid'] = $use_uid;
}
if ($id1 && $id2) {
	$condition .= " AND id>=:id1 AND id<=:id2";
	$params[':id1'] = $id1;
	$params[':id2'] = $id2;
}elseif ($id1 && !$id2) {
	$condition .= " AND id=:id1";
	$params[':id1'] = $id1;
}elseif (!$id1 && $id2) {
	$condition .= " AND id=:id2";
	$params[':id2'] = $id2;
}
if (!empty($prefix)) {
	$condition .= " AND password LIKE :prefix";
	$params[':prefix'] = $prefix."%";
}
if (!empty($password)) {
	$condition .= " AND password=:password";
	$params[':password'] = $password;
}
if ($is_use != '') {
	if($is_use==0){
		$condition .= " AND is_use=:is_use AND validity>:validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;

	}elseif($is_use==1){
		$condition .= " AND is_use=:is_use ";
		$params[':is_use'] = 1;

	}elseif($is_use==-1){
		$condition .= " AND is_use=:is_use AND validity<:validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}
}
if (!empty($lesson_id)) {
	$condition .= " AND lesson_id=:lesson_id ";
	$params[':lesson_id'] = $lesson_id;
}
if (!empty($_GPC['time']['start'])) {
	$starttime	= strtotime($_GPC['time']['start']);
	$endtime	= strtotime($_GPC['time']['end']);
	$endtime = !empty($endtime) ? $endtime + 86399 : 0;
	if (!empty($starttime)) {
		$condition .= " AND use_time>=:starttime ";
		$params[':starttime'] = $starttime;
	}
	if (!empty($endtime)) {
		$condition .= " AND use_time<:endtime ";
		$params[':endtime'] = $endtime;
	}
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_lessoncard). " WHERE {$condition}", $params);

if($_GPC['export']){
	set_time_limit(0);
	
	$list  = pdo_fetchall("SELECT * FROM " .tablename($this->table_lessoncard). " WHERE {$condition} ORDER BY id DESC", $params);
	foreach ($list as $key => $value) {
		$arr[$key]['id']		= $value['id'];
		$arr[$key]['password']	= $value['password'];
		$arr[$key]['cardtime']	= $value['cardtime'];
		$arr[$key]['lesson_id']	= $value['lesson_id'];
		$arr[$key]['validity']	= date('Y-m-d H:i:s',$value['validity']);
		if($value['is_use']==1){
			$status = "已使用";
		}elseif($value['is_use']==0 && $value['validity']>time()){
			$status = "未使用";
		}elseif($value['is_use']==0 && $value['validity']<time()){
			$status = "已过期";
		}
		$arr[$key]['is_use']	= $status;
		$arr[$key]['use_time']	= $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '';
		$arr[$key]['use_uid']	= $value['use_uid'];
		$arr[$key]['ordersn']	= $value['ordersn'];
		$arr[$key]['addtime']	= date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('卡号', '密钥', '课程时长(天)', '对应课程ID', '有效期', '卡状态', '使用时间', '使用者UID', '使用订单编号', '添加时间');
	$site_common->exportCSV($arr, $title, $fileName="课程卡密");

}elseif($_GPC['qrcode']){
	set_time_limit(180);
	include_once IA_ROOT."/framework/library/qrcode/phpqrcode.php";

	$dirPath = ATTACHMENT_ROOT."images/fy_lessonv2/";
	if(!file_exists($dirPath)){
		mkdir($dirPath, 0777);
	}
	$dirPath .= "{$uniacid}/";
	if(!file_exists($dirPath)){
		mkdir($dirPath, 0777);
	}

	$tmpdir = random(4);
	$dirPath .= "{$tmpdir}/";
	if(!file_exists($dirPath)){
		mkdir($dirPath, 0777);
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lessoncard). " WHERE {$condition} ORDER BY id DESC", $params);
	if(count($list) > 1000){
		message("单次最多仅支持生成1000个二维码");
	}
	foreach($list as $item){
		$qrcode_url = $_W['siteroot']."app/".$this->createMobileUrl('lessoncard', array('type'=>1,'password'=>$item['password']));
		$tmpName = "lessoncard-".$item['id'].".png";
		$qrcodeName = $dirPath.$tmpName;

		QRcode::png($qrcode_url, $qrcodeName, 'L', '8', 2);
	}

	/* 打包下载 */
	$filepath = "../attachment/images/fy_lessonv2/{$uniacid}/{$tmpdir}/";
	$files = glob($filepath.'*');
	$pack = $filepath.'课程卡密二维码-'.$tmpdir.'.zip';
	$zip = new ZipArchive();

	if($zip->open($pack, ZipArchive::CREATE)=== TRUE){
		foreach($files as $file){
			if(file_exists($file)){
				$zip->addFile($file);
			}else{
				exit('无法打开文件，或者文件创建失败');
			}
		}
		$zip->close();
	}

	header('Content-Type:text/html;charset=utf-8');
	header('Content-disposition:attachment;filename=课程卡密二维码-'.$tmpdir.'.zip');
	$filesize = filesize($pack);
	ob_end_clean();
	readfile($pack);
	header('Content-length:'.$filesize);

	foreach($files as $file) {
		unlink($file);
	}
	unlink($pack);
	rmdir($dirPath);
	exit();

}else{
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lessoncard). " WHERE {$condition} ORDER BY id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$v['lesson_id']), array('bookname'));
		$v['bookname'] = $lesson['bookname'];

		$list[$k] = $v;
	}
	$pager = pagination($total, $pindex, $psize);
}


?>