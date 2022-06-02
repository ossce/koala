<?php

$cate_id = intval($_GPC['cate_id']);

$category = pdo_get($this->table_article_category, array('uniacid'=>$uniacid,'id'=>$cate_id));
if(empty($category)){
	message("该文章分类不存在", "", "error");
}

if(pdo_delete($this->table_article_category, array('uniacid'=>$uniacid,'id'=>$cate_id))){
	cache_delete('fy_lesson_'.$uniacid.'_article_categorylist');
	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "文章公告", "删除ID:{$cate_id}的文章分类");
	message("删除分类成功", $this->createWebUrl('article', array('op'=>'category')), "success");

}else{

	message("删除分类失败，请稍后重试", "", "error");
}

?>