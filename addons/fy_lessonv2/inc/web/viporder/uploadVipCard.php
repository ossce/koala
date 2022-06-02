<?php

//set_time_limit(0);

load()->func('logging');

if($_FILES['vipCardFile']['error'] != '0'){
	message("系统繁忙，上传文件失败，请稍后重试", $this->createWebUrl("viporder", array('op'=>'vipcard')), "error");
}

$filename = $_FILES['vipCardFile']['name'];
$tmp_file = $_FILES['vipCardFile']['tmp_name'];

header("Content-type:text/html;charset=utf-8");

//文件后缀名
$suffix = strtolower(substr(strrchr($filename, '.'), 1));
if($suffix != "xls") {
	message("请上传后缀是xls的文件", $this->createWebUrl("viporder", array('op'=>'vipcard')), "error");
}

//设置excel文件临时存储目录
$savePath = ATTACHMENT_ROOT.'excel/';
if (!file_exists($savePath)) {
	mkdir($savePath, 0777);
}
$savePath .= 'fy_lessonv2/';
if (!file_exists($savePath)) {
	mkdir($savePath, 0777);
}
$savePath .= $uniacid . '/';
if (!file_exists($savePath)) {
	mkdir($savePath, 0777);
}

/* 命名临时上传的文件 */
$newfile = $savePath . 'VIPCARD_' . random(24) . "." . $suffix;

/* 开始上传 */
if (!copy($tmp_file, $newfile)) {
	message("上传文件失败，请稍候重试", $this->createWebUrl("viporder", array('op'=>'vipcard')), "error");
}

$phpexcel = new FyLessonv2PHPExcel();
$data = $phpexcel->inputExcel($newfile);
if (file_exists($newfile)) {
	unlink($newfile);
}


/* VIP等级ID数组 */
$vip_level = pdo_getall($this->table_vip_level, array('uniacid'=>$uniacid), array('id'));
$level_ids = array();
foreach($vip_level as $v){
	$level_ids[] = $v['id'];
}

$t = 0;
foreach($data as $v){
	if(empty($v[0])){
		continue;
	}
	if(!in_array($v[1], $level_ids)){
		$t++;
		logging_run('卡密：'.$v[0].'导入失败，原因：VIP等级ID不存在', 'trace', 'fylessonv2_'.$uniacid.'_vipcard_'.date('Y-m-d'));
		continue;
	}
	if($v[2]<=0){
		$t++;
		logging_run('卡密：'.$v[0].'导入失败，原因：VIP等级有效期错误', 'trace', 'fylessonv2_'.$uniacid.'_vipcard_'.date('Y-m-d'));
		continue;
	}

	$vipcard = array(
		'uniacid'	=> $uniacid,
		'password'	=> $v[0],
		'viptime'	=> $v[2],
		'level_id'	=> $v[1],
		'validity'	=> strtotime(gmdate("Y-m-d H:i:s",\PHPExcel_Shared_Date::ExcelToPHP($v[3]))),
		'addtime'	=> time(),
	);
	if(!pdo_insert($this->table_vipcard, $vipcard)){
		$t++;
		logging_run('卡密：'.$v[0].'导入失败，原因：卡密入库失败，请检查卡密是否重复', 'trace', 'fylessonv2_'.$uniacid.'_vipcard_'.date('Y-m-d'));
		continue;
	}
}

if($t){
	$trace_tips = "，系统已过滤{$t}个无效或重复卡密";
}

message("导入成功".$trace_tips, $this->createWebUrl("viporder", array('op'=>'vipcard')), "success");

