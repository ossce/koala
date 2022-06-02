<?php
if(!$_W['isajax']){
	$this->resultJson('Illegal Request');
}

$ordersn = 'A' . date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999));
$amount = $_GPC['amount'];

if(!is_numeric($amount)){
	$json_data = array(
		'code'		=> -1,
		'message'	=> "打赏金额必须为数字",
	);
	$this->resultJson($json_data);
}
if($amount <= 0){
	$json_data = array(
		'code'		=> -1,
		'message'	=> "打赏金额有误，请重新输入",
	);
	$this->resultJson($json_data);
}

$lessonid = intval($_GPC['lessonid']);
$lesson = pdo_fetch("SELECT a.bookname,a.teacherid,a.teacher_income,a.award_income,b.uid AS teacher_uid FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id", array(':uniacid'=>$uniacid,':id'=>$lessonid));

if(empty($lesson)){
	$json_data = array(
		'code'		=> -1,
		'message'	=> "获取课程数据失败，请刷新后重试",
	);
	$this->resultJson($json_data);
}

$data = array(
	'uniacid'		 => $uniacid,
	'uid'			 => $uid,
	'ordersn'		 => $ordersn,
	'price'			 => intval(abs($amount)),
	'lessonid'		 => $lessonid,
	'bookname'		 => $lesson['bookname'],
	'teacherid'		 => $lesson['teacherid'],
	'teacher_income' => $lesson['award_income'] ? $lesson['award_income'] : $lesson['teacher_income'],
	'addtime'		 => time(),
);

if($lesson['teacher_uid']){
	$data['teacher_amount'] = round($data['price']*$data['teacher_income']*0.01, 2);
}

$res = pdo_insert($this->table_live_award, $data);
$orderid = pdo_insertid();

if($res){
	$json_data = array(
		'code'		=> 0,
		'message'	=> "请求接口成功",
		'goUrl'		=> $_W['siteroot']."{$uniacid}/payment.html?orderid={$orderid}&ordertype=liveaward",
	);
	$this->resultJson($json_data);
}else{
	$json_data = array(
		'code'		=> -1,
		'message'	=> "网络繁忙，请稍后重试",
	);
	$this->resultJson($json_data);
}


?>