<?php
/**
 * 清空缓存
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
	
	if(checksubmit()){
		cache_delete('fy_lesson_'.$uniacid.'_start_adv');
		cache_delete('fy_lesson_'.$uniacid.'_index_banner');
		cache_delete('fy_lesson_'.$uniacid.'_index_category');
		cache_delete('fy_lesson_'.$uniacid.'_index_discount_banner');
		cache_delete('fy_lesson_'.$uniacid.'_recommend_teacher');
		cache_delete('fy_lesson_'.$uniacid.'_index_newlesson');
		cache_delete('fy_lesson_'.$uniacid.'_index_recommend');
		cache_delete('fy_lesson_'.$uniacid.'_index_html');
		cache_delete('fy_lesson_'.$uniacid.'_navigation');
		cache_delete('fy_lesson_'.$uniacid.'_navigation_rightBar');
		cache_delete('fy_lesson_'.$uniacid.'_navigation_self');
		cache_delete('fy_lesson_'.$uniacid.'_poster_list');
		cache_delete('fy_lesson_'.$uniacid.'_lesson_poster_list');
		cache_delete('fy_lesson_'.$uniacid.'_setting');
		cache_delete('fy_lesson_'.$uniacid.'_commission_setting');
		cache_delete('fy_lesson_'.$uniacid.'_market');
		cache_delete('fy_lesson_'.$uniacid.'_article_categorylist');
		cache_delete('fy_lesson_'.$uniacid.'_teacher_categorylist');
		cache_delete('fy_lesson_'.$uniacid.'_attribute1');
		cache_delete('fy_lesson_'.$uniacid.'_attribute2');
		cache_delete('fy_lessonv2_'.$uniacid.'_mylesson_bg');
		cache_delete('fy_lessonv2_'.$uniacid.'_myteacher_bg');
		cache_delete('fy_lessonv2_'.$uniacid.'_ucenter_bg');
		cache_delete('fy_lessonv2_'.$uniacid.'_vip_bg');


		cache_delete('fy_lesson_'.$uniacid.'_top_navigation_pc');
		cache_delete('fy_lesson_'.$uniacid.'_menu_navigation_pc');
		cache_delete('fy_lesson_'.$uniacid.'_categorylist');
		cache_delete('fy_lesson_'.$uniacid.'_index_banner_pc');
		cache_delete('fy_lesson_'.$uniacid.'_index_notice_adv_pc');
		cache_delete('fy_lesson_'.$uniacid.'_index_article_pc');
		cache_delete('fy_lesson_'.$uniacid.'_index_discount_banner_pc');
		cache_delete('fy_lesson_'.$uniacid.'_recommend_teacher_pc');
		cache_delete('fy_lesson_'.$uniacid.'_index_new_lesson_pc');
		cache_delete('fy_lesson_'.$uniacid.'_index_recommend_pc');
		cache_delete('fy_lesson_'.$uniacid.'_bottom_navigation_pc');
		cache_delete('fy_lesson_'.$uniacid.'_self_navigation_pc');
		cache_delete('fy_lesson_'.$uniacid.'_lesson_audio_bg_pc');
		cache_delete('fy_lesson_'.$uniacid.'_article_avd_pc');
		cache_delete('fy_lesson_'.$uniacid.'_setting_pc');
		cache_delete('fy_lessonv2_'.$uniacid.'_getcoupon_bg_pc');
		cache_delete('fy_lessonv2_'.$uniacid.'_vip_bg_pc');


		/* 清空会员推广海报缓存 */
		if($_GPC['userposter']){
			$files = glob(ATTACHMENT_ROOT."images/fy_lessonv2/{$uniacid}/*");
			foreach($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
		/* 清空课程海报缓存 */
		if($_GPC['lessonposter']){
			$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/*");
			foreach($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}

			$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/poster/*");
			foreach($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		message("清空缓存成功", $this->createWebUrl('clearcache'), "success");
	}
}

include $this->template('web/clearcache');
