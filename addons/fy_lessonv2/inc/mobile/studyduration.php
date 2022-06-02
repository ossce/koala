<?php
/**
 * 学习时长兑换积分
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

$ucenter_bg = cache_load('fy_lessonv2_'.$uniacid.'_ucenter_bg');
if(!$ucenter_bg){
	$ucenter_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>7,'is_pc'=>0,'is_show'=>1), array('picture'));
	$ucenter_bg = $ucenter_bg_data ? $_W['attachurl'].$ucenter_bg_data['picture'] : MODULE_URL."static/mobile/{$template}/images/agency-top.jpg?v=2";
	cache_write('fy_lessonv2_'.$uniacid.'_ucenter_bg', $ucenter_bg);
}

//头像区域
$memberinfo = pdo_get($this->table_mc_members, array('uid'=>$uid), array('nickname','avatar'));
if(empty($memberinfo['avatar'])){
	$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($memberinfo['avatar'], "http://") || strstr($memberinfo['avatar'], "https://");
	$avatar = $inc ? $memberinfo['avatar'] : $_W['attachurl'].$memberinfo['avatar'];
}

//学习时长参数
$market = pdo_get($this->table_market, array('uniacid'=>$uniacid), array('study_duration'));
$duration_setting = json_decode($market['study_duration'], true);
$durationLog = pdo_get($this->table_study_duration, array('uid'=>$uid,'date'=>date('Ymd')));

if($op=='display'){
	$title = "学习时长";
	

	/* 每1分钟更新一次排名 */
	if(!empty($durationLog) && (time()>$durationLog['update_time']+60)){
		//时长高于其他学员
		$exceed_student = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_study_duration). " WHERE date=:date AND (article+audio+video)<:duration", array(':date'=>date('Ymd'),':duration'=>($durationLog['article']+$durationLog['audio']+$durationLog['video'])));
		//今日学习人数
		$total_student = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_study_duration). " WHERE date=:date", array(':date'=>date('Ymd')));

		$ranking = round($exceed_student/$total_student,2)*100;
		$ranking = $ranking==100 ? 99 : $ranking;
		//如果当天学习人数只有自己，则学习时长高于平台99%的人
		if($total_student==1){
			$ranking = 99;
		}

		$data = array(
			'ranking' => $ranking,
			'update_time' => time()
		);
		pdo_update($this->table_study_duration, $data, array('study_id'=>$durationLog['study_id']));
		$durationLog = pdo_get($this->table_study_duration, array('uid'=>$uid,'date'=>date('Ymd')));
	}

	//每天最多可兑换积分
	$max_exchange_credit1 = $duration_setting['exchange_credit1']*$duration_setting['max_exchange_minute'];
	//今天总共学习时长
	$total_duration = intval(($durationLog['article']+$durationLog['audio']+$durationLog['video'])/60);
	//今天已兑换积分
	$today_already_credit1 = $durationLog['exchange']*$duration_setting['exchange_credit1'];
	//今天可兑换积分
	$today_remain_credit1 = 0;
	if($durationLog['exchange']<=$duration_setting['max_exchange_minute']){
		if($total_duration<$duration_setting['max_exchange_minute']){
			$today_remain_credit1 = ($total_duration - $durationLog['exchange'])*$duration_setting['exchange_credit1'];
		}else{
			$today_remain_credit1 = ($duration_setting['max_exchange_minute'] - $durationLog['exchange'])*$duration_setting['exchange_credit1'];
		}
	}

	//累计学习时长
	$member = pdo_get($this->table_member, array('uid'=>$uid), array('video_duration','audio_duration','article_duration'));
	$member['video_duration']   = intval($member['video_duration']/60);
	$member['audio_duration']   = intval($member['audio_duration']/60);
	$member['article_duration'] = intval($member['article_duration']/60);

	//今日学习时长
	$today['video_duration']   = intval($durationLog['video']/60);
	$today['audio_duration']   = intval($durationLog['audio']/60);
	$today['article_duration'] = intval($durationLog['article']/60);

}elseif($op=='exchange'){
	
	if(!$duration_setting['switch']){
		message('系统未开启学习时长兑换功能', "", "warning");
	}
	if($duration_setting['exchange_credit1']<=0){
		message('单位兑换积分有误，请联系管理员', "", "warning");
	}
	if($duration_setting['max_exchange_minute']<=0){
		message('每天最多兑换积分有误，请联系管理员', "", "warning");
	}

	//今天总共学习时长
	$total_duration = intval(($durationLog['article']+$durationLog['audio']+$durationLog['video'])/60);
	//今日可兑换积分
	if($durationLog['exchange']<=$duration_setting['max_exchange_minute']){
		if($total_duration<$duration_setting['max_exchange_minute']){
			$exchange_minute = $total_duration - $durationLog['exchange'];
			$today_remain_credit1 = $exchange_minute*$duration_setting['exchange_credit1'];
		}else{
			$exchange_minute = $duration_setting['max_exchange_minute'] - $durationLog['exchange'];
			$today_remain_credit1 = $exchange_minute*$duration_setting['exchange_credit1'];
		}
		
	}

	if(!$today_remain_credit1){
		message('您的可兑换积分不足', "", "warning");
	}
	if($durationLog['exchange'] >= $duration_setting['max_exchange_minute']){
		message('您今天的兑换额度已经用完，明天再来吧', "", "warning");
	}

	load()->model('mc');
	$log = array(
		'0' => "",
		'1' => "用户学习时长兑换积分", /* 操作积分备注 */
		'2' => 'fy_lessonv2', /* 模块标识 */
		'3' => '', /* 店员uid */
		'4' => '', /* 门店id */
		'5' => '', /* 1(线上操作) 2(系统后台,公众号管理员和操作员) 3(店员) */
	);
	$res = mc_credit_update($uid, 'credit1', $today_remain_credit1, $log);

	if($res){
		$data = array();
		$data['exchange +='] = $exchange_minute;

		if(pdo_update($this->table_study_duration, $data, array('study_id'=>$durationLog['study_id']))){
			message('成功兑换'.$today_remain_credit1.'积分', $this->createMobileUrl('studyduration'), 'success');
		}else{
			message('扣除学习时长失败，请稍候重试', $this->createMobileUrl('studyduration'), 'warning');
		}
	}else{
		message('增加用户积分失败，请稍候重试，', $this->createMobileUrl('studyduration'), 'warning');
	}
}



include $this->template("../mobile/{$template}/studyDuration");
