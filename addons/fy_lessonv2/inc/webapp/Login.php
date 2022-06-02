<?php
/**
 * 登录
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 背景图片 */
$login_register = json_decode($setting_pc['login_register'], true);

/* 手机端域名 */
$mobile_site_root = $setting_pc['mobile_site_root'] ? $setting_pc['mobile_site_root'] : $_W['siteroot'];

/* 忘记密码二维码链接 */
$reset_password_url = $mobile_site_root.'app/index.php?i='.$uniacid.'&c=entry&do=modifyMobile&m=fy_lessonv2&uid='.$rec_uid;

/* 微课堂扫码登录链接 */
$rec_uid = $_COOKIE['rec_uid'];
$random = md5($uniacid.$uid.random(16).time());
$mobile_url = $mobile_site_root.'app/index.php?i='.$uniacid.'&c=entry&do=pclogin&m=fy_lessonv2&uid='.$rec_uid.'&random='.$random;

if($op=='display'){
	/* 已登录状态 */
	if($_SESSION['fy_lessonv2_'.$uniacid.'_uid']){
		setcookie('fy_lessonv2_'.$uniacid.'_refurl', '', time()-3600);
		if($_GPC['refurl']){
			header('Location:'.$_GPC['refurl']);
		}else{
			header('Location:/'.$uniacid.'/index.html');
		}
	}

	if($_GPC['refurl']){
		setcookie('fy_lessonv2_'.$uniacid.'_refurl', $_GPC['refurl']);
	}

	if($_W['ispost'] && $_W['isajax']) {
		$mobile  = trim($_GPC['mobile']);
		$password = $_GPC['password'];
		$code = trim($_GPC['code']);
		if(empty($mobile)){
			$data = array(
				'code' => -1,
				'message' => '请输入手机号码',
			);
			$this->resultJson($data);
		}
		if(strlen($mobile) != 11){
			$data = array(
				'code' => -1,
				'message' => '手机号码格式错误',
			);
			$this->resultJson($data);
		}
		if(empty($password)){
			$data = array(
				'code' => -1,
				'message' => '请输入登录密码',
			);
			$this->resultJson($data);
		}
		if(empty($code)){
			$data = array(
				'code' => -1,
				'message' => '请输入验证码',
			);
			$this->resultJson($data);
		}

		if(!$this->codeVerify($code)){
			$data = array(
				'code' => -1,
				'message' => '验证码错误，请重新输入',
			);
			$this->resultJson($data);
		}

		$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$mobile));
		if(!$user){
			$data = array(
				'code' => -1,
				'message' => '该手机号码尚未注册',
			);
			$this->resultJson($data);
		}

		$hash = md5($password . $user['salt'] . $_W['config']['setting']['authkey']);
		if ($user['password'] != $hash) {
			$data = array(
				'code' => -1,
				'message' => '密码错误，请重新输入',
			);
			$this->resultJson($data);
		}

		$member = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$user['uid']));
		if(empty($member) && !empty($user)){
		    $rec_uid = 0;
		    if($comsetting['is_sale']){
		        if($_COOKIE['rec_uid']){
    		        $recmember = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$_COOKIE['rec_uid']));
    		        if($recmember['status']==1){
    		            $rec_uid = $recmember['uid'];
    		        }
    		    }
		    }
		    
		    $insertarr = array(
    			'uniacid'	=> $_W['uniacid'],
    			'uid'		=> $user['uid'],
    			'nickname'  => $user['nickname'],
    			'parentid'  => $rec_uid,
    			'status'	=> $comsetting['agent_status'],
    			'coupon_tip'=> 0,
    			'uptime'	=> 0,
    			'addtime'	=> time(),
    		);
    		pdo_insert($this->table_member, $insertarr);
    		setcookie("rec_uid");
		}

		/* 记录登录ip */
		$login_data = array(
			'uniacid'	  => $uniacid,
			'uid'		  => $user['uid'],
			'login_token' => 'display',
			'status'	  => 1,
			'login_ip'	  => $_W['clientip'],
			'login_time'  => time(),
			'addtime'	  => time(),
		);
		pdo_insert($this->table_login_pc, $login_data);

		/* 是否开通VIP */
		$memberVipCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity", array(':uniacid'=>$uniacid,':uid'=>$user['uid'],':validity'=>time()));

		/* 是否开通讲师服务 */
		$teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid,'uid'=>$user['uid'],'status'=>1));

		/* 设置登录SESSION */
		session_start();
		$_SESSION['fy_lessonv2_'.$uniacid.'_uid'] = $user['uid'];
		$_SESSION['fy_lessonv2_'.$uniacid.'_mobile'] = $user['mobile'];
		$_SESSION['fy_lessonv2_'.$uniacid.'_nickname'] = $user['nickname'];
		$_SESSION['fy_lessonv2_'.$uniacid.'_vip'] = $memberVipCount;
		$_SESSION['fy_lessonv2_'.$uniacid.'_teacher_id'] = $teacher['id'];

		if(empty($user['avatar'])){
			$avatar = MODULE_URL."static/webapp/default/images/default_avatar.jpg";
		}else{
			$inc = strstr($user['avatar'], "http://") || strstr($user['avatar'], "https://");
			$avatar = $inc ? $user['avatar'] : $_W['attachurl'].$user['avatar'];
		}
		$_SESSION['fy_lessonv2_'.$uniacid.'_avatar'] = $avatar;

		/* 如果用户为讲师身份，则自动登录讲师(不含跨域名) */
		$apply_teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid,'uid'=>$user['uid']));
		if(!empty($apply_teacher)){
			$_SESSION[$uniacid.'_teacher_id']	   = $apply_teacher['id'];
			$_SESSION[$uniacid.'_teacher_account'] = $apply_teacher['account'];
			$_SESSION[$uniacid.'_teacher_avatar']  = $avatar;
		}

		/* 同步登录手机端 */
		_mc_login($user);

		$data = array(
			'code' => 0,
			'message' => '登录成功',
			'refurl'  => '',
		);
		if($_COOKIE['fy_lessonv2_'.$uniacid.'_refurl']){
			$data['refurl']	= $_COOKIE['fy_lessonv2_'.$uniacid.'_refurl'];
		}
		$this->resultJson($data);
	}

}elseif($op=='checkLogin' && $_W['isajax']){
	$random = trim($_GPC['random']);
	$record = pdo_get($this->table_login_pc, array('uniacid'=>$uniacid, 'login_token'=>$random));

	if(!$record['uid'] || !$record['status']){
		$data = array(
			'code' => -1,
		);
		$this->resultJson($data);
	}

	$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$record['uid']));
	if(!$user){
		$data = array(
			'code' => -1,
			'message' => '用户状态异常，请稍候重试',
		);
		$this->resultJson($data);
	}

	/* 记录登录ip */
	pdo_update($this->table_login_pc, array('login_ip'=>$_W['clientip']), array('uniacid'=>$uniacid, 'id'=>$record['id']));

	/* 是否开通VIP */
	$memberVipCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity", array(':uniacid'=>$uniacid,':uid'=>$user['uid'],':validity'=>time()));

	/* 是否开通讲师服务 */
	$teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid,'uid'=>$user['uid'],'status'=>1));

	/* 设置登录SESSION */
	session_start();
	$_SESSION['fy_lessonv2_'.$uniacid.'_uid'] = $user['uid'];
	$_SESSION['fy_lessonv2_'.$uniacid.'_mobile'] = $user['mobile'];
	$_SESSION['fy_lessonv2_'.$uniacid.'_nickname'] = $user['nickname'];
	$_SESSION['fy_lessonv2_'.$uniacid.'_vip'] = $memberVipCount;
	$_SESSION['fy_lessonv2_'.$uniacid.'_teacher_id'] = $teacher['id'];

	if(empty($user['avatar'])){
		$avatar = MODULE_URL."static/webapp/default/images/default_avatar.jpg";
	}else{
		$inc = strstr($user['avatar'], "http://") || strstr($user['avatar'], "https://");
		$avatar = $inc ? $user['avatar'] : $_W['attachurl'].$user['avatar'];
	}
	$_SESSION['fy_lessonv2_'.$uniacid.'_avatar'] = $avatar;

	/* 如果用户为讲师身份，则自动登录讲师(不含跨域名) */
	$apply_teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid,'uid'=>$user['uid']));
	if(!empty($apply_teacher)){
		$_SESSION[$uniacid.'_teacher_id']	   = $apply_teacher['id'];
		$_SESSION[$uniacid.'_teacher_account'] = $apply_teacher['account'];
		$_SESSION[$uniacid.'_teacher_avatar']  = $avatar;
	}

	/* 同步登录手机端 */
	_mc_login($user);

	$refurl = $_COOKIE['fy_lessonv2_'.$uniacid.'_refurl'];
	$data = array(
		'code' => 0,
		'refurl'  => $refurl ? $refurl : $_W['siteroot']."{$uniacid}/index.html",
	);
	$this->resultJson($data);
}


include $this->template("../webapp/{$template}/login");


?>