<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = " uniacid=:uniacid AND is_pc=:is_pc ";
$params = array(
	':uniacid' => $uniacid,
	':is_pc'   => 1,
);

if(trim($_GPC['nav_name'])){
	$condition .= " AND nav_name LIKE :nav_name";
	$params[':nav_name'] = '%'.trim($_GPC['nav_name']).'%';
}
if(intval($_GPC['location'])){
	$condition .= " AND location = :location";
	$params[':location'] = intval($_GPC['location']);
}

$navigation = pdo_fetchall("SELECT * FROM " .tablename($this->table_navigation). " WHERE {$condition} ORDER BY displayorder DESC,nav_id ASC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_navigation). " WHERE {$condition}", $params);

$pager = pagination($total, $pindex, $psize);



