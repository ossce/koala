<?php
/**
 * 申请入驻
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$title = $common['apply_teacher']['title'] ? $common['apply_teacher']['title'] : "申请讲师";

$apply_page = $common['apply_teacher'];

/* 短信配置信息 */
$smsConfig = json_decode($setting['sms'], true);
if($smsConfig['type']==1){
	$sms = $smsConfig['aliyun'];
}elseif($smsConfig['type']==2){
	$sms = $smsConfig['qcloud'];
}

if(!in_array('teachercenter', $self_item)){
    message("系统没有开启讲师入驻！", "", "warning");
}

/* 会员信息 */
$member = pdo_fetch("SELECT a.*,b.nickname AS mnickname FROM " . tablename($this->table_member) . " a LEFT JOIN " . tablename($this->table_mc_members) . " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

/* 讲师信息 */
$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teacher) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));
if($teacher['teacherphoto']){
	$teacher_phone = $_W['attachurl'].$teacher['teacherphoto'];
}else{
	$teacher_phone = MODULE_URL."static/webapp/{$template}/images/upload-image.png";
}
if($teacher['weixin_qrcode']){
	$weixin_qrcode = $_W['attachurl'].$teacher['weixin_qrcode'];
}else{
	$weixin_qrcode = MODULE_URL."static/webapp/{$template}/images/upload-image.png";
}

if($op=='display') {
    if($teacher['status'] == 1) {
        header("Location:".$_W['siteroot']."/{$uniacid}/self.html");
    }
	if($teacher['status'] == 2) {
        message("您的申请已提交，请耐心等待审核");
    }

} elseif ($op=='postTeacher' && $_W['isajax']) {
    $data = array();
    $data['teacher'] = trim($_GPC['teacher']);
	$data['idcard'] = trim($_GPC['idcard']);
	$data['mobile'] = trim($_GPC['mobile']);
	$data['weixin_qrcode'] = trim($_GPC['weixin_qrcode']);
	$data['teacherphoto'] = trim($_GPC['teacherphoto']);
    $data['qq'] = trim($_GPC['qq']);
    $data['qqgroup'] = trim($_GPC['qqgroup']);
    $data['teacherdes'] = trim($_GPC['teacherdes']);
    $data['status'] = 2;

    if (!$data['teacherphoto']) {
		$jsonData = array(
			'code' => -1,
			'message' => '请上传头像',
		);
		$this->resultJson($jsonData);
    }
	if (!$data['teacher']) {
		$jsonData = array(
			'code' => -1,
			'message' => '请填写名称',
		);
		$this->resultJson($jsonData);
    }
	if (!$data['mobile']) {
		$jsonData = array(
			'code' => -1,
			'message' => '请填写手机号码',
		);
		$this->resultJson($jsonData);
    }
	if(!(preg_match("/1\d{10}/",$data['mobile']))){
		$jsonData = array(
			'code' => -1,
			'message' => '您输入的手机号码格式有误',
		);
		$this->resultJson($jsonData);
	}
	if (!$data['teacherdes']) {
		$jsonData = array(
			'code' => -1,
			'message' => '请填写自我介绍',
		);
		$this->resultJson($jsonData);
    }
	if($sms['template_id']){
		$mobile_code = trim($_GPC['verify_code']);
		if($mobile_code != $_SESSION['mobile_code']){
			$jsonData = array(
				'code' => -1,
				'message' => '短信验证码错误',
			);
			$this->resultJson($jsonData);
		}
	}
	unset($_SESSION['mobile_code']);


	$tplmessage = pdo_get($this->table_tplmessage, array('uniacid'=>$uniacid), array('apply_teacher','apply_teacher_format'));
	$apply_teacher_format = json_decode($tplmessage['apply_teacher_format'], true);

    $manage = explode(",", $setting['manageopenid']);
	if (!$teacher) {
        $data['uid'] = $uid;
        $data['uniacid'] = $uniacid;
        $data['addtime'] = time();

        pdo_insert($this->table_teacher, $data);
		
        foreach ($manage as $manageopenid) {
            $sendneworder = array(
                'touser' => $manageopenid,
                'template_id' => $tplmessage['apply_teacher'],
                'url' => "",
                'topcolor' => "",
                'data' => array(
                    'first' => array(
                        'value' => $apply_teacher_format['first'] ? $apply_teacher_format['first'] : "您有一条新的讲师入驻申请，请及时审核",
                        'color' => "",
                    ),
                    'keyword1' => array(
                        'value' => trim($_GPC['teacher']),
                        'color' => "",
                    ),
                    'keyword2' => array(
                        'value' => $apply_teacher_format['keyword2'] ? $apply_teacher_format['keyword2'] : "讲师入驻申请",
                        'color' => "",
                    ),
                    'remark' => array(
                        'value' => $apply_teacher_format['remark'] ? $apply_teacher_format['remark'] : "详情请登录网站后台查看！",
                        'color' => "",
                    ),
                )
            );
            $this->send_template_message($sendneworder);
        }

		$jsonData = array(
			'code' => 0,
			'message' => '提交申请成功，请耐心等待审核',
		);
		$this->resultJson($jsonData);

	} else {
        pdo_update($this->table_teacher, $data, array('uid' => $uid));
        foreach ($manage as $manageopenid) {
            $sendneworder = array(
                'touser' => $manageopenid,
                'template_id' => $tplmessage['apply_teacher'],
                'url' => "",
                'topcolor' => "#7B68EE",
                'data' => array(
                    'first' => array(
                        'value' => $apply_teacher_format['first'] ? $apply_teacher_format['first'] : "您有一条新的讲师入驻申请，请及时审核",
                        'color' => "",
                    ),
                    'keyword1' => array(
                        'value' => trim($_GPC['teacher']),
                        'color' => "",
                    ),
                    'keyword2' => array(
                        'value' => $apply_teacher_format['keyword2'] ? $apply_teacher_format['keyword2'] : "讲师入驻申请",
                        'color' => "",
                    ),
                    'remark' => array(
                        'value' => $apply_teacher_format['remark'] ? $apply_teacher_format['remark'] : "详情请登录网站后台查看！",
                        'color' => "",
                    ),
                )
            );
            $this->send_template_message($sendneworder);
        }
		$jsonData = array(
			'code' => 0,
			'message' => '重新提交申请成功，请耐心等待审核',
		);
		$this->resultJson($jsonData);
    }
}

include $this->template("../webapp/{$template}/applyTeacher");


?>