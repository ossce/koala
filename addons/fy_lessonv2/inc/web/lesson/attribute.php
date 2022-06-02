<?php

if(checksubmit()){
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $k=>$v) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$k]));
			pdo_update($this->table_attribute, $data, array('id'=>$k));
		}
	}

	$common['lesson_attribute'] = $_GPC['lesson_attribute'];
	pdo_update($this->table_setting, array('common'=>json_encode($common)), array('uniacid'=>$uniacid));
	$this->updateCache('fy_lesson_'.$uniacid.'_setting');

	message('更新成功', $this->createWebUrl('lesson', array('op'=>'attribute')), 'success');
}

?>