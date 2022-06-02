<?php

set_time_limit(0);
load()->func('logging');

if($_FILES['lessonFile']['error'] != '0'){
	message("系统繁忙，上传文件失败，请稍后重试", $this->createWebUrl("lesson"), "error");
}

$filename = $_FILES['lessonFile']['name'];
$tmp_file = $_FILES['lessonFile']['tmp_name'];

header("Content-type:text/html;charset=utf-8");

//文件后缀名
$suffix = strtolower(substr(strrchr($filename, '.'), 1));
if($suffix != "xls") {
	message("请上传后缀是xls的文件", $this->createWebUrl("lesson"), "error");
}

//设置图片上传目录
$image_dirpath = ATTACHMENT_ROOT."images/";
$this->checkdir($image_dirpath);
$image_dirpath .= "{$uniacid}/";
$this->checkdir($image_dirpath);
$image_dirpath .= date('Y', time())."/";
$this->checkdir($image_dirpath);
$image_dirpath .= date('m', time())."/";
$this->checkdir($image_dirpath);

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
$newfile = $savePath . 'LESSON_' . random(24) . "." . $suffix;

/* 开始上传 */
if (!copy($tmp_file, $newfile)) {
	message("上传文件失败，请稍候重试", $this->createWebUrl("lesson"), "error");
}

$phpexcel = new FyLessonv2PHPExcel();
$data = $phpexcel->inputExcel($newfile);
if (file_exists($newfile)) {
	unlink($newfile);
}

$i=0;
foreach($data as $k=>$v){
	$serial = $k+1;

	/* 过滤第一行说明和第二行名称 */
	if($k < 3){
		continue;
	}

	if(!trim($v[2])){
		logging_run('课程'.$serial.'导入失败，原因：课程名称为空', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
		continue;
	}
	if(!trim($v[6])){
		logging_run('课程'.$serial.'导入失败，原因：课程封面图为空', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
		continue;
	}
	
	if($v[6]){
		if(strstr($v[6], "http://") || strstr($v[6], "https://")){
			$images = random(30).'.';
			$image_type = $site_common->saveImage($v[6], $image_dirpath.$images, $type = '');
			if(!$image_type){
				logging_run('课程'.$serial.'封面图导入失败，原因：远程图片保存失败', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
			}else{
				$images = "images/{$uniacid}/".date('Y', time())."/".date('m', time())."/".$images.$image_type;
				if (!empty($_W['setting']['remote']['type'])) {
					$remotestatus = file_remote_upload($images);
					if (is_error($remotestatus)) {
						exit(json_encode("远程附件上传失败，请联系管理员检查配置"));
						logging_run('课程'.$serial.'封面图导入失败，原因：远程附件上传失败', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
					}
				}
			}
		}else{
			$images = $v[6];
		}
	}

	if($v[9]){
		$vipview = str_replace("，", ",", $v[9]);
		$vipview = trim($vipview, ",");
		$vipview = explode(',', $vipview);
		$vipview = array_filter($vipview);
		if(!empty($vipview)){
			$vipview = json_encode(array_values($vipview));
		}else{
			$vipview = '';
		}
	}

	if($v[14]){
		$price_info = str_replace("，", ",", $v[14]);
		$price_info = trim($price_info, ",");
		$price_info = explode(',', $price_info);
	}

	if($v[20]){
		$recommendid = str_replace("，", ",", $v[20]);
		$recommendid = trim($recommendid, ",");
	}

	$commission = array(
		'commission_type' => intval($v[23]),
		'commission1'	  => floatval($v[24]),
		'commission2'	  => floatval($v[25]),
		'commission3'	  => floatval($v[26]),
	);

	if($v[29]){
		$appoint_info = str_replace("，", ",", $v[29]);
		$appoint_info = trim($appoint_info, ",");
		$appoint_info = explode(',', $appoint_info);
		$appoint_info = array_filter($appoint_info);
		if(!empty($appoint_info)){
			$appoint_info = json_encode(array_values($appoint_info));
		}else{
			$appoint_info = '';
		}
	}

	if($v[30] || $v[31]){
		if($v[31]){
			$appoint_validity = trim($v[31], "'");
			$appoint_validity = date('Y-m-d H:i:s', strtotime($appoint_validity));
		}
		$buynow_info = array(
			'appoint_addres'   => $v[30],
			'appoint_validity' => $appoint_validity,
		);
		$buynow_info= json_encode($buynow_info);
	}
	
	if($v[32]){
		$saler_uids = str_replace("，", ",", $v[32]);
		$saler_uids = trim($saler_uids, ",");
		$saler_uids = explode(',', $saler_uids);
		$saler_uids = array_filter($saler_uids);
		if(!empty($saler_uids)){
			$saler_uids = json_encode(array_values($saler_uids));
		}else{
			$saler_uids = '';
		}
	}

	$newData = array(
		'uniacid'			=> $uniacid,
		'lesson_type'		=> $v[1],
		'bookname'			=> trim($v[2]),
		'pid'				=> $v[3],
		'cid'				=> $v[4],
		'teacherid'			=> $v[5],
		'images'			=> $images,
		'status'			=> $v[7],
		'teacher_income'	=> $v[8],
		'vipview'			=> $vipview,
		'lesson_show'		=> $v[10],
		'drag_play'			=> $v[11],
		'section_status'	=> $v[12],
		'displayorder'		=> $v[13],
		'price'				=> $price_info[1],
		'stock'				=> $price_info[2],
		'descript'			=> $v[15],
		'integral'			=> $v[16],
		'integral_rate'		=> $v[17],
		'deduct_integral'	=> $v[18],
		'virtual_buynum'	=> $v[19],
		'recommendid'		=> $recommendid,
		'support_coupon'	=> $v[21],
		'ico_name'			=> $v[22],
		'commission'		=> serialize($commission),
		'appoint_dir'		=> $v[27],
		'verify_number'		=> $v[28],
		'appoint_info'		=> $appoint_info,
		'buynow_info'		=> $buynow_info,
		'saler_uids'		=> $saler_uids,
		'addtime'			=> time(),
		'update_time'		=> time(),
	);
	if($v[0]){
		$newData['id'] = $v[0];
	}

	$res = pdo_insert($this->table_lesson_parent, $newData);
	$lessonid = pdo_insertid();
	if($res){
		if(intval($price_info[0])){
			$spec_data = array(
				'uniacid'    => $uniacid,
				'lessonid'   => $lessonid,
				'spec_day'	 => $price_info[0],
				'spec_price' => $price_info[1],
				'spec_stock' => $price_info[2],
				'spec_name'  => $price_info[3],
				'addtime'    => time(),
			);
			pdo_insert($this->table_lesson_spec, $spec_data);
		}
		$i++;
	}else{
		logging_run('课程'.$serial.'导入失败，原因：课程ID重复，写入失败', 'trace', 'fy_lessonv2_'.$uniacid.date('Y-m-d'));
	}

	unset($images);
	unset($vipview);
	unset($price_info);
	unset($recommendid);
	unset($appoint_info);
	unset($appoint_validity);
	unset($buynow_info);
	unset($saler_uids);
	unset($spec_data);
	
}

message("成功导入{$i}个课程", $this->createWebUrl("lesson"), "success");