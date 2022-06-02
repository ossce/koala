<?php
/**
 * 讲师帐号管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$this->checkTeacherStatus($self_item);

$title = $teacher_page['account'] ? $teacher_page['account'] : '讲师帐号管理';

$teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid, 'uid'=>$uid));

$mobile_site_root = $setting_pc['mobile_site_root'] ? $setting_pc['mobile_site_root'] : $_W['siteroot'];
$wap_account_url = $mobile_site_root."app/index.php?i={$uniacid}&c=entry&op=account&do=teachercenter&m=fy_lessonv2";

if($op=='setAccountInfo' && $_W['isajax']){
	$account = trim($_GPC['account']);
	$password = $_GPC['password'];
	$password2 = $_GPC['password2'];

	if($teacher['account']){
		$data = array(
			'code' => -1,
			'message' => '您已设置过帐号，请勿重复设置',
		);
		$this->resultJson($data);
	}

	if(strlen($account)<6 || strlen($account)>16){
		$data = array(
			'code' => -1,
			'message' => '登陆账号长度必须介于6~16位',
		);
		$this->resultJson($data);
	}
	if(strlen($password)<6 || strlen($password)>16){
		$data = array(
			'code' => -1,
			'message' => '登陆密码长度必须介于6~16位',
		);
		$this->resultJson($data);
	}
	if($password != $password2){
		$data = array(
			'code' => -1,
			'message' => '两次密码不一致，请重新输入',
		);
		$this->resultJson($data);
	}

	$isExist = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND account=:account LIMIT 1", array(':uniacid'=>$uniacid, ':account'=>$account));
	if($isExist && $account!=$teacher['account']){
		$data = array(
			'code' => -1,
			'message' => '该登录帐号已被占用，请重新输入登录帐号',
		);
		$this->resultJson($data);
	}

	$update = array('password'=>md5($password.$_W['config']['setting']['authkey']));
	if(empty($teacher['account'])){
		$update['account'] = $account;
	}
	$update['update_time'] = time();

	$res = pdo_update($this->table_teacher, $update, array('uniacid'=>$uniacid,'uid'=>$uid));
	if($res){
		$data = array(
			'code' => 0,
			'message' => '设置成功',
		);
		$this->resultJson($data);
	}else{
		$data = array(
			'code' => -1,
			'message' => '保存失败，请稍候重试',
		);
		$this->resultJson($data);
	}
}


include $this->template("../webapp/{$template}/teacherAccount");


?>