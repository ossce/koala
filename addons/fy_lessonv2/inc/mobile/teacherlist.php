<?php
/**
 * 讲师列表
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$common = json_decode($setting['common'], true);
$self_item = $common['self_item'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid=:uniacid AND status=:status ";
$params[':uniacid'] = $uniacid;
$params[':status']  = 1;

$keyword = trim($_GPC['keyword']);
$cate_id = intval($_GPC['cate_id']);
if(!empty($keyword)){
	$condition .= " AND teacher LIKE :teacher ";
	$params[':teacher'] = "%{$keyword}%";
}
if(!empty($cate_id)){
	$condition .= " AND cate_id=:cate_id ";
	$params[':cate_id'] = $cate_id;
}

$teacherlist = pdo_fetchall("SELECT id,teacher,teacherdes,digest,teacherphoto FROM " .tablename($this->table_teacher). " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
foreach($teacherlist as $k=>$v){
	$v['teacherdes'] = $v['digest'] ? explode("\n", $v['digest']) : explode("\n", strip_tags(htmlspecialchars_decode($v['teacherdes'])));
	$v['lessonCount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE teacherid=:teacherid AND status=:status", array(':teacherid'=>$v['id'], ':status'=>1));

	$teacherlist[$k] = $v;
}

if($op=='display'){
	$title = $common['page_title']['teacherlist'] ? $common['page_title']['teacherlist'] : '讲师列表';

	$category_list = $site_common->getTeacherCategory();

}elseif($op=='ajaxgetteacherlist'){
	$this->resultJson($teacherlist);;
}

include $this->template("../mobile/{$template}/teacherlist");