<?php
/**
 * 分享课程赠送优惠券
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
$member = pdo_fetch("SELECT a.uid,b.openid,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_fans). " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$_W['member']['uid']));

if($_W['isajax'] && !empty($member)){
	$market = pdo_fetch("SELECT * FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
	$shareLesson = json_decode($market['share_lesson'], true);
	$remark = $receive_coupon_format['remark'] ? $receive_coupon_format['remark'] : "点击详情可查看您的帐号优惠券详情哦~";
	$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('coupon');

	if(!empty($shareLesson)){
		if($market['share_lesson_time']>0){
			$shareTotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uid=:uid AND source=:source", array(':uid'=>$member['uid'], 'source'=>4));
			if($shareTotal >= $market['share_lesson_time']) return;
		}

		$t = $coupon_amount = 0;
		foreach($shareLesson as $item){
			$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE id=:id", array(':id'=>$item));
			if(empty($coupon)) continue;
			$lessonCoupon = array(
				'uniacid'	  => $uniacid,
				'uid'		  => $member['uid'],
				'amount'      => $coupon['amount'],
				'conditions'  => $coupon['conditions'],
				'validity'	  => $coupon['validity_type']==1 ? $coupon['days1'] : time()+ $coupon['days2']*86400,
				'use_type'	  => $coupon['use_type'],
				'category_id' => $coupon['category_id'],
				'lesson_ids'  => $coupon['lesson_ids'],
				'status'	  => 0, /* 未使用 */
				'source'	  => 4, /* 分享课程赠送 */
				'coupon_id'	  => $coupon['id'],
				'addtime'	  => time(),
			);
			if(pdo_insert($this->table_member_coupon, $lessonCoupon)){
				$t++;
				$coupon_amount += $coupon['amount'];
			}
		}

		if($t){
			$tplmessage = pdo_fetch("SELECT receive_coupon,receive_coupon_format FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
			$receive_coupon_format = json_decode($tplmessage['receive_coupon_format'], true);

			if($tplmessage['receive_coupon']){
				$sendmessage = array(
					'touser' => $member['openid'],
					'template_id' => $tplmessage['receive_coupon'],
					'url' => $url,
					'topcolor' => "#7B68EE",
					'data' => array(
						'first' => array(
							'value' => "恭喜您成功分享课程，系统赠您{$t}张优惠券已发放到您的帐号，请注意查收。",
							'color' => "#2392EA",
							),
						'keyword1' => array(
							'value' => "价值".$coupon_amount."元的优惠券".$t." 张",
							'color' => "",
						),
						'keyword2' => array(
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
			}else{
				$custom = array(
					'msgtype' => 'text',
					'text' => array(
						'content' => urlencode('恭喜您成功分享课程，系统赠您'.$t.'张优惠券已发放到您的帐号，请注意查收。\n\n账户名：'.$member['nickname'].'\n数量：'.$t.'张\n时间：'.date('Y年m月d日', time()).'\n\n'.$receive_coupon_format['remark'].'<a href=\"'.$url.'\">点击此处可查看详情</a>')
					),
					'touser' => $member['openid'],
				);
				$account_api = WeAccount::create();
				$account_api->sendCustomNotice($custom);
			}
		}
	}
}


?>