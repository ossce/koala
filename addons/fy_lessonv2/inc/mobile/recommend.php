<?php
/**
 * 推荐板块课程列表
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if((!$userAgent) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}


$pindex = max(1, intval($_GPC['page']));
$psize = 10;

if ($op == 'display') {
	$recid = intval($_GPC['recid']);
	$recommend = pdo_fetch("SELECT * FROM " .tablename($this->table_recommend). " WHERE id=:id AND is_show=:is_show", array(':id'=>$recid, ':is_show'=>1));
	if(empty($recommend)){
		message("该推荐版块不存在", "", "warning");
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND status=1 AND (recommendid='{$recid}' OR (recommendid LIKE '{$recid},%') OR (recommendid LIKE '%,{$recid}') OR (recommendid LIKE '%,{$recid},%')) ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach ($list as $k => $v) {
		$v['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_lesson_son) . " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$v['id'],':status'=>1));
		if($v['price']>0){
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
		}else{
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'] + $v['visit_number'];
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
		}

		$list[$k] = $v;
	}
}

if(!$_W['isajax']){
	include $this->template("../mobile/{$template}/recommend");
}else{
	echo json_encode($list);
}

?>