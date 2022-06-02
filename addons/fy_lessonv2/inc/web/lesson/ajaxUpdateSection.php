<?php

$pid = trim($_GPC['pid']);
$type = trim($_GPC['type']);
$sectionids = trim($_GPC['sectionids']);
$ids = explode(",", $sectionids);

$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$pid));
if(empty($lesson)){
	$data = array(
		'code' => -1,
		'msg'  => '课程不存在或已被删除',
	);
	$this->resultJson($data);
}
if(empty($sectionids)){
	$data = array(
		'code' => -1,
		'msg'  => '未选中任何章节',
	);
	$this->resultJson($data);
}

if($type=='status'){
	//批量修改章节
	$is_free = $_GPC['is_free'];
	$test_time = $_GPC['test_time'];
	$password = $_GPC['password'];
	$status = trim($_GPC['status']);
	
	$update = array();
	if($is_free!=''){
		$update['is_free'] = $is_free;

		if($is_free==1){
			$update['test_time'] = intval($test_time);
		}
	}
	if($password!=''){
		$update['password'] = $password;
		if($password=='-1'){
			$update['password'] = '';
		}
	}
	if($status!=''){
		$update['status'] = $status;
	}

	if(empty($update)){
		$data = array(
			'code' => -1,
			'msg'  => '没有要批量操作的内容',
		);
		$this->resultJson($data);
	}

	foreach($ids as $id){
		if(empty($id)) continue;

		$section = pdo_get($this->table_lesson_son, array('parentid'=>$pid,'id'=>$id));
		if(empty($section)) continue;

		pdo_update($this->table_lesson_son, $update, array('parentid'=>$pid,'id'=>$id));
	}

	$data = array(
		'code' => 0,
		'msg'  => '批量操作成功',
	);
	$this->resultJson($data);

}elseif($type=='delete'){
	//批量删除章节
	$i = 0;
	$cid = "";
	foreach($ids as $id){
		if(empty($id)) continue;

		$section = pdo_get($this->table_lesson_son, array('parentid'=>$pid,'id'=>$id));
		if(empty($section)) continue;

		$res = pdo_delete($this->table_lesson_son, array('parentid'=>$pid,'id'=>$id));
		if($res){
			$i++;
			$cid .= $id.'，';
		}
	}

	if($i){
		$site_common->addSysLog($_W['uid'], $_W['username'], 2, "课程管理", "删除ID:{$pid}的课程下ID:{$cid}的章节");
	}

	$data = array(
		'code' => 0,
		'msg'  => '成功删除'.$i.'个章节',
	);
	$this->resultJson($data);
}