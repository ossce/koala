<?php

$tpl_code = $_GPC['tpl_code'];
$tplmessage = pdo_fetch("SELECT * FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

if($tpl_code == 'buysucc'){
	$tpl_name = '购买成功通知';
	$buysucc_format = json_decode($tplmessage['buysucc_format'], true);
}elseif($tpl_code == 'cnotice'){
	$tpl_name = '结算成功通知';
	$cnotice_format = json_decode($tplmessage['cnotice_format'], true);
}elseif($tpl_code == 'newjoin'){
	$tpl_name = '成员加入提醒';
	$newjoin_format = json_decode($tplmessage['newjoin_format'], true);
}elseif($tpl_code == 'neworder'){
	$tpl_name = '新订单通知(管理员)';
	$neworder_format = json_decode($tplmessage['neworder_format'], true);
}elseif($tpl_code == 'newcash'){
	$tpl_name = '提现申请通知(管理员)';
	$newcash_format = json_decode($tplmessage['newcash_format'], true);
}elseif($tpl_code == 'apply_teacher'){
	$tpl_name = '讲师申请通知(管理员)';
	$apply_teacher_format = json_decode($tplmessage['apply_teacher_format'], true);
}elseif($tpl_code == 'teacher_notice'){
	$tpl_name = '申请讲师结果通知';
	$teacher_notice_format = json_decode($tplmessage['teacher_notice_format'], true);
}elseif($tpl_code == 'deliver'){
	$tpl_name = '订单发货通知';
	$deliver_format = json_decode($tplmessage['deliver_format'], true);
}elseif($tpl_code == 'grade'){
	$tpl_name = '考试成绩通知';
	$grade_format = json_decode($tplmessage['grade_format'], true);
}

if(checksubmit('submit')){
	$data = array(
		'uniacid' => $uniacid,
		'update_time' => time(),
	);
	if($tpl_code == 'buysucc'){
		$data['buysucc_format'] = json_encode($_GPC['buysucc_format']);
	}elseif ($tpl_code == 'cnotice'){
		$data['cnotice_format'] = json_encode($_GPC['cnotice_format']);
	}elseif ($tpl_code == 'newjoin'){
		$data['newjoin_format'] = json_encode($_GPC['newjoin_format']);
	}elseif ($tpl_code == 'neworder'){
		$data['neworder_format'] = json_encode($_GPC['neworder_format']);
	}elseif ($tpl_code == 'newcash'){
		$data['newcash_format'] = json_encode($_GPC['newcash_format']);
	}elseif ($tpl_code == 'apply_teacher'){
		$data['apply_teacher_format'] = json_encode($_GPC['apply_teacher_format']);
	}elseif ($tpl_code == 'teacher_notice'){
		$data['teacher_notice_format'] = json_encode($_GPC['teacher_notice_format']);
	}elseif ($tpl_code == 'deliver'){
		$data['deliver_format'] = json_encode($_GPC['deliver_format']);
	}elseif ($tpl_code == 'grade'){
		$data['grade_format'] = json_encode($_GPC['grade_format']);
	}

	if (empty($tplmessage)) {
		$result = pdo_insert($this->table_tplmessage, $data);
	} else {
		$result = pdo_update($this->table_tplmessage, $data, array('uniacid' => $uniacid));
	}
	if($result){
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->模版消息", "编辑模版消息格式");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}