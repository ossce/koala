<?php defined('IN_IA') or exit('Access Denied');?><div style="background:<?php  echo $diy['style']['background'];?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<div class="shop-goods-wrap">
			<div class="flex shop-flex-title pad-15" style="border-bottom: 1px solid <?php  echo $diy['style']['title_bottom_color'];?>;">
				<div class="flex-box shop-recommend-title" style="color:<?php  echo $diy['style']['title_color'];?>;">
					<i class="icon-title mar-r-8" style="background-image:url(<?php  echo $diy['style']['icon_imgsrc'];?>);"></i><?php  echo $diy['style']['title_font'];?><i class="icon-title mar-l-8" style="background-image:url(<?php  echo $diy['style']['icon_imgsrc'];?>);"></i>
				</div>
			</div>
		<?php  if($diy['style']['type'] == 1) { ?>
			<div class="shop-recommend-two static">
				<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $item) { ?>
				<div class="shop-hot-list-img">
					<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="position-re ds-block">
						<div class="goods-cover">
							<div class="img-cover">
								<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
							</div>
						</div>
						<?php  if($item['show_sales']) { ?>
							<div class="icon-sell-num">已售<?php  echo $item['show_sales'];?></div>
						<?php  } ?>
						<i class="icon-goods-image <?php  echo $item['icon_name'];?>"></i>
					</a>
					<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="goods-title">
						<?php  echo $item['title'];?>
					</a>
					<h2><?php  echo $item['show_price'];?></h2>
					<h3>
						<em>
						<?php  if($item['sell_type'] != 1 && $item['market_price']>0) { ?>
							￥<?php  echo $item['market_price'];?>
						<?php  } else { ?>
							&nbsp;
						<?php  } ?>
						</em>
					</h3>
				</div>
				<?php  } } ?>
			</div>
		<?php  } else if($diy['style']['type'] == 2) { ?>
			<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $item) { ?>
			<div class="shop-recommend-one">
				<div class="shop-flex-2">
					<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="shop-goods-img">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
						<i class="icon-goods-image <?php  echo $item['icon_name'];?>"></i>
					</a>
					<div class="shop-flex-box">
						<a href="<?php  echo $this->createMobileUrl('shopgoods',array('id'=>$item['id']))?>" class="ds-block">
							<h2><?php  echo $item['title'];?></h2>
							<h3><em><?php  echo $item['show_price'];?></em><?php  if($item['sell_type'] != 1 && $item['market_price']>0) { ?><i><?php  echo $item['market_price'];?></i><?php  } ?></h3>
						</a>
						<h4><?php  if($item['show_sales']) { ?><em>已售<?php  echo $item['show_sales'];?></em><?php  } ?></h4>
					</div>
				</div>
			</div>
			<?php  } } ?>
		<?php  } ?>
		<?php  if($diy['style']['show_more']) { ?>
			<div class="browse-more" style="border-top:1px solid <?php  echo $diy['style']['more_top_color'];?>;">
				<a href="<?php  echo $diy['style']['more_link'];?>" style="color:<?php  echo $diy['style']['more_color'];?>;"><?php  echo $diy['style']['more_font'];?>&gt;&gt; </a>
			</div>
		<?php  } ?>
	</div>
</div>