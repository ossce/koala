<?php

$id = intval($_GPC['id']);

$poster = pdo_get($this->table_poster, array('uniacid'=>$uniacid,'id'=>$id,'poster_type'=>2));
if(empty($poster)){
	message("该分销海报不存在", "", "error");
}

if(pdo_delete($this->table_poster, array('uniacid'=>$uniacid, 'id'=>$id))){
	cache_delete('fy_lesson_'.$uniacid.'_lesson_poster_list');
	message("删除成功", $this->createWebUrl('lesson',array('op'=>'qrcodeList')), "success");
}else{
	message("删除失败，请稍候重试", "", "error");
}
