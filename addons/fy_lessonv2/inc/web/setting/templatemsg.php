<?php

$tplmessage = pdo_fetch("SELECT * FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
if (checksubmit('submit')) {
	$data = array(
		'uniacid'			=> $uniacid,
		'buysucc'			=> trim($_GPC['buysucc']),
		'cnotice'			=> trim($_GPC['cnotice']),
		'newjoin'			=> trim($_GPC['newjoin']),
		'newlesson'			=> trim($_GPC['newlesson']),
		'neworder'			=> trim($_GPC['neworder']),
		'newcash'			=> trim($_GPC['newcash']),
		'apply_teacher'		=> trim($_GPC['apply_teacher']),
		'teacher_notice'	=> trim($_GPC['teacher_notice']),
		'recommend_junior'	=> trim($_GPC['recommend_junior']),
		'deliver'			=> trim($_GPC['deliver']),
		'grade'				=> trim($_GPC['grade']),
		'addtime'			=> time(),
		'update_time'		=> time(),
	);

	if (empty($tplmessage)) {
		$result = pdo_insert($this->table_tplmessage, $data);
	} else {
		$result = pdo_update($this->table_tplmessage, $data, array('uniacid' => $uniacid));
	}

	if($result){
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->模版消息", "编辑模版消息");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试', "", 'error');
	}
}