<?php

set_time_limit(0);
load()->func('logging');

$pid = intval($_GPC['pid']);
$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$pid));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error", $this->createWebUrl("lesson", array('op'=>'viewsection','pid'=>$pid)), "error");
}

if($_FILES['sectionFile']['error'] != '0'){
	message("系统繁忙，上传文件失败，请稍后重试", $this->createWebUrl("lesson", array('op'=>'viewsection','pid'=>$pid)), "error");
}

$filename = $_FILES['sectionFile']['name'];
$tmp_file = $_FILES['sectionFile']['tmp_name'];

header("Content-type:text/html;charset=utf-8");

//文件后缀名
$suffix = strtolower(substr(strrchr($filename, '.'), 1));
if($suffix != "xls") {
	message("请上传后缀是xls的文件", $this->createWebUrl("lesson", array('op'=>'viewsection','pid'=>$pid)), "error");
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
$newfile = $savePath . 'SECTION_' . random(24) . "." . $suffix;

/* 开始上传 */
if (!copy($tmp_file, $newfile)) {
	message("上传文件失败，请稍候重试", $this->createWebUrl("lesson", array('op'=>'viewsection','pid'=>$pid)), "error");
}

$phpexcel = new FyLessonv2PHPExcel();
$data = $phpexcel->inputExcel($newfile);
if (file_exists($newfile)) {
	unlink($newfile);
}

$i=0;
foreach($data as $k=>$v){
	$serial = $k+1;

	//章节标题为空，则跳过
	if(empty($v[2])){
		logging_run('章节'.$serial.'导入失败，原因：章节名称为空', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
		continue;
	}

	//如果课程id存在在，则检查课程
	if(intval($v[0])){
		$parent = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>intval($v[0])), array('id'));
		if(empty($parent)){
			continue;
		}
		$parentid = intval($v[0]);
	}else{
		$parentid = $pid;
	}

	//excel表格课程目录ID不为空
	if(intval($v[1])){
		$title_list = pdo_getall($this->table_lesson_title, array('uniacid'=>$uniacid,'lesson_id'=>$parentid), array('title_id'));
		if(!empty($title_list)){
			$title_arr = array();
			foreach($title_list as $v1){
				$title_arr[] = $v1['title_id'];
			}
		}

		//数据库该课程目录为空
		if($v[1] && empty($title_arr)){
			logging_run('章节'.$serial.'导入失败，原因：课程目录ID不存在', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
			continue;
		}

		//excel里的课程目录无法匹配当前课程目录ID
		if($v[1] && !in_array($v[1],$title_arr)){
			logging_run('章节'.$serial.'导入失败，原因：课程目录ID不属于当前课程', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
			continue;
		}
	}

	$newData = array(
		'uniacid'		=> $uniacid,
		'parentid'		=> $parentid,
		'title_id'		=> $v[1],
		'title'			=> $v[2],
		'images'		=> $v[3],
		'sectiontype'	=> $v[4],
		'savetype'		=> $v[5],
		'is_live'		=> $v[6],
		'videourl'		=> $v[7],
		'videotime'		=> $v[8],
		'content'		=> $v[9],
		'displayorder'	=> $v[10],
		'is_free'		=> $v[11],
		'test_time'		=> $v[12],
		'password'		=> $v[13],
		'status'		=> $v[14],
		'auto_show'		=> 0,
		'addtime'		=> time(),
	);
	$res = pdo_insert($this->table_lesson_son, $newData);
	if($res){
		$i++;
		pdo_update($this->table_lesson_parent, array('update_time'=>time()),array('uniacid'=>$uniacid,'id'=>$parentid));
	}

	unset($parentid);
	unset($title_list);
	unset($title_arr);
}

message("成功导入{$i}个章节", $this->createWebUrl("lesson", array('op'=>'viewsection','pid'=>$pid)), "success");