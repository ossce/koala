<?php

$cate_id = intval($_GPC['cate_id']);
if (!empty($cate_id)) {
	$category = pdo_get($this->table_article_category, array('uniacid'=>$uniacid,'id'=>$cate_id));
	if(empty($category)){
		message("该文章分类不存在", "", "error");
	}
}

if (checksubmit('submit')) {

	$data = array(
		'uniacid'      => $_W['uniacid'],
		'name'         => trim($_GPC['name']),
		'displayorder' => intval($_GPC['displayorder']),
		'status'	   => intval($_GPC['status']),
	);

	if (!empty($cate_id)) {
		$res = pdo_update($this->table_article_category, $data, array('id'=>$cate_id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "文章公告", "编辑ID:{$cate_id}的文章分类");
		}
	} else {
		$data['addtime'] = time();
		pdo_insert($this->table_article_category, $data);
		$cate_id = pdo_insertid();
		if($cate_id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "文章公告", "新增ID:{$cate_id}的文章分类");
		}
	}
	cache_delete('fy_lesson_'.$uniacid.'_article_categorylist');
	message("操作成功", $this->createWebUrl('article', array('op'=>'category')), "success");
}

?>