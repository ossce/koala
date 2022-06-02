<?php

$id = intval($_GPC['id']);
$suggest_cateory = pdo_get($this->table_suggest_category, array('uniacid'=>$uniacid,'id'=>$id));
if(empty($suggest_cateory)){
	$json_data = array(
		'code'		=> '-1',
		'message'	=> '该投诉类型不存在',
	);
	$this->resultJson($json_data);
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_suggest_category). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
if($total==1){
	$json_data = array(
		'code'		=> '-1',
		'message'	=> '投诉类型最少需保留一个',
	);
	$this->resultJson($json_data);
}

if(pdo_delete($this->table_suggest_category, array('uniacid'=>$uniacid,'id'=>$id))){
	cache_delete('fy_lesson_'.$uniacid.'_suggest_category');
	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "其他管理->投诉建议", "删除投诉类型[{$suggest_cateory['title']}]");
	$json_data = array(
		'code'		=> '0',
		'message'	=> '删除成功',
	);
	$this->resultJson($json_data);
}else{
	$json_data = array(
		'code'		=> '-1',
		'message'	=> '删除失败，请稍后重试',
	);
	$this->resultJson($json_data);
}

?>