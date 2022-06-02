<?php

$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort DESC,id DESC", array(':uniacid'=>$uniacid));

if(checksubmit('submit')){
	$data = array(
		'uniacid'	=> $uniacid,
		'uid'		=> intval($_GPC['uid']),
		'level_id'	=> intval($_GPC['level_id']),
		'validity'	=> strtotime($_GPC['validity']),
	);

	if(empty($data['uid'])){
		message("请输入会员UID", "", "error");
	}
	$member = pdo_get($this->table_mc_members, array('uid'=>$data['uid']));
	if(empty($member)){
		message("没有找到指定会员", "", "error");
	}
	if(empty($data['level_id'])){
		message("请选择要开通的VIP等级", "", "error");
	}
	if($data['validity'] < time()){
		message("有效期不能小于当前时间", "", "error");
	}

	/*检查会员等级是否存在*/
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$data['level_id']));
	if(empty($level)){
		message("该会员等级不存在，请重新选择", "", "error");
	}
	$data['discount'] = $level['discount'];

	/*检查会员是否开通过该等级*/
	$member_vip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND level_id=:level_id", array(':uniacid'=>$uniacid, ':uid'=>$data['uid'], ':level_id'=>$data['level_id']));
	if(empty($member_vip)){
		$data['addtime'] = time();
		$res = pdo_insert($this->table_member_vip, $data);
	}else{
		$data['update_time'] = time();
		$res = pdo_update($this->table_member_vip, $data, array('id'=>$member_vip['id']));
	}

	if($res){
		/* 添加VIP服务订单 */
		$days = ceil(($data['validity']-time())/86400);
		$vipOrder = array(
			'acid' => $_W['acid'],
			'uniacid' => $uniacid,
			'ordersn' => 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
			'uid' => $data['uid'],
			'viptime' => $days,
			'vipmoney' => 0,
			'paytype' => 'admin',
			'status' => 1,
			'paytime' => time(),
			'addtime' => time(),
			'level_id' => $data['level_id'],
			'level_name' => $level['level_name'],
		);
		$res = pdo_insert($this->table_member_order, $vipOrder);

		if($res){
			pdo_update($this->table_member, array('vip'=>1), array('uniacid'=>$uniacid,'uid'=>$data['uid']));
			$fans = pdo_get($this->table_fans, array('uid'=>$data['uid']), array('openid'));
			$tplmessage = pdo_fetch("SELECT buysucc,buysucc_format FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

			$buysucc_format = json_decode($tplmessage['buysucc_format'], true);
			$first = $buysucc_format['first1'] ? $buysucc_format['first1'] : "您已购买成功!";
			$orderContent = "购买[{$level['level_name']}]服务-{$days}天";
			$remark = "有效期至：" . date('Y年m月d日', $data['validity']);
			if(!empty($buysucc_format['remark1'])){
				$remark .= "\n".$buysucc_format['remark1'];
			}

			/* 发送模版消息 */
			$sendmessage = array(
				'touser'      => $fans['openid'],
				'template_id' => $tplmessage['buysucc'],
				'url'         => $_W['siteroot'] ."app/index.php?i={$uniacid}&c=entry&do=vip&m=fy_lessonv2",
				'topcolor'    => "",
				'data'        => array(
					 'first' => array(
						'value' => $first,
						'color' => "",
					 ),
					 'keyword1' => array(
						'value' => $orderContent,
						'color' => "",
					 ),
					 'keyword2' => array(
						'value' => "0元(后台开通)",
						'color' => "",
					 ),
					 'keyword3' => array(
						'value' => date('Y年m月d日', time()),
						'color' => "",
					 ),
					 'remark' => array(
						'value' => $remark,
						'color' => "",
					 ),
			 
				  )
			);
			$this->send_template_message($sendmessage);
		}

		/* 更新会员vip字段 */
		$this->updateMemberVip($data['uid'], 1);

		/* 写入系统日志 */
		$site_common->addSysLog($_W['uid'], $_W['username'], 1, "VIP订单->创建VIP服务", "给[uid:".$data['uid']."]的会员开通[id:".$level['id']." - ".$level['level_name']."]的VIP等级，有效期至:".$_GPC['validity']);
		message("创建会员VIP成功", $this->createWebUrl('viporder', array('op'=>'createOrder')), "success");
	}else{
		message("创建会员VIP失败，请稍候重试", "", "error");
	}

}