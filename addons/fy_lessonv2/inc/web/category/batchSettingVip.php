<?php

$category_id = intval($_GPC['category_id']);
$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$category_id));
if(empty($category)){
	$data = array(
		'code' => -1,
		'msg'  => '分类不存在，请刷新页面重试',
	);
	$this->resultJson($data);
}

if(!$category['parentid']){
	//一级分类
	$lesson_list = pdo_getall($this->table_lesson_parent, array('uniacid'=>$uniacid,'pid'=>$category_id), array('id'));
}else{
	//二级分类
	$lesson_list = pdo_getall($this->table_lesson_parent, array('uniacid'=>$uniacid,'cid'=>$category_id,'pid'=>$category['parentid']), array('id'));
}


$vips = trim($_GPC['vips']);
if(empty($vips)){
	$data = array(
		'code' => -1,
		'msg'  => '请选择免费学习的VIP等级',
	);
	$this->resultJson($data);
}

$vipview = explode(',', $vips);
foreach($lesson_list as $item){
	pdo_update($this->table_lesson_parent, array('vipview'=>json_encode($vipview)), array('uniacid'=>$uniacid,'id'=>$item['id']));
}

$data = array(
	'code' => 0,
	'msg'  => '批量设置VIP等级免费学习成功',
);
$this->resultJson($data);


exit();