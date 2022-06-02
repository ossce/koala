<?php defined('IN_IA') or exit('Access Denied');?><div style="background:<?php  echo $diy['style']['background'];?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<div class="shop-goods-wrap">
		<div class="flex shop-flex-title pad-15" style="border-bottom: 1px solid <?php  echo $diy['style']['title_bottom_color'];?>;">
			<div class="flex-box shop-recommend-title" style="color:<?php  echo $diy['style']['title_color'];?>;">
				<i class="icon-title mar-r-8" style="background-image:url(<?php  echo $diy['style']['icon_imgsrc'];?>);"></i><?php  echo $diy['style']['title_font'];?><i class="icon-title mar-l-8" style="background-image:url(<?php  echo $diy['style']['icon_imgsrc'];?>);"></i>
			</div>
		</div>
		<div class="swiper_shop_wrap shop-recommend-two slidegoods_<?php  echo $diy['tpl_name'];?>">
			<div class="swiper-wrapper">
				<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $item) { ?>
				<div class="swiper-slide shop-hot-list-img mar-0-0-0-5">
					<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="position-re ds-block">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
						<?php  if($item['show_sales']) { ?>
							<div class="icon-sell-num">已售<?php  echo $item['show_sales'];?></div>
						<?php  } ?>
						<i class="icon-goods-image <?php  echo $item['icon_name'];?>"></i>
					</a>
					<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="goods-title"><?php  echo $item['title'];?></a>
					<h2><?php  echo $item['show_price'];?></h2>
					<?php  if($item['sell_type'] != 1 && $item['market_price']>0) { ?>
						<h3><em>￥<?php  echo $item['market_price'];?></em></h3>
					<?php  } ?>
				</div>
				<?php  } } ?>
			</div>
		</div>
		<?php  if($diy['style']['show_more']) { ?>
		<div class="browse-more" style="border-top:1px solid <?php  echo $diy['style']['more_top_color'];?>;">
			<a href="<?php  echo $diy['style']['more_link'];?>" style="color:<?php  echo $diy['style']['more_color'];?>;"><?php  echo $diy['style']['more_font'];?>&gt;&gt; </a>
		</div>
		<?php  } ?>
	</div>
</div>
<script>
	var slidegoods_<?php  echo $diy['tpl_name'];?> = new Swiper(".slidegoods_<?php  echo $diy['tpl_name'];?>", {
		slidesPerView: 2, 
		spaceBetween: 5, 
		autoplay: {
			delay: <?php  echo $diy['style']['speed']*1000?>,
			stopOnLastSlide: false,
			disableOnInteraction: false,
		}, 
		loop : true,
	});
</script>