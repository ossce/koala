<?php
/**
 * 讲师收入提现记录
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

/* 提现方式、状态、银行列表 */
$typeStatus = new TypeStatus();
$cashWayList = $typeStatus->cashWayList();
$cashStatusList = $typeStatus->cashStatus();
$bankList = $typeStatus->bankList();

$uid = $_W['member']['uid'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_cashlog). " WHERE uid=:uid AND lesson_type=:lesson_type ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uid'=>$uid, ':lesson_type'=>2));
foreach($list as $k=>$v){
	$v['cash_name'] = $cashWayList[$v['cash_way']];
	$v['statu'] = $cashStatusList[$v['status']];
	$v['admin_img_tips'] = $v['admin_img'] ? true : false;
	$v['disposetime'] = $v['disposetime'] ? date("Y-m-d H:i", $v['disposetime']) : "";
	$v['remark'] = $v['remark'] ? $v['remark'] : "";
	$v['addtime'] = date("Y-m-d", $v['addtime']);

	$list[$k] = $v;
}
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_cashlog). " WHERE uid=:uid AND lesson_type=:lesson_type", array(':uid'=>$uid, ':lesson_type'=>2));

$title = "讲师收入提现记录(".$total.")";

if(!$_W['isajax']){
	/* 累计提现 */
	$total_cashlog = pdo_fetchall("SELECT SUM(cash_num) AS cash_num, SUM(service_num) AS service_num FROM " .tablename($this->table_cashlog). " WHERE uniacid=:uniacid AND uid=:uid AND lesson_type=:lesson_type AND status!=:status", array(':uniacid'=>$uniacid,':uid'=>$uid,':lesson_type'=>2,':status'=>-2));

	include $this->template("../mobile/{$template}/lessoncashlog");
}else{
	echo json_encode($list);
}


?>