<?php

$lessonid = intval($_GPC['lessonid']);
$lesson = pdo_fetch("SELECT bookname FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$lessonid));
if(empty($lesson)){
	message("该课程不存在或已被删除", "", "error");
}

$dirPath = ATTACHMENT_ROOT."images/fy_lessonv2/";
if(!file_exists($dirPath)){
	mkdir($dirPath, 0777);
}

$dirPath .= "{$uniacid}/";
if(!file_exists($dirPath)){
	mkdir($dirPath, 0777);
}

$lessonUrl = $_W['siteroot']."app/".$this->createMobileUrl('lesson', array('op'=>'display','id'=>$lessonid));
$tmpName = "lesson_".$lessonid.".png";
$qrcodeName = $dirPath.$tmpName;

include_once IA_ROOT."/framework/library/qrcode/phpqrcode.php";
QRcode::png($lessonUrl, $qrcodeName, 'L', '8', 2);

$downloadName = $lesson['bookname'].".png";

header("Content-type: octet/stream");
header("Content-disposition:attachment;filename=".$downloadName.";");
header("Content-Length:".filesize($qrcodeName));
readfile($qrcodeName);

unlink($qrcodeName);
exit();