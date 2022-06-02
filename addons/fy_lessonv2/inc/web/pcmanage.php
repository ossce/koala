<?php
/**
 * PC端管理管理
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
$nav_location = $typeStatus->pcNavLocation();

if ($op == 'display') {
	
	include_once 'pcmanage/display.php';

}elseif ($op == 'navigation') {
	
	include_once 'pcmanage/navigation.php';

}elseif ($op == 'addNav') {
	
	include_once 'pcmanage/addNav.php';

}elseif ($op == 'delNav') {
	
	include_once 'pcmanage/delNav.php';

}elseif ($op == 'about') {
	
	include_once 'pcmanage/about.php';

}

include $this->template('web/pcmanage');


?>