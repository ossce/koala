<?php

if(checksubmit()){
	$user_id = intval($_GPC['user_id']);
	$type = intval($_GPC['commission_type']);
	$amount = trim($_GPC['amount']);
	$remark = trim($_GPC['remark']);

	if(empty($user_id)){
		message("请选择用户");
	}
	$user = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$user_id));
	if(empty($user)){
		message("该会员不存在");
	}

	if(empty($type)){
		message("请选择佣金类型");
	}
	if(empty($amount)){
		message("请输入调整金额");
	}
	if(!is_numeric($amount)){
		message("调整金额必须是数字");
	}
	if(empty($remark)){
		message("请输入备注信息");
	}

	$data = array();
	if($type==1){
		$commission_txt = "分销商佣金";
		$data['nopay_commission'] = $user['nopay_commission'] + $amount;
	}
	if($type==2){
		$commission_txt = "课程佣金";
		$data['nopay_lesson'] = $user['nopay_lesson'] + $amount;
	}

	pdo_begin();
	try {
		pdo_update($this->table_member, $data, array('uid'=>$user_id));

		if($type==1){
			$log = array(
				'uniacid'	=> $uniacid,
				'orderid'	=> 0,
				'uid'		=> $user_id,
				'bookname'	=> '管理员:'.$_W['username'].' 后台操作:'.$amount,
				'change_num' => $amount,
				'grade'		=> -1,
				'remark'	=> $remark,
				'addtime'	=> time()
			);
			pdo_insert($this->table_commission_log, $log);

		}elseif($type==2){
			$teacher = pdo_get($this->table_teacher, array('uniacid'=>$uniacid,'uid'=>$user_id), array('teacher'));
			$log = array(
				'uniacid'	=> $uniacid,
				'uid'		=> $user_id,
				'teacher'	=> $teacher['teacher'],
				'ordersn'	=> '备注:'.$remark,
				'bookname'	=> '管理员:'.$_W['username'].' 后台操作:'.$amount,
				'orderprice' => $amount,
				'teacher_income' => '',
				'income_amount'	 => $amount,
				'addtime'	=> time()
			);
			pdo_insert($this->table_teacher_income, $log);
		}


		pdo_commit();

	} catch (Exception $e) {
		load()->func('logging');
		logging_run('管理员后台操作'.$commission_txt.'失败(uniacid:'.$uniacid.')，原因：'.$e->getMessage(), 'trace', 'fylessonv2_finance');
		pdo_rollback(); 
	}

	message("操作成功", $this->createWebUrl('finance', array('op'=>'handle')), "success");
}

?>