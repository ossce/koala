<?php
/**
 * 分销设置
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

 load()->func('tpl');
 load()->func('file');

 /* 分销商状态列表 */
$font = json_decode($comsetting['font'], true);
$agentStatusList = array(
	'1' => $font['agent_status_on'] ? $font['agent_status_on'] : '正常',
	'0'	=> $font['agent_status_off'] ? $font['agent_status_off'] : '冻结',
);

$glo_comsetting = $this->getComsetting();
if($op=='display'){
	
	include_once 'comsetting/display.php';

}elseif($op=='font'){
	
	include_once 'comsetting/font.php';

}


include $this->template('web/comsetting');

?>