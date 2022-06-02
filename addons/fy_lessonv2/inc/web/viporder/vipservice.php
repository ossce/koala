<?php

$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort DESC,id DESC", array(':uniacid'=>$uniacid));
foreach($level_list as $k=>$v){
	$level_list[$k]['com'] = json_decode($v['commission'], true);
}

if (checksubmit('submit')) {

	if (is_array($_GPC['sort'])) {
		foreach ($_GPC['sort'] as $k=>$v) {
			$data = array('sort' => intval($_GPC['sort'][$k]));
			pdo_update($this->table_vip_level, $data, array('id' => $k));
		}
	}

	$data = array(
		'uniacid'		=> $uniacid,
		'vipdesc'		=> $_GPC['vipdesc'],
		'vip_sale'		=> $_GPC['vip_sale'],
		'vipcard_show'	=> $_GPC['vipcard_show'],
		'vip_agreement' => $_GPC['vip_agreement'],
		'addtime'		=> time(),
	);

	if (empty($comsetting)) {
		$result = pdo_insert($this->table_commission_setting, $data);
	} else {
		$result = pdo_update($this->table_commission_setting, $data, array('uniacid' => $uniacid));
	}

	if($result){
		/* 更新分销表缓存 */
		$comsetting = $this->getComsetting();
		$this->updateCache('fy_lesson_'.$uniacid.'_commission_setting', $comsetting);

		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "VIP服务->VIP等级管理", "编辑VIP等级");
		message('更新成功', $this->createWebUrl('viporder', array('op' => 'vipservice')), 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}
}