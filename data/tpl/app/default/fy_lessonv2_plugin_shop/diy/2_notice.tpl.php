<?php defined('IN_IA') or exit('Access Denied');?><div class="show-news-wrap flex" style="background-color:<?php  echo $diy['style']['background'];?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<a href="<?php echo $diy['style']['icon_link'] ? $diy['style']['icon_link'] : $this->createMobileUrl('shopnotice',array('op'=>'list'));?>" class="shop-news">
		<img src="<?php  echo $diy['style']['icon_image'];?>" />
	</a>
	<div class="flex-box h-45">
		<div class="news-line <?php  echo $diy['tpl_name'];?>">
			<ul style="margin-top:0px;">
				<?php  if(is_array($diy['data'])) { foreach($diy['data'] as $notice) { ?>
				<li>
					<a href="<?php  echo $notice['link'];?>" style="<?php  if($notice['color']) { ?>color:<?php  echo $notice['color'];?><?php  } ?>">
						<?php  if($notice['em_title']) { ?>
							<em style="<?php  if($notice['em_color']) { ?>color:<?php  echo $notice['em_color'];?>;<?php  } ?>"><?php  echo $notice['em_title'];?></em>
						<?php  } ?>
						<?php  echo $notice['title'];?>
					</a>
				</li>
				<?php  } } ?>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".<?php  echo $diy['tpl_name'];?>").Scroll({
			line: 1,
			speed: 1000,
			timer: <?php  echo $diy['style']['speed'] * 1000?>,
		});
	});
</script>