<?php
/**
 * 订阅模板消息
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$uid = $_W['member']['uid'];

if($op == 'display'){
	$subscribe = intval($_GPC['subscribe']);

	if(empty($uid)){
		$data = array(
			'code' => -1,
			'msg'  => '获取会员信息失败，请稍后重试',
		);
		$this->resultJson($data);
	}
	
	$fans = pdo_get($this->table_fans, array('uid'=>$uid), array('openid'));
	
	$record = pdo_get($this->table_subscribe_msg, array('uid'=>$uid));
	$params = array(
		'uniacid'   => $uniacid,
		'uid'		=> $uid,
		'openid'	=> $fans['openid'],
		'subscribe' => $subscribe,
		'update_time' => time(),
	);

	if($record){
		$result = pdo_update($this->table_subscribe_msg, $params, array('id'=>$record['id']));
	}else{
		$params['addtime'] = time();
		$result = pdo_insert($this->table_subscribe_msg, $params);
	}

	if($result){
		$data = array(
			'code' => 0,
			'msg'  => $subscribe ? '订阅消息成功' : '取消订阅成功，您不会再收到课程提醒',
		); 
	}else{
		$data = array(
			'code' => -1,
			'msg'  => $subscribe ? '订阅消息失败，请稍后重试' : '取消订阅消息失败，请稍后重试',
		); 
	}

	$this->resultJson($data);
}

