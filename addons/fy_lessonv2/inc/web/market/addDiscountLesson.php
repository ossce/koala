<?php

$discount_id = intval($_GPC['discount_id']);
$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
if(empty($discount)){
	message('该限时折扣活动不存在');
}

$lessons = pdo_getall($this->table_discount_lesson, array(), array('lesson_id'));
$lesson_ids = array();
if(!empty($lessons)){
	foreach($lessons as $k=>$v){
		$lesson_ids[$k] = $v['lesson_id'];
	}
}

$condition = "uniacid=:uniacid AND status=:status";
$params[':uniacid'] = $uniacid;
$params[':status'] = 1;

if(!empty($_GPC['bookname'])){
	$condition .= " AND bookname LIKE :bookname";
	$params[':bookname'] = '%'.trim($_GPC['bookname']).'%';
}

$list = pdo_fetchall("SELECT id,bookname,price,addtime FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} LIMIT ".($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}",$params);
$pager = pagination($total, $pindex, $psize);