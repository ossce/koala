<?php
/**
 * 查找课程
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$pid = intval($_GPC['pid']);
$cid = intval($_GPC['cid']);
$lesson_type = intval($_GPC['lesson_type']);
$status = intval($_GPC['status']);
$keyword = trim($_GPC['keyword']);

$condition = " uniacid=:uniacid AND lesson_type!=:wxapp_type";
$params[':uniacid'] = $uniacid;
$params[':wxapp_type'] = 2;

if($pid){
	$condition .= " AND pid=:pid ";
	$params[':pid'] = $pid;
}
if($cid){
	$condition .= " AND cid=:cid ";
	$params[':cid'] = $cid;
}
if($lesson_type){
	$condition .= " AND lesson_type=:lesson_type ";
	$params[':lesson_type'] = $lesson_type;
}
if($status){
	$condition .= " AND status=:status ";
	$params[':status'] = $status;
}
if(!empty($keyword)){
	$condition .= " AND bookname LIKE :bookname ";
	$params[':bookname'] = "%".$keyword."%";
}


$pindex = max(1, intval($_GPC['page']));
$psize = 12;

$list = pdo_fetchall("SELECT id,bookname,lesson_type,price,teacher_income,images,validity FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_lesson_parent). "  WHERE {$condition}", $params);
$total_page = ceil($total/$psize);


include $this->template('web/getLesson');

?>