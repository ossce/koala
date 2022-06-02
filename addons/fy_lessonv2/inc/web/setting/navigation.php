<?php

if (checksubmit('submit')) { /* 排序 */
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $key => $val) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
			pdo_update($this->table_navigation, $data, array('nav_id'=>$key));
		}
	}
	message('操作成功!', referer, 'success');
}

$nav_ident = $typeStatus->navigationType();
$nav_location = $typeStatus->mobileNavLocation();

$condition = " uniacid=:uniacid AND is_pc=:is_pc ";
$params = array(
	':uniacid'	=> $uniacid,
	':is_pc'	=> 0,
);

$nav_name = trim($_GPC['nav_name']);
if($nav_name){
	$condition .= " AND nav_name LIKE :nav_name";
	$params[':nav_name'] = "%".$nav_name."%";
}

$location = intval($_GPC['location']);
if($location){
	$condition .= " AND location = :location";
	$params[':location'] = $location;
}

$navigation = pdo_fetchall("SELECT * FROM " .tablename($this->table_navigation). " WHERE {$condition} ORDER BY location DESC,displayorder DESC, nav_id DESC", $params);


