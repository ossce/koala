<?php

if (checksubmit('submit')) { /* 排序 */
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $key => $val) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
			pdo_update($this->table_recommend, $data, array('id' => $key));
		}
	}
	message("操作成功!",$this->createWebUrl('recommend'),"success");
}

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
$recommend = pdo_fetchall("SELECT * FROM " . tablename($this->table_recommend) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_recommend) . " WHERE {$condition} ", $params);
$pager = pagination($total, $pindex, $psize);

?>