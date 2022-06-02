<?php

$lessonids = trim($_GPC['lessonids']);
if(empty($lessonids)){
	$data = array(
		'code' => -1,
		'msg'  => '请选择课程',
	);
	$this->resultJson($data);
}
$ids = explode(',', $lessonids);

if($_GPC['type']=='online' || $_GPC['type']=='offline'){
	if($_GPC['type']=='online'){
		$status = 1;
		$msg = '批量上架';
	}elseif($_GPC['type']=='offline'){
		$status = 0;
		$msg = '批量下架';
	}

	foreach($ids as $item){
		pdo_update($this->table_lesson_parent, array('status'=>$status), array('uniacid'=>$uniacid,'id'=>$item));
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程管理", "{$msg}ID为：{$lessonids}的课程");

	$data = array(
		'code' => 0,
		'msg'  => $msg."成功",
	);
	$this->resultJson($data);

}elseif($_GPC['type']=='setVIP'){
	$vips = trim($_GPC['vips']);
	if(empty($vips)){
		$data = array(
			'code' => -1,
			'msg'  => '请选择免费学习的VIP等级',
		);
		$this->resultJson($data);
	}

	$vipview = explode(',', $vips);
	foreach($ids as $item){
		pdo_update($this->table_lesson_parent, array('vipview'=>json_encode($vipview)), array('uniacid'=>$uniacid,'id'=>$item));
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程管理", "批量设置ID为：{$lessonids}的课程免费学习等级");

	$data = array(
		'code' => 0,
		'msg'  => '批量设置免费学习等级成功',
	);
	$this->resultJson($data);

}elseif($_GPC['type']=='dragPlay'){
	$drag_play = $_GPC['drag_play'];
	if(!in_array($drag_play, array('1','2','-1','-2'))){
		$data = array(
			'code' => -1,
			'msg'  => '批量设置项出错，请刷新页面重新操作',
		);
		$this->resultJson($data);
	}

	$updata['drag_play'] = $drag_play > 0 ? 1 : 0;
	$hand_type = $updata['drag_play'] ? '允许' : '禁止';

	/* 允许或禁止所选课程拖拽播放 */
	if($drag_play==1 || $drag_play==-1){
		foreach($ids as $item){
			pdo_update($this->table_lesson_parent, $updata, array('uniacid'=>$uniacid,'id'=>$item));
		}
		$hand_log = "批量设置ID为：{$lessonids}的课程{$hand_type}拖拽播放";
	}

	/* 允许或禁止所有课程拖拽播放 */
	if($drag_play==2 || $drag_play==-2){
		pdo_update($this->table_lesson_parent, $updata, array('uniacid'=>$uniacid));
		$hand_log = "批量设置所有课程{$hand_type}拖拽播放";
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 1, "课程管理", $hand_log);

	$data = array(
		'code' => 0,
		'msg'  => "批量{$hand_type}课程拖拽播放成功",
	);
	$this->resultJson($data);

}elseif($_GPC['type']=='delete'){
	
	foreach($ids as $item){
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'ctype' => 1, 'outid'=>$item));
		pdo_delete($this->table_lesson_title, array('uniacid'=>$uniacid, 'lesson_id'=>$item));
		pdo_delete($this->table_lesson_spec, array('uniacid'=>$uniacid, 'lessonid'=>$item));
		pdo_delete($this->table_lesson_son, array('uniacid'=>$uniacid, 'parentid'=>$item));
		pdo_delete($this->table_lesson_parent, array('uniacid'=>$uniacid, 'id'=>$item));
		pdo_delete($this->table_playrecord, array('uniacid'=>$uniacid,'lessonid'=>$item));
		pdo_delete($this->table_lesson_history, array('uniacid'=>$uniacid,'lessonid'=>$item));
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 1, "课程管理", "批量删除ID为：{$lessonids}的课程");

	$data = array(
		'code' => 0,
		'msg'  => '批量删除成功',
	);
	$this->resultJson($data);

}elseif($_GPC['type']=='category'){
	$pid = intval($_GPC['batch_pid']);
	$cid = intval($_GPC['batch_cid']);

	$p_category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$pid,'parentid'=>0));
	if(empty($p_category)){
		$data = array(
			'code' => -1,
			'msg'  => '一级分类选择有误，请刷新后重新设置',
		);
		$this->resultJson($data);
	}

	if($cid){
		$c_category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$cid,'parentid'=>$pid));
		if(empty($p_category)){
			$data = array(
				'code' => -1,
				'msg'  => '二级分类选择有误，请刷新后重新设置',
			);
			$this->resultJson($data);
		}
	}

	$update = array(
		'pid' => $pid,
		'cid' => $cid,
	);
	foreach($ids as $item){
		pdo_update($this->table_lesson_parent, $update, array('uniacid'=>$uniacid,'id'=>$item));
	}

	$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程管理", "批量设置ID为：{$lessonids}的课程分类为: {$p_category['name']}/{$c_category['name']}");

	$data = array(
		'code' => 0,
		'msg'  => '批量设置课程分类成功',
	);
	$this->resultJson($data);
}


exit();