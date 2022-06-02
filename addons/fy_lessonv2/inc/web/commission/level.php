<?php

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid=:uniacid ORDER BY id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid));

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_commission_level) . " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
$pager = pagination($total, $pindex, $psize);

?>