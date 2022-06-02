<?php
/**
 * 我的讲师服务
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 支付方式集合 */
$typeStatus = new TypeStatus();
$paytype = $typeStatus->orderPayType();

$pindex =max(1,$_GPC['page']);
$psize = 10;

if($op == 'display'){

	$title = "我的讲师服务";
	
	/* 我的讲师服务 */
	$condition = " uniacid=:uniacid AND uid=:uid AND validity>:validity ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;
	$params[':validity'] = time();


	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_buyteacher). " WHERE {$condition}", $params);
	foreach($list as $k=>$v){
		$list[$k]['teacher'] = pdo_get($this->table_teacher, array('id'=>$v['teacherid']));
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member_buyteacher) . " WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='orderlist'){

	$title = "讲师服务订单";

	$condition = " uniacid=:uniacid AND uid=:uid AND status=:status";
	$params = array(
		':uniacid' => $uniacid,
		':uid'	   => $uid,
		':status'  => 1,
	);

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_order). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$teacher = pdo_get($this->table_teacher, array('id'=>$v['teacherid']), array('teacher'));
		$list[$k]['teacher'] = $teacher['teacher'];
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher_order) . " WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);
}


include $this->template("../webapp/{$template}/myTeacher");


?>