<?php defined('IN_IA') or exit('Access Denied');?><?php  if($setting['show_newlesson'] && $newlesson) { ?>
<div class="index_unit <?php  if($common['small_index']) { ?>mar10-15 b-radius-10<?php  } ?>">
	<div class="index_title flex1">
		<div class="img flex0">
			<img class="flex_g0" src="<?php echo $common['newlesson_ico'] ? $_W['attachurl'].$common['newlesson_ico'] : MODULE_URL.'static/mobile/'.$template.'/images/index_new_lesson.png'?>" style="width: 19px;" />
		</div>
		<div class="flex_all index_newlesson_title"><?php echo $index_page['newLesson'] ? $index_page['newLesson'] : '最新更新';?></div>
	</div>

	<?php  if(is_array($newlesson)) { foreach($newlesson as $item) { ?>
	<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']));?>" class="new_lesson flex0_1">
		<div class="new_lesson_a flex_g0">
			<div class="img-box flex_g0">
				<div class="img">
					<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
					<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
				</div>
				<?php  if($setting['show_study_number']) { ?>
				<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
					<?php  echo $item['study_number'];?>
					<?php  if($item['lesson_type'] == 0) { ?>
						<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
					<?php  } else if($item['lesson_type'] == 1) { ?>
						<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
					<?php  } ?>
				</div>
				<?php  } ?>
				<?php  if(!empty($item['ico_name'])) { ?>
					<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
				<?php  } ?>
			</div>
		</div>

		<div class="flex_all flex10">
			<div>
				<div class="new_lesson_title"><?php  echo $item['bookname'];?></div>
				<div class="new_lesson_info"><?php  echo $item['section']['title'];?></div>
			</div>
			<div class="new_lesson_bottom flex1">
				<span class="time"><?php  echo $item['tran_time'];?>更新</span>
				<?php  if($item['price']==0) { ?>
					<span class="price flex_g0 index_price_lesson fs-14">免费</span>
				<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
					<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
				<?php  } else { ?>
					<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
				<?php  } ?>
			</div>
		</div>
	</a>
	<?php  } } ?>
</div>
<?php  } ?>