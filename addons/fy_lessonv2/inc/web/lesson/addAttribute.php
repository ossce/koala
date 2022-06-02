<?php


$attr_type = trim($_GPC['type']);
if(!in_array($attr_type, array('attribute1','attribute2'))){
	message('属性参数缺失');
}

$id = intval($_GPC['id']);
if($id){
	$attribute = pdo_get($this->table_attribute, array('uniacid'=>$uniacid, 'id'=>$id, 'attr_type'=>$attr_type));
	if(empty($attribute)){
		message('该属性值不存在');
	}
}

if(checksubmit()){
	$data = array(
		'uniacid'	   => $uniacid,
		'name'		   => trim($_GPC['name']),
		'attr_type'	   => $attr_type,
		'displayorder' => intval($_GPC['displayorder']),
	);

	if($id){
		pdo_update($this->table_attribute, $data, array('uniacid'=>$uniacid,'id'=>$id));
	}else{
		$data['addtime'] = time();
		pdo_insert($this->table_attribute, $data);
	}

	cache_delete('fy_lesson_'.$uniacid.'_attribute1');
	cache_delete('fy_lesson_'.$uniacid.'_attribute2');
	message('操作成功', $this->createWebUrl('lesson', array('op'=>'attribute')), 'success');
}


?>