<?php

$teacherStatusList = $typeStatus->teacherStatus();

$uid = intval($_GPC['uid']);
$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid), array('nickname','realname','avatar','mobile'));

if(empty($member)){
	message("记录不存在！");
}

if(empty($member['avatar'])){
	$member['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
	$member['avatar'] = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}

$list = pdo_fetchall("SELECT a.*, b.nickname,b.avatar FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.company_uid=:company_uid", array(':uniacid'=>$uniacid,':company_uid'=>$uid));
foreach($list as $k1=>$v1){
	if(empty($v1['avatar'])){
		$list[$k1]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v1['avatar'], "http://") || strstr($v1['avatar'], "https://");
		$list[$k1]['avatar'] = $inc ? $v1['avatar'] : $_W['attachurl'].$v1['avatar'];
	}
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.company_uid=:company_uid", array(':uniacid'=>$uniacid,':company_uid'=>$uid));