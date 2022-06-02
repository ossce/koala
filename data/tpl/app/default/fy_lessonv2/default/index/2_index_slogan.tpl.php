<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($config['index_slogan'])) { ?>
<div class="slogan_wrap <?php  if($common['small_index']) { ?>mar10-15 small-slogan_wrap<?php  } ?>">
	<div class="slogan_bd" style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $config['index_slogan'];?>);"></div>
</div>
<?php  } ?>