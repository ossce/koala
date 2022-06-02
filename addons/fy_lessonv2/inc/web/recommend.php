<?php
/**
 * 推荐板块管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$typeStatus = new TypeStatus();
$styleList = $typeStatus->recommendType();
$lessonStatus = $typeStatus->lessonStatus();

$pindex = max(1, intval($_GPC['page']));
$psize = 15;


if ($op == 'display') {
	
	include_once "recommend/display.php";

}elseif($op == 'post') {

	include_once "recommend/post.php";

}elseif ($op == 'details') {
	
	include_once "recommend/details.php";

}elseif ($op == 'removerec') {
	
	include_once "recommend/removerec.php";

}elseif ($op == 'addtorec') {
	
	include_once "recommend/addtorec.php";

}elseif ($op == 'recpost') {
	
	include_once "recommend/recpost.php";

}elseif ($op == 'delete') {
	
	include_once "recommend/delete.php";

}

include $this->template('web/recommend');

?>