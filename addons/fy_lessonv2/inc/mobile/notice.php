<?php
/**
 * 定时课程提醒
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

set_time_limit(180);

$hour = date('H', time());
if($hour<7 || $hour>=22){
	echo 'The send message is not running at the current time.';
	exit();
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_inform). " WHERE uniacid=:uniacid AND status=:status", array(':uniacid'=>$uniacid, ':status'=>1));
foreach($list as $v){
	$tplmessage = pdo_fetch("SELECT newlesson FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$v['uniacid']));

	$fans_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_inform_fans). " WHERE inform_id=:inform_id LIMIT 0,180", array(':inform_id'=>$v['inform_id'],));

	if(empty($tplmessage['newlesson'])){
		continue;
	}
	if(empty($fans_list) || time()-86400 > $v['addtime']){
		pdo_update($this->table_inform, array('status'=>0), array('inform_id'=>$v['inform_id']));
		pdo_delete($this->table_inform_fans, array('inform_id'=>$v['inform_id']));
		continue;
	}

	$data = json_decode($v['content'], true);
	$url = $data['link'] ? $data['link'] : $_W['siteroot'] . 'app/' . $this->createMobileUrl('lesson', array('id'=>$v['lesson_id']));
	$message = array(
		'template_id' => $tplmessage['newlesson'],
		'url'		  => $url,
		'topcolor'	  => "#222222",
		'data'		  => array(
			'first'  => array(
				'value' => $data['first'],
				'color' => "#222222",
			),
			'keyword1' => array(
				'value' => $data['keyword1'],
				'color' => "#222222",
			),
			'keyword2' => array(
				'value' => $data['keyword2'],
				'color' => "#222222",
			),
			'keyword3' => array(
				'value' => $data['keyword3'],
				'color' => "#222222",
			),
			'keyword4' => array(
				'value' => $data['keyword4'],
				'color' => "#222222",
			),
			'remark' => array(
				'value' => $data['remark'],
				'color' => "#222222",
			),
		)
	);

	foreach($fans_list as $item){
		$message['touser'] = $item['openid'];
		$this->send_template_message($message);
		pdo_delete($this->table_inform_fans, array('inform_fans_id'=>$item['inform_fans_id']));
		sleep(1);
	}

	if(count($fans_list) < 180){
		pdo_update($this->table_inform, array('status'=>0), array('inform_id'=>$v['inform_id']));
	}
}
exit();



?>