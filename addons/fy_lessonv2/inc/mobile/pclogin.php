<?php
/**
 * PC端登录
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();
$uid = $_W['member']['uid'];

if($op=='display'){
	$random = trim($_GPC['random']);

	if(!$random){
		header("Location:".$this->createMobileUrl('pclogin', array('op'=>'return','return_type'=>'warn','message'=>'二维码已过期，请刷新页面重新扫码')));
		exit();
	}

	$record = pdo_get($this->table_login_pc, array('uniacid'=>$uniacid, 'login_token'=>$random));
	if(empty($record)){
		$login_data = array(
			'uniacid' => $uniacid,
			'login_token' => $random,
			'status' => 0,
			'addtime' => time()
		);
		pdo_insert($this->table_login_pc, $login_data);
		$record['id'] = pdo_insertid();
	}else{
		if($record['status'] != 0){
			header("Location:".$this->createMobileUrl('pclogin', array('op'=>'return','return_type'=>'warn','message'=>'二维码已过期，请刷新页面重新扫码')));
			exit();
		}
	}

	if(checksubmit()){
		$result = pdo_update($this->table_login_pc, array('uid'=>$uid,'status'=>1,'login_time'=>time()), array('id'=>$record['id']));
		if($result){
			header("Location:".$this->createMobileUrl('pclogin', array('op'=>'return','return_type'=>'success','message'=>'授权登陆成功')));
			exit();
		}else{
			header("Location:".$this->createMobileUrl('pclogin', array('op'=>'return','return_type'=>'warn','message'=>'系统繁忙，请稍候重新扫码登录')));
			exit();
		}
	}

}


include $this->template("../mobile/{$template}/pclogin");

?>