<?php

$userStatusList = $typeStatus->userStatus();

$member_files = $this->disposeMemberFields();

$uid = intval($_GPC['uid']);
$member = pdo_fetch("SELECT a.uid,a.parentid,a.nopay_commission,a.pay_commission,a.agent_level,a.payment_amount,a.payment_order,a.status,a.blacklist,a.remark,a.addtime, b.mobile,b.nickname,b.realname,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.education,b.position,b.address,b.avatar,c.openid FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_fans). " c ON a.uid=c.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

if(empty($member)){
	message("该会员不存在");
}

if(empty($member['avatar'])){
	$member['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$member['avatar'] = (strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://")) ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}

/* 已开通VIP等级列表 */
$viplist = pdo_fetchall("SELECT a.id,a.validity,b.level_name FROM " .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_vip_level). " b ON a.level_id=b.id WHERE a.uniacid=:uniacid AND a.uid=:uid ORDER BY b.id ASC", array(':uniacid'=>$uniacid,':uid'=>$uid));

if(checksubmit('submit')){
	$member_data = array(
		'nickname'		 => trim($_GPC['nickname']),
		'realname'		 => trim($_GPC['realname']),
		'mobile'		 => trim($_GPC['mobile']),
		'msn'			 => trim($_GPC['msn']),
		'idcard'		 => trim($_GPC['idcard']),
		'occupation'	 => trim($_GPC['occupation']),
		'company'		 => trim($_GPC['company']),
		'graduateschool' => trim($_GPC['graduateschool']),
		'grade'			 => trim($_GPC['grade']),
		'address'		 => trim($_GPC['address']),
		'education'		 => trim($_GPC['education']),
		'position'		 => trim($_GPC['position']),
	);
	
	$checkmobile = pdo_fetch("SELECT mobile FROM " .tablename($this->table_mc_members). " WHERE uniacid=:uniacid AND mobile=:mobile LIMIT 1", array(':uniacid'=>$uniacid, ':mobile'=>$member_data['mobile']));
	
	if(!empty($member_data['mobile'])){
		if(!(preg_match("/^1\d{10}$/", $member_data['mobile']))){
			message("手机号码格式错误", "", "error");
		}
		if(!empty($checkmobile) && $member['mobile'] != $member_data['mobile']){
			message("手机号码已存在", "", "error");
		}
	}
	pdo_update($this->table_mc_members, $member_data, array('uniacid'=>$uniacid,'uid'=>$uid));
	cache_build_memberinfo($uid);

	$fymember = array();
	$parentid	 = intval($_GPC['parentid']);
	$status		 = intval($_GPC['status']);
	if($parentid == $uid){
		message("上级会员不能为自己", "", "error");
	}
	if($parentid != $member['parentid']){
		if($parentid==0){
			$fymember['parentid']=0;
		}else{
			$new_member = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uid=:uid", array(':uid'=>$parentid));
			if(empty($new_member)){
				message("该上级会员不存在");
			}

			$fymember['parentid'] = $parentid;
		}
	}
	$fymember['status'] = $status;
	$fymember['blacklist'] = intval($_GPC['blacklist']);
	$fymember['agent_level'] = intval($_GPC['agent_level']);
	$fymember['remark'] = trim($_GPC['remark']);
	pdo_update($this->table_member, $fymember, array('uniacid'=>$uniacid,'uid'=>$uid));
	cache_build_memberinfo($uid);

	$validity = $_GPC['validity'];
	if(!empty($validity)){
		foreach($validity as $k=>$v){
			pdo_update($this->table_member_vip, array('validity'=>strtotime($v)), array('id'=>$k));
		}
	}


	$remark = "编辑uid:{$uid}的分销商资料";
	if($member['parentid'] != $parentid){
		$site_common->recordRelation($uid, $parentid, $_W['uid'], $_W['username']);
		$remark .= "原上级ID[".$member['parentid']."]，现上级ID[".$parentid."]；";
	}
	if($member['agent_level'] != $fymember['agent_level']){
		$remark .= "原分销等级:".$member['agent_level']."[".$site_common->getAgentLevelName($member['agent_level'])."]，现分销等级:".$fymember['agent_level']."[".$site_common->getAgentLevelName($fymember['agent_level'])."]；";
	}
	if($member['status'] != $status){
		$remark .= "原分销状态[".$member['status']."]，现分销状态[".$status."]；";
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销商管理", $remark);
	message("操作成功", $refurl, "success");
}