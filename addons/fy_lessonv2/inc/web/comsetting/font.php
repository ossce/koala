<?php

$font = json_decode($comsetting['font'], true);
if(checksubmit()){
	$data = array(
		'uniacid'	=> $uniacid,
		'font'		=> json_encode($_GPC['font']),
		'addtime'	=> time(),
	);
	if (empty($glo_comsetting)) {
		$result = pdo_insert($this->table_commission_setting, $data);
	} else {
		$result = pdo_update($this->table_commission_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新分销表缓存 */
		$comsetting = $this->getComsetting();
		$this->updateCache('fy_lesson_'.$uniacid.'_commission_setting', $comsetting);

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销文字", "编辑分销文字");
		itoast('更新成功', '', 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}