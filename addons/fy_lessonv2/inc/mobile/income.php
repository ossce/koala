<?php
/**
 * 讲师收入明细
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_income). " WHERE uniacid=:uniacid AND uid=:uid ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uniacid'=>$uniacid, ':uid'=>$uid));
foreach($list as $key=>$value){
	$list[$key]['remark']  = "课程售价：".$value['orderprice']." 元，收入提成：".$value['teacher_income']."%";
	$list[$key]['addtime'] = date("Y-m-d", $value['addtime']);
}
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher_income). " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$uid));

$title = "我的收入明细(".$total.")";

if(!$_W['isajax']){
	/* 累计收入 */
	$total_imcome = pdo_fetchall("SELECT SUM(income_amount) AS income_amount FROM " .tablename($this->table_teacher_income). " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

	include $this->template("../mobile/{$template}/incomelog");
}else{
	echo json_encode($list);
}


?>