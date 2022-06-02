<?php

$condition = " a.uniacid=:uniacid AND a.is_delete=:is_delete ";
$params[':uniacid'] = $uniacid;
$params[':is_delete'] = intval($_GPC['is_delete']);;

if (!empty($_GPC['keyword'])) {
	$condition .= " AND (a.ordersn = :keyword OR a.bookname = :keyword) ";
	$params[':keyword'] = $_GPC['keyword'];
}
if (intval($_GPC['rec_uid'])) {
	$condition .= " AND a.member1=:member1 ";
	$params[':member1'] = $_GPC['rec_uid'];
}
if ($_GPC['status']!='') {
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}
if (!empty($_GPC['paytype'])) {
	$condition .= " AND a.paytype = :paytype ";
	$params[':paytype'] = $_GPC['paytype'];
}
if ($_GPC['is_verify']!='') {
	$condition .= " AND a.is_verify=:is_verify ";
	$params[':is_verify'] = $_GPC['is_verify'];
}
if ($_GPC['validity']==2) {
	$condition .= " AND validity<:validity AND validity>0 AND status>0";
	$params[':validity'] = time();
}
if (!empty($_GPC['nickname'])) {
	$condition .= " AND ((b.nickname LIKE :nickname) OR (b.realname LIKE :nickname) OR (b.mobile LIKE :nickname)) ";
	$params[':nickname'] = "%".$_GPC['nickname']."%";
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


if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		$vipNumber = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity > :validity", array(':uid'=>$v['uid'], ':validity'=>time()));
		$list[$k]['vip'] = $vipNumber>0 ? 1 : 0;
	}

	$statis = pdo_fetchall('SELECT COUNT(*) AS total, SUM(price) AS price FROM ' .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
	$total = $statis[0]['total'];

	$pager = pagination($total, $pindex, $psize);


	if($_GPC['status']=='-1'){
		$filename = "已取消课程订单";
	}elseif($_GPC['status']=='0'){
		$filename = "未支付课程订单";
	}elseif($_GPC['status']=='1'){
		$filename = "已付款课程订单";
	}elseif($_GPC['status']=='2'){
		$filename = "已评价课程订单";
	}else{
		$filename = "全部课程订单";
	}

	$site_common->updateOrderVerifyLog(); //更新旧的核销订单记录

}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile,b.msn,b.occupation,b.company,b.graduateschool,b.grade,b.address FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC", $params);

	$j = 0;
	$verify_user = array();
	foreach ($list as $key => $value) {
		$arr[$j]['ordersn']         = $value['ordersn'];
		$arr[$j]['uid']				= $value['uid'];
		$arr[$j]['nickname']		= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$j]['realname']        = $value['realname'];
		$arr[$j]['mobile']          = $value['mobile'];
		$arr[$j]['spec_name']		= $value['spec_name'];
		$arr[$j]['lesson_type']		= $lesson_type[$value['lesson_type']];
		$arr[$j]['bookname']        = $value['bookname'];
		$arr[$j]['price']           = $value['price'];
		$arr[$j]['integral']        = $value['integral'];
		$arr[$j]['member1']			= $value['member1'];
		$arr[$j]['commission1']     = $value['commission1'];
		$arr[$j]['member2']			= $value['member2'];
		$arr[$j]['commission2']     = $value['commission2'];
		$arr[$j]['member3']			= $value['member3'];
		$arr[$j]['commission3']     = $value['commission3'];
		$arr[$j]['teacher_income']  = $value['teacher_income'];
		$arr[$j]['income_amount']	= round($value['price']*$value['teacher_income']*0.01,2);
		
		if($value['status'] == '-2'){
			$arr[$j]['status'] = "已退款";
		}elseif($value['status'] == '-1'){
			$arr[$j]['status'] = "已取消";
		}elseif($value['status'] == '0'){
			$arr[$j]['status'] = "未支付";
		}elseif($value['status'] == '1'){
			$arr[$j]['status'] = "已付款";
		}elseif($value['status'] == '2'){
			$arr[$j]['status'] = "已评价";
		}
		if($value['paytype'] == 'credit'){
			$arr[$j]['paytype'] = "余额支付";
		}elseif($value['paytype'] == 'wechat'){
			$arr[$j]['paytype'] = "微信支付";
		}elseif($value['paytype'] == 'alipay'){
			$arr[$j]['paytype'] = "支付宝支付";
		}elseif($value['paytype'] == 'offline'){
			$arr[$j]['paytype'] = "线下支付";
		}elseif($value['paytype'] == 'admin'){
			$arr[$j]['paytype'] = "后台支付";
		}elseif($value['paytype'] == 'wxapp'){
			$arr[$j]['paytype'] = "微信小程序";
		}elseif($value['paytype'] == 'vipcard'){
			$arr[$j]['paytype'] = "卡密支付";
		}elseif($value['paytype'] == '' && $value['status']>0){
			$arr[$j]['paytype'] = "已支付";
		}else{
			$arr[$j]['paytype'] = "无";
		}
		$arr[$j]['paytime'] = $value['paytime'] ? date('Y-m-d H:i:s', $value['paytime']) : '';
		$arr[$j]['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
		if($value['lesson_type']==1){
			$arr[$j]['verify_name'] = $value['is_verify']==1 ? '已核销' : '未核销';
		}else{
			$arr[$j]['verify_name'] ='';
		}

		$appoint_info = json_decode($value['appoint_info'], true);
		foreach($appoint_info as $key=>$item){
			$arr[$j][$key] = $item;
		}
		$j++;
	}

	$title =  array('订单编号','用户uid','昵称','姓名','手机号码','规格名称','课程类型','课程名称','课程价格(元)','获赠积分','一级推荐人(uid)','一级佣金(元)','二级推荐人(uid)','二级佣金(元)','三级推荐人(uid)','三级佣金(元)','讲师分成(%)','讲师收入(元)','订单状态','付款方式','付款时间','下单时间','核销状态','报名课程信息');
	$site_common->exportCSV($arr, $title, $fileName="课程订单");
}

?>