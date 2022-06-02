<?php
/**
 * 收藏课程/讲师
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

$ctype = intval($_GPC['ctype']); /* 收藏类型 */
$uid = $_W['member']['uid'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " b.uniacid=:uniacid AND b.uid=:uid ";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;

if($ctype==1){
	$title = $common['page_title']['collectLesson'] ? $common['page_title']['collectLesson'] : '收藏课程';
	$condition .= "  AND b.ctype=:ctype AND (a.status=:status1 OR a.status=:status2) ";
	$params[':ctype'] = 1;
	$params[':status1'] = -1;
	$params[':status2'] = 1;
	
	$lessonlist = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($lessonlist as $k=>$v){
		$v['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid=:parentid", array(':parentid'=>$v['id']));
		if($v['price']>0){
			$v['buynum_total'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
		}else{
			$v['buynum_total'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'] + $v['visit_number'];
		}
		if($v['score']>0){
			$v['score_rate'] = $v['score']*100;
		}else{
			$v['score_rate'] = "";
		}

		if($setting['lesson_vip_status']==1){
			$v['vipview'] = json_decode($v['vipview'], true);
			$v['show_vip'] = $v['vipview'] ? 1 : 0;
		}

		$v['discount'] = $site_common->getLessonDiscount($v['id']);
		$v['price'] = round($v['price']*$v['discount'], 2);
		if($v['discount']<1 && !$v['ico_name']){
			$v['ico_name'] = 'ico-discount';
		}

		if($v['lesson_type']==1){
			$buynow_info = json_decode($v['buynow_info'], true);
			if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
				$v['ico_name'] = 'ico-appointed';
			}

			/* 重新计算报名课程学习人数 */
			$v['buynum_total'] = $v['buynum']+$v['virtual_buynum'];
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
			$v['learned_hide'] = 'hide';
			unset($v['soncount']);
		}

		if($v['lesson_type'] == 0){
			$v['study_word'] = $common['index_page']['videoLessonNum'] ? $common['index_page']['videoLessonNum'] : '人已学习';
		}elseif($v['lesson_type'] == 1){
			$v['study_word'] = $common['index_page']['appointLessonNum'] ? $common['index_page']['appointLessonNum'] : '人已报名';
		}else{
			
		}

		$lessonlist[$k] = $v;
	}

}elseif($ctype==2){
	$title = $common['page_title']['collectTeacher'] ? $common['page_title']['collectTeacher'] : '关注讲师';
	$condition .= "  AND b.ctype=:ctype ";
	$params[':ctype'] = 2;
	
	$teacherlist = pdo_fetchall("SELECT a.id,a.teacher,a.teacherdes,a.teacherphoto FROM " . tablename($this->table_teacher) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($teacherlist as $k=>$v){
		$v['teacherdes'] = strip_tags(htmlspecialchars_decode($v['teacherdes']));
		$v['lessonCount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE teacherid=:teacherid AND status=:status", array(':teacherid'=>$v['id'], ':status'=>1));

		$teacherlist[$k] = $v;
	}
}

if($op=='ajaxgetlesson'){
	$this->resultJson($lessonlist);

}elseif($op=='ajaxgetteacher'){
	$this->resultJson($teacherlist);

}else{
	include $this->template("../mobile/{$template}/collect");

}
