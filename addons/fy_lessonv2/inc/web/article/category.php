<?php

if (checksubmit('submit')) {
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $k=>$v) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$k]));
			pdo_update($this->table_article_category, $data, array('id' => $k));
		}
		message("批量排序成功", $this->createWebUrl('article', array('op'=>'category')), "success");
	}
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_article_category) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_article_category) . " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);

?>