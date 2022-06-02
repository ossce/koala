<?php

$uid = $_W['member']['uid'];

if($op == 'display'){
	$title = $common['page_title']['suggest'] ? $common['page_title']['suggest'] : '投诉建议';
	$suggest_page = $common['suggest_page'];

	$suggest_category = $site_common->getSuggestCategory();

}elseif($op == 'submitSuggest'){
	if(!$_W['isajax']){
		$this->resultJson('Illegal Request');
	}

	$postData = $_GPC['postData'];

	$data = array(
		'uniacid'	  => $uniacid,
		'uid'		  => $uid,
		'category_id' => intval($postData['category_id']),
		'content'	  => trim($postData['content']),
		'mobile'	  => trim($postData['mobile']),
		'picture'	  => json_encode(array_values($postData['picture'])),
		'status'	  => 0,
		'addtime'	  => time(),
		'update_time' => time(),
	);

	if(empty($data['category_id'])){
		$json_data = array(
			'code'		=> '-1',
			'message'	=> '请选择投诉类型',
		);
		$this->resultJson($json_data);
	}
	if(empty($data['content'])){
		$json_data = array(
			'code'		=> '-1',
			'message'	=> '请填写投诉内容',
		);
		$this->resultJson($json_data);
	}
	if(empty($data['mobile'])){
		$json_data = array(
			'code'		=> '-1',
			'message'	=> '请填写联系手机号码',
		);
		$this->resultJson($json_data);
	}

	if(pdo_insert($this->table_suggest, $data)){
		$json_data = array(
			'code'		=> '0',
			'message'	=> '提交成功',
		);
		$this->resultJson($json_data);
	}else{
		$json_data = array(
			'code'		=> '-1',
			'message'	=> '系统繁忙，请稍后重试',
		);
		$this->resultJson($json_data);
	}
}


include $this->template("../mobile/{$template}/suggest");

?>