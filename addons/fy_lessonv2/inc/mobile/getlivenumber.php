<?php
/**
 * 获取学习人数作为直播人数，过渡使用
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$lessonid = $_GPC['lessonid'];

$study_number = $this->readCommonCache('fy_lesson_'.$uniacid.'_live_'.$lessonid);
if(empty($study_number)){
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid),array('buynum','virtual_buynum','vip_number','teacher_number','visit_number','live_info'));
	$live_info = json_decode($lesson['live_info'], true);

	if($lesson['price']>0){
		$study_number = $lesson['buynum']+$lesson['virtual_buynum']+$lesson['vip_number']+$lesson['teacher_number'];
	}else{
		$study_number = $lesson['buynum']+$lesson['virtual_buynum']+$lesson['vip_number']+$lesson['teacher_number']+$lesson['visit_number'];
	}
	$study_number += intval($live_info['virtual_num']);
	cache_write('fy_lesson_'.$uniacid.'_live_'.$lessonid, $study_number);
}

$this->resultJson(intval($study_number));