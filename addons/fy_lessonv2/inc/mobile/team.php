<?php
/**
 * 我的团队
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();
$uid = $_W['member']['uid'];

$mid   = intval($_GPC['mid']);
$level = $_GPC['level']?intval($_GPC['level']):'1';
$font = json_decode($comsetting['font'], true);
$title = $font['my_team'] ? $font['my_team'] : "我的团队";

$pindex =max(1,$_GPC['page']);
$psize = 10;

$userid = $mid ? $mid : $uid;
$member = pdo_fetch("SELECT nickname FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$userid));

$teamlist = pdo_fetchall("SELECT a.uid,a.nopay_commission+a.pay_commission AS commission,a.addtime, b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.parentid=:parentid ORDER BY a.id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':parentid'=>$userid));
/* 一级会员人数 */
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.parentid=:parentid", array(':parentid'=>$userid));

foreach($teamlist as $k1=>$v1){
	$direct2 = pdo_fetchall("SELECT uid FROM " .tablename($this->table_member). " WHERE parentid=:parentid", array(':parentid'=>$v1['uid']));
	/* 二级会员人数 */
	$direct2_num = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE parentid=:parentid", array(':parentid'=>$v1['uid']));
	
	$teamlist[$k1]['recnum']  = $direct2_num;
	$teamlist[$k1]['addtime'] = date('Y-m-d', $v1['addtime']);
	
	if(empty($v1['avatar'])){
		$teamlist[$k1]['avatar'] = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v1['avatar'], "http://") || strstr($v1['avatar'], "https://");
		$teamlist[$k1]['avatar'] = $inc ? $v1['avatar'] : $_W['attachurl'].$v1['avatar'];
	}
}


$sontitle = $level==1?"我的团队成员({$total})":"[".$member['nickname']."]的团队成员({$total})";

if(!$_W['isajax']){
	include $this->template("../mobile/{$template}/team");
}else{
	echo json_encode($teamlist);
}


?>