<?php defined('IN_IA') or exit('Access Denied');?><style>
.search-input::-webkit-input-placeholder{
	color: <?php  echo $diy['style']['text_color'];?>;
}
.search-input::-webkit-input-placeholder{
	color: <?php  echo $diy['style']['text_color'];?>;
}
.search-input:-moz-placeholder{
	color: <?php  echo $diy['style']['text_color'];?>;
}
.search-input::-moz-placeholder{
	color: <?php  echo $diy['style']['text_color'];?>;
} 
.search-input:-ms-input-placeholder{
	color: <?php  echo $diy['style']['text_color'];?>;
}
</style>

<header class="shop-navBar" style="background-color:<?php  echo $diy['style']['background'];?>;padding:<?php  echo $diy['style']['vertical_padding'];?> <?php  echo $diy['style']['horizontal_padding'];?>;">
	<a href="<?php  echo $diy['style']['category_link'];?>" class="shop-navBar-item">
		<i class="icon-common" style="background-image:url(<?php  echo $diy['style']['icon_category'];?>);"></i>
	</a>
	<div class="shop-center">
		<div class="shop-search-box">
			<i class="icon-common icon-search" style="background-image:url(<?php  echo $diy['style']['icon_search'];?>);"></i>
			<input type="search" class="search-input search-goods-keyword" name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="<?php  echo $diy['style']['input_placeholder'];?>" style="color:<?php  echo $diy['style']['text_color'];?>;background-color:<?php  echo $diy['style']['input_background'];?>;border-radius:<?php  echo $diy['style']['searchbar_radius'];?>;">
		</div>
	</div>
	<a href="<?php  echo $diy['style']['cart_link'];?>" class="shop-navBar-item">
		<i class="icon-common" style="background-image:url(<?php  echo $diy['style']['icon_cart'];?>);"></i>
	</a>
</header>