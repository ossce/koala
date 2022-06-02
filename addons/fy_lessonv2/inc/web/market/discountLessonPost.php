<?php

$idarr = $_GPC['id'];
$discount_id = intval($_GPC['discount_id']);
$posttype = trim($_GPC['posttype']);
$lesson_discount = $_GPC['discount'];

$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
if(empty($discount)){
	message('该限时折扣活动不存在');
}
if($posttype != 'cancel' && !is_numeric($lesson_discount)){
	message("课程折扣必须为整数", "", "error");
}
if($posttype != 'cancel' && $lesson_discount <=0){
	message("课程折扣不能小于1%", "", "error");
}
if($posttype != 'cancel' && $lesson_discount >=100){
	message("课程折扣不能大于99%", "", "error");
}

$data = array(
	'uniacid'	  => $uniacid,
	'discount_id' => $discount_id,
	'discount'	  => $lesson_discount,
	'member_discount' => $discount['member_discount'],
	'starttime'	  => $discount['starttime'],
	'endtime'	  => $discount['endtime'],
	'addtime'	  => time()
);
if(is_array($idarr) && !empty($idarr)){
	foreach($idarr as $value){
		if($posttype=='cancel'){
			pdo_delete($this->table_discount_lesson, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id, 'lesson_id'=>$value));
		}else{
			$data['lesson_id'] = $value;
			pdo_insert($this->table_discount_lesson, $data);
		}
	}

	if($posttype=='cancel'){
		itoast("批量取消成功", "", "success");
	}else{
		message("批量添加成功", $this->createWebUrl('market', array('op'=>'discountLesson','discount_id'=>$discount_id)), "success");
	}

}else{
	message("参数错误，系统已自动修复，请重试！", referer, "error");
}