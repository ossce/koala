<?php
/**
 * 我的收藏
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$lesson_page = $common['lesson_page']; /* 课程页面字体 */
$type = $_GPC['type'];

$pindex =max(1,$_GPC['page']);
$psize = 9;

$condition = " b.uniacid=:uniacid AND b.uid=:uid ";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;

if($type==1){
	$title = $common['page_title']['collectLesson'] ? $common['page_title']['collectLesson'] : '收藏课程';

	$condition .= " AND b.ctype=:ctype AND (status=:status1 OR status=:status2)";
	$params[':ctype'] = 1;
	$params[':status1'] = 1;
	$params[':status2'] = -1;
	
	$list = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid=:parentid", array(':parentid'=>$v['id']));
		if($v['price']>0){
			$v['buynum_total'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
		}else{
			$v['buynum_total'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'] + $v['visit_number'];
		}

		$v['vipview'] = json_decode($v['vipview'], true);

		if($v['score']>0){
			$v['score_rate'] = $v['score']*100;
		}else{
			$v['score_rate'] = '';
		}
		$v['descript'] = strip_tags(html_entity_decode($v['descript']));

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
		}
		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($type==2){
	$title = $common['page_title']['collectTeacher'] ? $common['page_title']['collectTeacher'] : '关注讲师';

	$psize = 12;
	$condition .= " AND b.ctype=:ctype ";
	$params[':ctype'] = 2;
	
	$list = pdo_fetchall("SELECT a.id,a.teacher,a.teacherdes,a.teacherphoto FROM " . tablename($this->table_teacher) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['teacherdes'] = strip_tags(htmlspecialchars_decode($v['teacherdes']));
		$v['lessonCount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE teacherid=:teacherid AND status=:status", array(':teacherid'=>$v['id'], ':status'=>1));

		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);
}



include $this->template("../webapp/{$template}/collect");



?>