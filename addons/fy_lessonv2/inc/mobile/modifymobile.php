<?php
/**
 * 修改手机号码
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
load()->model('mc');
load()->model('user');
$uid = $_W['member']['uid'];
$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid));

$smsConfig = json_decode($setting['sms'], true);
if($smsConfig['type']==1){
	$sms = $smsConfig['aliyun'];
}elseif($smsConfig['type']==2){
	$sms = $smsConfig['qcloud'];
}

if($op=='display'){
	$title = $member['mobile'] ? '修改手机号码':'绑定手机号码';
	$userAgent = $this->checkUserAgent();

	if(empty($sms['template_id'])){
		message("短信验证码未配置，请联系管理员", "", "warning");
	}

	/* 加密手机号码 */
	if($member['mobile']){
		$encrypt_mobile = substr_replace($member['mobile'], '******', 2, 6);
	}

	if($_W['isajax']){
		$old_mobile		= trim($_GPC['old_mobile']);
		$mobile			= trim($_GPC['mobile']);
		$verify_code	= trim($_GPC['verify_code']);
		$new_password	= $_GPC['new_password'];
		$new_password2	= $_GPC['new_password2'];


		$data = array();
		if(!empty($mobile)){
			if($member['mobile'] && !$old_mobile){
				$json_data = array(
					'code' => -1,
					'errmsg' => '请输入原手机号码',
				);
				$this->resultJson($json_data);
			}
			if($member['mobile'] && $old_mobile != $member['mobile']){
				$json_data = array(
					'code' => -1,
					'errmsg' => '原手机号码输入有误',
				);
				$this->resultJson($json_data);
			}

			if(!(preg_match("/1\d{10}/", $mobile))){
				$json_data = array(
					'code' => -1,
					'errmsg' => '新手机号码格式有误',
				);
				$this->resultJson($json_data);
			}
			$exist = pdo_fetch("SELECT uid FROM " .tablename($this->table_mc_members). " WHERE uniacid=:uniacid AND mobile=:mobile", array(':uniacid'=>$uniacid,':mobile'=>$mobile));
			if(!empty($exist)){
				$json_data = array(
					'code' => -1,
					'errmsg' => '新手机号码已存在，请更换新手机号码',
				);
				$this->resultJson($json_data);
			}

			if(empty($verify_code)){
				$json_data = array(
					'code' => -1,
					'errmsg' => '请输入的短信验证码',
				);
				$this->resultJson($json_data);
			}
			if($verify_code != $_SESSION['mobile_code']){
				$json_data = array(
					'code' => -1,
					'errmsg' => '短信验证码错误',
				);
				$this->resultJson($json_data);
			}
			if($mobile != $_SESSION['mobile_record']){
				$json_data = array(
					'code' => -1,
					'errmsg' => '验证码已过期，请重新获取',
				);
				$this->resultJson($json_data);
			}

			$data['mobile'] = trim($_GPC['mobile']);
		}

		if(!empty($new_password)){
			if(strlen($new_password) < 6){
				$json_data = array(
					'code' => -1,
					'errmsg' => '密码长度不能小于6位',
				);
				$this->resultJson($json_data);
			}
			if(strlen($new_password) > 20){
				$json_data = array(
					'code' => -1,
					'errmsg' => '密码长度不能大于20位',
				);
				$this->resultJson($json_data);
			}
			if($new_password != $new_password2){
				$json_data = array(
					'code' => -1,
					'errmsg' => '两次密码不一致',
				);
				$this->resultJson($json_data);
			}

			$data['password'] = md5($new_password . $member['salt'] . $_W['config']['setting']['authkey']);
		}

		$result = pdo_update($this->table_mc_members, $data, array('uniacid'=>$uniacid,'uid'=>$uid));
		if($result){
			cache_build_memberinfo($uid);
			/* 销毁短信验证码 */
			unset($_SESSION['mobile_record']);
			unset($_SESSION['mobile_code']);

			$json_data = array(
				'code' => 0,
				'errmsg' => $member['mobile'] ? "修改成功" : '绑定成功',
			);
			$this->resultJson($json_data);
		}else{
			$json_data = array(
				'code' => -1,
				'errmsg' => $member['mobile'] ? "修改失败，请稍后重试" : '绑定失败，请稍后重试',
			);
			$this->resultJson($json_data);
		}
	}
}

include $this->template("../mobile/{$template}/modifymobile");