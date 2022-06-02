<?php
/**
 * 学习记录
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

$pindex =max(1,$_GPC['page']);
$psize = 10;


if($op=='display'){
	$title = '学习记录';

	$condition = " b.uniacid=:uniacid AND b.uid=:uid ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;

	$list = pdo_fetchall("SELECT a.id,a.lesson_type,a.bookname,a.images,a.price,a.score,a.section_status,a.ico_name,a.buynum+a.virtual_buynum+a.vip_number AS buynum,a.visit_number,a.live_info, b.study_process,b.addtime FROM " .tablename($this->table_lesson_parent). " a INNER JOIN " .tablename($this->table_lesson_history). " b ON a.id=b.lessonid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['addtime'] = date('Y-m-d H:i', $v['addtime']);
		$v['sectionid'] = $v['section_title'] = $v['progress'] = $v['playtime'] = $v['duration'] = '';

		$play_record = pdo_fetch("SELECT * from " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid ORDER BY addtime DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$v['id']));
		if(!empty($play_record)){
			$section = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'id'=>$play_record['sectionid']), array('id','title'));
			$v['sectionid'] = $section['id'];
			$v['section_title'] = $section['title'];
			if($play_record['duration']){
				$v['duration'] = $play_record['duration'];
				$v['progress'] = round($play_record['playtime']/$play_record['duration'],2)*100;
			}
			$v['playtime'] = gmstrftime('%H:%M:%S', $play_record['playtime']);
		}

		if($v['lesson_type']==0){
			$v['lessonUrl'] = $this->createMobileUrl('history',array('op'=>'details','lessonid'=>$v['id']));
		}else{
			$v['lessonUrl'] = $this->createMobileUrl('lesson',array('id'=>$v['id'],'sectionid'=>$v['sectionid']));
		}

		if($v['lesson_type']==3){
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
			$v['learned_hide'] = 'hide';
		}

		$list[$k] = $v;
	}

	if($_GPC['type']=='getlist'){
		$this->resultJson($list);
	}

}elseif($op=='details'){
	$title = "学习记录明细";

	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid), array('bookname','lesson_type'));
	if(empty($lesson)){
		message("该课程不存在", "", "warning");
	}
	if($lesson['lesson_type'] != 0){
		header("Location:".$this->createMobileUrl('lesson',array('id'=>$lessonid)));
	}

	$condition = " a.uniacid=:uniacid AND a.lessonid=:lessonid AND a.uid=:uid ";
	$params[':uniacid'] = $uniacid;
	$params[':lessonid'] = $lessonid;
	$params[':uid'] = $uid;

	$list = pdo_fetchall("SELECT a.lessonid,a.sectionid,a.playtime,a.duration,a.playcount,a.addtime,b.title FROM " .tablename($this->table_playrecord). " a INNER JOIN " .tablename($this->table_lesson_son). " b ON a.sectionid=b.id WHERE {$condition} ORDER BY a.addtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $key=>$value){
		$value['progress'] = intval(($value['playtime']/$value['duration'])*100);
		$value['progress_color'] = $value['progress'] >= 100 ? 'color-44bea3' : 'color-fc9153';
		$value['playtime'] = $site_common->secToTime($value['playtime']);
		$value['duration'] = $site_common->secToTime($value['duration']);
		$value['addtime']  = date('Y-m-d H:i:s',$value['addtime']);

		$list[$key] = $value;
	}

	if($_GPC['type']=='getlist'){
		$this->resultJson($list);
	}
}


include $this->template("../mobile/{$template}/history");