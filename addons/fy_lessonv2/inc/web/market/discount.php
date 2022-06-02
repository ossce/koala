<?php

$condition = "uniacid = :uniacid";
$params[':uniacid'] = $uniacid;
if($_GPC['keyword']){
	$condition = "title LIKT :title";
	$params[':title'] = '%'.$_GPC['keyword'].'%';
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_discount). " WHERE {$condition} ORDER BY displayorder DESC LIMIT ".($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_discount). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);