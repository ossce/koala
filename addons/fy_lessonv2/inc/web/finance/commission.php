<?php

$status = $_GPC['status'];
$lesson_type = intval($_GPC['lesson_type']);
$cash_way    = intval($_GPC['cash_way']);
$cashid      = intval($_GPC['cashid']);
$nickname    = trim($_GPC['nickname']);

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if($status != ''){
	$condition .= " AND a.status=:status ";
	$params[':status'] = $status;
}
if(!empty($lesson_type)){
	$condition .= " AND a.lesson_type=:lesson_type ";
	$params[':lesson_type'] = $lesson_type;
}
if(!empty($cash_way)){
	$condition .= " AND a.cash_way=:cash_way ";
	$params[':cash_way'] = $cash_way;
}
if(!empty($cashid)){
	$condition .= " AND a.id=:id ";
	$params[':id'] = $cashid;
}
if(!empty($nickname)){
	$condition .= " AND (b.nickname LIKE :nickname OR b.realname LIKE :nickname OR b.mobile LIKE :nickname) ";
	$params[':nickname'] = "%".$nickname."%";
}

if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}


$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_cashlog) . "  a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.mobile,b.nickname,b.avatar FROM " . tablename($this->table_cashlog) . " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if(empty($v['avatar'])){
			$list[$k]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
			$list[$k]['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
		}
	}
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);

		if($status=='0'){
			$tmpname = "待打款提现申请";
		}elseif($status==1){
			$tmpname = "已打款提现申请";
		}elseif($status==-1){
			$tmpname = "无效提现申请";
		}else{
			$tmpname = "提现申请";
		}
		
		$outputlist = pdo_fetchall("SELECT a.*,b.mobile,b.nickname,b.avatar FROM " . tablename($this->table_cashlog) . " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC ", $params);
		
		foreach ($outputlist as $key => $value) {
			$arr[$key]['id']           = $value['id'];
			$arr[$key]['nickname']     = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
			$arr[$key]['mobile']       = $value['mobile'];
			$arr[$key]['cash_way']	   = $cashWayList[$value['cash_way']];
			$arr[$key]['bank_name']	   = $value['bank_name'];
			$arr[$key]['bank_row']	   = $value['bank_row'];
			$arr[$key]['pay_account']  = $value['pay_account'];
			$arr[$key]['pay_name']     = $value['pay_name'];
			$arr[$key]['lesson_type']  = $value['lesson_type']==1?'分销佣金提现':'课程收入提现';
			$arr[$key]['cash_num']	   = $value['cash_num'];
			$arr[$key]['service_num']  = $value['service_num'];
			$arr[$key]['addtime']	   = date('Y-m-d H:i:s', $value['addtime']);
			$arr[$key]['cash_type']	   = $value['cash_type']==1 ? '管理员审核' : '自动到账';
			$arr[$key]['disposetime']  = $value['disposetime'] ? date('Y-m-d H:i:s', $value['disposetime']) : '';
			$arr[$key]['status']	   = $cashStatusList[$value['status']];
			$arr[$key]['partner_trade_no'] = $value['partner_trade_no'];
			$arr[$key]['payment_no']       = $value['payment_no'];
			$arr[$key]['remark']           = $value['remark'];
		}

		$title = array('提现单号', '粉丝昵称', '手机号码','提现方式','提现银行','银行开户行','提现帐号','提现帐号人姓名', '提现类型', '申请佣金(元)','手续费(元)', '申请时间', '处理方式', '处理时间','状态','商户订单号','微信订单号','管理员备注');
		$filename = $cashStatusList[$status] ? $cashStatusList[$status] : "提现申请";
		$site_common->exportCSV($arr, $title, $filename);
	
}

?>