<?php

$teacherid = intval($_GPC['teacherid']);
$teacher = pdo_fetch("SELECT teacher FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$teacherid));
if(empty($teacher)){
	message("该讲师不存在或已被删除！", "", "error");
}

$dirPath = ATTACHMENT_ROOT."images/fy_lessonv2/";
if(!file_exists($dirPath)){
	mkdir($dirPath, 0777);
}
$teacherUrl = $_W['siteroot']."app/".$this->createMobileUrl('teacher', array('teacherid'=>$teacherid));
$tmpName = "teacher_".$teacherid.".png";
$qrcodeName = $dirPath.$tmpName;

include_once IA_ROOT."/framework/library/qrcode/phpqrcode.php";
QRcode::png($teacherUrl, $qrcodeName, 'L', '8', 2);

$downloadName = $teacher['teacher'].".png";

header("Content-type: octet/stream");
header("Content-disposition:attachment;filename=".$downloadName.";");
header("Content-Length:".filesize($qrcodeName));
readfile($qrcodeName);

unlink($qrcodeName);
exit();