<?php

$id = intval($_GPC['id']);
$category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if (empty($category)) {
	message("抱歉，分类不存在或是已经被删除！", $this->createWebUrl('category', array('op' => 'display')), "error");
}

if($_GPC['type']=='index'){
	if($category['is_show']==1){
		$data['is_show'] = 0;
		$message = "隐藏首页分类";
	}else{
		$data['is_show'] = 1;
		$message = "显示首页分类";
	}
}elseif($_GPC['type']=='search'){
	if($category['search_show']==1){
		$data['search_show'] = 0;
		$message = "隐藏分类";
	}else{
		$data['search_show'] = 1;
		$message = "显示分类";
	}
}

if(pdo_update($this->table_category, $data, array('id'=>$id))){
	cache_delete('fy_lesson_'.$uniacid.'_categorylist');
	message("{$message}成功", $this->createWebUrl('category', array('op' => 'display')), "success");
}else{
	message("{$message}失败", $this->createWebUrl('category', array('op' => 'display')), "error");
}


?>