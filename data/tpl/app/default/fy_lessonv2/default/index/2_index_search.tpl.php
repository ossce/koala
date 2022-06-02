<?php defined('IN_IA') or exit('Access Denied');?><div class="index-search <?php  if($common['small_index']) { ?>mar10-15 pad0<?php  } ?>">
	<div class="index-header-search <?php  if($common['small_index']) { ?>b-radius-50<?php  } ?>">
		<div class="u-search">
			<i class="fa fa-search"></i>
			<input type="text" name="keyword" class="search_input z-abled" autocorrect="off" placeholder="<?php echo $index_page['searchBox'] ? $index_page['searchBox'] : '搜索您感兴趣的课程';?>">
			<a class="search-btn <?php  if($common['small_index']) { ?>small-search-btn<?php  } ?>">搜索</a>
		</div>
	</div>
</div>