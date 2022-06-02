<?php

$id = intval($_GPC['id']);
$member_buyteacher = pdo_get($this->table_member_buyteacher, array('uniacid'=>$uniacid, 'id'=>$id));

$member_buyteacher = pdo_fetch("SELECT a.id,a.uid,a.validity,b.nickname,b.realname,c.teacher FROM " .tablename($this->table_member_buyteacher). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_teacher). " c ON a.teacherid=c.id WHERE a.uniacid=:uniacid AND a.id=:id LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));


if(!$member_buyteacher){
	message('该条记录不存在', '', 'error');
}

if(checksubmit('submit')){
	if(!$_GPC['validity']){
		message('请选择有效期', '', 'error');
	}

	$data = array(
		'validity'	  => strtotime($_GPC['validity']),
		'update_time' => time(),
	);
	
	if(pdo_update($this->table_member_buyteacher, $data, array('id'=>$id))){
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理->购买讲师会员", "修改编号:[{$member_buyteacher['id']}]记录的有效期");
		message("修改成功", $this->createWebUrl('teacher', array('op'=>'teacherMember')), "success");
	}else{
		message('更新失败，请稍候重试', '', 'error');
	}




	exit();
}

