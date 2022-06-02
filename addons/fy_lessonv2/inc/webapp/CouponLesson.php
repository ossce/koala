<?php
/**
 * 优惠券指定课程
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


if($op=='display'){
	$title = "优惠券可用课程";

	$member_coupon_id = intval($_GPC['member_coupon_id']);
	$coupon_id = intval($_GPC['coupon_id']);

	if($member_coupon_id){
		$coupon = pdo_get($this->table_member_coupon, array('uniacid'=>$uniacid, 'id'=>$member_coupon_id));
	}elseif($coupon_id){
		$coupon = pdo_get($this->table_mcoupon, array('uniacid'=>$uniacid, 'id'=>$coupon_id));
	}

	if(empty($coupon)){
		message("该优惠券不存在");
	}

	if($coupon['use_type']!=2){
		message("该优惠券不属于指定课程可用");
	}

	$lesson_ids = json_decode($coupon['lesson_ids'], true);
	$list = array();
	foreach($lesson_ids as $item){
		$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$item));
		$lesson['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$item,':status'=>1));

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
		if($lesson['discount']<1){
			$lesson['ico_name'] = 'ico-discount';
			$lesson['market_price'] = $lesson['price'];
		}
		$lesson['price'] = round($lesson['price']*$lesson['discount'], 2);

		$lesson['descript'] = strip_tags(html_entity_decode($lesson['descript']));

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
}

include $this->template("../webapp/{$template}/couponLesson");

?>