<?php

$pid = intval($_GPC['pid']);
$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$pid));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error");
}

if ($_GPC['displayorder']) { /* 排序 */
	if (is_array($_GPC['sectionorder'])) {
		foreach ($_GPC['sectionorder'] as $sid => $val) {
			$data = array('displayorder' => intval($_GPC['sectionorder'][$sid]));
			pdo_update($this->table_lesson_son, $data, array('id' => $sid));
		}
	}
	
	message('操作成功!', referer, 'success');
}

/* 课程目录列表 */
$title_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_title)." WHERE lesson_id=:lesson_id ORDER BY displayorder DESC,title_id ASC", array('lesson_id'=>$pid));
foreach($title_list as $k=>$v){
	$title_list[$k]['section'] = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE title_id=:title_id ORDER BY displayorder DESC,id ASC", array(':title_id'=>$v['title_id']));
}

$condition = " uniacid=:uniacid AND parentid=:parentid AND title_id=:title_id";
$params[':uniacid'] = $uniacid;
$params[':parentid'] = $pid;
$params[':title_id'] = 0;

$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE {$condition} ORDER BY displayorder DESC,id ASC", $params);