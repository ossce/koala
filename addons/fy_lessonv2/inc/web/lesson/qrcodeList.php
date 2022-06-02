<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 10;


$list = pdo_getall($this->table_poster, array('uniacid'=>$uniacid,'poster_type'=>2));

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_poster) . " WHERE uniacid=:uniacid AND poster_type=:poster_type ", array(':uniacid'=>$uniacid,':poster_type'=>1));
$pager = pagination($total, $pindex, $psize);

