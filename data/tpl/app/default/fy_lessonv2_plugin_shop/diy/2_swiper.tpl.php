<?php defined('IN_IA') or exit('Access Denied');?><?php  if($diy['style']['show_slider_background']) { ?>
	<div class="swiper-slider-bg" style="background-color:<?php  echo $diy['style']['slider_background'];?>;"></div>
<?php  } ?>
<div class="swiper-container diy-swiper-<?php  echo $diy['tpl_name'];?>" style="background-color:<?php echo $diy['style']['show_background'] ? $diy['style']['background'] : 'unset';?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<div class="swiper-wrapper">
		<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $item) { ?>
		<div class="swiper-slide">
			<a href="<?php  echo $item['link'];?>">
				<img src="<?php  echo $item['imgurl'];?>" class="swiper-lazy" style="border-radius:<?php  echo $diy['style']['image_radius'];?>;box-shadow: <?php  echo $diy['style']['offset_x'];?> <?php  echo $diy['style']['offset_y'];?> <?php  echo $diy['style']['blur_radius'];?> <?php  echo $diy['style']['spread_radius'];?> rgba(0, 0, 0, 0.2);">
			</a>
		</div>
		<?php  } } ?>
	</div>
	<div class="swiper-pagination diy-swiper-pagination-<?php  echo $diy['tpl_name'];?>"></div>
</div>

<script type="text/javascript">
	//动画效果
	var <?php  echo $diy['tpl_name'];?> = new Swiper(".diy-swiper-<?php  echo $diy['tpl_name'];?>", {
		pagination: {
            el: ".diy-swiper-pagination-<?php  echo $diy['tpl_name'];?>",
            type: 'bullets',
			clickable :true,
        },
		autoplay: {
			delay: <?php  echo $diy['style']['speed'] * 1000?>,
			stopOnLastSlide: false,
			disableOnInteraction: false,
		},
		effect: 'coverflow',
		centeredSlides: true,
	});
</script>