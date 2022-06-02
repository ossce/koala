<?php

$member = pdo_fetch("SELECT a.uid,a.nopay_commission+a.pay_commission AS commission,a.addtime, b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

if(empty($member['avatar'])){
	$member['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
	$member['avatar'] = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}

/* 一级会员人数 */
$teamlist = pdo_fetchall("SELECT a.*, b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.parentid=:parentid", array(':uniacid'=>$uniacid,':parentid'=>$uid));
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.parentid=:parentid", array(':uniacid'=>$uniacid,':parentid'=>$uid));


foreach($teamlist as $k1=>$v1){
	if(empty($v1['avatar'])){
		$teamlist[$k1]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v1['avatar'], "http://") || strstr($v1['avatar'], "https://");
		$teamlist[$k1]['avatar'] = $inc ? $v1['avatar'] : $_W['attachurl'].$v1['avatar'];
	}

	/* 二级会员人数 */
	$direct2_num = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE uniacid=:uniacid AND parentid=:parentid", array(':uniacid'=>$uniacid,':parentid'=>$v1['uid']));
	$teamlist[$k1]['recnum']  = $direct2_num;
}

?>