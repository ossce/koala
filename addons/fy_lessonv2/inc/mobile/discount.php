<?php
/**
 * 限时折扣
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$pindex =max(1,$_GPC['page']);
$psize = 10;

if($op=='display'){
	$discount_id = intval($_GPC['discount_id']);
	$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
	if(empty($discount)){
		message('限时折扣活动不存在');
	}
	if($discount['starttime'] > time()){
		message('限时折扣活动未开始');
	}
	if($discount['endtime'] < time()){
		message('限时折扣活动已结束');
	}

	$banner = pdo_fetch("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND banner_type=:banner_type AND link LIKE :link", array(':uniacid'=>$uniacid,':banner_type'=>2, ':link'=>"%&discount_id={$discount_id}&%"));

	$title = $discount['title'];
	$condition = " b.uniacid=:uniacid AND b.discount_id=:discount_id";
	$params[':uniacid'] = $uniacid;
	$params[':discount_id'] = $discount_id;
	
	$list = pdo_fetchall("SELECT a.*,b.discount FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " . tablename($this->table_discount_lesson) . " b ON a.id=b.lesson_id WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$list[$k]['discount_name'] = $v['discount']*0.1.'折';
		$list[$k]['discount_price'] = round($v['price']*$v['discount']*0.01, 2);
	}
}

if($_W['isajax']){
	echo json_encode($list);
	exit();
}

include $this->template("../mobile/{$template}/discount");

?>