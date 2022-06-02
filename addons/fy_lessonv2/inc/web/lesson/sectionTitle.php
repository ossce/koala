<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error");
}

if (checksubmit('submit')) {
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $key => $val) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
			pdo_update($this->table_lesson_title, $data, array('title_id' => $key));
		}
	}
	
	message('操作成功!', referer, 'success');
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " uniacid=:uniacid AND lesson_id=:lessonid ";
$params[':uniacid'] = $uniacid;
$params[':lessonid'] = $pid;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_title). " WHERE {$condition} ORDER BY displayorder DESC,title_id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_title). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);