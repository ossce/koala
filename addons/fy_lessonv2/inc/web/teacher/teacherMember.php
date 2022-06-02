<?php
 
$keyword  = trim($_GPC['keyword']);
$status   = $_GPC['status'];
$teacherid = intval($_GPC['teacherid']);
$teacher_name = trim($_GPC['teacher_name']);

$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " a.uniacid = :uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['keyword'])) {
	$condition .= " AND ((b.nickname LIKE :keyword) OR (b.realname LIKE :keyword) OR (b.mobile LIKE :keyword)) ";
	$params[':keyword'] = "%{$_GPC['keyword']}%";
}
if ($status!='') {
	if($status==1){
		$condition .= " AND a.validity >= :validity ";
	}
	if($status==-1){
		$condition .= " AND a.validity < :validity ";
	}
	$params[':validity'] = time();
}
if ($teacherid) {
	$condition .= " AND a.teacherid=:teacherid ";
	$params[':teacherid'] = $teacherid;
}
if (!empty($teacher_name)) {
	$condition .= " AND c.teacher LIKE :teacher ";
	$params[':teacher'] = "%{$teacher_name}%";
}


$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_buyteacher). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_teacher). " c ON a.teacherid=c.id WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.id,a.uid,a.validity,a.update_time,b.nickname,b.realname,b.mobile,c.teacher FROM " .tablename($this->table_member_buyteacher). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_teacher). " c ON a.teacherid=c.id WHERE {$condition} ORDER BY a.validity DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	
	$pager = pagination($total, $pindex, $psize);
}else{
	set_time_limit(180);
	$psize = 10000;
	$max = ceil($total/$psize);
	$random = random(4);

	for($i=1; $i<=$max; $i++){
		$outputlist = pdo_fetchall("SELECT a.uid,a.validity,a.update_time,b.nickname,b.realname,b.mobile,c.teacher FROM " .tablename($this->table_member_buyteacher). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_teacher). " c ON a.teacherid=c.id WHERE {$condition} ORDER BY a.validity DESC LIMIT " . ($i - 1) * $psize . ',' . $psize, $params);

		foreach ($outputlist as $key => $value) {				
			$arr[$key]['uid']		  = $value['uid'];
			$arr[$key]['nickname']    = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
			$arr[$key]['realname']    = $value['realname'];
			$arr[$key]['mobile']      = $value['mobile'];
			$arr[$key]['teacher']	  = $value['teacher'];
			$arr[$key]['validity']	  = date('Y-m-d H:i', $value['validity']);
			if($value['validity'] >= time()){
				$arr[$key]['status']  = "生效中";
			}else{
				$arr[$key]['status']  = "已过期";
			}
			$arr[$key]['update_time'] = date('Y-m-d H:i', $value['update_time']);
		}

		$title = array('会员ID', '昵称', '姓名', '手机号码', '讲师名称', '有效期', '状态', '更新时间');
		$filename = '购买讲师会员'.$random.$uniacid.'-'.$i;

		$phpexcel = new FyLessonv2PHPExcel();
		$savetype = $max>1 ? 1 : 0;
		$phpexcel->exportTable($title, $arr, $filename, $savetype);
		unset($arr);

		$filenameArr[] = $filename.'-'.date('Ymd').'.xls';
	}

	/* 打包下载 */
	$filepath = '../data/excel/';
	$pack = $filepath.'购买讲师会员'.$random.$uniacid.'-'.date('Ymd').'.zip';
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
	header('Content-disposition:attachment;filename=购买讲师会员'.$random.$uniacid.'-'.date('Ymd').'.zip');
	$filesize = filesize($pack);
	readfile($pack);
	header('Content-length:'.$filesize);

	$files = glob($filepath.'*');
	foreach($files as $file) {
		if(strstr($file, "购买讲师会员{$random}{$uniacid}-")){
			unlink($file);
		}
	}
}