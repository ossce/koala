·<?php

$uid = intval($_GPC['uid']);
$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid), array('nickname','realname','mobile'));

if(empty($member)){
	message("该会员不存在");
}

$condition = " uniacid=:uniacid AND uid=:uid";
$params = array(
	':uniacid'  => $uniacid,
	':uid'		=> $uid,
);

$list  = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_bind). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
$total = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_member_bind). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);