<?php

$vip_list = pdo_getall($this->table_vip_level, array('uniacid'=>$uniacid));
$vipname_list = array();
foreach($vip_list as $v){
	$vipname_list[$v['id']] = $v['level_name'];
}

$pindex = max(1, intval($_GPC['page']));
$psize = 15;
$time = time();


$ordersn	= trim(($_GPC['ordersn']));
$own_uid	= trim(($_GPC['own_uid']));
$id1		= trim(($_GPC['id1']));
$id2		= trim(($_GPC['id2']));
$password	= trim(($_GPC['password']));
$is_use		= trim(($_GPC['is_use']));
$level_id	= trim(($_GPC['level_id']));

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if (!empty($ordersn)) {
	$condition .= " AND ordersn=:ordersn ";
	$params[':ordersn'] = $ordersn;
}
if ($own_uid != '') {
	$condition .= " AND own_uid=:own_uid ";
	$params[':own_uid'] = $own_uid;
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
		$params[':is_use'] = $is_use;

	}elseif($is_use==-1){
		$condition .= " AND is_use=:is_use AND validity<:validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}
}
if (!empty($level_id)) {
	$condition .= " AND level_id=:level_id ";
	$params[':level_id'] = $level_id;
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

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_vipcard). " WHERE {$condition}", $params);

if($_GPC['export']){
	set_time_limit(0);
	
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition}", $params);
	foreach ($list as $key => $value) {
		$arr[$key]['id']			= $value['id'];
		$arr[$key]['password']	= $value['password'];
		$arr[$key]['level_name']	= $vipname_list[$value['level_id']];
		$arr[$key]['viptime']		= $value['viptime'];
		$arr[$key]['validity']	= date('Y-m-d H:i:s',$value['validity']);
		if($value['is_use']==1){
			$status = "已使用";
		}elseif($value['is_use']==0 && $value['validity']>time()){
			$status = "未使用";
		}elseif($value['is_use']==0 && $value['validity']<time()){
			$status = "已过期";
		}
		$arr[$key]['is_use']		= $status;
		$arr[$key]['own_uid']		= $value['own_uid'];
		$arr[$key]['nickname']    = $value['nickname'];
		$arr[$key]['ordersn']     = $value['ordersn'];
		$arr[$key]['use_time']    = $value['use_time']?date('Y-m-d H:i:s', $value['use_time']):'';
		$arr[$key]['addtime']     = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('服务卡号', '卡密','VIP等级', '卡时长(天)','有效期', '卡状态', '拥有者uid', '使用者', '订单号', '使用时间', '添加时间');
	$site_common->exportCSV($arr, $title, $fileName="VIP服务卡");

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

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition}", $params);
	if(count($list) > 1000){
		message("单次最多仅支持生成1000个二维码");
	}
	foreach($list as $item){
		$qrcode_url = $_W['siteroot']."app/".$this->createMobileUrl('vip', array('op'=>'vipcard','code'=>$item['password']));
		$tmpName = "vipcard-".$item['id'].".png";
		$qrcodeName = $dirPath.$tmpName;

		QRcode::png($qrcode_url, $qrcodeName, 'L', '8', 2);
	}

	/* 打包下载 */
	$filepath = "../attachment/images/fy_lessonv2/{$uniacid}/{$tmpdir}/";
	$files = glob($filepath.'*');
	$pack = $filepath.'VIP服务卡二维码-'.$tmpdir.'.zip';
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
	header('Content-disposition:attachment;filename=VIP服务卡二维码-'.$tmpdir.'.zip');
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
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition} ORDER BY id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['own_uid']){
			$own_user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$v['own_uid']), array('nickname'));
			$v['own_nickname'] = $own_user['nickname'];
		}

		$list[$k] = $v;
	}

	$pager = pagination($total, $pindex, $psize);
}