<?php

$id = intval($_GPC['id']); /* 当前板块id */

if (!empty($id)) {
	$recommend = pdo_fetch("SELECT * FROM " . tablename($this->table_recommend) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($recommend)){
		message("该板块不存在或已被删除！", "", "error");
	}
}

if (checksubmit('submit')) {
	if (empty($_GPC['rec_name'])) {
		message("抱歉，请输入板块名称！");
	}

	$data = array(
		'uniacid'	      => $_W['uniacid'],
		'rec_name'		  => trim($_GPC['rec_name']),
		'icon'			  => trim($_GPC['icon']),
		'show_style'	  => intval($_GPC['show_style']),
		'displayorder'	  => intval($_GPC['displayorder']),
		'limit_number'	  => intval($_GPC['limit_number']),
		'limit_number_pc' => intval($_GPC['limit_number_pc']),
		'is_show'		  => intval($_GPC['is_show']),
		'addtime'		  => time(),
	);

	if (!empty($id)) {
		unset($data['addtime']);
		$res = pdo_update($this->table_recommend, $data, array('id' => $id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "推荐板块", "新增ID:{$id}的课程推荐板块");
		}
	} else {
		pdo_insert($this->table_recommend, $data);
		$id = pdo_insertid();
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "推荐板块", "编辑ID:{$id}的课程推荐板块");
		}
	}
	message("更新板块成功！", $this->createWebUrl('recommend', array('op' => 'display')), "success");
}

?>