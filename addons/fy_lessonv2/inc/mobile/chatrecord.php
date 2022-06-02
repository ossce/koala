<?php
/**
 * (直播)聊天记录
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if(!$_W['isajax']){
	$this->resultJson('Illegal Request');
}

$data = array(
	'uniacid'	=> $uniacid,
	'uid'		=> $_W['member']['uid'],
	'lessonid'	=> $_GPC['lessonid'],
	'roomid'	=> $_GPC['roomid'],
	'name'		=> $_GPC['name'],
	'body'		=> urlencode($_GPC['body']),
	'time'		=> $_GPC['time'] ? $_GPC['time'] : time(),
	'status'	=> !intval($_GPC['check_content']),
	'extra'		=> $_GPC['extra'],
);

if(pdo_insert($this->table_live_chatrecord, $data)){
	$this->resultJson('success');
}else{
	$this->resultJson('error');
}