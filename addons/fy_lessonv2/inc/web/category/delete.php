<?php

$id = intval($_GPC['id']);
$category = pdo_get($this->table_category, array('uniacid'=>$uniacid, 'id'=>$id));
	
if (empty($category)) {
	message("抱歉，分类不存在或是已经被删除！", $this->createWebUrl('category', array('op' => 'display')), "error");
}

$res = pdo_delete($this->table_category, array('uniacid'=>$uniacid,'id' => $id));
if($res){
	if(!$category['parentid']){
		//删除子分类
		pdo_delete($this->table_category, array('uniacid'=>$uniacid,'parentid' => $id));
	}
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程分类", "删除ID:{$id}的课程分类");
}

cache_delete('fy_lesson_'.$uniacid.'_categorylist');
itoast("删除成功", "", "success");


?>