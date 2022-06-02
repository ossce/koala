<?php

$id = intval($_GPC['id']);
if($id){
	$poster = pdo_get($this->table_poster, array('uniacid'=>$uniacid,'id'=>$id,'poster_type'=>2));
	if(empty($poster)){
		message("该课程海报不存在", "", "error");
	}
}

$poster_setting= json_decode(str_replace("&quot;", "'", $poster['poster_setting']) , true);

if(checksubmit()){

	$data = array(
		'uniacid'		 => $uniacid,
		'poster_name'	 => trim($_GPC['poster_name']),
		'poster_bg'		 => trim($_GPC['poster_bg']),
		'poster_setting' => htmlspecialchars_decode($_GPC['poster_setting']),
		'poster_type'	 => 2,
		'update_time'	 => time(),
	);

	if(empty($data['poster_name'])){
		message("请填写海报名称", "", "error");
	}
	if(empty($data['poster_bg'])){
		message("请上传海报背景图片", "", "error");
	}

	if(!$id){
		$data['addtime'] = time();
		$res = pdo_insert($this->table_poster, $data);
	}else{
		$res = pdo_update($this->table_poster, $data, array('id'=>$id));
	}

	if($res){
		cache_delete('fy_lesson_'.$uniacid.'_lesson_poster_list');
		message("保存海报成功", $this->createWebUrl('lesson',array('op'=>'qrcodeList')), "success");
	}else{
		message("保存海报失败，请稍候重试", "", "error");
	}
}


