<?php

$id = intval($_GPC['id']);
if($id>0){
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($level)){
		message("该分销商等级不存在或已被删除", "", "error");
	}
}

if(checksubmit('submit')){
	$data = array(
		'uniacid'	  => $uniacid,
		'levelname'   => trim($_GPC['levelname']),
		'commission1' => floatval($_GPC['commission1']),
		'commission2' => floatval($_GPC['commission2']),
		'commission3' => floatval($_GPC['commission3']),
		'updatemoney' => floatval($_GPC['updatemoney']),
	);
	if(empty($data['levelname'])){
		message("请输入等级名称");
	}

	if(empty($id)){
		pdo_insert($this->table_commission_level, $data);
		$id = pdo_insertid();
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "分销管理->分销商等级", "新增ID:{$id}的分销商等级");
		}
	}else{
		$res = pdo_update($this->table_commission_level, $data, array('uniacid'=>$uniacid, 'id'=>$id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销商等级", "编辑ID:{$id}的分销商等级");
		}
	}

	message("操作成功", $this->createWebUrl("commission", array('op'=>'level')), "success");
}

?>