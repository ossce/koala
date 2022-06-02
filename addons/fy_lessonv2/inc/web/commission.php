<?php
/**
 * 分销佣金管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

if($op=='level'){
	
	include_once "commission/level.php";

}elseif($op=='editlevel'){
	
	include_once "commission/editlevel.php";

}elseif($op=='dellevel'){
	
	include_once "commission/dellevel.php";

}elseif($op=='commissionlog'){
	
	include_once "commission/commissionlog.php";

}elseif($op=='statis'){
	
	include_once "commission/statis.php";

}

include $this->template('web/commission');


?>