<?php

$font = json_decode($comsetting['font'], true); //分销文字

$id = intval($_GPC['id']);
$cashlog = pdo_fetch("SELECT a.*,b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_cashlog). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
if(empty($cashlog)){
	message("该条提现申请不存在或已被删除！", "", "error");
}
if(empty($cashlog['avatar'])){
	$cashlog['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($cashlog['avatar'], "http://") || strstr($cashlog['avatar'], "https://");
	$cashlog['avatar'] = $inc ? $cashlog['avatar'] : $_W['attachurl'].$cashlog['avatar'];
}

/* 返回上一步 */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('finance', array('op'=>'commission'));

if(checksubmit('submit')){
	if($cashlog['status']!=0){
		message("该条提现申请已处理！", "", "error");
	}

	$status	   = intval($_GPC['status']);  /* 状态 0.待打款 1.已打款 -1.作废无效佣金 -2.驳回申请 */
	$remark	   = trim($_GPC['remark']);    /* 管理员文字备注 */
	$admin_img = trim($_GPC['admin_img']); /* 管理员图片备注 */

	$upcashlog = array();
	$upcashlog['remark'] = $remark;
	$upcashlog['admin_img'] = $admin_img;
	if($status == 1){
		if($cashlog['cash_way']==2){ //提现到微信钱包
			$desc1 = $_W['current_module']['title'] ? $_W['current_module']['title'] : '微课堂';
			if($cashlog['lesson_type']==1){
				$desc2 = $font['commission_cash_title'] ? $font['commission_cash_title'] : $desc1.'奖励提现';
			}elseif($cashlog['lesson_type']==2){
				
				$desc2 = $common['teacher_page']['cash_title'] ? $common['teacher_page']['cash_title'] : $desc1.'讲师收入提现';
			}
			
			$desc = $desc2.'(编号:'.$cashlog['id'].')';

			$post = array('total_amount'=>$cashlog['cash_num'], 'desc'=>$desc);
			$fans = array('openid'=>$cashlog['openid'], 'nickname'=>$cashlog['nickname']);
			$result = $this->companyPay($post,$fans);

			if($result['result_code']=='SUCCESS'){
				$upcashlog['status']           = 1;
				$upcashlog['disposetime']      = strtotime($result['payment_time']);
				$upcashlog['partner_trade_no'] = $result['partner_trade_no'];
				$upcashlog['payment_no']	   = $result['payment_no'];

				$res = pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));
				if($res){
					$site_common->addSysLog($_W['uid'], $_W['username'], 3, "财务管理->处理提现申请", "[处理成功]提现单号:{$id}的提现申请");
				}
				message("提现处理成功，佣金已发放到用户微信钱包！", $refurl, "success");

			}elseif($result['result_code']=='FAIL'){
				$site_common->addSysLog($_W['uid'], $_W['username'], 3, "财务管理->处理提现申请", "[处理失败]提现单号:{$id}的提现申请，原因:".$result['return_msg']);
				message($result['return_msg']."，微信接口返回信息：".$result['err_code_des'], "", "error");
			}
		}elseif($cashlog['cash_way']==3 || $cashlog['cash_way']==4 || $cashlog['cash_way']==5){ //提现到支付宝、银行卡、二维码收款
			if(!$remark && !$admin_img){
				message("请输入管理员备注", "", "warning");
			}
			$upcashlog['status']           = 1;
			$upcashlog['disposetime']      = time();
			pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));

			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "财务管理->处理提现申请", "处理提现单号:{$id}的提现申请状态为".$cashStatusList[$status]);
			message("提现处理成功", $refurl, "success");
		}
		
	}elseif($status=='-1' || $status=='-2'){
		if(!$remark && !$admin_img){
			message("请输入管理员备注", "", "warning");
		}

		$upcashlog['status']	  = $status;
		$upcashlog['disposetime'] = time();

		$res = pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));
		if($res){
			$lessonMember = pdo_get($this->table_member, array('uid'=>$cashlog['uid']));
			/* 分销佣金 */
			if($status=='-2' && $cashlog['lesson_type']==1){
				$commissionData = array(
					'nopay_commission' => $lessonMember['nopay_commission'] + $cashlog['cash_num'] + $cashlog['service_num'],
					'pay_commission'   => $lessonMember['pay_commission'] - $cashlog['cash_num'] - $cashlog['service_num']
				);
				pdo_update($this->table_member, $commissionData, array('uid'=>$cashlog['uid']));

				
				$commissionLog = array(
					'uniacid'	 => $uniacid,
					'orderid'	 => '',
					'uid'		 => $cashlog['uid'],
					'nickname'	 => $cashlog['nickname'],
					'bookname'	 => '分销佣金申请驳回:'.$cashlog['id'],
					'change_num' => $cashlog['cash_num'] + $cashlog['service_num'],
					'grade'		 => '-1',
					'remark'	 => '管理员驳回(提现编号'.$cashlog['id'].')：'.$remark,
					'addtime'	 => time(),
				);
				pdo_insert($this->table_commission_log, $commissionLog);
			}

			/* 课程佣金 */
			if($status=='-2' && $cashlog['lesson_type']==2){
				$incomeData = array(
					'nopay_lesson' => $lessonMember['nopay_lesson'] + $cashlog['cash_num'] + $cashlog['service_num'],
					'pay_lesson'   => $lessonMember['pay_lesson'] - $cashlog['cash_num'] - $cashlog['service_num']
				);
				pdo_update($this->table_member, $incomeData, array('uid'=>$cashlog['uid']));

				$teacher = pdo_get($this->table_teacher, array('uid'=>$cashlog['uid']), array('teacher'));			
				$teacherIncomeLog = array(
					'uniacid'		 => $uniacid,
					'ordersn'		 => 0,
					'uid'			 => $cashlog['uid'],
					'teacher'	     => $teacher['teacher'],
					'bookname'		 => '课程佣金申请驳回:'.$cashlog['id'],
					'orderprice'	 => $cashlog['cash_num'] + $cashlog['service_num'],
					'teacher_income' => 100,
					'income_amount'	 => $cashlog['cash_num'] + $cashlog['service_num'],
					'addtime'		 => time(),
				);
				pdo_insert($this->table_teacher_income, $teacherIncomeLog);
			}

			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "财务管理->处理提现申请", "处理提现单号:{$id}的提现申请为".$cashStatusList[$status]);
		}

		message("操作成功，提现申请状态已设置为：".$cashStatusList[$status], $refurl, "success");
	}
}

?>