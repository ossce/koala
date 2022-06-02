<?php

$time = time();

$condition = " uniacid = :uniacid ";
$params['uniacid'] = $uniacid;
if (!empty($_GPC['ordersn'])) {
	$condition .= " AND ordersn LIKE :ordersn ";
	$params[':ordersn'] = "%{$_GPC[ordersn]}%";
}
if (!empty($_GPC['keyword'])) {
	$condition .= " AND (password LIKE :pwd OR amount=:keyword OR card_id=:keyword OR password=:keyword) ";
	$params[':pwd'] = "{$_GPC['keyword']}%";
	$params[':keyword'] = trim($_GPC['keyword']);
}
if ($_GPC['is_use'] != '') {
	if($_GPC['is_use']==0){
		$condition .= " AND is_use = :is_use AND validity > :validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}elseif($_GPC['is_use']==1){
		$condition .= " AND is_use = :is_use ";
		$params[':is_use'] = $_GPC['is_use'];
	}elseif($_GPC['is_use']==-1){
		$condition .= " AND is_use = :is_use AND validity < :validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}
}
if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_coupon). " WHERE {$condition}", $params);
$pager = pagination($total, $pindex, $psize);

if($_GPC['export']==1){
	$outputlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC", $params);

	foreach ($outputlist as $key => $value) {
		$arr[$key]['card_id']		= $value['card_id'];
		$arr[$key]['password']	= $value['password'];
		$arr[$key]['amount']		= $value['amount'];
		$arr[$key]['conditions']	= "订单满".$value['conditions']."元可用";
		$arr[$key]['validity']	= date('Y-m-d H:i:s',$value['validity']);
		if($value['is_use']==1){
			$status = "已使用";
		}elseif($value['is_use']==0 && $value['validity']>time()){
			$status = "未使用";
		}elseif($value['is_use']==0 && $value['validity']<time()){
			$status = "已过期";
		}
		$arr[$key]['is_use']	  = $status;
		$arr[$key]['nickname']    = $value['nickname'];
		$arr[$key]['ordersn']     = $value['ordersn'];
		$arr[$key]['use_time']    = $value['use_time']?date('Y-m-d H:i:s', $value['use_time']):'';
		$arr[$key]['addtime']     = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('编号', '密钥', '面值', '使用条件', '有效期', '状态', '使用者', '订单号', '使用时间', '添加时间');
	$phpexcel = new FyLessonv2PHPExcel();
	$phpexcel->exportTable($title, $arr, '课程优惠码');
}

?>