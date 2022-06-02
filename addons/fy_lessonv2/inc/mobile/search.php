<?php
/**
 * 微课学堂搜索页
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if((!$userAgent && in_array('search', $login_visit)) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}

$systemType = $site_common->checkSystenType();  /* 手机操作系统 */
$site_common->check_black_list('visit', $_W['member']['uid']);


/* 全部分类图标 */
$all_category_ico = $setting['category_ico'] ? $_W['attachurl'].$setting['category_ico'] : MODULE_URL."static/mobile/{$template}/images/ico-allcategory.png";

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

/* 全部分类 */
$hot_category = pdo_fetchall("SELECT * FROM " . tablename($this -> table_category) . " WHERE uniacid=:uniacid AND is_hot=:is_hot AND search_show=:search_show ORDER BY displayorder DESC", array(':uniacid' => $uniacid, ':is_hot' => 1,':search_show'=>1));
$categorylist = pdo_fetchall("SELECT * FROM " . tablename($this -> table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid AND search_show=:search_show ORDER BY displayorder DESC", array(':uniacid'=>$uniacid, ':parentid'=>0,':search_show'=>1));

foreach ($categorylist as $k => $v) {
	$categorylist[$k]['child'] = pdo_fetchall("SELECT * FROM " . tablename($this -> table_category) . " WHERE uniacid=:uniacid AND parentid=:parentid AND search_show=:search_show ORDER BY displayorder DESC", array(':uniacid'=>$uniacid, ':parentid'=>$v['id'],':search_show'=>1));
}

if ($op == 'display') {
	$keyword = trim($_GPC['keyword']);
	$pid = $_GPC['pid'];
	$cat_id = $_GPC['cat_id'];
	$sort = trim($_GPC['sort']);
	$attr1 = $_GPC['attr1'];
	$attr2 = $_GPC['attr2'];

	$condition = " a.uniacid = '{$uniacid}' AND a.status=1 ";
	$order = " ORDER BY a.displayorder DESC, a.id DESC ";

	if (!empty($keyword)) {
		$condition .= " AND (a.bookname LIKE '%{$keyword}%' OR b.teacher LIKE '%{$keyword}%') ";
	}
	if ($cat_id > 0) {
		$condition .= " AND (a.pid='{$cat_id}' OR a.cid='{$cat_id}')";
		$nowcat = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$cat_id), array('name','link','attribute1','attribute2'));

		if(!empty($nowcat['link'])){
			header("Location:".$nowcat['link']);
		}

		$catname = $nowcat['name'];
		$cat_attribute1 = json_decode($nowcat['attribute1'], true);
		$cat_attribute2 = json_decode($nowcat['attribute2'], true);
	} else {
		$catname = $common['page_title']['search'] ? $common['page_title']['search'] : '全部课程';
	}

	/* 综合排序 */
	if ($sort == 'general') {
		$condition .= " AND a.lesson_type=0";
		$sortname = '普通课程';
	} elseif ($sort == 'apply') {
		$condition .= " AND a.lesson_type=1";
		$sortname = '报名课程';
	} elseif ($sort == 'live') {
		$condition .= " AND a.lesson_type=3";
		$sortname = '直播课程';
	} elseif ($sort == 'price') {
		$order = " ORDER BY a.price ASC, a.displayorder DESC ";
		$sortname = '价格优先';
	} elseif ($sort == 'price') {
		$order = " ORDER BY a.price ASC, a.displayorder DESC ";
		$sortname = '价格优先';
	} elseif ($sort == 'hot') {
		$order = " ORDER BY (a.buynum+a.virtual_buynum+a.vip_number+a.teacher_number+a.visit_number) DESC, a.displayorder DESC ";
		$sortname = '人气优先';
	} elseif ($sort == 'score') {
		$order = " ORDER BY a.score DESC, a.displayorder DESC ";
		$sortname = '好评优先';
	} else {
		$sortname = '综合排序';
	}

	/* 课程属性 */
	$lesson_attribute = $common['lesson_attribute'];
	$attribute1 = $site_common->getLessonAttribute($type=1);
	$attribute2 = $site_common->getLessonAttribute($type=2);
	if($attr1){
		$condition .= " AND attribute1={$attr1}";
		$now_attr1 = pdo_get($this->table_attribute, array('uniacid'=>$uniacid,'id'=>$attr1), array('name'));
		$attr1_name = $now_attr1['name'];
	}else{
		$attr1_name = $lesson_attribute['attribute1'];
	}
	if($attr2){
		$condition .= " AND attribute2={$attr2}";
		$now_attr2 = pdo_get($this->table_attribute, array('uniacid'=>$uniacid,'id'=>$attr2), array('name'));
		$attr2_name = $now_attr2['name'];
	}else{
		$attr2_name = $lesson_attribute['attribute2'];
	}

	$list = pdo_fetchall("SELECT a.* FROM " . tablename($this -> table_lesson_parent) . " a LEFT JOIN " . tablename($this -> table_teacher) . " b ON a.teacherid=b.id WHERE {$condition} {$order} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach ($list as $k=>$v) {
		$v['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_lesson_son) . " WHERE parentid=:parentid", array(':parentid'=>$v['id']));
		if($v['price']>0){
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
		}else{
			$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['visit_number'] + $v['teacher_number'] + $v['vip_number'];
		}

		if($setting['lesson_vip_status']==1){
			$v['vipview'] = json_decode($v['vipview'], true);
			$v['show_vip'] = $v['vipview'] ? 1 : 0;
		}

		$v['score_rate'] = $v['score'] ? $v['score']*100 : '';
		$v['score_rate'] = $v['score_rate']>100 ? 100 : $v['score_rate'];
		
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
		}else{
			
		}

		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ");

	if (!empty($keyword)) {
		$title = $keyword;
	} else{
		$title = $catname;
	}

	if($_W['isajax']){
		$this->resultJson($list);
	}

}elseif ($op == 'allcategory') {
	$title = "全部分类";
}

include $this->template("../mobile/{$template}/search");