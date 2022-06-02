<?php

$id = intval($_GPC['id']); /* 当前分类id */
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':parentid'=>0));

if (!empty($id)) {
	$category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($category)){
		message("该分类不存在或已被删除", "", "error");
	}

	if($category['parentid']){
		$parent = pdo_get($this->table_category, array('uniacid'=>$uniacid,'parentid'=>0,'id'=>$category['parentid']), array('name'));
	}
	$parent_name = !empty($parent) ? $parent['name'] : '顶级分类';

	/*分类课程属性*/
	$category['attribute1'] = json_decode($category['attribute1']);
	$category['attribute2'] = json_decode($category['attribute2']);
}else{
	$parent = pdo_get($this->table_category, array('uniacid'=>$uniacid,'parentid'=>0,'id'=>$_GPC['parentid']), array('name'));
	$parent_name = !empty($parent) ? $parent['name'] : '顶级分类';
}

if (checksubmit('submit')) {
	if (empty($_GPC['catename'])) {
		message("抱歉，请输入分类名称");
	}

	$data = array(
		'uniacid'      => $_W['uniacid'],
		'name'         => trim($_GPC['catename']),
		'ico'          => trim($_GPC['ico']),
		'link'		   => trim($_GPC['link']),
		'link_pc'	   => trim($_GPC['link_pc']),
		'parentid'     => intval($_GPC['parentid']),
		'displayorder' => intval($_GPC['displayorder']),
		'is_hot'	   => intval($_GPC['is_hot']),
		'is_show'	   => intval($_GPC['is_show']),
		'search_show'  => intval($_GPC['search_show']),
		'attribute1'   => json_encode($_GPC['attribute1']),
		'attribute2'   => json_encode($_GPC['attribute2']),
		'addtime'      => time(),
	);

	if($data['parentid']){
		$parent_cate = pdo_get($this->table_category, array('id'=>$data['parentid'], 'parentid'=>0));
		if(!$parent_cate){
			message("父分类不存在，请重新选择"); 
		}
	}

	if (!empty($id)) {
		unset($data['addtime']);
		$res = pdo_update($this->table_category, $data, array('id' => $id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程分类", "编辑ID:{$id}的课程分类");
		}
	} else {
		pdo_insert($this->table_category, $data);
		$cid = pdo_insertid();
		if($cid){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "课程分类", "新增ID:{$cid}的课程分类");
		}
	}
	cache_delete('fy_lesson_'.$uniacid.'_categorylist');
	message("更新分类成功！", $this->createWebUrl('category', array('op' => 'display')), "success");
}


?>