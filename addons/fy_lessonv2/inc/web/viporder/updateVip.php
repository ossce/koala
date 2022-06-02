<?php

$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	
if(checksubmit('submit')){
	$level_id = intval($_GPC['level_id']);
	$level = $site_common->getLevelById($level_id);
	if(empty($level)){
		message("指定VIP等级不存在", "", "error");
	}
	
	$member_list = pdo_fetchall("SELECT uid,vip,validity FROM " .tablename($this->table_member). " WHERE uniacid=:uniacid AND vip=:vip AND validity>:validity", array(':uniacid'=>$uniacid,':vip'=>1, ':validity'=>time()));
	$t = 0;
	foreach($member_list as $member){
		$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND  level_id=:level_id", array(':uid'=>$member['uid'],':level_id'=>$level_id));
		if(empty($memberVip)){
			$data = array(
				'uniacid' => $uniacid,
				'uid'	  => $member['uid'],
				'level_id'=> $level_id,
				'validity'=> $member['validity'],
				'discount'=> $level['discount'],
				'addtime' => time(),
			);
			if(pdo_insert($this->table_member_vip, $data)){
				$t++;
			}
		}
	}
	
	
	$lesson_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND vipview=:vipview", array(':uniacid'=>$uniacid,':vipview'=>1));
	$s=0;
	foreach($lesson_list as $v){
		$lessonData = array(
			'vipview'=>json_encode(array("{$level_id}"))
		);
		if(pdo_update($this->table_lesson_parent, $lessonData, array('id'=>$v['id']))){
			$s++;
		}
	}
	
	message("成功同步{$t}位用户VIP信息，{$s}个课程", "", "success");
}