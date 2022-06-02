<?php defined('IN_IA') or exit('Access Denied');?><div class="header-right-top-shortcut ds-none">
	<?php  if(is_array($rightTopMenu)) { foreach($rightTopMenu as $item) { ?>
	<li>
		<a href="<?php  echo $item['url_link'];?>">
			<span style="background: url(<?php  echo $_W['attachurl'];?><?php  echo $item['menu_icon'];?>) no-repeat center center;background-size: 15px"></span>
			<strong><?php  echo $item['nav_name'];?></strong>
		</a>
	</li>
	<?php  } } ?>
	<div class="clearfix"></div>
</div>
<div class="white-shade"></div>

<script type="text/javascript">
	$("#right-top-shortcut-menu").click(function(){
		$(".header-right-top-shortcut").toggleClass('ds-none');
		if($(".header-right-top-shortcut").hasClass('ds-none')){
			$(".white-shade").hide();
		}else{
			$(".white-shade").show();
		}
	})
	$(".white-shade").click(function(){
		$(".header-right-top-shortcut").addClass('ds-none');
		$(".white-shade").hide();
	})
</script>