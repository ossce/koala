<?php

if(!$_W['isajax']){
	$this->resultJson('Illegal Request');
}

$data = array(
	'uniacid'	=> $uniacid,
	'uid'		=> $uid,
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

?>