<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_inform). " WHERE uniacid=:uniacid ORDER BY inform_id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid));
foreach($list as $k=>$v){
	$list[$k]['remain_number'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_inform_fans). " WHERE inform_id=:inform_id", array(':inform_id'=>$v['inform_id']));
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_inform). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
$pager = pagination($total, $pindex, $psize);