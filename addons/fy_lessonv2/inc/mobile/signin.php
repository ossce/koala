<?php
/**
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

$market = $site_common->getMarketParams();
$signin = json_decode($market['signin'], true);

$today = date('Y-m-d', time());
$yesterday = date('Y-m-d', strtotime('-1 day'));

$today_log = pdo_get($this->table_signin, array('uniacid'=>$uniacid,'uid'=>$uid,'sign_date'=>$today));
$yesterday_log = pdo_get($this->table_signin, array('uniacid'=>$uniacid,'uid'=>$uid,'sign_date'=>$yesterday));

if($op=='display'){
	$title = "每日签到";

	if(!$signin['switch'] || !$signin['everyday']){
		message('系统未开启签到功能', "", "warning");
	}

	//签到规则
	if($signin['continuity']){
		$rule = "第一天签到奖励{$signin['everyday']}积分，连续签到奖励增加{$signin['continuity']}积分，每日签到奖励上限{$signin['limitation']}积分，非连续签到视为第一天签到";
	}else{
		$rule = "每日签到奖励{$signin['everyday']}积分";
	}

	//连续签到天数
	$sign_count = $today_log ? intval($today_log['timer']) : intval($yesterday_log['timer']);

	$month_start = date('Y-m-01', strtotime(date("Y-m-d")));
	$month_end   = date('Y-m-d', strtotime("$month_start +1 month -1 day"));

	$list = pdo_fetchall("SELECT days FROM " .tablename($this->table_signin). " WHERE uniacid=:uniacid AND uid=:uid AND sign_date>=:month_start AND sign_date<=:month_end", array(':uniacid'=>$uniacid, ':uid'=>$uid, ':month_start'=>$month_start, ':month_end'=>$month_end));
	
	$signDay = array();		
	foreach($list as $v){
		$signDay[] = array(
			'signDay' => $v['days'],
		);
	}
	$signDay = json_encode($signDay);

}elseif($op=='sign' && $_W['isajax']){
	if(!$signin['switch'] || !$signin['everyday']){
		$jsonData = array(
			'code' => -1,
			'message' => '系统未开启签到功能',
		);
		$this->resultJson($jsonData);
	}

	if(!empty($today_log)){
		$jsonData = array(
			'code' => -1,
			'message' => '您今天已签到，明天再来',
		);
		$this->resultJson($jsonData);

	}else{
		if(!empty($yesterday_log)){
			//连续签到
			$yesterday_timer = intval($yesterday_log['timer']);
			$timer = $yesterday_timer + 1;

			if($signin['continuity']){
				$moment_award = $signin['everyday'] + ($yesterday_timer * $signin['continuity']);
				$award = $moment_award <= $signin['limitation'] ? $moment_award : $signin['limitation'];
			}else{
				$award = $signin['everyday'];
			}
			
		}else{
			//周期首次签到
			$timer = 1;
			$award = $signin['everyday'];
		}

		load()->model('mc');
		$credit_log = array(
			'0' => '',
			'1' => '签到奖励(连续签到'.$timer.'天)',
			'2' => 'fy_lessonv2',
			'3' => '',
			'4' => '',
			'5' => '',
		);
        $result = mc_credit_update($uid, 'credit1', $award, $credit_log);

		if($result){
			$signin_data = array(
				'uniacid'	=> $uniacid,
				'uid'		=> $uid,
				'award'		=> $award,
				'timer'		=> $timer,
				'days'		=> date('d', time()),
				'sign_date' => $today,
			);
			if(pdo_insert($this->table_signin, $signin_data)){
				$jsonData = array(
					'code' => 0,
					'message' => '签到成功，获得'.$award.'积分',
				);
				$this->resultJson($jsonData);
			}else{
				$jsonData = array(
					'code' => -1,
					'message' => '系统繁忙，请稍后重试(-2)',
				);
				$this->resultJson($jsonData);
			}
		
		}else{
			$jsonData = array(
				'code' => -1,
				'message' => '系统繁忙，请稍后重试(-1)',
			);
			$this->resultJson($jsonData);
		}
	}
	exit();
}



include $this->template("../mobile/{$template}/signin");