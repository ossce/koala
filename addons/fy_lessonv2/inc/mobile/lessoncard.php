<?php
/**
 * 课程卡密兑换课程
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];
$studen_no = $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';

if($op == 'display'){
	$title = $common['self_page']['lessonCard'] ? $common['self_page']['lessonCard'] : '兑换课程';
	$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;

	if(checksubmit()){
		$password = trim($_GPC['password']);
		$use_uid  = $type == 1 ? $uid : intval($_GPC['use_uid']);

		if(!$password){
			message("请输入课程卡密", "", "warning");
		}
		
		$lessoncard = pdo_fetch("SELECT * FROM " .tablename($this->table_lessoncard). " WHERE uniacid=:uniacid AND password=:password AND is_use=:is_use AND validity>:validity", array(':uniacid'=>$uniacid,':password'=>$password,':is_use'=>0,':validity'=>time()));
		if(empty($lessoncard)){
			message("卡密不存在或已被使用", "", "warning");
		}

		$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessoncard['lesson_id']));
		if(empty($lesson)){
			message("卡密对应的课程不存在", "", "warning");
		}

		if($type == 2){
			$open_type = intval($_GPC['open_type']);
			if($open_type == 1){
				/* 学号验证方式 */
				if(!$use_uid){
					message("请输入{$studen_no}(uid)", "", "warning");
				}
				$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$use_uid));
				if(empty($user)){
					message("该{$studen_no}(uid)不存在", "", "warning");
				}
			}elseif($open_type == 2){
				/* 手机号码验证方式 */
				if(!$use_uid){
					message("请输入手机号码", "", "warning");
				}
				$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$use_uid));
				if(empty($user)){
					message("该手机号码不存在", "", "warning");
				}
				$use_uid = $user['uid'];
			}
		}
	
		$orderdata = array(
			'acid'			=> $uniacid,
			'uniacid'		=> $uniacid,
			'ordersn'		=> 'L' . date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
			'uid'			=> $use_uid,
			'spec_day'		=> $lessoncard['cardtime'],
			'lessonid'		=> $lesson['id'],
			'bookname'		=> $lesson['bookname'],
			'teacherid'		=> $lesson['teacherid'],
			'paytype'		=> 'vipcard',
			'paytime'		=> time(),
			'validity'		=> time() + 86400 * $lessoncard['cardtime'],
			'status'		=> 1,
			'addtime'		=> time(),
		);
		$res1 = pdo_insert($this->table_order, $orderdata);
		$orderid = pdo_insertid();
		if($res1){
			$updateCard = array(
				'is_use'   => 1,
				'ordersn'  => $orderdata['ordersn'],
				'use_uid'  => $use_uid,
				'use_time' => time(),
				'open_uid' => $uid,
			);
			pdo_update($this->table_lessoncard, $updateCard, array('uniacid'=>$uniacid,'id'=>$lessoncard['id']));

			/* 发送模版消息 Start */
			$fans = pdo_get($this->table_fans, array('uniacid'=>$uniacid,'uid'=>$use_uid), array('openid'));
			$tplmessage = pdo_get($this->table_tplmessage, array('uniacid'=>$uniacid), array('buysucc','buysucc_format'));
			if($fans['openid'] && $tplmessage['buysucc']){
				$buysucc_format = json_decode($tplmessage['buysucc_format'], true);
				$sendmessage = array(
					'touser' => $fans['openid'],
					'template_id' => $tplmessage['buysucc'],
					'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=mylesson&status=payed&m=fy_lessonv2",
					'topcolor' => "",
					'data' => array(
						'first' => array(
							'value' => $buysucc_format['first2'] ? $buysucc_format['first2'] : "您已购买成功。",
							'color' => "",
						),
						'keyword1' => array(
							'value' => "《{$orderdata['bookname']}》",
							'color' => "",
						),
						'keyword2' => array(
							'value' => "0元(卡密兑换)",
							'color' => "",
						),
						'keyword3' => array(
							'value' => date('Y年m月d日', time()),
							'color' => "",
						),
						'remark' => array(
							'value' => $buysucc_format['remark2'] ? $buysucc_format['remark2'] : '',
							'color' => "",
						),
					)
				);
				$this->send_template_message($sendmessage);
			}
			/* 发送模版消息 End */


			if($type == 1){
				$refurl = $this->createMobileUrl('orderdetail',array('orderid'=>$orderid));
			}else{
				$success_tips = "，赶紧通知好友吧";
				$refurl = $this->createMobileUrl('lessoncard', array('type'=>2));
			}
			message("兑换成功{$success_tips}", $refurl, "success");
		}else{
			message("写入订单失败，请稍后重试", "", "warning");
		}

	}
}

include $this -> template("../mobile/{$template}/lessonCard");