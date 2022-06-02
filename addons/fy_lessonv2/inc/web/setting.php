<?php
/**
 * 系统设置
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
$typeStatus = new TypeStatus();

$glo_setting = $this->getSetting();
$common = json_decode($setting['common'], true);
$pindex = max(1, intval($_GPC['page']));
$psize = 10;

if($op == 'display') {
	include_once 'setting/display.php';

}elseif ($op == 'frontshow') {
	include_once 'setting/frontshow.php';

}elseif ($op == 'moduleshow') {
	include_once 'setting/moduleshow.php';

}elseif ($op == 'templatemsg') {
	include_once 'setting/templatemsg.php';

}elseif ($op == 'templateformat'){
	include_once 'setting/templateformat.php';

}elseif ($op == 'picture') {
	include_once 'setting/picture.php';

}elseif($op == 'addPic'){
	include_once 'setting/addPic.php';

}elseif ($op == 'delPic'){
	include_once 'setting/delPic.php';
	
}elseif ($op == 'navigation'){
	include_once 'setting/navigation.php';
	
}elseif ($op == 'addNav'){
	include_once 'setting/addNav.php';
	
}elseif ($op == 'delNav'){
	include_once 'setting/delNav.php';
	
}elseif ($op == 'savetype') {
	include_once 'setting/savetype.php';

}elseif ($op == 'sms') {
	include_once 'setting/sms.php';

}elseif ($op == 'pageText') {
	include_once 'setting/pageText.php';

}elseif ($op == 'service') {
	include_once 'setting/service.php';

}elseif ($op == 'diy') {
	include_once 'setting/diy.php';

}elseif ($op == 'addDiy') {
	include_once 'setting/addDiy.php';

}elseif ($op == 'deleteDiy') {
	include_once 'setting/deleteDiy.php';

}elseif ($op == 'addDiySelectLesson') {
	include_once 'setting/addDiySelectLesson.php';

}elseif ($op == 'sysncDemo') {
	include_once 'setting/sysncDemo.php';

}elseif ($op == 'resetver') {
	if($_W['isfounder']){
		if(pdo_update('modules', array('version'=>'3.0.0'), array('name'=>'fy_lessonv2'))){
			message("重置版本号成功，请更新缓存后继续升级", $this->createWebUrl('setting'), "success");
		}else{
			message("重置版本号失败，请稍后重试", "", "error");
		}
	}else{
		message("非法访问，请使用创始人身份操作", "", "error");
	}
}

include $this->template('web/setting');
