<?php
/**
 * 全部课程
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$login_visit = json_decode($setting_pc['login_visit'], true);
if(in_array('search', $login_visit) && empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
	header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
}

/* 访问权限 */
$site_common->check_black_list('visit', $uid);

/* 自定义字体 */
$index_page = $common['index_page'];
$lesson_page = $common['lesson_page'];

$keyword	   = trim($_GPC['keyword']);
$pid		   = trim($_GPC['pid']);
$cid		   = trim($_GPC['cid']);
$lesson_type   = trim($_GPC['lesson_type']);
$lesson_nature = trim($_GPC['lesson_nature']);
$sort		   = trim($_GPC['sort']);
$attr1		   = trim($_GPC['attr1']);
$attr2		   = trim($_GPC['attr2']);

$title = $keyword ? $keyword."_全部课程" : "全部课程";

if($pid){
	$child = pdo_fetchall("SELECT * FROM " .tablename($this->table_category). " WHERE uniacid=:uniacid AND parentid=:parentid ORDER BY displayorder DESC, id ASC", array(':uniacid'=>$uniacid, ':parentid'=>$pid));
}

$condition = " a.uniacid=:uniacid AND a.status=:status";
$params = array(
	':uniacid' => $uniacid,
	':status'  => 1,
);

if ($keyword) {
	$condition .= " AND a.bookname LIKE :keyword";
	$params[':keyword'] = "%{$keyword}%";
}
if($pid){
	$condition .= " AND a.pid=:pid";
	$params[':pid'] = $pid;

	$now_parent_cat = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$pid), array('name','link_pc','attribute1','attribute2'));
	$cat_attribute1 = json_decode($now_parent_cat['attribute1'], true);
	$cat_attribute2 = json_decode($now_parent_cat['attribute2'], true);
}
if($cid){
	$condition .= " AND a.cid=:cid";
	$params[':cid'] = $cid;

	$now_son_cat = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$cid), array('name','link_pc','attribute1','attribute2'));
	$cat_attribute1 = json_decode($now_son_cat['attribute1'], true);
	$cat_attribute2 = json_decode($now_son_cat['attribute2'], true);
}

if($cid && !empty($now_son_cat['link_pc'])){
	header("Location:".$now_son_cat['link_pc']);
}elseif($pid && !empty($now_parent_cat['link_pc'])){
	header("Location:".$now_parent_cat['link_pc']);
}

if($lesson_type!=''){
	$condition .= " AND a.lesson_type=:lesson_type";
	$params[':lesson_type'] = $lesson_type;
}

/* 课程性质 */
if($lesson_nature){
	if($lesson_nature==1){
		$condition .= " AND a.price=:price";
	}elseif($lesson_nature==2){
		$condition .= " AND a.price>:price";
	}
	$params[':price'] = 0;
}

/* 排序 */
$order = " ORDER BY a.displayorder DESC, a.id DESC ";
if ($sort == 'hot'){
	$order = " ORDER BY (a.buynum+a.virtual_buynum) DESC, a.displayorder DESC ";
}elseif($sort == 'price'){
	$order = " ORDER BY a.price ASC, a.displayorder DESC ";
}elseif($sort == 'score'){
	$order = " ORDER BY a.score DESC, a.displayorder DESC ";
}

/* 课程属性 */
$lesson_attribute = $common['lesson_attribute'];
$attribute1 = $site_common->getLessonAttribute($type=1);
$attribute2 = $site_common->getLessonAttribute($type=2);
if($attr1){
	$condition .= " AND attribute1=:attribute1";
	$params[':attribute1'] = $attr1;

	$now_attr1 = pdo_get($this->table_attribute, array('uniacid'=>$uniacid,'id'=>$attr1), array('name'));
	$attr1_name = $now_attr1['name'];
}else{
	$attr1_name = $lesson_attribute['attribute1'];
}
if($attr2){
	$condition .= " AND attribute2=:attribute2";
	$params[':attribute2'] = $attr2;

	$now_attr2 = pdo_get($this->table_attribute, array('uniacid'=>$uniacid,'id'=>$attr2), array('name'));
	$attr2_name = $now_attr2['name'];
}else{
	$attr2_name = $lesson_attribute['attribute2'];
}


$pindex = max(1, intval($_GPC['page']));
$psize = 16;

$list = pdo_fetchall("SELECT a.* FROM " . tablename($this -> table_lesson_parent) . " a LEFT JOIN " . tablename($this -> table_teacher) . " b ON a.teacherid=b.id WHERE {$condition} {$order} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach ($list as $k=>$v) {
	$v['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_lesson_son) . " WHERE parentid=:parentid", array(':parentid'=>$v['id']));
	
	$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
	if($v['price']==0){
		$v['buyTotal'] += $v['visit_number'];
	}

	$v['vipview'] = json_decode($v['vipview'], true);

	if($v['score']>0){
		$v['score_rate'] = $v['score']*100;
	}else{
		$v['score_rate'] = '';
	}

	$v['discount'] = $webAppCommon->getLessonDiscount($v['id']);
	if($v['discount']<1){
		$v['ico_name'] = $v['ico_name'] ? $v['ico_name'] : 'ico-discount';
		$v['market_price'] = $v['price'];
	}
	$v['price'] = round($v['price']*$v['discount'], 2);

	$v['descript'] = strip_tags(html_entity_decode($v['descript']));

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
	}
	$list[$k] = $v;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ", $params);

$pager = $webAppCommon->pagination($total, $pindex, $psize);



include $this->template("../webapp/{$template}/search");


?>