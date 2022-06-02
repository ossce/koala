<?php

if (checksubmit('submit')) { /* 排序 */
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $k => $v) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$k]));
			pdo_update($this->table_article, $data, array('id'=>$k));
		}
	}
	itoast('批量排序成功', "", 'success');
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
if (!empty($_GPC['keyword'])) {
	$condition .= " AND (title LIKE :keyword OR author LIKE :keyword )";
	$params[':keyword'] = "%".$_GPC['keyword']."%";
}
if ($_GPC['cate_id']) {
	$condition .= " AND cate_id=:cate_id ";
	$params[':cate_id'] = $_GPC['cate_id'];
}
if ($_GPC['attribute']) {
	if($_GPC['attribute']=='commend'){
		$condition .= " AND commend=:commend ";
		$params[':commend'] = 1;
	}
	if($_GPC['attribute']=='is_vip'){
		$condition .= " AND is_vip=:is_vip ";
		$params[':is_vip'] = 1;
	}
}
if ($_GPC['isshow']!='') {
	$condition .= " AND isshow=:isshow ";
	$params[':isshow'] = $_GPC['isshow'];
}

if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_article). " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_article). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);