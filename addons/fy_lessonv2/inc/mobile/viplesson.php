<?php
/**
 * VIP等级对应课程
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
if((!$userAgent) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}

$level_id = intval($_GPC['level_id']);
$level = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$level_id));
if(empty($level)){
	message("该VIP等级不存在", "", "warning");
}


$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid=:uniacid AND vipview LIKE :vipview AND (status=:status1 OR status=:status2) AND lesson_type!=:lesson_type";
$params[':uniacid'] = $uniacid;
$params[':vipview'] = '%"'.$level_id.'"%';
$params[':status1'] = -1;
$params[':status2'] = 1;
$params[':lesson_type'] = 2;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} ORDER BY displayorder DESC,id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);

if($op=='display'){
	$title = '【'.$level['level_name'].'】课程列表';

}elseif($op=='ajaxgetlist'){
	foreach($list as $k=>$v){
		if($v['price']>0){
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
		}else{
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'] + $v['visit_number'];
		}
		$v['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE  parentid=:parentid", array(':parentid'=>$v['id']));
		if($v['score']>0){
			$v['score_rate'] = $v['score']*100;
		}else{
			$v['score_rate'] = "";
		}

		if($v['lesson_type']==1){
			$buynow_info = json_decode($v['buynow_info'], true);
			if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
				$v['ico_name'] = 'ico-appointed';
			}

			/* 重新计算报名课程学习人数 */
			$v['buyTotal'] = $v['buynum']+$v['virtual_buynum'];
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

		$list[$k] = $v;
	}

	$this->resultJson($list);

}


include $this->template("../mobile/{$template}/viplesson");