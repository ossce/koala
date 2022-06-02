<?php defined('IN_IA') or exit('Access Denied');?><?php  if(is_array($list)) { foreach($list as $item) { ?>
<div class="item" style="height:192px;background:#eee url(<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>);background-size:cover;" onclick='selectGoods(<?php  echo json_encode($item)?>);'>
	<div class="text"><?php  echo $item['title'];?></div>
</div>
<?php  } } ?>

<span class="hide goods_curr_page"><?php  echo $pindex;?></span><span class="hide goods_total_page"><?php  echo $total_page;?></span>