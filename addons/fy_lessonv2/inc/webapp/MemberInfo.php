<?php
/**
 * 个人信息
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$title = '完善个人信息';


/* 会员信息 */
$member = pdo_get($this->table_mc_members, array('uid'=>$uid));
if(!$member['avatar']){
	$avatar = MODULE_URL."static/webapp/{$template}/images/upload-image.png";
}else{
	$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
	$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}
/* 加密手机号码 */
if($member['mobile']){
	$encrypt_mobile = substr_replace($member['mobile'], '****', 3, 4);
}

/* 短信配置 */
$smsConfig = json_decode($setting['sms'], true);
if($smsConfig['type']==1){
	$sms = $smsConfig['aliyun'];
}elseif($smsConfig['type']==2){
	$sms = $smsConfig['qcloud'];
}
$user_info = json_decode($setting['user_info'], true);

if($op=='postMemberInfo') {
    $data = array(
		'avatar' => trim($_GPC['avatar']),
	);
	if(!empty($common_member_fields)){
		foreach($common_member_fields as $v){
			if(in_array($v['field_short'],$user_info)){
				if($v['field_short']=='mobile' && $member['mobile']) continue;

				$data[$v['field_short']] = trim($_GPC[$v['field_short']]);
				if(empty($data[$v['field_short']])){
					message("请填写您的".$v['field_name']);
				}
				if($v['field_short']=='mobile'){
					if(!(preg_match("/1\d{10}/",$data['mobile']))){
						message("您输入的手机号码格式有误");
					}
					$exist = pdo_fetch("SELECT uid FROM " .tablename($this->table_mc_members). " WHERE uniacid=:uniacid AND mobile=:mobile", array(':uniacid'=>$uniacid,':mobile'=>$data['mobile']));
					if(!empty($exist) && $member['mobile']!=$data['mobile']){
						message("该手机号码已存在，请重新输入其他手机号码");
					}

					if($sms['template_id']){
						if($data['mobile'] != $_SESSION['mobile_record']){
							message("提交的手机号码有误，请重新获取验证码");
						}

						$mobile_code = trim($_GPC['verify_code']);
						if(empty($mobile_code)){
							message("请输入的短信验证码");
						}
						if($mobile_code != $_SESSION['mobile_code']){
							message("短信验证码错误");
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

		if($_GPC['type']=='vip'){
			/* VIP购买 */
			message("完善信息成功", $_W['siteroot']."{$uniacid}/vip.html?op=buyvip&level_id={$_GPC['level_id']}", "success");
		}elseif($_GPC['type']=='buylesson'){
			/* 课程购买 */
			message("完善信息成功", $_W['siteroot']."{$uniacid}/confirm.html?id={$_GPC['lessonid']}&spec_id={$_GPC['spec_id']}", "success");
		}elseif($_GPC['type']=='lesson'){
			/* 课程学习 */
			message("完善信息成功", $_W['siteroot']."{$uniacid}/lesson.html?id={$_GPC['lessonid']}&sectionid={$_GPC['sectionid']}", "success");
		}elseif($_GPC['type']=='buyteacher'){
			/* 讲师购买 */
			message("完善信息成功", $_W['siteroot']."{$uniacid}/teacher.html?&op=buyTeacher&teacherid={$_GPC['teacherid']}", "success");
		}else{
			message("完善信息成功", $_W['siteroot']."{$uniacid}/memberInfo.html", "success");
		}

	}else{
		if($_GPC['type']=='vip'){
			message("网络错误，请稍后重试", $_W['siteroot']."{$uniacid}/vip.html", "error");
		}elseif($_GPC['type']=='confirm' || $_GPC['type']=='lesson'){
			message("网络错误，请稍后重试", $_W['siteroot']."{$uniacid}/lesson.html?id={$_GPC['lessonid']}", "error");
		}elseif($_GPC['type']=='buyteacher'){
			message("网络错误，请稍后重试", $_W['siteroot']."{$uniacid}/teacher.html?teacherid={$_GPC['teacherid']}", "error");
		}
	}

}elseif($op=='checkMobile' && $_W['isajax']) {
	$mobile = trim($_GPC['mobile']);
	$mobile_code = trim($_GPC['verify_code']);

	if(!(preg_match("/1\d{10}/",$mobile))){
		$data = array(
			'code' => -1,
			'message' => '您输入的手机号码格式有误',
		);
		$this->resultJson($data);
	}

	$exist = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$mobile), array('uid'));
	if($exist && $member['mobile']!=$mobile){
		$data = array(
			'code' => -1,
			'message' => '该手机号码已存在，请重新输入其他手机号码',
		);
		$this->resultJson($data);
	}

	if($sms['template_id']){
		if(!$mobile_code){
			$data = array(
				'code' => -1,
				'message' => '请输入的短信验证码',
			);
			$this->resultJson($data);
		}
		if($mobile_code != $_SESSION['mobile_code']){
			$data = array(
				'code' => -1,
				'message' => '短信验证码错误',
			);
			$this->resultJson($data);
		}
	}
	$data = array(
		'code' => 0,
		'message' => '手机号码验证码验证通过',
	);
	$this->resultJson($data);
}


include $this->template("../webapp/{$template}/memberInfo");


?>