<?php
/**
 * 讲师列表
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 讲师分类 */
$category_list = $site_common->getTeacherCategory();

/* 名师风采 */
$rec_teacher_setting = json_decode($setting_pc['rec_teacher'], true);
$title = $rec_teacher_setting['font'] ? $rec_teacher_setting['font'] : '名师风采';

/* 课程页面字体 */
$lesson_page = $common['lesson_page'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid=:uniacid AND status=:status ";
$params[':uniacid'] = $uniacid;
$params[':status']  = 1;

$keyword = trim($_GPC['keyword']);
$cate_id = intval($_GPC['cate_id']);
if($keyword){
	$condition .= " AND teacher LIKE :teacher ";
	$params[':teacher'] = "%{$keyword}%";
}
if($cate_id){
	$condition .= " AND cate_id=:cate_id ";
	$params[':cate_id'] = $cate_id;
}

$orderby = " ORDER BY displayorder DESC, id DESC";
if(trim($_GPC['sort'])=='time'){
	$orderby = " ORDER BY update_time DESC, displayorder DESC, id DESC";
}

$list = pdo_fetchall("SELECT id,teacher,teacherdes,digest,teacherphoto FROM " .tablename($this->table_teacher). " WHERE {$condition} {$orderby} LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
foreach($list as $k=>$v){
	$v['lessonlist'] = pdo_fetchall("SELECT id,lesson_type,bookname,price,images,buynum,virtual_buynum,visit_number,ico_name,vipview,live_info FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND teacherid=:teacherid AND status=:status ORDER BY displayorder DESC,update_time DESC LIMIT 0,3", array(':uniacid'=>$uniacid, ':teacherid'=>$v['id'],':status'=>1));
	foreach($v['lessonlist'] as $key=>$value){
		$value['discount'] = $webAppCommon->getLessonDiscount($value['id']);
		if($value['discount']<1){
			$value['ico_name'] = $value['ico_name'] ? $value['ico_name'] : 'ico-discount';
			$value['market_price'] = $value['price'];
		}
		$value['price'] = round($value['price']*$value['discount'], 2);

		if($value['lesson_type']==3){
			$live_info = json_decode($value['live_info'], true);
			$starttime = strtotime($live_info['starttime']);
			$endtime = strtotime($live_info['endtime']);
			if(time() < $starttime){
				$value['icon_live_status'] = 'icon-live-nostart';
			}elseif(time() > $endtime){
				$value['icon_live_status'] = 'icon-live-ended';
			}elseif(time() > $starttime && time() < $endtime){
				$value['icon_live_status'] = 'icon-live-starting';
			}
		}

		$v['lessonlist'][$key] = $value;
	}
	
	$v['teacherdes'] = $v['digest'] ? explode("\n", $v['digest']) : explode("\n", strip_tags(htmlspecialchars_decode($v['teacherdes'])));
	$v['lessonCount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE teacherid=:teacherid AND status=:status", array(':teacherid'=>$v['id'], ':status'=>1));

	$list[$k] = $v;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher) . " WHERE {$condition} ", $params);

$pager = $webAppCommon->pagination($total, $pindex, $psize);


include $this->template("../webapp/{$template}/teacherList");


?>