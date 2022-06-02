<?php
/**
 * 优惠券指定课程
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if($op=='display'){
	$title = "优惠券可用课程";
	$already_study = $common['index_page']['studyNum'] ? $common['index_page']['studyNum'] : '人已学习';

	$member_coupon_id = intval($_GPC['member_coupon_id']);
	$coupon_id = intval($_GPC['coupon_id']);

	if($member_coupon_id){
		$coupon = pdo_get($this->table_member_coupon, array('uniacid'=>$uniacid, 'id'=>$member_coupon_id));
	}elseif($coupon_id){
		$coupon = pdo_get($this->table_mcoupon, array('uniacid'=>$uniacid, 'id'=>$coupon_id));
	}
	
	if(empty($coupon)){
		message("该优惠券不存在", "", "warning");
	}

	if($coupon['use_type']!=2){
		message("该优惠券不属于指定课程可用", "", "warning");
	}

	$lesson_ids = json_decode($coupon['lesson_ids'], true);
	$list = array();
	foreach($lesson_ids as $item){
		$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$item,'status'=>1));
		if(empty($lesson)){
			continue;
		}

		$lesson['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_lesson_son) . " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$item,':status'=>1));

		if($lesson['price']>0){
			$lesson['buyTotal'] = $lesson['buynum'] + $lesson['virtual_buynum'] + $lesson['vip_number'] + $lesson['teacher_number'];
		}else{
			$lesson['buyTotal'] = $lesson['buynum'] + $lesson['virtual_buynum'] + $lesson['vip_number'] + $lesson['teacher_number'] + $lesson['visit_number'];
		}
		if($lesson['score']>0){
			$lesson['score_rate'] = $lesson['score']*100;
		}else{
			$lesson['score_rate'] = "";
		}

		$lesson['discount'] = $site_common->getLessonDiscount($lesson['id']);
		$lesson['price'] = round($lesson['price']*$lesson['discount'], 2);
		if($lesson['discount']<1 && !$lesson['ico_name']){
			$lesson['ico_name'] = 'ico-discount';
		}

		if($lesson['lesson_type']==3){
			$live_info = json_decode($lesson['live_info'], true);
			$starttime = strtotime($live_info['starttime']);
			$endtime = strtotime($live_info['endtime']);
			if(time() < $starttime){
				$lesson['icon_live_status'] = 'icon-live-nostart';
			}elseif(time() > $endtime){
				$lesson['icon_live_status'] = 'icon-live-ended';
			}elseif(time() > $starttime && time() < $endtime){
				$lesson['icon_live_status'] = 'icon-live-starting';
			}
			$lesson['learned_hide'] = 'hide';
			unset($lesson['soncount']);
		}

		$list[] = $lesson;
		unset($lesson);
	}

	if($_W['isajax']){
		$this->resultJson($list);
	}
}

if(!$_W['isajax']){
	include $this->template("../mobile/{$template}/couponlesson");
}

?>