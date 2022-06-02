<?php
/**
 * 查找商品
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$goods_type = intval($_GPC['goods_type']);
$status = $_GPC['status'];
$keyword = trim($_GPC['keyword']);

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if($goods_type){
	$condition .= " AND goods_type=:goods_type ";
	$params[':goods_type'] = $goods_type;
}
if($status != ''){
	$condition .= " AND status=:status ";
	$params[':status'] = $status;
}
if(!empty($keyword)){
	$condition .= " AND title LIKE :title ";
	$params[':title'] = "%".$keyword."%";
}


$pindex = max(1, intval($_GPC['page']));
$psize = 8;

$list = pdo_fetchall("SELECT id,title,cover FROM " .tablename($this->table_shop_goods). " WHERE {$condition} LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_shop_goods). "  WHERE {$condition}", $params);
$total_page = ceil($total/$psize);


include $this->template('web/getGoods');

?>