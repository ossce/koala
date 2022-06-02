<?php
/**
 * 其他管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$pindex =max(1,$_GPC['page']);
$psize = 20;

if($op=='display'){

	include_once "others/display.php";

}elseif($op == 'suggestDetails'){

	include_once "others/suggestDetails.php";

}elseif($op == 'deleteSuggest'){

	include_once "others/deleteSuggest.php";

}elseif($op == 'suggestCategory'){

	include_once "others/suggestCategory.php";

}elseif($op == 'deleteSuggestCategory'){

	include_once "others/deleteSuggestCategory.php";

}elseif($op == 'syslog'){

	include_once "others/syslog.php";

}else if ($op == 'deleteSyslog'){
	
	include_once "others/deleteSyslog.php";

}else if ($op == 'clearcache'){
	
	include_once "others/clearcache.php";

}


include $this->template('web/others');

?>