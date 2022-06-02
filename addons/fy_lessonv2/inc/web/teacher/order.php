<?php

$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['ordersn'])) {
	$condition .= " AND a.ordersn = :ordersn ";
	$params[':ordersn'] = trim($_GPC['ordersn']);
}
if ($_GPC['status']!='') {
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}
if (!empty($_GPC['paytype'])) {
	$condition .= " AND a.paytype = :paytype ";
	$params[':paytype'] = $_GPC['paytype'];
}
if (!empty($_GPC['nickname'])) {
	$condition .= " AND b.nickname LIKE :nickname ";
	$params[':nickname'] = "%{$_GPC['nickname']}%";
}

$timetype = intval($_GPC['timetype']) ? intval($_GPC['timetype']) : 1;
if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
}elseif(!$_GPC['search']){
	$starttime = strtotime('-1 month');
	$endtime = strtotime('today') + 86399;
}
if($starttime && $endtime){
	if($timetype==1){
		$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	}elseif($timetype==2){
		$condition .= " AND a.paytime>=:starttime AND a.paytime<=:endtime";
	}
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_teacher_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC", $params);

	foreach($list as $key=>$value){			
		$arr[$key]['ordersn']         = $value['ordersn'];
		$arr[$key]['uid']			  = $value['uid'];
		$arr[$key]['nickname']		  = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']        = $value['realname'];
		$arr[$key]['mobile']          = $value['mobile'];
		$arr[$key]['ordertime']         = "[".$value['teacher_name'] ."]讲师服务-". $value['ordertime']."天";
		$arr[$key]['vipmoney']        = $value['price'];
		$arr[$key]['member1']		  = $value['member1'];
		$arr[$key]['commission1']     = $value['commission1'];
		$arr[$key]['member2']		  = $value['member2'];
		$arr[$key]['commission2']     = $value['commission2'];
		$arr[$key]['member3']		  = $value['member3'];
		$arr[$key]['commission3']     = $value['commission3'];
		if($value['status'] == '0'){
			$arr[$key]['status'] = "未支付";
		}elseif($value['status'] == '1'){
			$arr[$key]['status'] = "已付款";
		}
		if($value['paytype'] == 'credit'){
			$arr[$key]['paytype'] = "余额支付";
		}elseif($value['paytype'] == 'wechat'){
			$arr[$key]['paytype'] = "微信支付";
		}elseif($value['paytype'] == 'alipay'){
			$arr[$key]['paytype'] = "支付宝支付";
		}elseif($value['paytype'] == 'offline'){
			$arr[$key]['paytype'] = "线下支付";
		}elseif($value['paytype'] == 'admin'){
			$arr[$key]['paytype'] = "后台支付";
		}elseif($value['paytype'] == 'vipcard'){
			$arr[$key]['paytype'] = "服务卡支付";
		}elseif($value['paytype'] == 'wxapp'){
			$arr[$key]['paytype'] = "微信小程序";
		}else{
			$arr[$key]['paytype'] = "无";
		}
		$arr[$key]['paytime']	 = $value['paytime'] ? date('Y-m-d H:i:s', $value['paytime']) : '';
		$arr[$key]['addtime']	 = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('订单编号', '用户uid', '昵称', '姓名', '手机号码', '购买服务', '服务价格(元)', '一级推荐人(uid)', '一级佣金(元)', '二级推荐人(uid)', '二级佣金(元)', '三级推荐人(uid)', '三级佣金(元)', '订单状态', '付款方式', '付款时间', '下单时间');
	$site_common->exportCSV($arr, $title, $fileName="购买讲师订单");
}