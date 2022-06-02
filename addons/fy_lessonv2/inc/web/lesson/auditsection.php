<?php

if(!$_W['isajax']){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;

	$condition = "uniacid=:uniacid AND status=:status";
	$params[':uniacid'] = $uniacid;
	$params[':status']  = 2;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE {$condition} ORDER BY id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

}else{
	$sectionids = trim($_GPC['sectionids']);
	$ids = explode(",", $sectionids);

	foreach($ids as $id){
		if(empty($id)) continue;

		$section = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'id'=>$id,'status'=>2));
		if(empty($section)) continue;

		pdo_update($this->table_lesson_son, array('status'=>1), array('uniacid'=>$uniacid,'id'=>$id));
	}

	$data = array(
		'code' => 0,
		'msg'  => '批量审核成功',
	);
	$this->resultJson($data);
}