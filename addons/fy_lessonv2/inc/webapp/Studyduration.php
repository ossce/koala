<?php
/**
 * 学习时长
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


/* 学习时长参数 */
$market = pdo_get($this->table_market, array('uniacid'=>$uniacid), array('study_duration'));
$duration_setting = json_decode($market['study_duration'], true);
$durationLog = pdo_get($this->table_study_duration, array('uid'=>$uid,'date'=>date('Ymd')));

if($op=='display'){
	$title = "学习时长";
	
	/* 每1分钟更新一次排名 */
	if(!empty($durationLog) && (time()>$durationLog['update_time']+60)){
		/* 时长高于其他学员 */
		$exceed_student = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_study_duration). " WHERE date=:date AND (article+audio+video)<:duration", array(':date'=>date('Ymd'),':duration'=>($durationLog['article']+$durationLog['audio']+$durationLog['video'])));
		/* 今日学习人数 */
		$total_student = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_study_duration). " WHERE date=:date", array(':date'=>date('Ymd')));

		$ranking = round($exceed_student/$total_student,2)*100;
		$ranking = $ranking==100 ? 99 : $ranking;
		/* 如果当天学习人数只有自己，则学习时长高于平台99%的人 */
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

	/* 每天最多可兑换积分 */
	$max_exchange_credit1 = $duration_setting['exchange_credit1']*$duration_setting['max_exchange_minute'];
	/* 今天总共学习时长 */
	$total_duration = intval(($durationLog['article']+$durationLog['audio']+$durationLog['video'])/60);
	/* 今天已兑换积分 */
	$today_already_credit1 = $durationLog['exchange']*$duration_setting['exchange_credit1'];
	/* 今天可兑换积分 */
	$today_remain_credit1 = 0;
	if($durationLog['exchange']<=$duration_setting['max_exchange_minute']){
		if($total_duration<$duration_setting['max_exchange_minute']){
			$today_remain_credit1 = ($total_duration - $durationLog['exchange'])*$duration_setting['exchange_credit1'];
		}else{
			$today_remain_credit1 = ($duration_setting['max_exchange_minute'] - $durationLog['exchange'])*$duration_setting['exchange_credit1'];
		}
	}

	/* 累计学习时长 */
	$member = pdo_get($this->table_member, array('uid'=>$uid), array('video_duration','audio_duration','article_duration'));
	$member['video_duration']   = intval($member['video_duration']/60);
	$member['audio_duration']   = intval($member['audio_duration']/60);
	$member['article_duration'] = intval($member['article_duration']/60);

	/* 今日学习时长 */
	$today['video_duration']   = intval($durationLog['video']/60);
	$today['audio_duration']   = intval($durationLog['audio']/60);
	$today['article_duration'] = intval($durationLog['article']/60);

}elseif($op=='exchange'){
	
	if(!$duration_setting['switch']){
		$data = array(
			'code' => -1,
			'message' => '系统未开启学习时长兑换功能',
		);
		$this->resultJson($data);
	}
	if($duration_setting['exchange_credit1']<=0){
		$data = array(
			'code' => -1,
			'message' => '单位兑换积分有误，请联系管理员',
		);
		$this->resultJson($data);
	}
	if($duration_setting['max_exchange_minute']<=0){
		$data = array(
			'code' => -1,
			'message' => '每天最多兑换积分有误，请联系管理员',
		);
		$this->resultJson($data);
	}

	/* 今天总共学习时长 */
	$total_duration = intval(($durationLog['article']+$durationLog['audio']+$durationLog['video'])/60);
	/* 今日可兑换积分 */
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
		$data = array(
			'code' => -1,
			'message' => '您的可兑换积分不足',
		);
		$this->resultJson($data);
	}
	if($durationLog['exchange'] >= $duration_setting['max_exchange_minute']){
		$data = array(
			'code' => -1,
			'message' => '您今天的兑换额度已经用完，明天再来吧',
		);
		$this->resultJson($data);
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
			$data = array(
				'code' => 0,
				'message' => '成功兑换'.$today_remain_credit1.'积分',
			);
			$this->resultJson($data);
		}else{
			$data = array(
				'code' => -1,
				'message' => '扣除学习时长失败，请稍候重试',
			);
			$this->resultJson($data);
		}
	}else{
		$data = array(
			'code' => -1,
			'message' => '增加用户积分失败，请稍候重试',
		);
		$this->resultJson($data);
	}
}

include $this->template("../webapp/{$template}/studyDuration");


?>