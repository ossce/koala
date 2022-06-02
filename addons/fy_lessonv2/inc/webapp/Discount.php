<?php
/**
 * 限时抢购
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


$pindex =max(1,$_GPC['page']);
$psize = 16;

$discount_id = intval($_GPC['discount_id']);
$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
if(!$discount){
	message('活动不存在');
}
if($discount['starttime'] > time()){
	$nostart = true;
	$btn_name = "未开始";
	$count_date = date('Y-m-d H:i:s', $discount['starttime']);
}
if($discount['starttime'] < time() && $discount['endtime'] > time()){
	$starting = true;
	$btn_name = "立即抢购";
	$count_date = date('Y-m-d H:i:s', $discount['endtime']);
}
if($discount['endtime'] < time()){
	$ended = true;
	$btn_name = "已结束";
}

$title = $discount['title'];
$condition = " b.uniacid=:uniacid AND b.discount_id=:discount_id";
$params[':uniacid'] = $uniacid;
$params[':discount_id'] = $discount_id;

$list = pdo_fetchall("SELECT a.*,b.discount FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " . tablename($this->table_discount_lesson) . " b ON a.id=b.lesson_id WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach($list as $k=>$v){
	$list[$k]['discount_price'] = round($v['price']*$v['discount']*0.01, 2);
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " . tablename($this->table_discount_lesson) . " b ON a.id=b.lesson_id WHERE {$condition} ", $params);
$pager = $webAppCommon->pagination($total, $pindex, $psize);


include $this->template("../webapp/{$template}/discount");


?>