<?php
/**
 * VIP服务
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];
$site_common->check_black_list('visit', $uid);

$member = pdo_fetch("SELECT a.*,b.credit1,b.credit2,b.nickname,b.realname,b.mobile,b.msn,b.idcard,b.occupation,b.company,b.graduateschool,b.grade,b.address,b.education,b.position FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uid=:uid LIMIT 1", array(':uid'=>$uid));

if($op=='display'){
	$title = $common['page_title']['vip'] ? $common['page_title']['vip'] : 'VIP服务';
	$comsetting['vipdesc'] = htmlspecialchars_decode($comsetting['vipdesc']);

	$vip_bg = cache_load('fy_lessonv2_'.$uniacid.'_vip_bg');
	if(!$vip_bg){
		$vip_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>8,'is_pc'=>0,'is_show'=>1), array('picture'));
		$vip_bg = $vip_bg_data ? $_W['attachurl'].$vip_bg_data['picture'] : MODULE_URL."static/mobile/{$template}/images/vip-default_bg.jpg?v=1";
		cache_write('fy_lessonv2_'.$uniacid.'_vip_bg', $vip_bg);
	}
	
	/*VIP等级列表*/
	$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND is_show=:is_show ORDER BY sort DESC", array(':uniacid'=>$uniacid,':is_show'=>1));
	foreach($level_list as $k=>$v){
		$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND level_id=:level_id AND validity>:validity", array(':uid'=>$uid,':level_id'=>$v['id'],':validity'=>time()));
		
		$v['market_price'] = 0;
		if(empty($memberVip)){
			/* 开通VIP */
			$v['renew'] = 0;
			if($v['open_discount']>0 && $v['open_discount']<100){
				$v['market_price'] = $v['level_price'];
				$v['level_price'] = round($v['level_price'] * $v['open_discount'] * 0.01, 2);
			}
		}else{
			/* 续费VIP */
			$v['renew'] = 1;
			if($v['renew_discount']>0 && $v['renew_discount']<100){
				$v['market_price'] = $v['level_price'];
				$v['level_price'] = round($v['level_price'] * $v['renew_discount'] * 0.01, 2);
			}
		}

		$level_list[$k] = $v;
	}
	
	/*我的VIP等级列表*/	
	$memberVip_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
	foreach($memberVip_list as $k=>$v){
		$memberVip_list[$k]['level'] = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$v['level_id']));
	}

	/* 随机获取客服列表 */
	if($_GPC['ispay']==1){
		$service = json_decode($setting['qun_service'], true);
		if(!empty($service)){
			$rand = rand(0, count($service)-1);
			$now_service = $service[$rand];
		}
	}

	/* 检查是否完善个人信息 */
	if($setting['mustinfo']){
		$smsConfig = json_decode($setting['sms'], true);
		if($smsConfig['type']==1){
			$sms = $smsConfig['aliyun'];
		}elseif($smsConfig['type']==2){
			$sms = $smsConfig['qcloud'];
		}
		$user_info = json_decode($setting['user_info'], true);

		if(!empty($common_member_fields)){
			foreach($common_member_fields as $v){
				if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
					 $writemsg = true;
				}
			}
		}
	}

	/* 存在vip等级且允许展示初始购买信息 */
	if($level_list && $common['virtual_buy']){
		$rand_member_list = $site_common->getRandMember();
		$rand_total = count($rand_member_list);

		if($rand_total >=20){
			$rand_arr = array_rand($rand_member_list, 20);
		}else{
			$rand_arr = array_rand($rand_member_list, $rand_total);
		}

		$virtual_buyinfo = array();
		foreach($rand_arr as $v){
			$vip_rand = array_rand($level_list, 1);
			$virtual_buyinfo[] = $rand_member_list[$v].'开通了'.$level_list[$vip_rand]['level_name'];
		}
	}

	/* 最近VIP推广佣金记录 */
	if($member['status']){
		/* 分销身份为VIP身份 */
		if($comsetting['sale_rank'] == 2){
			$member_vip = pdo_fetch("SELECT * FROM  " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 0,1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
			$commission_switch = $member_vip ? true : false;
		}else{
			$commission_switch = true;
		}
	}
	if($commission_switch && $common['lately_commission']){
		$commission_log = $this->readCommonCache('fy_lesson_'.$uniacid.'_vip_commission_log');
		if(empty($commission_log)){
			$commission_log = pdo_fetchall("SELECT a.change_num,b.nickname,b.realname FROM " .tablename($this->table_commission_log). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.change_num>:change_num AND a.source=:source ORDER BY a.id DESC LIMIT 0,40", array(':uniacid'=>$uniacid,':change_num'=>5,':source'=>2));
			
			foreach($commission_log as $k=>$v){
				$v['nickname'] = $v['nickname'] ? $v['nickname'] : $v['realname'];
				$v['nickname'] = $site_common->substrCut($v['nickname'], 1, 1);

				if($v['change_num']*100 == intval($v['change_num'])*100){
					$v['change_num'] = intval($v['change_num']);
				}elseif($v['change_num']*10 == round($v['change_num'],1)*10){
					$v['change_num'] = round($v['change_num'],1);
				}

				$commission_log[$k] = $v;
			}
			shuffle($commission_log);
			cache_write('fy_lesson_'.$uniacid.'_vip_commission_log', $commission_log);
		}
	}

	/* 订单总数 */
	$order_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member_order) . "  WHERE uid=:uid AND status=:status", array(':uid'=>$uid,':status'=>1));
	/* 服务卡数量统计 */
	$nouse_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use AND validity>:validity", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>0,':validity'=>time()));
	$used_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>1));
	$pass_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use AND validity<:validity", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>0,':validity'=>time()));
	$card_total = $nouse_total + $used_total + $pass_total;

}elseif($op=='buyvip'){
	/* 检查黑名单操作 */
	$site_common->check_black_list('order', $uid);

	$level_id = intval($_GPC['level_id']);
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$level_id));
	if(empty($level)){
		message("该VIP等级不存在", "", "warning");
	}

	if ($setting['mustinfo']) {
		$user_info = json_decode($setting['user_info']);

		if(!empty($common_member_fields)){
			foreach($common_member_fields as $v){
				if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
					 message("请先完善您的信息", "", "warning");
				}
			}
		}
	}

	/* 构造购买会员订单信息 */
	$orderdata = array(
		'acid'	    => $_W['account']['acid'],
		'uniacid'   => $uniacid,
		'ordersn'   => 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'	    => $uid,
		'level_id'  => $level_id,
		'level_name'=> $level['level_name'],
		'viptime'   => $level['level_validity'],
		'vipmoney'  => $level['level_price'],
		'integral'	=> $level['integral'],
		'addtime'   => time(),
	);

	/* 计算开通和续费会员的折扣价格 */
	$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND level_id=:level_id AND validity>:validity", array(':uid'=>$uid,':level_id'=>$level_id,':validity'=>time()));
	if(empty($memberVip) && $level['open_discount']>0 && $level['open_discount']<100){
		$orderdata['vipmoney'] = round($level['level_price'] * $level['open_discount'] * 0.01, 2);
		$orderdata['discount_money'] = $level['level_price'] - $orderdata['vipmoney'];
	}
	if(!empty($memberVip) && $level['renew_discount']>0 && $level['renew_discount']<100){
		$orderdata['vipmoney'] = round($level['level_price'] * $level['renew_discount'] * 0.01, 2);
		$orderdata['discount_money'] = $level['level_price'] - $orderdata['vipmoney'];
	}


	/* 检查当前分销功能是否开启 */
	if($comsetting['is_sale']==1 && $comsetting['vip_sale']==1){
		$orderdata['commission1'] = 0;
		$orderdata['commission2'] = 0;
		$orderdata['commission3'] = 0;

		if($comsetting['self_sale']==1){
			/* 开启分销内购，一级佣金为购买者本人 */
			$orderdata['member1'] = $uid;
			$orderdata['member2'] = $site_common->getParentid($uid);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}else{
			/* 关闭分销内购 */
			$orderdata['member1'] = $site_common->getParentid($uid);;
			$orderdata['member2'] = $site_common->getParentid($orderdata['member1']);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}

		$vipordercom = json_decode($level['commission'], true); /* VIP单独佣金比例 */
		$settingcom = unserialize($comsetting['commission']);	/* 全局佣金比例 */
		if($orderdata['member1']>0){
			$orderdata['commission1'] = $site_common->getAgentCommission1($vipordercom['commission_type'], $vipordercom['commission1'], $settingcom['commission1'], $orderdata['vipmoney'], $orderdata['member1']);
		}
		if($orderdata['member2']>0 && in_array($comsetting['level'], array('2','3'))){
			$orderdata['commission2'] = $site_common->getAgentCommission2($vipordercom['commission_type'], $vipordercom['commission2'], $settingcom['commission2'], $orderdata['vipmoney'], $orderdata['member2']);
		}
		if($orderdata['member3']>0 && $comsetting['level']==3){
			$orderdata['commission3'] = $site_common->getAgentCommission3($vipordercom['commission_type'], $vipordercom['commission3'], $settingcom['commission3'], $orderdata['vipmoney'], $orderdata['member3']);
		}
	}

	if($uid>0){
		$result = pdo_insert($this->table_member_order, $orderdata);
		$orderid = pdo_insertid();
	}
	
	if($result){
		header("Location:".$this->createMobileUrl('pay', array('orderid'=>$orderid, 'ordertype'=>'buyvip')));
	}else{
		message("写入订单信息失败，请重试", $this->createMobileUrl('vip'), "warning");
	}

}elseif($op=='ajaxgetList'){
	/* 购买会员VIP服务订单 */
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$memberorder_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_order). " WHERE uid=:uid AND status=:status ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uid'=>$uid,':status'=>1));
	foreach($memberorder_list as $key=>$value){
		$memberorder_list[$key]['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
		$memberorder_list[$key]['paytime'] = $value['paytime']>0?date('Y-m-d H:i:s', $value['paytime']):'';
		$memberorder_list[$key]['status']  = $value['status']==0?'未支付':'购买成功';
		if($value['paytype']=='credit'){
			$memberorder_list[$key]['paytype'] = '余额支付';
		}elseif($value['paytype']=='wechat'){
			$memberorder_list[$key]['paytype'] = '微信支付';
		}elseif($value['paytype']=='alipay'){
			$memberorder_list[$key]['paytype'] = '支付宝支付';
		}elseif($value['paytype']=='vipcard'){
			$memberorder_list[$key]['paytype'] = '服务卡支付';
		}elseif($value['paytype']=='admin'){
			$memberorder_list[$key]['paytype'] = '后台支付';
		}elseif($value['paytype']=='wxapp'){
			$memberorder_list[$key]['paytype'] = '微信小程序';
		}
		$level = pdo_fetch("SELECT level_name FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$value['level_id']));
		$memberorder_list[$key]['level_name'] = $level['level_name'] ? $level['level_name'] : "默认等级";
	}

	$this->resultJson($memberorder_list);

}elseif($op=='ajaxgetCard'){
	/* 用户的VIP服务卡 */
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND own_uid=:own_uid ";
	$params = array(
		':uniacid' => $uniacid,
		':own_uid' => $uid
	);

	if($_GPC['card_status']!=''){
		if($_GPC['card_status']==0){
			$condition .= " AND is_use=:is_use AND validity>:validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = time();
		}elseif($_GPC['card_status']==1){
			$condition .= " AND is_use=:is_use ";
			$params[':is_use'] = 1;
		}elseif($_GPC['card_status']==2){
			$condition .= " AND is_use=:is_use AND validity<:validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = time();
		}
	}

	$card_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition} ORDER BY is_use ASC,validity ASC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($card_list as $k=>$v){
		if($v['is_use']==0 && time()<$v['validity']){
			$v['is_use_status'] = "未使用";
			$v['status'] = 0;
		}elseif($v['is_use']==1){
			$v['is_use_status'] = "已使用";
			$v['status'] = 1;
		}elseif($v['is_use']==0 && time()>$v['validity']){
			$v['is_use_status'] = "已过期";
			$v['status'] = 2;
		}
		$v['validity_date'] = date('Y-m-d H:i', $v['validity']);
		
		if($v['use_time']){
			$v['use_time_date'] = date('Y-m-d H:i:s', $v['use_time']);
		}

		$level = pdo_get($this->table_vip_level, array('uniacid'=>$uniacid,'id'=>$v['level_id']), array('level_name'));
		$v['level_name'] = $level['level_name'];

		$card_list[$k] = $v;
	}

	/* 服务卡数量统计 */
	$nouse_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use AND validity>:validity", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>0,':validity'=>time()));
	$used_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>1));
	$pass_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_vipcard) . "  WHERE uniacid=:uniacid AND own_uid=:own_uid AND is_use=:is_use AND validity<:validity", array(':uniacid'=>$uniacid,':own_uid'=>$uid,':is_use'=>0,':validity'=>time()));

	$data = array(
		'nouse_total' => $nouse_total,
		'used_total'  => $used_total,
		'pass_total'  => $pass_total,
		'card_list'   => $card_list,	
	);
	$this->resultJson($data);

}elseif($op=='vipcard'){
	$title = $common['page_title']['vipcard'] ? $common['page_title']['vipcard'] : 'VIP卡密开通服务';

	if($_W['isajax']){
		$password = trim($_GPC['card_password']);
		$vipcard = pdo_fetch("SELECT * FROM " .tablename($this->table_vipcard). " WHERE uniacid=:uniacid AND password=:password AND is_use=0 AND validity>:time ", array(':uniacid'=>$uniacid, ':password'=>$password, ':time'=>time()));
		if(empty($vipcard)){
			$data = array(
				'code' => '-1',
				'msg'  => '该服务卡不存在或已被使用',
			);
			$this->resultJson($data);
		}
		if(!$vipcard['level_id']){
			$data = array(
				'code' => '-1',
				'msg'  => '该服务卡已暂停使用',
			);
			$this->resultJson($data);
		}
		
		$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND level_id=:level_id", array(':uid'=>$uid,':level_id'=>$vipcard['level_id']));
		$newLevel = $site_common->getLevelById($vipcard['level_id']);
		if(!empty($memberVip)){
			if(time()>=$memberVip['validity']){
				$vipData = array(
					'validity' => time()+$vipcard['viptime']*86400,
					'discount'=> $newLevel['discount'],
					'update_time' => time(),
				);
			}else{
				$vipData = array(
					'validity' => $memberVip['validity']+$vipcard['viptime']*86400,
					'discount'=> $newLevel['discount'],
					'update_time' => time(),
				);
			}
			$result = pdo_update($this->table_member_vip, $vipData, array('id'=>$memberVip['id']));
		}else{
			$vipData = array(
				'uniacid' => $uniacid,
				'uid'	  => $uid,
				'level_id'=> $vipcard['level_id'],
				'validity'=> time()+$vipcard['viptime']*86400,
				'discount'=> $newLevel['discount'],
				'addtime' => time(),
			);
			$result = pdo_insert($this->table_member_vip, $vipData);
		}
		
		if($result){
			/* 构造购买会员订单信息 */
			$orderdata = array(
				'acid'		=> $_W['account']['acid'],
				'uniacid'	=> $uniacid,
				'ordersn'	=> 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
				'uid'		=> $uid,
				'level_id'  => $newLevel['id'],
				'level_name'=> $newLevel['level_name'],
				'viptime'	=> $vipcard['viptime'],
				'vipmoney'	=> '0.00',
				'paytype'	=> 'vipcard',
				'status'	=> 1,
				'paytime'	=> time(),
				'refer_id'	=> $vipcard['id'],
				'addtime'	=> time(),
			);
			pdo_insert($this->table_member_order, $orderdata);

			$vipcardData = array(
				'is_use'	=> 1,
				'nickname'	=> $member['nickname'],
				'uid'		=> $uid,
				'ordersn'	=> $orderdata['ordersn'],
				'use_time'	=> $orderdata['addtime'],
			);
			pdo_update($this->table_vipcard, $vipcardData, array('uniacid'=>$uniacid,'id'=>$vipcard['id']));
			
			/* 更新会员vip字段 */
			$site_common->updateMemberVip($uid, 1);
			
			$fans = pdo_fetch("SELECT openid FROM " .tablename($this->table_fans). " WHERE uid=:uid", array(':uid'=>$uid));
			$tplmessage = pdo_fetch("SELECT buysucc,buysucc_format FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

			$buysucc_format = json_decode($tplmessage['buysucc_format'], true);
			$first = $buysucc_format['first1'] ? $buysucc_format['first1'] : "您已购买成功!";
			$orderContent = "购买[{$newLevel['level_name']}]服务-{$vipcard['viptime']}天";
			$remark = "有效期至：" . date('Y年m月d日', $vipData['validity']);
			if(!empty($buysucc_format['remark1'])){
				$remark .= "\n".$buysucc_format['remark1'];
			}

			/* 发送模版消息 */
			$sendmessage = array(
				'touser' => $fans['openid'],
				'template_id' => $tplmessage['buysucc'],
				'url' => $_W['siteroot'] ."app/index.php?i={$uniacid}&c=entry&do=vip&m=fy_lessonv2",
				'topcolor' => "",
				'data' => array(
					'first' => array(
						'value' => $first,
						'color' => "",
					),
					'keyword1' => array(
						'value' => $orderContent,
						'color' => "",
					),
					'keyword2' => array(
						'value' => "0元(服务卡消费)",
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

			$data = array(
				'code' => '0',
				'msg'  => "开通{$newLevel['level_name']}成功",
			);
			$this->resultJson($data);
		}else{
			$data = array(
				'code' => '0',
				'msg'  => "更新会员VIP状态失败，请稍候重试",
			);
			$this->resultJson($data);
		}
	}

}

include $this->template("../mobile/{$template}/vip");