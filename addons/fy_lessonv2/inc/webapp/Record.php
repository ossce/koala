<?php
/**
 * 记录播放章节
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if(!$_W['isajax']){
	$this->resultJson('error');
}

$lessonid    = intval($_GPC['lessonid']);
$sectionid   = intval($_GPC['sectionid']);
$playtoken	 = $_GPC['playtoken'];

if(!$uid){
	$this->resultJson('Uid is null');
}

$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid), array('lesson_type','live_info'));
if(empty($lesson)){
	$this->resultJson('The lesson does not exist');
}

if($lesson['lesson_type'] != 3){
	$section = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'id'=>$sectionid), array('sectiontype'));
	if(empty($section)){
		$this->resultJson('The section does not exist');
	}
}

if($op=='display'){
	$currentTime = intval($_GPC['currentTime']);
	$duration = intval($_GPC['duration']);

	if(empty($currentTime)){
		$this->resultJson('CurrentTime is null');
	}
	
	$data = array(
		'uniacid'	 => $uniacid,
		'uid'		 => $uid,
		'lessonid'   => $lessonid,
		'sectionid'  => $sectionid,
		'duration'	 => $duration,
		'playtoken'	 => $playtoken,
		'addtime'	 => time(),
	);

	if($setting['repeat_record_lesson']){
		$record = pdo_fetch("SELECT * FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND sectionid=:sectionid AND playtoken=:playtoken ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$lessonid,':sectionid'=>$sectionid,':playtoken'=>$playtoken));
		/* 完成章节学习标记 */
		if(!$record['is_end']){
			$data['is_end'] = $currentTime==$duration ? 1 : 0;
		}

		/* 重复记录多条学习记录 */
		$data['playtime'] = $currentTime;

		if(!empty($record)){
			unset($data['addtime']);
			$r = pdo_update($this->table_playrecord, $data, array('uniacid'=>$uniacid,'id'=>$record['id']));
		}else{
			$r = pdo_insert($this->table_playrecord, $data);
		}
	}else{
		$record = pdo_fetch("SELECT * FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND sectionid=:sectionid ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$lessonid,':sectionid'=>$sectionid));
		/* 完成章节学习标记 */
		if(!$record['is_end']){
			$data['is_end'] = $currentTime==$duration ? 1 : 0;
		}

		if($lesson['lesson_type'] == 3){
			$live_space_time = 60; /* 直播记录时间间隔 */

			/* 系统未开启重复记录学习记录，直播课程的开播和结束时间生成MD5的token一致时，累计播放时间，否则生成新的直播记录 */
			$live_info = json_decode($lesson['live_info'], true);
			echo $data['playtoken'] = md5(strtotime($live_info['starttime']).strtotime($live_info['endtime']));

			$live_record = pdo_fetch("SELECT * FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND playtoken=:playtoken ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$lessonid,':playtoken'=>$data['playtoken']));
			if($data['playtoken'] == $live_record['playtoken']){
				unset($data['addtime']);
				$data['playtime +='] = $live_space_time;
				$r1 = pdo_update($this->table_playrecord, $data, array('uniacid'=>$uniacid,'id'=>$record['id']));
			}else{
				$data['playtime'] = $live_space_time;
				$r1 = pdo_insert($this->table_playrecord, $data);
			}
			if($r1){
				$this->resultJson('Live Recode success');
			}else{
				$this->resultJson('Live Recode error');
			}
		}else{
			if($currentTime >= $record['playtime']){
				$data['playtime'] = $currentTime;
			}
			
			/* 非图文章节(视频或音频)，播放时间大于媒体总时间，则播放时间为总媒体时间 */
			if($section['sectiontype'] !=2 && $record['playtime'] > $duration){
				$data['playtime'] = $duration;
			}
		}

		if(empty($record)){
			$r = pdo_insert($this->table_playrecord, $data);
		}else{
			if($lesson['lesson_type']!=3 && $playtoken != $record['playtoken']){
				$data['playcount +='] = 1;
			}
			$r = pdo_update($this->table_playrecord, $data, array('uniacid'=>$uniacid,'id'=>$record['id']));
		}
	}

	if($r){
		if($lesson['lesson_type']!=3){
			$section_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND parentid=:parentid AND status=:status ", array(':uniacid'=>$uniacid,':parentid'=>$lessonid,':status'=>1));
			$study_list = pdo_fetchall("SELECT COUNT(DISTINCT(sectionid)) AS total FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND is_end=:is_end", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$lessonid,':is_end'=>1));
			$study_count = $study_list[0]['total'];
			
			$study_process = intval(($study_count/$section_count)*100) > 100 ? 100 : intval(($study_count/$section_count)*100);
			pdo_update($this->table_lesson_history, array('study_process'=>$study_process), array('uniacid'=>$uniacid,'uid'=>$uid,'lessonid'=>$lessonid));
		}
		$this->resultJson('Recode success');
	}else{
		$this->resultJson('Recode error');
	}

}elseif($op=='realPlay'){
	$realPlayTime = intval($_GPC['realPlayTime']) > 60 ? 60 : intval($_GPC['realPlayTime']);

	$studyLog = pdo_get($this->table_study_duration, array('uniacid'=>$uniacid,'uid'=>$uid,'date'=>date('Ymd',time())));
	$study_data = array(
		'uniacid'	 => $uniacid,
		'uid'		 => $uid,
		'date'		 => date('Ymd',time()),
	);

	if($section['sectiontype']==1 || $lesson['lesson_type'] == 3){
		/* 视频章节或直播课程 */
		if($studyLog['video'] > 43200){
			$this->resultJson('Today video real play time overflow');
		}

		$member_duration['video_duration +='] = $realPlayTime;
		if(empty($studyLog)){
			$study_data['video'] = $realPlayTime;
		}else{
			$study_data['video +='] = $realPlayTime;
		}
		
	}elseif($section['sectiontype']==2){
		/* 图文章节 */
		if($studyLog['article'] > 43200){
			$this->resultJson('Today video real play time overflow');
		}

		$member_duration['article_duration +='] = $realPlayTime;
		if(empty($studyLog)){
			$study_data['article'] = $realPlayTime;
		}else{
			$study_data['article +='] = $realPlayTime;
		}

	}elseif($section['sectiontype']==3){
		/* 音频章节 */
		if($studyLog['audio'] > 43200){
			$this->resultJson('Today video real play time overflow');
		}

		$member_duration['audio_duration +='] = $realPlayTime;
		if(empty($studyLog)){
			$study_data['audio'] = $realPlayTime;
		}else{
			$study_data['audio +='] = $realPlayTime;
		}
	}
	$member_duration['duration_uptime'] = time();

	pdo_update($this->table_member, $member_duration, array('uid'=>$uid));
	if(empty($studyLog)){
		$r = pdo_insert($this->table_study_duration, $study_data);
	}else{
		$r = pdo_update($this->table_study_duration, $study_data, array('study_id'=>$studyLog['study_id']));
	}

	if($r){
		$this->resultJson('Real play time success');
	}else{
		$this->resultJson('Real play time error');
	}
}


?>