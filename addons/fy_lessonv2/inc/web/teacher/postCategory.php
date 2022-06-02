<?php

echo $id = intval($_GPC['id']);
if (!empty($id)) {
	$category = pdo_get($this->table_teacher_category, array('uniacid'=>$uniacid,'id'=>$id));
	if(empty($category)){
		message("该讲师分类不存在", "", "error");
	}
}

if (checksubmit('submit')) {

	$data = array(
		'uniacid'      => $_W['uniacid'],
		'name'         => trim($_GPC['name']),
		'displayorder' => intval($_GPC['displayorder']),
		'status'	   => intval($_GPC['status']),
	);

	if (!empty($id)) {
		$res = pdo_update($this->table_teacher_category, $data, array('id'=>$id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理", "编辑ID:{$id}的讲师分类");
		}
	} else {
		$data['addtime'] = time();
		pdo_insert($this->table_teacher_category, $data);
		$id = pdo_insertid();
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理", "新增ID:{$id}的讲师分类");
		}
	}
	cache_delete('fy_lesson_'.$uniacid.'_teacher_categorylist');
	message("操作成功", $this->createWebUrl('teacher', array('op'=>'category')), "success");
}

?>