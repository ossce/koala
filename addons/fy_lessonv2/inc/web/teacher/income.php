<?php
 
$teacher = $_GPC['teacher'];
$lesson  = $_GPC['lesson'];
$ordersn = $_GPC['ordersn'];
$ordertype = $_GPC['ordertype'];

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
if(!empty($teacher)){
	$condition .= " AND teacher LIKE :teacher ";
	$params[':teacher'] = "%".$teacher."%";
}
if(!empty($lesson)){
	$condition .= " AND bookname LIKE :bookname ";
	$params[':bookname'] = "%".$lesson."%";
}
if($ordersn!=''){
	$condition .= " AND ordersn=:ordersn ";
	$params[':ordersn'] = $ordersn;
}
if($ordertype!=''){
	$condition .= " AND ordertype=:ordertype ";
	$params[':ordertype'] = $ordertype;
}

if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$count_record = pdo_fetchall("SELECT COUNT(*) AS count, SUM(income_amount) as income FROM " .tablename($this->table_teacher_income). " WHERE {$condition}", $params);
$total = $count_record[0]['count'];
$income_count = floatval($count_record[0]['income']);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_income). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(180);
	$psize = 10000;
	$max = ceil($total/$psize);
	$random = random(4);

	for($i=1; $i<=$max; $i++){
		$outputlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_income). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($i - 1) * $psize . ',' . $psize, $params);
	
		foreach ($outputlist as $key => $value) {
			$arr[$key]['id']			  = $value['id'];
			$arr[$key]['teacher']         = $value['teacher'];
			$arr[$key]['openid']          = $value['openid'];
			$arr[$key]['ordersn']         = $value['ordersn'];
			$arr[$key]['bookname']        = $value['bookname'];
			$arr[$key]['orderprice']      = $value['orderprice'];
			$arr[$key]['teacher_income']  = $value['teacher_income'];
			$arr[$key]['income_amount']   = $value['income_amount'];
			$arr[$key]['addtime']         = date('Y-m-d H:i:s', $value['addtime']);
		}

		$title =  array('ID', '讲师名称','讲师openid', '订单编号', '课程名称', '出售金额(元)', '讲师分成(%)', '讲师收入(元)', '添加时间');
		$filename = '讲师收入明细'.$random.$uniacid.'-'.$i;

		$phpexcel = new FyLessonv2PHPExcel();
		$savetype = $max>1 ? 1 : 0;
		$phpexcel->exportTable($title, $arr, $filename, $savetype);
		unset($arr);

		$filenameArr[] = $filename.'-'.date('Ymd').'.xls';
	}

	/* 打包下载 */
	$filepath = '../data/excel/';
	$pack = $filepath.'讲师收入明细'.$random.$uniacid.'-'.date('Ymd').'.zip';
	$zip = new ZipArchive();

	if($zip->open($pack, ZipArchive::CREATE)=== TRUE){
		foreach($filenameArr as $file){
			if(file_exists($filepath.$file)){
				$zip->addFile($filepath.$file);
			}else{
				exit('无法打开文件，或者文件创建失败');
			}
		}
		$zip->close();
	}

	header('Content-Type:text/html;charset=utf-8');
	header('Content-disposition:attachment;filename=讲师收入明细'.$random.$uniacid.'-'.date('Ymd').'.zip');
	$filesize = filesize($pack);
	readfile($pack);
	header('Content-length:'.$filesize);

	$files = glob($filepath.'*');
	foreach($files as $file) {
		if(strstr($file, "讲师收入明细{$random}{$uniacid}-")){
			unlink($file);
		}
	}
}