<?php
/**
 * 评价订单
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


$title = "评价订单";

/* 评价字体自定义 */
$evaluate_page = $common['evaluate_page'];

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT a.id,a.ordersn,a.uid,a.lessonid,a.status,a.teacherid,a.lesson_type,a.spec_name,a.spec_day, b.bookname,b.images,b.price FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.uid=:uid", array(':uniacid'=>$uniacid,':id'=>$orderid,':uid'=>$uid));

if($op == 'display'){
	if(!$order){
		message("该订单不存在", "", "error");
	}

	if($order['status']==2){
		message("该订单已评价，无需重复评价", $_W['siteroot']."{$uniacid}/mylesson.html?status=no_evaluate", "error");
	}

}elseif($op=='postEvaluate' && $_W['isajax']){
	if(!$order){
		$jsonData = array(
			'code' => -1,
			'message' => '该订单不存在',
		);
		$this->resultJson($jsonData);
	}

	if($order['status']==2){
		$jsonData = array(
			'code' => -1,
			'message' => '该订单已评价，无需重复评价',
		);
		$this->resultJson($jsonData);
	}

	$data = array(
		'uniacid'			=> $uniacid,
		'orderid'			=> $orderid,
		'ordersn'			=> $order['ordersn'],
		'lessonid'			=> $order['lessonid'],
		'bookname'			=> $order['bookname'],
		'teacherid'			=> $order['teacherid'],
		'uid'				=> $order['uid'],
		'grade'				=> intval($_GPC['grade']),
		'global_score'		=> intval($_GPC['global_score']),
		'content_score'		=> intval($_GPC['content_score']),
		'understand_score'  => intval($_GPC['understand_score']),
		'content'			=> trim($_GPC['content']),
		'status'			=> $setting['audit_evaluate']==0 ? 1 : 0,
		'type'				=> 1,
		'addtime'			=> time(),
	);

	if(strlen($data['content'])<10){
		$jsonData = array(
			'code' => -1,
			'message' => '评论内容不得少于10个字符',
		);
		$this->resultJson($jsonData);
	}
	if(!$data['grade']){
		$jsonData = array(
			'code' => -1,
			'message' => '请对课程进行总体评价',
		);
		$this->resultJson($jsonData);
	}
	if(!$data['global_score'] || !$data['content_score'] || !$data['understand_score']){
		$jsonData = array(
			'code' => -1,
			'message' => '请点亮评分的每一行星星',
		);
		$this->resultJson($jsonData);
	}

	$result = pdo_insert($this->table_evaluate, $data);
	if($result){
		/* 更新订单状态 */
		pdo_update($this->table_order, array('status'=>2), array('id'=>$order['id']));

		/* 用户评价课程 */
		$site_common->memberEvaluate($data);

		$evaluate_word = $setting['audit_evaluate']==0 ? "评价成功" : "评价成功，等待管理员审核";
		$jsonData = array(
			'code' => 0,
			'message' => $evaluate_word,
		);
		$this->resultJson($jsonData);
		
	}else{
		$jsonData = array(
			'code' => -1,
			'message' => '系统繁忙，请稍候重试',
		);
		$this->resultJson($jsonData);
	}
}



include $this->template("../webapp/{$template}/evaluate");


?>