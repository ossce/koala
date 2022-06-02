<?php
/**
 * 课程分类管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 课程属性列表 */
$lesson_attribute = $common['lesson_attribute'];
$attribute1 = $site_common->getLessonAttribute($type=1);
$attribute2 = $site_common->getLessonAttribute($type=2);
 
if ($operation == 'display') {
	
	include_once 'category/display.php';

}elseif($operation == 'post') {
	
	include_once 'category/post.php';

}elseif($operation == 'delete') {
	
	include_once 'category/delete.php';

}elseif($op=='changeShow'){
	
	include_once 'category/changeShow.php';

}elseif($op=='hotList'){
	
	include_once 'category/hotList.php';

}elseif($op=='attribute'){
	
	include_once 'category/attribute.php';

}elseif($op=='batchSettingVip'){
	
	include_once 'category/batchSettingVip.php';

}

include $this->template('web/category');

?>