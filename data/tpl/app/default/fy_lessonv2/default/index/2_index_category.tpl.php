<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($category_list)) { ?>
<style type="text/css">
<?php  if($category_row_number == 3) { ?>
	.grid_wrap .grid_item{
		width: 32%;
	}
<?php  } else if($category_row_number == 4) { ?>
	.grid_wrap .grid_item{
		width: 24%;
	}
<?php  } ?>
</style>
<div class="grid_wrap bor_no <?php  if($common['small_index']) { ?>mar10-15 b-radius-10<?php  } ?>">
	<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
	<a class="grid_item" href="<?php  echo $this->createMobileUrl('search', array('cat_id'=>$item['id'], 'clear'=>1));?>">
		<div class="grid_hd">
			<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['ico'];?>" alt="<?php  echo $item['name'];?>" />
		</div>
		<div class="grid_bd">
			<p><?php  echo $item['name'];?></p>
		</div>
	</a>
	<?php  } } ?>
	<?php  if(!empty($allCategoryIco)) { ?>
	<a class="grid_item" href="<?php  echo $this->createMobileUrl('search',array('op'=>'allcategory', 'clear'=>1));?>">
		<div class="grid_hd">
			<img src="<?php  echo $allCategoryIco;?>" alt="全部分类">
		</div>
		<div class="grid_bd">
			<p>全部分类</p>
		</div>
	</a>
	<?php  } ?>
</div>
<?php  } ?>