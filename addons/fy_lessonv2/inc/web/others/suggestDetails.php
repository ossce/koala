<?php

$id = intval($_GPC['id']);
$suggest = pdo_get($this->table_suggest, array('uniacid'=>$uniacid,'id'=>$id));
if(empty($suggest)){
	message("该投诉记录不存在");
}

$picture = json_decode($suggest['picture'], true);

/* 投诉类型 */
$suggest_category = pdo_get($this->table_suggest_category, array('uniacid'=>$uniacid,'id'=>$suggest['category_id']));

/* 投诉人信息 */
$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$suggest['uid']), array('nickname','realname','mobile'));

if(checksubmit()){
	$data = array(
		'status' => intval($_GPC['status']),
		'remark' => trim($_GPC['remark']),
		'update_time' => time(),
	);

	pdo_update($this->table_suggest, $data, array('uniacid'=>$uniacid,'id'=>$id));
	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "其他管理->投诉建议", "修改投诉建议状态，ID: [{$id}]");
	
	$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('others');
	message("操作成功", $refurl, "success");
}

?>