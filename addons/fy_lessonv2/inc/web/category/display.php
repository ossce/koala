<?php

/* VIP等级列表 */
$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort DESC,id ASC", array(':uniacid'=>$uniacid));

if (checksubmit('submit')) { /* 排序 */
	if (is_array($_GPC['category'])) {
		foreach ($_GPC['category'] as $pid => $val) {
			$data = array('displayorder' => intval($_GPC['category'][$pid]));
			pdo_update($this->table_category, $data, array('id' => $pid));
		}
	}
	if (is_array($_GPC['son'])) {
		foreach ($_GPC['son'] as $sid => $val) {
			$data = array('displayorder' => intval($_GPC['son'][$sid]));
			pdo_update($this->table_category, $data, array('id' => $sid));
		}
	}
	message('操作成功!', referer, 'success');
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid AND parentid=:parentid ";
$params[':uniacid'] = $uniacid;
$params[':parentid'] = 0;

$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach($category as $k=>$v){
	$category[$k]['son'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>$v['id']));
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_category) . " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);

?>