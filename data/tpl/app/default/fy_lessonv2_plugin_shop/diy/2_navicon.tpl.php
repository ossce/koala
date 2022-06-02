<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
<?php  if($diy['style']['row_number'] == 2) { ?>
	.<?php  echo $diy['tpl_name'];?>{
		width: 50%;
	}
<?php  } else if($diy['style']['row_number'] == 3) { ?>
	.<?php  echo $diy['tpl_name'];?>{
		width: 33.3%;
	}
<?php  } else if($diy['style']['row_number'] == 4) { ?>
	.<?php  echo $diy['tpl_name'];?>{
		width: 25%;
	}
<?php  } else if($diy['style']['row_number'] == 5) { ?>
	.<?php  echo $diy['tpl_name'];?>{
		width: 20%;
	}
<?php  } ?>
</style>

<div class="shop-palace shop-palace-one" style="background-color:<?php  echo $diy['style']['background'];?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $item) { ?>
	<a href="<?php  echo $item['link'];?>" class="shop-palace-grid <?php  echo $diy['tpl_name'];?>">
		<div class="shop-palace-grid-icon">
			<img src="<?php  echo $item['imgurl'];?>" alt="<?php  echo $item['text'];?>" />
		</div>
		<div class="shop-palace-grid-text">
			<h2 style="color:<?php  echo $item['color'];?>;"><?php  echo $item['text'];?></h2>
		</div>
	</a>
	<?php  } } ?>
</div>