<?php
/**
 * 完善个人信息
 * ============================================================================
 * 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 * 
 * ============================================================================
 */

if(!$_W['isajax']){
	$this->resultJson("Illegal Request");
}

$smsConfig = json_decode($setting['sms'], true);
if($smsConfig['type']==1){
	$sms = $smsConfig['aliyun'];
}elseif($smsConfig['type']==2){
	$sms = $smsConfig['qcloud'];
}

load()->model('mc');
load()->model('user');
$uid = $_W['member']['uid'];
$member = pdo_get($this->table_mc_members, array('uid'=>$uid));

if(!$uid){
	$json_data = array(
		'code' => -1,
		'errmsg' => '您还没有登录，请先登录',
	);
	$this->resultJson($json_data);
}

if($op=='display'){
	$writemsg = $_GPC['writemsg'];
	$user_info = json_decode($setting['user_info'], true);

	$data = array();
	if(!empty($common_member_fields)){
		foreach($common_member_fields as $v){
			if(in_array($v['field_short'],$user_info)){
				$data[$v['field_short']] = trim($writemsg[$v['field_short']]);
				if(empty($data[$v['field_short']])){
					$json_data = array(
						'code' => -1,
						'errmsg' => '请填写您的'.$v['field_name'],
					);
					$this->resultJson($json_data);
					
				}
				if($v['field_short']=='mobile'){
					if(!(preg_match("/1\d{10}/",$data['mobile']))){
						$json_data = array(
							'code' => -1,
							'errmsg' => '您输入的手机号码格式有误',
						);
						$this->resultJson($json_data);
					}
					$exist = pdo_fetch("SELECT uid FROM " .tablename($this->table_mc_members). " WHERE uniacid=:uniacid AND mobile=:mobile", array(':uniacid'=>$uniacid,':mobile'=>$data['mobile']));
					if(!empty($exist) && $member['mobile']!=$data['mobile']){
						$json_data = array(
							'code' => -1,
							'errmsg' => '该手机号码已存在，请重新输入其他手机号码',
						);
						$this->resultJson($json_data);
					}

					if($sms['template_id']){
						if($data['mobile'] != $_SESSION['mobile_record']){
							$json_data = array(
								'code' => -1,
								'errmsg' => '验证码已过期，请重新获取',
							);
							$this->resultJson($json_data);
						}

						$mobile_code = trim($writemsg['verify_code']);
						if(empty($mobile_code)){
							$json_data = array(
								'code' => -1,
								'errmsg' => '请输入的短信验证码',
							);
							$this->resultJson($json_data);
						}
						if($mobile_code != $_SESSION['mobile_code']){
							$json_data = array(
								'code' => -1,
								'errmsg' => '短信验证码错误',
							);
							$this->resultJson($json_data);
						}
					}
				}
			}
		}
	}

	$result = pdo_update($this->table_mc_members, $data, array('uniacid'=>$uniacid,'uid'=>$uid));
	if($result){
		/* 销毁短信验证码 */
		unset($_SESSION['mobile_record']);
		unset($_SESSION['mobile_code']);
		$json_data = array(
			'code' => 0,
		);
		$this->resultJson($json_data);
	}else{
		$json_data = array(
			'code' => -1,
			'errmsg' => '保存失败，请稍后再试',
		);
		$this->resultJson($json_data);
	}

}

?>