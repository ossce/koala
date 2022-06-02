<?php
/**
 * 帐号安全
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$title = '帐号安全';

$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid, 'uid'=>$uid));

/* 加密手机号码 */
if($member['mobile']){
	$encrypt_mobile = substr_replace($member['mobile'], '******', 2, 6);
}

if($op=='resetPassword' && $_W['isajax']){
	$old_pwd  = $_GPC['old_pwd'];
	$new_pwd  = $_GPC['new_pwd'];
	$new_pwd2 = $_GPC['new_pwd2'];

	$old_login_pwd = md5($old_pwd . $member['salt'] . $_W['config']['setting']['authkey']);
	if($old_login_pwd != $member['password']){
		$jsonData = array(
			'code' => -1,
			'message' => '原登录密码错误，请重新输入',
		);
		$this->resultJson($jsonData);
	}
	if(strlen($new_pwd) < 6){
		$jsonData = array(
			'code' => -1,
			'message' => '新登录密码长度至少6位',
		);
		$this->resultJson($jsonData);
	}
	if($new_pwd != $new_pwd){
		$jsonData = array(
			'code' => -1,
			'message' => '两次密码不一致，请重新输入',
		);
		$this->resultJson($jsonData);
	}

	$new_login_pwd = md5($new_pwd . $member['salt'] . $_W['config']['setting']['authkey']);
	if(pdo_update($this->table_mc_members, array('password'=>$new_login_pwd), array('uniacid'=>$uniacid,'uid'=>$uid))){
		$jsonData = array(
			'code' => 0,
			'message' => '修改密码成功，请重新登录',
		);
		$this->resultJson($jsonData);
	}else{
		$jsonData = array(
			'code' => -1,
			'message' => '系统繁忙，请稍候重试',
		);
		$this->resultJson($jsonData);
	}

}elseif($op=='resetMobile' && $_W['isajax']){
	$old_mobile  = $_GPC['old_mobile'];
	$new_mobile  = $_GPC['new_mobile'];
	$mobile_code = $_GPC['verify_code'];

	if($old_mobile != $member['mobile']){
		$jsonData = array(
			'code' => -1,
			'message' => '原手机号码错误',
		);
	}
	if(!(preg_match("/1\d{10}/", $new_mobile))){
		$jsonData = array(
			'code' => -1,
			'message' => '您输入的手机号码格式有误',
		);
		$this->resultJson($jsonData);
	}

	$exist = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$new_mobile), array('uid'));				
	if($exist){
		$jsonData = array(
			'code' => -1,
			'message' => '该手机号码已存在，请重新输入其他手机号码',
		);
		$this->resultJson($jsonData);
	}

	if($new_mobile != $_SESSION['mobile_record']){
		$jsonData = array(
			'code' => -1,
			'message' => '验证码已过期，请重新获取',
		);
		$this->resultJson($jsonData);
	}
	if($mobile_code != $_SESSION['mobile_code']){
		$jsonData = array(
			'code' => -1,
			'message' => '短信验证码错误',
		);
		$this->resultJson($jsonData);
	}
	unset($_SESSION['mobile_record']);
	unset($_SESSION['mobile_code']);

	if(pdo_update($this->table_mc_members, array('mobile'=>$new_mobile), array('uniacid'=>$uniacid,'uid'=>$uid))){
		$jsonData = array(
			'code' => 0,
			'message' => '修改手机号码成功',
		);
		$this->resultJson($jsonData);
	}else{
		$jsonData = array(
			'code' => -1,
			'message' => '系统繁忙，请稍候重试',
		);
		$this->resultJson($jsonData);
	}

}


include $this->template("../webapp/{$template}/account");


?>