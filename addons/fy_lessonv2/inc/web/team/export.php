<?php

$member = pdo_fetch("SELECT a.nickname,b.nickname AS mc_nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));
if(empty($member)){
	message('分销商不存在');
}

$list = pdo_fetchall("SELECT a.uid,a.nopay_commission,a.pay_commission,a.payment_amount,a.payment_order,a.status,a.agent_level,a.addtime, b.nickname,b.realname,b.mobile FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.parentid=:parentid", array(':parentid'=>$uid));

$level_list = pdo_getall($this->table_commission_level, array('uniacid'=>$uniacid), array('id','levelname'));
foreach($level_list as $v){
	$level_arr[$v['id']] = $v['levelname'];
}

foreach ($list as $key => $value) {
	$arr[$key]['uid']				= $value['uid'];
	$arr[$key]['nickname']			= strip_emoji($value['nickname']);
	$arr[$key]['realname']			= $value['realname'];
	$arr[$key]['mobile']			= $value['mobile'];
	$arr[$key]['status']			= $agentStatusList[$value['status']];
	$arr[$key]['levelname']			= $value['agent_level'] ? $level_arr[$value['agent_level']] : '默认等级';
	$arr[$key]['pay_commission']	= $value['pay_commission'];
	$arr[$key]['nopay_commission']	= $value['nopay_commission'];
	$arr[$key]['payment_amount']	= $value['payment_amount'];
	$arr[$key]['payment_order']		= $value['payment_order'];
	$arr[$key]['addtime']		    = date('Y-m-d H:i:s', $value['addtime']);
}

$title = array('会员ID', '昵称', '真实姓名', '手机号码', '分销商状态', '分销商级别', '已结算佣金', '未结算佣金','订单金额','订单笔数', '加入时间');
$filename = $member['mc_nickname'] ? $member['mc_nickname'] : $member['nickname'];
$filename .= '-直接下级成员';

$phpexcel = new FyLessonv2PHPExcel();
$phpexcel->exportTable($title, $arr, $filename);

?>