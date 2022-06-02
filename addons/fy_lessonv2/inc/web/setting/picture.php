<?php

if (checksubmit('submit')) {
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $k => $v) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$k]));
			pdo_update($this->table_banner, $data, array('banner_id' => $k));
		}
	}
	message('批量排序成功', $this->createWebUrl('setting', array('op'=>'picture')), 'success');
}


$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
$bannerType = $typeStatus->bannerType();

if($_GPC['banner_name']){
	$condition .= " AND banner_name LIKE :banner_name";
	$params[':banner_name'] = '%'.trim($_GPC['banner_name']).'%';
}
if($_GPC['banner_type']!=''){
	$condition .= " AND banner_type=:banner_type";
	$params[':banner_type'] = intval($_GPC['banner_type']);
}
if($_GPC['is_pc']!=''){
	$condition .= " AND is_pc=:is_pc";
	$params[':is_pc'] = intval($_GPC['is_pc']);
}
if($_GPC['is_show']!=''){
	$condition .= " AND is_show=:is_show";
	$params[':is_show'] = intval($_GPC['is_show']);
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE {$condition} ORDER BY displayorder desc,banner_id desc LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_banner). "  WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);