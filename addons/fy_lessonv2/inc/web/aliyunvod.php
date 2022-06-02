<?php
/**
 * 阿里云点播
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 阿里云点播全局配置 */
include_once MODULE_ROOT."/inc/common/AliyunVod.php";
$aliyun = unserialize($setting['aliyunvod']);
$aliyunVod = new AliyunVod($aliyun);

/* 视频分类 */
$category_list = $site_common->getVideoCategoryList(0);

/* 上一步URL */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('aliyunvod');

if($op=='display'){
	
	include_once "aliyunvod/display.php";

}elseif($op=='getUploadInfo'){

	include_once "aliyunvod/getUploadInfo.php";

}elseif($op=='refreshUploadInfo'){

	include_once "aliyunvod/refreshUploadInfo.php";

}elseif($op=='saveVideo'){

	include_once "aliyunvod/saveVideo.php";

}elseif($op=='delVideo'){
	
	include_once "aliyunvod/delVideo.php";

}elseif($op=='preview'){
	
	include_once "aliyunvod/preview.php";

}


include $this->template('web/aliyunVod');