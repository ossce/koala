<?php
/**
 * 首页
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$login_visit = json_decode($setting_pc['login_visit'], true);
if(in_array('index', $login_visit) && empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
	header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
}

/* 访问权限 */
$site_common->check_black_list('visit', $uid);

/* 自定义字体 */
$index_page = $common['index_page'];
$lesson_page = $common['lesson_page'];

/* 首页轮播图 */
$banner_list = $webAppCommon->getIndexBanner();

/* 最新通知 */
$new_notice_setting = json_decode($setting_pc['new_notice'], true);  //标题配置
if($new_notice_setting['number']){
	$notice_adv = $webAppCommon->getIndexArticelAdv(); //左侧广告
	$notice_list = $webAppCommon->getIndexArticleList($new_notice_setting['number']);  //公告列表

	if(!$_SESSION['fy_lessonv2_'.$uniacid.'_vip']){
		foreach($notice_list as $k=>$v){
			if($v['is_vip']) unset($notice_list[$k]);
		}
	}
}

/* 限时折扣广告 */
$discount_adv = $webAppCommon->getIndexDiscountAdv($list=3);

/* 名师风采 */
$rec_teacher_setting = json_decode($setting_pc['rec_teacher'], true);
if($rec_teacher_setting['number']){
	$rec_teacher = $webAppCommon->getIndexTeacherList($rec_teacher_setting['number']);
}

/* 最新更新课程 */
$newlesson_setting = json_decode($setting_pc['new_lesson'], true);
if($newlesson_setting['number']){
	$newlesson = $webAppCommon->getIndexNewLesson($newlesson_setting['number']);
}

/* 推荐板块 */
$rec_lesson = $webAppCommon->getIndexRecomendLesson();


include $this->template("../webapp/{$template}/index");


?>