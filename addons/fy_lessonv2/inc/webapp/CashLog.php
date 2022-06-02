<?php
/**
 * 分销提现明细
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$this->checkDistributorStatus($comsetting);

$typeStatus = new TypeStatus();
/* 提现状态 */
$cashStatusList = $typeStatus->cashStatus();
/* 提现方式 */
$cashWayList = $typeStatus->cashWayList();

$title = $salefont['commission_cash'] ? $salefont['commission_cash'] : '提现明细';

$setting_cashway = unserialize($comsetting['cash_way']);
$member = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$uid));

$mobile_site_root = $setting_pc['mobile_site_root'] ? $setting_pc['mobile_site_root'] : $_W['siteroot'];
$wap_cash_url = $mobile_site_root."app/index.php?i={$uniacid}&c=entry&op=cash&do=commission&m=fy_lessonv2";

if($op=='display'){
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND uid=:uid AND lesson_type=:lesson_type";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;
	$params[':lesson_type'] = 1;

	if($_GPC['cash_way'] != ''){
		$condition .= " AND cash_way=:cash_way";
		$params[':cash_way'] = $_GPC['cash_way'];
	}
	if($_GPC['status'] != ''){
		$condition .= " AND status=:status";
		$params[':status'] = $_GPC['status'];
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_cashlog). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_cashlog). " WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='getCashStatus' && $_W['isajax']){
	if(!$setting_cashway){
		$data = array(
			'code' => -1,
			'message' => '没有有效的提现方式，请联系管理员',
		);
		$this->resultJson($data);
	}

	$memberVip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
	$cash_lower = $memberVip ? $comsetting['cash_lower_common'] : $comsetting['cash_lower_vip'];

	if($member['nopay_commission'] < $cash_lower){
		$data = array(
			'code' => -1,
			'message' => "当前提现最低额度为{$cash_lower}元，您的可提现额度不够",
		);
		$this->resultJson($data);
	}

	$data = array(
		'code' => 0,
		'message' => "请求成功",
	);
	$this->resultJson($data);
}


include $this->template("../webapp/{$template}/cashLog");


?>