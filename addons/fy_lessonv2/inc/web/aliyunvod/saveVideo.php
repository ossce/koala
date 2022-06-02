<?php

$data = array(
	'uniacid'	=> $uniacid,
	'uid'		=> '',
	'teacherid'	=> '',
	'name'		=> trim($_GPC['filename']),
	'pid'		=> intval($_GPC['pid']),
	'cid'		=> intval($_GPC['cid']),
	'ccid'		=> intval($_GPC['ccid']),
	'videoid'	=> trim($_GPC['videoId']),
	'object'	=> trim($_GPC['object']),
	'size'		=> $_GPC['size'],
	'addtime'	=> time(),
);
$result = pdo_insert($this->table_aliyun_upload, $data);

if($result){
	$this->resultJson(array('code'=>0,'msg'=>'保存成功'));
}else{
	$this->resultJson(array('code'=>-1,'msg'=>'保存失败'));
}