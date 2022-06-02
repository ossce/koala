<?php
/**
 * 学习记录
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if($op=='display'){
	$title = '学习记录';

	$pindex = max(1,$_GPC['page']);
	$psize = 9;

	$condition = " b.uniacid=:uniacid AND b.uid=:uid ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;

	if($_GPC['lesson_type'] != ''){
		$condition .= " AND a.lesson_type=:lesson_type ";
		$params[':lesson_type'] = $_GPC['lesson_type'];
	}
	if($_GPC['bookname']){
		$condition .= " AND a.bookname LIKE :bookname ";
		$params[':bookname'] = "%".$_GPC['bookname']."%";
	}

	$list = pdo_fetchall("SELECT a.id,a.lesson_type,a.bookname,a.images,a.price,a.ico_name,a.score,a.buynow_info,a.live_info,a.descript,b.study_process,b.addtime FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_lesson_history). " b ON a.id=b.lessonid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$play_record = pdo_fetch("SELECT * from " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid ORDER BY addtime DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$v['id']));
		if(!empty($play_record)){
			$section = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'id'=>$play_record['sectionid']), array('id','title'));
			$v['sectionid'] = $section['id'];
			$v['section_title'] = $section['title'];
			if($play_record['duration']){
				$v['progress'] = round($play_record['playtime']/$play_record['duration'],2)*100;
			}
			$v['playtime'] = gmstrftime('%H:%M:%S', $play_record['playtime']);
		}

		if($v['lesson_type']==1){
			$buynow_info = json_decode($v['buynow_info'], true);
			if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
				$v['ico_name'] = 'ico-appointed';
			}
		}elseif($v['lesson_type']==3){
			$live_info = json_decode($v['live_info'], true);
			$starttime = strtotime($live_info['starttime']);
			$endtime = strtotime($live_info['endtime']);
			if(time() < $starttime){
				$v['icon_live_status'] = 'icon-live-nostart';
			}elseif(time() > $endtime){
				$v['icon_live_status'] = 'icon-live-ended';
			}elseif(time() > $starttime && time() < $endtime){
				$v['icon_live_status'] = 'icon-live-starting';
			}
		}
		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_history). " b ON a.id=b.lessonid WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='details'){
	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid), array('bookname','lesson_type'));
	if(empty($lesson)){
		message("该课程不存在", $_W['siteroot'].$uniacid."/history.html", "warning");
	}
	if($lesson['lesson_type'] != 0){
		header("Location:".$_W['siteroot'].$uniacid."/lesson.html?id=".$lessonid);
	}

	$pindex = max(1,$_GPC['page']);
	$psize = 20;

	$condition = " a.uniacid=:uniacid AND a.lessonid=:lessonid AND a.uid=:uid ";
	$params[':uniacid'] = $uniacid;
	$params[':lessonid'] = $lessonid;
	$params[':uid'] = $uid;

	$list = pdo_fetchall("SELECT a.lessonid,a.sectionid,a.playtime,a.duration,a.playcount,a.addtime,b.title FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_son). " b ON a.sectionid=b.id WHERE {$condition} ORDER BY a.addtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $key=>$value){
		$value['progress'] = intval(($value['playtime']/$value['duration'])*100);
		$value['playtime'] = $site_common->secToTime($value['playtime']);
		$value['duration'] = $site_common->secToTime($value['duration']);

		$list[$key] = $value;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_son). " b ON a.sectionid=b.id WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);
}


include $this->template("../webapp/{$template}/history");


?>