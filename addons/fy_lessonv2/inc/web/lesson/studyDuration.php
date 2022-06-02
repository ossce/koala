<?php 

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$keyword  = trim($_GPC['keyword']);
$uid1     = intval($_GPC['uid1']);
$uid2     = intval($_GPC['uid2']);
$orderby  = intval($_GPC['orderby']);

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
if($keyword!=''){
	$condition .= " AND (b.nickname LIKE :keyword OR b.realname LIKE :keyword OR b.mobile LIKE :keyword) ";
	$params[':keyword'] = "%".$keyword."%";
}

if ($uid1 && $uid2) {
	$condition .= " AND a.uid>=:uid1 AND a.uid<=:uid2";
	$params[':uid1'] = $uid1;
	$params[':uid2'] = $uid2;
}elseif ($uid1 && !$uid2) {
	$condition .= " AND a.uid=:uid1";
	$params[':uid1'] = $uid1;
}elseif (!$uid1 && $uid2) {
	$condition .= " AND a.uid=:uid2";
	$params[':uid2'] = $uid2;
}

if($orderby==1){
	$order = " ORDER BY a.video_duration DESC ";
}elseif($orderby==2){
	$order = " ORDER BY a.article_duration DESC ";
}elseif($orderby==3){
	$order = " ORDER BY a.audio_duration DESC ";
}elseif($orderby==4){
	$order = " ORDER BY a.total_duration DESC ";
}else{
	$order = " ORDER BY a.duration_uptime DESC ";
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " a LEFT JOIN " . tablename($this->table_mc_members) . " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.uid, a.article_duration,a.audio_duration,a.video_duration,(a.article_duration+a.audio_duration+a.video_duration) AS total_duration,b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} {$order},uid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$list[$k]['article_duration'] = $site_common->secToTime($v['article_duration']);
		$list[$k]['audio_duration'] = $site_common->secToTime($v['audio_duration']);
		$list[$k]['video_duration'] = $site_common->secToTime($v['video_duration']);
		$list[$k]['total_duration'] = $site_common->secToTime($v['total_duration']);
	}

	$pager = pagination($total, $pindex, $psize);

}else{
	
	set_time_limit(0);

	$outputlist = pdo_fetchall("SELECT a.uid, a.article_duration,a.audio_duration,a.video_duration,(a.article_duration+a.audio_duration+a.video_duration) AS total_duration,b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ", $params);

	$j = 0;
	foreach ($outputlist as $value) {
		$arr[$j]['uid']				 = $value['uid'];
		$arr[$j]['nickname']		 = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$j]['realname']		 = $value['realname'];
		$arr[$j]['mobile']			 = $value['mobile'];
		$arr[$j]['video_duration']	 = $site_common->secToTime($value['video_duration']);
		$arr[$j]['article_duration'] = $site_common->secToTime($value['article_duration']);
		$arr[$j]['audio_duration']	 = $site_common->secToTime($value['audio_duration']);
		$arr[$j]['total_duration']	 = $site_common->secToTime($value['total_duration']);
		$j++;
	}

	$title =  array('uid','昵称','姓名','手机号码','视频时长','图文时长','音频时长','总时长');
	$site_common->exportCSV($arr, $title, $fileName="学习时长汇总");
}