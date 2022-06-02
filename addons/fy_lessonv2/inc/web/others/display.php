<?php

$suggest_category = $site_common->getSuggestCategory();
$suggest_cate = array();
foreach($suggest_category as $v){
	$suggest_cate[$v['id']] = $v['title'];
}

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if(trim($_GPC['keyword'])){
	$condition .= " AND (b.nickname LIKE :keyword1 OR b.realname LIKE :keyword1 OR b.mobile=:keyword2) ";
	$params[':keyword1'] = "%".trim($_GPC['keyword'])."%";
	$params[':keyword2'] = trim($_GPC['keyword']);
}
if($_GPC['category_id']){
	$condition .= " AND a.category_id=:category_id ";
	$params[':category_id'] = $_GPC['category_id'];
}
if($_GPC['status'] != ''){
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}

if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
}elseif(!$_GPC['search']){
	$starttime = strtotime('-1 month');
	$endtime = strtotime('today') + 86399;
}
if($starttime && $endtime){
	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_suggest). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_suggest). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);

?>