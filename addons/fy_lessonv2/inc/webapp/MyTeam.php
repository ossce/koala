<?php
/**
 * 我的团队
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$this->checkDistributorStatus($comsetting);

$title = $salefont['my_team'] ? $salefont['my_team'] : "我的团队";

$mid   = intval($_GPC['mid']);
$level = $_GPC['level'] ? intval($_GPC['level']) : '1';
$userid = $mid ? $mid : $uid;

$now_member = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$userid), array('parentid','nickname'));

if($level==1){
	$level_name = '一级成员';
	$next_level = 2;
	if($userid != $uid){
		message('非法参数', $_W['siteroot']."/{$uniacid}/myTeam.html", "error");
	}
}elseif($level==2){
	$level_name = '二级成员';
	$next_level = 3;

	if($now_member['parentid'] != $uid){
		message('非法参数', $_W['siteroot']."/{$uniacid}/myTeam.html", "error");
	}

}elseif($level==3){
	$level_name = '三级成员';

	$user2 = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$now_member['parentid']), array('parentid'));
	if($user2['parentid'] != $uid){
		message('非法参数', $_W['siteroot']."/{$uniacid}/myTeam.html", "error");
	}
}

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " a.uniacid=:uniacid AND a.parentid=:parentid ";
$params[':uniacid'] = $uniacid;
$params[':parentid'] = $userid;

$list = pdo_fetchall("SELECT a.uid,a.nopay_commission+a.pay_commission AS commission,a.addtime, b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.uid DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
foreach($list as $k=>$v){
	$v['recnum']  = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE parentid=:parentid", array(':parentid'=>$v['uid']));
	
	if(!$v['avatar']){
		$v['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
		$v['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
	}
	$list[$k] = $v;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
$pager = $webAppCommon->pagination($total, $pindex, $psize);



include $this->template("../webapp/{$template}/myTeam");


?>