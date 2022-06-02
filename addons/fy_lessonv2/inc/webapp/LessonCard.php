<?php
/**
 * 卡密兑换课程
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;
$title = $common['self_page']['lessonCard'] ? $common['self_page']['lessonCard'] : '兑换课程';
$studen_no = $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';

if($op == 'exchange'){
	$password = trim($_GPC['password']);
	$use_uid  = $type == 1 ? $uid : intval($_GPC['use_uid']);

	if(!$password){
		$jsonData = array(
			'code' => -1,
			'msg'  => '请输入课程卡密',
		);
		$this->resultJson($jsonData);
	}

	$lessoncard = pdo_fetch("SELECT * FROM " .tablename($this->table_lessoncard). " WHERE uniacid=:uniacid AND password=:password AND is_use=:is_use AND validity>:validity", array(':uniacid'=>$uniacid,':password'=>$password,':is_use'=>0,':validity'=>time()));
	if(empty($lessoncard)){
		$jsonData = array(
			'code' => -1,
			'msg'  => '卡密不存在或已被使用',
		);
		$this->resultJson($jsonData);
	}

	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessoncard['lesson_id']));
	if(empty($lesson)){
		$jsonData = array(
			'code' => -1,
			'msg'  => '卡密对应的课程不存在',
		);
		$this->resultJson($jsonData);
	}

	if($type == 2){
		$open_type = intval($_GPC['open_type']);
		if($open_type == 1){
			/* 学号验证方式 */
			if(!$use_uid){
				$jsonData = array(
					'code' => -1,
					'msg'  => "请输入{$studen_no}(uid)",
				);
				$this->resultJson($jsonData);
			}
			$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$use_uid));
			if(empty($user)){
				$jsonData = array(
					'code' => -1,
					'msg'  => "该{$studen_no}(uid)不存在",
				);
				$this->resultJson($jsonData);
			}
		}elseif($open_type == 2){
			/* 手机号码验证方式 */
			if(!$use_uid){
				$jsonData = array(
					'code' => -1,
					'msg'  => "请输入手机号码",
				);
				$this->resultJson($jsonData);
			}
			$user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'mobile'=>$use_uid));
			if(empty($user)){
				$jsonData = array(
					'code' => -1,
					'msg'  => "该手机号码不存在",
				);
				$this->resultJson($jsonData);
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
				'url' => $setting_pc['mobile_site_root'] . "app/index.php?i={$uniacid}&c=entry&do=mylesson&status=payed&m=fy_lessonv2",
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
			$site_common->send_template_message($sendmessage);
		}
		/* 发送模版消息 End */


		if($type == 1){
			$refurl = "/{$uniacid}/orderDetails.html?orderid={$orderid}";
		}else{
			$refurl = "/{$uniacid}/lessoncard.html";
		}
		$jsonData = array(
			'refurl'	=> $refurl,	
			'code' => 0,
			'msg'  => "兑换成功",
		);
		$this->resultJson($jsonData);
	}else{
		$jsonData = array(
			'code' => -1,
			'msg'  => "写入订单失败，请稍后重试",
		);
		$this->resultJson($jsonData);
	}

}


include $this->template("../webapp/{$template}/lessonCard");


?>