<?php

$max_upload_size = intval(min(ini_get('upload_max_filesize'),ini_get('post_max_size')));

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error");
}

if (checksubmit('submit')) {
	if (is_array($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $key => $val) {
			$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
			pdo_update($this->table_document, $data, array('id' => $key));
		}
	}
	
	message('操作成功!', referer, 'success');
}

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = " uniacid=:uniacid AND lessonid=:lessonid ";
$params[':uniacid'] = $uniacid;
$params[':lessonid'] = $pid;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_document). " WHERE {$condition} ORDER BY displayorder DESC,id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_document). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);