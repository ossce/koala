<?php


if (checksubmit('submitOrder')) { /* 排序 */
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $k => $v) {
			$data = array('displayorder' => intval($v));
			pdo_update($this->table_mcoupon, $data, array('id' => $k));
		}
	}
	message('操作成功!', referer, 'success');
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid ORDER BY status DESC,displayorder DESC, id DESC LIMIT ".($pindex - 1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid));
foreach($list as $k=>$v){
	if($v['use_type']==1){
		$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
		$v['category_name'] = $category['name'] ? "".$category['name']."分类课程" : "全部分类课程";
	}elseif($v['use_type']==2){
		$v['category_name'] = "指定部分课程";
	}
	unset($category);

	$list[$k] = $v;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
$pager = pagination($total, $pindex, $psize);