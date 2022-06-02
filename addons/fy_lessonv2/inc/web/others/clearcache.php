<?php
	
if(checksubmit()){
	cache_delete('fy_lesson_'.$uniacid.'_article_categorylist');
	cache_delete('fy_lesson_'.$uniacid.'_attribute1');
	cache_delete('fy_lesson_'.$uniacid.'_attribute2');
	cache_delete('fy_lesson_'.$uniacid.'_commission_setting');
	cache_delete('fy_lesson_'.$uniacid.'_index_banner');
	cache_delete('fy_lesson_'.$uniacid.'_index_category');
	cache_delete('fy_lesson_'.$uniacid.'_index_discount_banner');
	cache_delete('fy_lesson_'.$uniacid.'_index_html');
	cache_delete('fy_lesson_'.$uniacid.'_index_newlesson');
	cache_delete('fy_lesson_'.$uniacid.'_index_recommend');
	cache_delete('fy_lesson_'.$uniacid.'_lately_cashlog');
	cache_delete('fy_lesson_'.$uniacid.'_lesson_commission_log');
	cache_delete('fy_lesson_'.$uniacid.'_lesson_poster_list');
	cache_delete('fy_lesson_'.$uniacid.'_market');
	cache_delete('fy_lesson_'.$uniacid.'_navigation');
	cache_delete('fy_lesson_'.$uniacid.'_navigation_self');
	cache_delete('fy_lesson_'.$uniacid.'_navigation_rightBar');
	cache_delete('fy_lesson_'.$uniacid.'_poster_list');
	cache_delete('fy_lesson_'.$uniacid.'_recommend_teacher');
	cache_delete('fy_lesson_'.$uniacid.'_setting');
	cache_delete('fy_lesson_'.$uniacid.'_start_adv');
	cache_delete('fy_lesson_'.$uniacid.'_suggest_category');
	cache_delete('fy_lesson_'.$uniacid.'_teacher_categorylist');
	cache_delete('fy_lesson_'.$uniacid.'_video_category_0');
	cache_delete('fy_lesson_'.$uniacid.'_vip_commission_log');
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
	if($_GPC['userPoster']){
		$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/salePoster/*");
		foreach($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}
	/* 清空课程海报缓存 */
	if($_GPC['lessonPoster']){
		$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/lessonPoster/*");
		foreach($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}
	/* 清空商品二维码缓存 */
	if($_GPC['lessonGoods']){
		$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/lessonGoods/*");
		foreach($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	itoast("清空缓存成功", "", "success");
}
