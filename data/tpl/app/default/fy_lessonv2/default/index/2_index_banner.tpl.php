<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($banner)) { ?>
<div class="swiper-container index-banner <?php  if($common['small_index']) { ?>mar10-15 b-radius-10<?php  } ?>">
	<div class="swiper-wrapper">
		<!--图片一-->
		<?php  if(is_array($banner)) { foreach($banner as $item) { ?>
		<div class="swiper-slide">
			<a href="<?php  echo $item['link'];?>">
				<img class="swiper-lazy" src="<?php  echo $_W['attachurl'];?><?php  echo $item['picture'];?>">
			</a>
		</div>
		<?php  } } ?>
		<!--图片一end-->
	</div>
	<div class="swiper-pagination index-banner-pagination"></div>
</div>
<script type="text/javascript">
	//动画效果
	var myBanner = new Swiper('.index-banner', {
		pagination: '.index-banner-pagination',
		effect: 'coverflow',
		paginationClickable: true,
		centeredSlides: true,
		autoplay: 5000,
		autoplayDisableOnInteraction: false,
		preloadImages: false,
		lazyLoading: true
	});
</script>
<?php  } ?>