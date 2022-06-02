<?php

$type = trim($_GPC['type']);


if($type=='contact'){
	$attribute1_item = $attribute2_item = array();
	foreach($attribute1 as $item){
		$attribute1_item[] = $item['id'];
	}
	foreach($attribute2 as $item){
		$attribute2_item[] = $item['id'];
	}

	$data = array(
		'attribute1' => json_encode($attribute1_item),
		'attribute2' => json_encode($attribute2_item),
	);
	pdo_update($this->table_category, $data, array('uniacid'=>$uniacid));

	message("一键关联课程属性成功", $this->createWebUrl('category'), "success");

}elseif($type=='uncontact'){

	$data = array(
		'attribute1' => '',
		'attribute2' => '',
	);
	pdo_update($this->table_category, $data, array('uniacid'=>$uniacid));

	message("一键取消关联课程属性成功", $this->createWebUrl('category'), "success");
}


?>