<?php

$id = intval($_GPC['id']);
$member_coupon = pdo_fetch("SELECT a.*,b.nickname,b.mobile,b.realname FROM " .tablename($this->table_member_coupon). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.id=:id", array(':id'=>$id));

if(empty($member_coupon)){
	message("该优惠券记录不存在", "", "error");
}

if($member_coupon['use_type']==1){
	$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$member_coupon['category_id']), array('name'));
	$category_name = $category['name'] ? "".$category['name']."分类课程" : "全部分类课程";
}elseif($member_coupon['use_type']==2){
	$category_name = "指定部分课程";

	$lesson_list = array();
	$lesson_ids = json_decode($member_coupon['lesson_ids'], true);
	foreach($lesson_ids as $lesson_id){
		$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lesson_id), array('id','bookname'));
		if(!empty($lesson)){
			$lesson_list[] = $lesson;
		}
	}
}
