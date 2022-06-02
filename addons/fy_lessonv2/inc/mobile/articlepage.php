<?php
/**
 * 长文章分页请求
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */


if($_GPC['type']=='lesson'){
	$lessonid = intval($_GPC['id']);
	$sectionid = intval($_GPC['sectionid']);

	$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND id=:id AND status=:status LIMIT 1", array(':parentid'=>$lessonid,':id'=>$sectionid,':status'=>1));
	if(empty($section)){
		$res = array(
			'msg' => '章节不存在',
		);
		exit(json_encode($res));
	}

	$content = htmlspecialchars_decode($section['content']);
	$page = $_GET["page"] ? intval($_GET["page"]) : 1;
	$CP = new cutpage($content, 5000, $page);
	$pageContent = $CP->cut_str();

	$res = array(
		'content' => $pageContent[$page-1],
	);
	exit(json_encode($res));
}






