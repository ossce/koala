<?php 

$uid = intval($_GPC['uid']);
$member = pdo_get($this->table_member, array('uniacid'=>$uniacid,'uid'=>$uid));

if(empty($member)){
	message("会员不存在");
}

//今日学习时长
$today = pdo_get($this->table_study_duration, array('uid'=>$uid,'date'=>date('Ymd', strtotime('today'))));
$today['total_duration'] = $site_common->secToTime2($today['article'] + $today['audio'] + $today['video']);
$today['article_duration'] = $site_common->secToTime2($today['article']);
$today['audio_duration'] = $site_common->secToTime2($today['audio']);
$today['video_duration'] = $site_common->secToTime2($today['video']);

//昨日学习时长
$yesterday = pdo_get($this->table_study_duration, array('uid'=>$uid,'date'=>date('Ymd', strtotime('yesterday'))));
$yesterday['total_duration'] = $site_common->secToTime2($yesterday['article'] + $yesterday['audio'] + $yesterday['video']);
$yesterday['article_duration'] = $site_common->secToTime2($yesterday['article']);
$yesterday['audio_duration'] = $site_common->secToTime2($yesterday['audio']);
$yesterday['video_duration'] = $site_common->secToTime2($yesterday['video']);


$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;

$list = pdo_fetchall("SELECT *,(article+audio+video) AS day_total FROM " .tablename($this->table_study_duration). " WHERE uid=:uid AND date>=:startdate AND date<=:enddate ORDER BY date ASC", array(':uid'=>$uid, ':startdate'=>date('Ymd',$starttime), ':enddate'=>date('Ymd',$endtime)));


$day = $video = $article = $audio = $day_total = array();
$total = 0;
if (!empty($list)) {
	foreach ($list as $row) {
		$day[] = $row['date'];
		$video[]   = round($row['video']/60,1);
		$article[] = round($row['article']/60,1);
		$audio[]   = round($row['audio']/60,1);
		$day_total[] = round($row['day_total']/60,1);
		$total += $row['day_total'];
	}
}

$total_time = $site_common->secToTime2($total);