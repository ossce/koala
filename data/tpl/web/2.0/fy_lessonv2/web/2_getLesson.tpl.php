<?php defined('IN_IA') or exit('Access Denied');?><?php  if(is_array($list)) { foreach($list as $item) { ?>
<div class="item" style="background:#eee url(<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>); background-size:cover;" onclick='selectLesson(<?php  echo json_encode($item)?>);'>
	<div class="text"><?php  echo $item['bookname'];?></div>
</div>
<?php  } } ?>

<span class="hide lesson_curr_page"><?php  echo $pindex;?></span><span class="hide lesson_total_page"><?php  echo $total_page;?></span>