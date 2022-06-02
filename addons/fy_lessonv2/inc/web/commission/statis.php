<?php

$keyword = trim($_GPC['keyword']);
$mobile	  = trim($_GPC['mobile']);
$ranking  = intval($_GPC['ranking']);

$condition = " a.uniacid='{$uniacid}' ";
if(!empty($keyword)){
	$condition .= " AND (b.nickname LIKE :keyword OR b.realname LIKE :keyword) ";
	$params[':keyword'] = "%".$keyword."%";
}

if(!empty($mobile)){
	$condition .= " AND b.mobile LIKE :mobile ";
	$params[':mobile'] = "%".$mobile."%";
}

if(empty($ranking) || $ranking==1){
	$ORDER = " ORDER BY total_commission DESC ";
}elseif($ranking==2){
	$ORDER = " ORDER BY pay_commission DESC ";
}elseif($ranking==3){
	$ORDER = " ORDER BY nopay_commission DESC ";
}

if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.nopay_commission,a.pay_commission,a.nopay_commission+a.pay_commission AS total_commission,a.addtime,b.uid,b.nickname,b.realname,b.mobile FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} {$ORDER},uid ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);

	$nopay_total = pdo_fetchcolumn("SELECT SUM(a.nopay_commission) FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}", $params);
	$pay_total = pdo_fetchcolumn("SELECT SUM(a.pay_commission) FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}", $params);

}else{
	set_time_limit(180);
	$psize = 10000;
	$max = ceil($total/$psize);
	$random = random(4);

	for($i=1; $i<=$max; $i++){
		$lists = pdo_fetchall("SELECT a.nopay_commission,a.pay_commission,a.nopay_commission+a.pay_commission AS total_commission,a.addtime,b.uid,b.nickname,b.realname,b.mobile FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} {$ORDER},uid ASC LIMIT " . ($i - 1) * $psize . ',' . $psize, $params);

		foreach ($lists as $key => $value) {
			$arr[$key]['uid']			   = $value['uid'];
			$arr[$key]['nickname']         = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
			$arr[$key]['realname']         = $value['realname'];
			$arr[$key]['mobile']		   = $value['mobile'];
			$arr[$key]['pay_commission']   = $value['pay_commission'];
			$arr[$key]['nopay_commission'] = $value['nopay_commission'];
			$arr[$key]['total_commission'] = $value['total_commission'];
			$arr[$key]['addtime']		   = date('Y-m-d H:i:s', $value['addtime']);
		}

		$title =  array('会员ID', '昵称', '姓名', '手机号码', '已申请佣金(元)', '待申请佣金(元)', '累计佣金(元)', '注册时间');
		$filename = '分销佣金统计'.$random.$uniacid.'-'.$i;

		$phpexcel = new FyLessonv2PHPExcel();
		$savetype = $max>1 ? 1 : 0;
		$phpexcel->exportTable($title, $arr, $filename, $savetype);
		unset($arr);

		$filenameArr[] = $filename.'-'.date('Ymd').'.xls';
	}

	/* 打包下载 */
	$filepath = '../data/excel/';
	$pack = $filepath.'分销佣金统计'.$random.$uniacid.'-'.date('Ymd').'.zip';
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
	header('Content-disposition:attachment;filename=分销佣金统计'.$random.$uniacid.'-'.date('Ymd').'.zip');
	$filesize = filesize($pack);
	readfile($pack);
	header('Content-length:'.$filesize);

	$files = glob($filepath.'*');
	foreach($files as $file) {
		if(strstr($file, "分销佣金统计{$random}{$uniacid}-")){
			unlink($file);
		}
	}
}

?>