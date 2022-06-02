<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid AND is_hot=:is_hot ";
$params[':uniacid'] = $uniacid;
$params[':is_hot'] = 1;

$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_category) . " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);


?>