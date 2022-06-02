<?php

$discount_id = intval($_GPC['discount_id']);
if($discount_id){
	$discount = pdo_get($this->table_discount, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));
	if(empty($discount)){
		message('该限时折扣活动不存在');
	}
}

$starttime = $discount['starttime'] ? date('Y-m-d H:i:s', $discount['starttime']) : date('Y-m-d H:i:s', strtotime('tomorrow'));
$endtime = $discount['endtime'] ? date('Y-m-d H:i:s', $discount['endtime']) : date('Y-m-d H:i:s', strtotime('+5 days 23:59:59'));

if(checksubmit('submit')){
	$data = array(
		'uniacid'	  => $uniacid,
		'title'		  => trim($_GPC['title']),
		'member_discount' => intval($_GPC['member_discount']),
		'starttime'	  => strtotime($_GPC['time']['start']),
		'endtime'	  => strtotime($_GPC['time']['end']),
		'update_time' => time(),
	);

	if(empty($data['title'])){
		message("请输入活动名称", "", "error");
	}

	pdo_begin();
	try{
		if($discount_id){
			pdo_update($this->table_discount, $data, array('uniacid'=>$uniacid, 'discount_id'=>$discount_id));

			$lessonData = array(
				'member_discount' => $data['member_discount'],
				'starttime'		  => $data['starttime'],
				'endtime'		  => $data['endtime'],
			);
			pdo_update($this->table_discount_lesson, $lessonData, array('discount_id'=>$discount_id));
		}else{
			$data['addtime'] = time();
			pdo_insert($this->table_discount, $data);
		}
		pdo_commit();
		message("更新成功", $this->createWebUrl('market', array('op'=>'discount')), "success");

	}catch(Exception $e){
		pdo_rollback();
		message("更新失败，错误信息:".print_r($e, true), "", "error");
	}
}