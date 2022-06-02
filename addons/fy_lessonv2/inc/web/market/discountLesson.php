<?php

$discount_id = intval($_GPC['discount_id']);
$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
if(empty($discount)){
	message('该限时折扣活动不存在');
}

$condition = " b.uniacid=:uniacid AND b.discount_id=:discount_id";
$params[':uniacid'] = $uniacid;
$params[':discount_id'] = $discount_id;

$list = pdo_fetchall("SELECT a.id,a.bookname,a.price,b.discount,b.starttime,b.endtime FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_discount_lesson). " b ON a.id=b.lesson_id WHERE {$condition} LIMIT ".($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_discount_lesson). " b ON a.id=b.lesson_id WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);