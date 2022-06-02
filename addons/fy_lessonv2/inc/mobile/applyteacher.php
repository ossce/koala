<?php

/**
 * 讲师中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

load()->model('app');
load()->func('tpl');

$self_item = $common['self_item'];	/* 个人中心菜单 */
$font = $common['apply_teacher'];	/* 申请讲师页面字体 */
/* 短信配置信息 */
$smsConfig = json_decode($setting['sms'], true);
if($smsConfig['type']==1){
	$sms = $smsConfig['aliyun'];
}elseif($smsConfig['type']==2){
	$sms = $smsConfig['qcloud'];
}

if(!in_array('teachercenter', $self_item)){
    message("系统没有开启讲师入驻", "", "warning");
}

$title = $common['apply_teacher']['title'] ? $common['apply_teacher']['title'] : "申请讲师";
$uid = $_W['member']['uid'];

/* 会员信息 */
$lessonmember = pdo_fetch("SELECT a.*,b.nickname AS mnickname FROM " . tablename($this->table_member) . " a LEFT JOIN " . tablename($this->table_mc_members) . " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$uid));

/* 讲师信息 */
$teacherlog = pdo_fetch("SELECT * FROM " . tablename($this->table_teacher) . " WHERE uid=:uid", array(':uid'=>$uid));

if ($op == 'display') {
    if ($teacherlog['status'] == 1 || $teacherlog['status'] == 2) {
        header("Location:" . $this->createMobileUrl('teachercenter'));
    }

}elseif($op == 'postteacher') {
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

    if (empty($data['teacher'])) {
        message("请填写讲师名称");
    }
	if (empty($data['mobile'])) {
        message("请填写手机号码");
    }
    if (empty($data['teacherdes'])) {
        message("请填写讲师介绍");
    }
	if($sms['template_id']){
		$mobile_code = trim($_GPC['verify_code']);
		if(empty($mobile_code)){
			message("请输入的短信验证码");
		}
		if($mobile_code != $_SESSION['mobile_code']){
			message("短信验证码错误");
		}
	}
	unset($_SESSION['mobile_code']);

	$tplmessage = pdo_fetch("SELECT apply_teacher,apply_teacher_format FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	$apply_teacher_format = json_decode($tplmessage['apply_teacher_format'], true);

    $manage = explode(",", $setting['manageopenid']);
	if (empty($teacherlog)) {
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
                        'value' => $apply_teacher_format['remark'] ? $apply_teacher_format['remark'] : "详情请登录网站后台查看。",
                        'color' => "",
                    ),
                )
            );
            $this->send_template_message($sendneworder);
        }
        message("提交申请成功，等待管理员审核", $this->createMobileUrl("index", array('t'=>1)), "success");
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
                        'value' => $apply_teacher_format['remark'] ? $apply_teacher_format['remark'] : "详情请登录网站后台查看。",
                        'color' => "",
                    ),
                )
            );
            $this->send_template_message($sendneworder);
        }
        message("重新提交申请成功", $this->createMobileUrl("index", array('t'=>1)), "success");
    }
}

include $this->template("../mobile/{$template}/applyteacher");