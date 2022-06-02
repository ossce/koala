<?php

$id = intval($_GPC['id']);

$poster = pdo_get($this->table_poster, array('uniacid'=>$uniacid,'id'=>$id,'poster_type'=>1));
if(empty($poster)){
	message("该分销海报不存在", "", "error");
}

if(pdo_delete($this->table_poster, array('uniacid'=>$uniacid, 'id'=>$id))){

	cache_delete('fy_lesson_'.$uniacid.'_poster_list');
	$files = glob(ATTACHMENT_ROOT."images/fy_lessonv2/{$uniacid}/*");
	foreach($files as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}
	message("删除成功", $this->createWebUrl('poster'), "success");
}else{

	message("删除失败，请稍候重试", "", "error");
}


