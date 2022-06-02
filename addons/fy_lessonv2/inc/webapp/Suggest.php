<?php
/**
 * 投诉建议
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if($op == 'display'){
	$title = $common['self_page']['suggest'] ? $common['self_page']['suggest'] : '投诉建议';
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


include $this->template("../webapp/{$template}/suggest");


?>