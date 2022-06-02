<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	$data = array(
		'code'	=> -1,
		'msg'	=> '课程不存在或已被删除',
	);
	$this->resultJson($data);
}


/* 创建目录 */
$dirPath = ATTACHMENT_ROOT."document/";
$this->checkdir($dirPath);
$dirPath .= $uniacid.'/';
$this->checkdir($dirPath);
$dirPath .= date('Y', time()).'/';
$this->checkdir($dirPath);
$dirPath .= date('m', time()).'/';
$this->checkdir($dirPath);
$dirPath .= date('d', time()).'/';
$this->checkdir($dirPath);

/* 判断上传文件类型 */
foreach($_FILES['documentFile']['name'] as $item){
	$suffix = strtolower(substr(strrchr($item, '.'), 1));
	if($suffix!='doc' && $suffix!='docx' && $suffix!='xls' && $suffix!='xlsx' && $suffix!='ppt' && $suffix!='pptx' && $suffix!='pdf' && $suffix!='zip'){
		$data = array(
			'code'	=> -1,
			'msg'	=> '不允许上传'.$suffix.'文件',
		);
		$this->resultJson($data);
	}
}

/* 执行文件保存处理 */
load()->func('file');
$count = count($_FILES['documentFile']['name'])-1;
for($i=0; $i<=$count; $i++){
	$suffix = '.'.strtolower(substr(strrchr($_FILES['documentFile']['name'][$i], '.'), 1));
	$filename = random(30, false).$suffix;
	
	if(move_uploaded_file($_FILES['documentFile']['tmp_name'][$i], $dirPath.$filename)){
		$res = true;

		$params = array(
			'uniacid'	=> $uniacid,
			'title'		=> $_FILES['documentFile']['name'][$i],
			'lessonid'  => $pid,
			'filepath'  => 'document/'.$uniacid.'/'.date('Y', time()).'/'.date('m', time()).'/'.date('d', time()).'/'.$filename,
			'addtime'   => time(),
		);

		if(!empty($_W['setting']['remote']['type'])) {
			$remotestatus = file_remote_upload($params['filepath']);
			if(is_error($remotestatus)) {
				$res = false;
				$data = array(
					'code'	=> -1,
					'msg'	=> '远程附件上传失败，请联系管理员检查配置',
				);
				$this->resultJson($data);
			}
		}

		if($res){
			pdo_insert($this->table_document, $params);
		}

		unset($params);
	}else{
		$data = array(
			'code'	=> -1,
			'msg'	=> '移动文件失败，请检查相关权限',
		);
		$this->resultJson($data);
	}
}

$data = array(
	'code'	=> 0,
	'msg'	=> '上传成功',
);
$this->resultJson($data);

