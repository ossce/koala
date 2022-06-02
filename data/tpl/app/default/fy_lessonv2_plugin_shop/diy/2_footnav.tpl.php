<?php defined('IN_IA') or exit('Access Denied');?><?php  if(is_array($diy_data['footnav']['data'])) { foreach($diy_data['footnav']['data'] as $item) { ?>
<a href="<?php  echo $item['link'];?>" class="weui-tabbar__item" style="background-color:<?php  echo $diy_data['footnav']['style']['background'];?>;">
	<?php  if($_GPC['do']==$item['nav_do'] && $op==$item['nav_op']) { ?>
		<img src="<?php  echo $item['imgurl_active'];?>" class="weui-tabbar__icon" />
	<?php  } else { ?>
		<img src="<?php  echo $item['imgurl'];?>" class="weui-tabbar__icon" />
	<?php  } ?>
	<p class="weui-tabbar__label" style="color:<?php  if($_GPC['do']==$item['nav_do'] && $op==$item['nav_op']) { ?><?php  echo $item['color_active'];?><?php  } else { ?><?php  echo $item['color'];?><?php  } ?>;"><?php  echo $item['text'];?></p>
</a>
<?php  } } ?>