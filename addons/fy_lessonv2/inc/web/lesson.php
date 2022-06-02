<?php
/**
 * 课程管理
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
/* 课程类型 */
$lessonTypeList = $typeStatus->lessonTypeList();
/* 课程状态 */
$lessonStatusList = $typeStatus->lessonStatus();


$qcloudvod = unserialize($setting['qcloudvod']);
$newqcloudVod = new QcloudVod($qcloudvod['secret_id'], $qcloudvod['secret_key']);
$aliyun = unserialize($setting['aliyunvod']);
$aliyunVod = new AliyunVod($aliyun);

/* VIP等级列表 */
$level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort DESC,id ASC", array(':uniacid'=>$uniacid));

/* 课程属性 */
$lesson_attribute = $common['lesson_attribute'];
$attribute1 = $site_common->getLessonAttribute($type=1);
$attribute2 = $site_common->getLessonAttribute($type=2);

/* 课程分类列表 */
$category = pdo_fetchall("SELECT id,parentid,name,attribute1,attribute2 FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>0));
foreach($category as $k=>$v){
	$category[$k]['attribute1'] = json_decode($v['attribute1']);
	$category[$k]['attribute2'] = json_decode($v['attribute2']);
	
	$category[$k]['child'] = pdo_fetchall("SELECT id,parentid,name,attribute1,attribute2 FROM " . tablename($this->table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id DESC", array(':uniacid'=>$uniacid,':parentid'=>$v['id']));
	foreach($category[$k]['child'] as $k1=>$v1){
		$category[$k]['child'][$k1]['attribute1'] = json_decode($v1['attribute1']);
		$category[$k]['child'][$k1]['attribute2'] = json_decode($v1['attribute2']);
	}
}

/* 上一步URL */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('lesson');

if ($op == 'display') {
	include_once 'lesson/display.php';

}elseif($op == 'postlesson') {
	include_once 'lesson/postlesson.php';

}elseif($op == 'auditsection') {
	include_once 'lesson/auditsection.php';

}elseif($op == 'viewsection'){
	include_once 'lesson/viewsection.php';

}elseif($op == 'postsection') {
	include_once 'lesson/postsection.php';

}elseif($op == 'copysection') {
	include_once 'lesson/copysection.php';

}elseif($op == 'batchAddSection') {
	include_once 'lesson/batchAddSection.php';

}elseif($op == 'exportSection') {
	include_once 'lesson/exportSection.php';

}elseif($op == 'ajaxUpdateSection') {
	include_once 'lesson/ajaxUpdateSection.php';

}elseif($op == 'sectionTitle') {
	include_once 'lesson/sectionTitle.php';

}elseif($op == 'postSectionTitle') {
	include_once 'lesson/postSectionTitle.php';

}elseif($op == 'addSectionToTitle') {
	include_once 'lesson/addSectionToTitle.php';

}elseif($op == 'delSectionTitle') {
	include_once 'lesson/delSectionTitle.php';

}elseif($op == 'document') {
	include_once 'lesson/document.php';

}elseif($op == 'postDocument') {
	include_once 'lesson/postDocument.php';

}elseif($op == 'delDocument') {
	include_once 'lesson/delDocument.php';

}elseif($op == 'downloadFile') {
	$site_common->downloadFile($_GPC['fileid']);

}elseif($op == 'attribute') {
	include_once 'lesson/attribute.php';

}elseif($op == 'addAttribute') {
	include_once 'lesson/addAttribute.php';

}elseif($op == 'delAttribute') {
	include_once 'lesson/delAttribute.php';

}elseif($op=='inform'){
	include_once 'lesson/inform.php';

}elseif($op=='delInform'){
	include_once 'lesson/delInform.php';

}elseif($op == 'informStudent'){
	include_once 'lesson/informStudent.php';

}elseif($op == 'delete') {
	include_once 'lesson/delete.php';

}elseif($op=='qrcode'){
	include_once 'lesson/qrcode.php';

}elseif($op=='qrcodeList'){
	include_once 'lesson/qrcodeList.php';

}elseif($op=='editQrcode'){
	include_once 'lesson/editQrcode.php';

}elseif($op=='delQrcode'){
	include_once 'lesson/delQrcode.php';

}elseif($op=='updomain'){
	include_once 'lesson/updomain.php';

}elseif($op=='previewVideo'){
	include_once 'lesson/previewVideo.php';

}elseif($op == 'copylesson') {
	include_once 'lesson/copylesson.php';

}elseif($op == 'batchSetting') {
	include_once 'lesson/batchSetting.php';

}elseif($op == 'batchUploadLesson') {
	include_once 'lesson/batchUploadLesson.php';

}

include $this->template('web/lesson');


?>