<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 商品分类
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 -->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/css/shop-category.css?v=<?php  echo $versions;?>">

<header class="shop-navBar bg-fff shop-category-header max-640">
	<a href="javascript:history.go(-1);" class="shop-navBar-item">
		<i class="icon-common icon-common-return"></i>
	</a>
	<div class="shop-center">
		<div class="shop-search-box">
			<i class="icon-common icon-search"></i>
			<input type="search" class="search-input shop-category-input search-goods-keyword" name="keyword" placeholder="搜索商品" >
		</div>
	</div>
	<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenu', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenu', TEMPLATE_INCLUDEPATH));?>
</header>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenuList', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenuList', TEMPLATE_INCLUDEPATH));?>

<div class="category-list-wrap iphone-max-height max-640">
	<div class="category-inner menu">
		<?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
		<nav class="first-category <?php  if($key==0) { ?>on<?php  } ?>" data-id="<?php  echo $item['id'];?>"><?php  echo $item['name'];?></nav>
		<?php  } } ?>
	</div>
	<div class="category-inner all-son-category">
	</div>
</div>

<script type="text/javascript">
	window.config = {
		     list: <?php  echo json_encode($list)?>,
		attachurl: "<?php  echo $_W['attachurl'];?>",
		searchurl: "<?php  echo $this->createMobileUrl('shoplist')?>",
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/shopcategory.js?v=<?php  echo $versions;?>"></script>


<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>