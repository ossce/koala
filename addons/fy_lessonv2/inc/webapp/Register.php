<?php
/**
 * 注册
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


/* 注册登录页面信息 */
$login_register = json_decode($setting_pc['login_register'], true);

/* 已登录状态 */
if($_SESSION['fy_lessonv2_'.$uniacid.'_uid']){
	setcookie('fy_lessonv2_'.$uniacid.'_refurl', '', time()-3600);
	header('Location:/'.$uniacid.'/index.html');
}

if($_GPC['refurl']){
	setcookie('fy_lessonv2_'.$uniacid.'_refurl', $_GPC['refurl']);
}

/* 微课堂手机端链接 */
$rec_uid = $_COOKIE['rec_uid'];
$login_token = md5($uniacid.$uid.random(16).time());

$mobile_url = $_W['siteroot'].'app/index.php?i='.$uniacid.'&c=entry&do=pclogin&m=fy_lessonv2&uid='.$rec_uid.'&login_token='.$login_token;


include $this->template("../webapp/{$template}/register");


?>