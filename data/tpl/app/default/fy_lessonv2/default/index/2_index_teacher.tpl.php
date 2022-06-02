<?php defined('IN_IA') or exit('Access Denied');?><?php  if($recommend_teacher) { ?>
<div class="index_unit <?php  if($common['small_index']) { ?>mar10-15 b-radius-10<?php  } ?>">
	<div class="index_title flex1">
		<div class="img flex0">
			<img class="flex_g0" src="<?php echo $common['teacherlist_ico'] ? $_W['attachurl'].$common['teacherlist_ico'] : MODULE_URL.'static/mobile/'.$template.'/images/index_teacher_list.png'?>" style="width: 19px;">
		</div>
		<div class="flex_all index_teacher_title"><?php echo $index_page['teacher'] ? $index_page['teacher'] : '名师风采';?></div>
		<a href="<?php  echo $this->createMobileUrl('teacherlist');?>" class="more icon_right">查看全部</a>
	</div>

	<div style="overflow:hidden;">
		<div class="teacher_swiper">
			<div class="swiper-wrapper">
				<?php  if(is_array($recommend_teacher)) { foreach($recommend_teacher as $teacher) { ?>
				<div class="swiper-slide">
					<a href="<?php  echo $this->createMobileUrl('teacher', array('teacherid'=>$teacher['id']));?>">
						<div class="img-box">
							<div class="img"><img src="<?php  echo $_W['attachurl'];?><?php  echo $teacher['teacherphoto'];?>"></div>
							<!-- <div class="job"></div> -->
						</div>
						<div class="name"><?php  echo $teacher['teacher'];?></div>
					</a>
				</div>
				<?php  } } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var teacherSwiper = new Swiper('.teacher_swiper', {
		slidesPerView: 3,
		autoplay: 4000,
		autoplayDisableOnInteraction: false,
		spaceBetween: $(".teacher_swiper").width() * 0.08,
	});
</script>
<?php  } ?>