<?php

$lessonid = intval($_GPC['lessonid']);
$uid = intval($_GPC['uid']);
$live_type = intval($_GPC['live_type']);
$export = intval($_GPC['export']);

$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if($lessonid){
	$condition .= " AND a.lessonid=:lessonid ";
	$params[':lessonid'] = $lessonid;
}
if($uid){
	$condition .= " AND a.uid=:uid ";
	$params[':uid'] = $uid;
}
if(strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
	
	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}


if(!$export){
	if(!$live_type){
		$list = pdo_fetchall("SELECT a.*,b.bookname,c.title FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id INNER JOIN " .tablename($this->table_lesson_son). " c ON a.sectionid=c.id WHERE {$condition} ORDER BY a.addtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id INNER JOIN " .tablename($this->table_lesson_son). " c ON a.sectionid=c.id WHERE {$condition}", $params);
	}else{
		$list = pdo_fetchall("SELECT a.*,b.bookname FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} AND b.lesson_type=3 ORDER BY a.addtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} AND b.lesson_type=3", $params);
	}
	
	foreach($list as $k=>$v){
		$v['user'] = pdo_fetch("SELECT nickname,realname,mobile FROM " .tablename('mc_members'). " WHERE uid=:uid", array(':uid'=>$v['uid']));
		$v['play_length'] = $site_common->secToTime($v['playtime']);
		$v['progress'] = intval(($v['playtime']/$v['duration'])*100);
		$list[$k] = $v;		
	}

	$pager = pagination($total, $pindex, $psize);

}else{

	if(!$lessonid){
		message("请选择要导出的课程");
	}
	
	$parent = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid), array('bookname'));
	$son_list = pdo_getall($this->table_lesson_son, array('uniacid'=>$uniacid,'parentid'=>$lessonid), array('id','title'));
	
	$son_arr = array();
	foreach($son_list as $v){
		$son_arr[$v['id']] = $v['title'];
	}

	set_time_limit(0);

	if(!$live_type){
		$outputlist = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid INNER JOIN " .tablename($this->table_lesson_son). " c ON a.sectionid=c.id WHERE {$condition} ", $params);
	}else{
		$outputlist = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ", $params);
	}

	$j = 0;
	foreach ($outputlist as $v) {
		$arr[$j]['bookname']	= $parent['bookname'];
		$arr[$j]['title']		= $son_arr[$v['sectionid']];
		$arr[$j]['playtime']	= $site_common->secToTime($v['playtime']);
		$arr[$j]['playcount']	= $v['playcount'];
		$arr[$j]['uid']			= $v['uid'];
		$arr[$j]['nickname']	= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$v['nickname']);
		$arr[$j]['realname']	= $v['realname'];
		$arr[$j]['mobile']		= $v['mobile'];
		$arr[$j]['addtime']		= date('Y-m-d H:i:s', $v['addtime']);
		$arr[$j]['endtime']		= date('Y-m-d H:i:s', $v['addtime']+$v['playtime']);
		$arr[$j]['progress']	= intval(($v['playtime']/$v['duration'])*100);
		$j++;
	}

	$title =  array('课程名称','章节标题','最长学习时间','学习次数','用户uid','昵称','姓名','手机号码','开始学习时间','结束学习时间','学习进度(%)');
	$site_common->exportCSV($arr, $title, $fileName="最近学习记录");
}

