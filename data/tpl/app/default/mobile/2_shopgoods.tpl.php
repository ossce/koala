<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 商品详情
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
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/css/shop-goods.css?v=<?php  echo $versions;?>">

<!-- 标题栏 S -->
<header class="shop-navBar bg-fff bor-bottom-eee max-640">
	<a href="javascript:history.go(-1);" class="shop-navBar-item">
		<i class="icon-common icon-common-return"></i>
	</a>
	<div class="shop-center">
		<nav class="detail_anchor">
            <a href="#goodsItem" class="detail_anchor_item cur"><span>商品</span></a>
			<a href="#goodsDetail" class="detail_anchor_item"><span>详情</span></a>
            <a href="#goodsComment" class="detail_anchor_item"><span>评价</span></a>
        </nav>
	</div>
	<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenu', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenu', TEMPLATE_INCLUDEPATH));?>
</header>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenuList', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenuList', TEMPLATE_INCLUDEPATH));?>
<!-- 标题栏 E -->

<?php  if($goods['poster_bg']) { ?>
<a href="<?php  echo $this->createMobileUrl('shopqrcode',array('id'=>$id))?>" class="sharecard__entry-global max-width-640">
	<div class="sharecard__entry">
		<div class="sharecard__entry-icon">
			<img src="<?php echo MODULE_URL;?>static/mobile/images/icon-goods-share.png">
		</div>
	分享</div>
</a>
<?php  } ?>

<!-- 商品图集 S -->
<div class="swiper-container goods-swiper" id="goodsItem">
	<div class="swiper-wrapper">
		<div class="swiper-slide">
			<a href="javascript:;">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $goods['cover'];?>" class="swiper-lazy">
			</a>
		</div>
		<?php  if(is_array($album)) { foreach($album as $item) { ?>
		<div class="swiper-slide">
			<a href="javascript:;">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $item;?>" class="swiper-lazy">
			</a>
		</div>
		<?php  } } ?>
	</div>
	<div class="swiper-pagination goods-swiper-pagination"></div>
</div>
<!-- 商品图集 E -->

<?php  if($adv_list) { ?>
<!-- 广告图 S -->
<div class="swiper-container goods-adv-swiper">
	<div class="swiper-wrapper">
		<?php  if(is_array($adv_list)) { foreach($adv_list as $item) { ?>
		<div class="swiper-slide">
			<a <?php  if($item['link']) { ?>href="<?php  echo $item['link'];?>"<?php  } ?>>
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['picture'];?>" class="swiper-lazy">
			</a>
		</div>
		<?php  } } ?>
	</div>
</div>
<!-- 广告图 E -->
<?php  } ?>

<!-- 价格标题 S -->
<div class="buy_area">
	<div class="price_wrap">
		<span class="price">
			<?php  if($goods['sell_type']==1) { ?>
				<strong><?php  echo $goods['integral'];?></strong> 积分
			<?php  } else if($goods['sell_type']==2) { ?>
				¥<strong><?php  echo $goods['price'];?></strong>
			<?php  } else if($goods['sell_type']==3) { ?>
				¥<strong><?php  echo $goods['price'];?></strong><span class="price-plus">+</span><strong><?php  echo $goods['integral'];?></strong> 积分
			<?php  } ?>
		</span>
		<?php  if($goods['sell_type']!=1 && $goods['market_price']>0) { ?>
		<span class="market-price">¥<?php  echo $goods['market_price'];?></span>
		<?php  } ?>
		<?php  if($goods['show_sales']) { ?>
		<span class="sale_num">已售<?php  echo $goods['show_sales'];?></span>
		<?php  } ?>
	</div>
	<div class="goods_name-wrap">
		<h1 class="goods_name"><?php  echo $goods['title'];?></h1>
	</div>
</div>
<!-- 价格标题 E -->

<!-- 规格配送 S -->
<div class="goods_floor">
	<?php  if($goods['spec_switch'] && $goods_attr) { ?>
	<div class="detail_sku">
		<div class="sku_choose_info">
			<h3>规格</h3>
			<span id="skuChoose1">请选择规格</span>
		</div>
		<div class="sku_service"><i class="iconfont icon-pull-right"></i></div>
	</div>
	<?php  } ?>
	<div class="goods_sku_main">
		<div class="main">
			<div class="header">
				<div>
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $goods['cover'];?>" class="avt" id="popupImg" alt="商品图">
					<p class="price">
						<?php  if($goods['sell_type']==1) { ?>
							<strong id="integralSale1"><?php  echo $goods['integral'];?></strong> 积分
						<?php  } else if($goods['sell_type']==2) { ?>
							¥<strong id="priceSale1"><?php  echo $goods['price'];?></strong>
						<?php  } else if($goods['sell_type']==3) { ?>
							¥<strong id="priceSale2"><?php  echo $goods['price'];?></strong><span class="price-plus">+</span><strong id="integralSale2"><?php  echo $goods['integral'];?></strong> 积分
						<?php  } ?>
					</p>
					<?php  if($goods['sell_type']!=1 && $goods['market_price']>0) { ?>
						<span class="old_price" id="priceMarket">¥<?php  echo $goods['market_price'];?></span>
					<?php  } ?>
					<?php  if($goods['spec_switch'] && $goods_attr) { ?>
					<p class="prop" id="popupSkuChoose">
						<span>请选择规格</span>
					</p>
					<?php  } ?>
					<p class="prop" id="popupTotalChoose">
						<span>库存</span> <?php  echo $goods['stock'];?>
					</p>
				</div>
				<i class="close goods_sku_close" id="popupClose"></i>
			</div>
			<div class="body">
				<?php  if($goods['spec_switch'] && $goods_attr) { ?>
					<?php  if(is_array($goods_attr)) { foreach($goods_attr as $key => $attr) { ?>
					<div class="sku_kind"><?php  echo $attr['title'];?></div>
					<div class="sku_choose">
						<?php  if(is_array($attr['values'])) { foreach($attr['values'] as $value) { ?>
						<span class="item" data-serial="<?php  echo $key;?>" data-valueid="<?php  echo $value['id'];?>"><?php  echo $value['value'];?></span>
						<?php  } } ?>
					</div>
					<?php  } } ?>
				<?php  } ?>
				<div class="count_choose">
					<div class="num_wrap_v2">
						<span class="minus disable" id="minus1"><i class="row"></i></span>
						<div class="text_wrap"><input class="text" type="tel" value="1" id="buyNum1" onblur="checkNumber();" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')"></div>
						<span class="plus" id="plus1"><i class="row"></i><i class="col"></i></span>
					</div>
					<p class="count">数量</p>
				</div>
			</div>
			<div class="btns show">
				<input type="hidden" id="orig_valueids" value="">
				<input type="hidden" id="valueids" value="">
				<input type="hidden" id="sku_id" value="">
				<input type="hidden" id="sku_choose_name" value="">
				<div class="btn yellow" id="addCart2">
					<span class="txt">加入购物车</span>
				</div>
				<div class="btn red" id="buyBtn2">
					<span class="txt">立即购买</span>
				</div>
			</div>
		</div>
	</div>
	
	<?php  if($goods['goods_type'] == 1) { ?>
	<div class="detail_transfer">
		<div class="detail_transfer_row">
			<span class="detail_transfer_row_tit">配送</span>
			<div class="detail_transfer_row_content">
				<p><?php  echo $transfer;?></p>
			</div>
		</div>
		<div class="transfer_service"><i class="iconfont icon-pull-right"></i></div>
	</div>
	<div class="goods_transfer_main">
		<div class="main">
			<div class="header">配送<i class="close goods_transfer_close"></i></div>
			<div class="body">
				<?php  if(is_array($express_list)) { foreach($express_list as $item) { ?>
				<div class="section">
					<div class="goods_transfer_main_tit"><?php  echo $item['title'];?></div>
					<?php  if($item['content']) { ?>
						<?php  if(is_array($item['content'])) { foreach($item['content'] as $key => $area) { ?>
							<p class="goods_transfer_main_content mar-t-5">
								<?php  if($area['renew']) { ?>
									<?php  echo $area['area'];?>首件(<?php  echo $area['start'];?>个)运费: ￥<?php  echo $area['postage'];?>，续件(<?php  echo $area['step'];?>个)运费: ￥<?php  echo $area['renew'];?>
								<?php  } else { ?>
									<?php  echo $area['area'];?>运费: ￥<?php  echo $area['postage'];?>
								<?php  } ?>
							</p>
						<?php  } } ?>
						<p class="goods_transfer_main_content mar-t-5">其他地区运费: ￥<?php  echo $item['price'];?></p>
					<?php  } else { ?>
						<p class="goods_transfer_main_content mar-t-10">运费: ￥<?php  echo $item['price'];?></p>
					<?php  } ?>
				</div>
				<?php  } } ?>
			</div>
			<div class="mod_btns goods_transfer_sure">
				<div class="btn-sure">确定</div>
			</div>
		</div>
	</div>
	<?php  } ?>
</div>
<!-- 规格配送 E -->

<!-- 商品详情 S -->
<div class="goods_detail goods_floor" id="goodsDetail">
	<div class="goods_detail_title cur">商品介绍</div>
	<div class="goods_content">
		<?php  echo htmlspecialchars_decode($goods['content']);?>		
	</div>
</div>
<!-- 商品详情 E -->

<!-- 商品评价 S -->
<div class="goods_comment goods_floor" id="goodsComment">
	<div class="detail_title">
		<h3 class="tit">评价</h3>
		<?php  if($good_comment && $goods['score']>3) { ?>
		<p class="good">评分 <strong class="color-fd5a45"><?php  echo $goods['score'];?></strong></p>
		<?php  } ?>
		<?php  if($comment_total) { ?>
		<p class="count">(<?php echo $comment_total>9 ? $comment_total.'+' : $comment_total;?>)</p>
		<?php  } ?>
	</div>
	<div class="comment_list_wrap">
		<?php  if($good_comment) { ?>
		<ul class="comment_list" id="evalDet_main">
			<li>
				<div class="user_info">
					<img class="user_avatar" src="<?php  echo $good_comment['avatar'];?>">
					<span class="user_name"><?php  echo $good_comment['nickname'];?></span>
					<span class="star">
						<?php  for($i=0; $i<5; $i++){ ?>
							<img src="<?php echo MODULE_URL;?>static/mobile/images/<?php echo $i<$good_comment['score'] ? 'icon-star-active.png' : 'icon-star-empty.png';?>" />
						<?php  } ?>
					</span>
					<span class="date"><?php  echo date('Y-m-d', $good_comment['addtime'])?></span>
				</div>
				<div class="comment_detail"><?php  echo $good_comment['content'];?></div>
				<?php  if($good_comment['picture']) { ?>
				<div class="comment_picture">
					<?php  if(is_array($good_comment['picture'])) { foreach($good_comment['picture'] as $item) { ?>
					<span class="img">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item;?>">
					</span>
					<?php  } } ?>
				</div>
				<?php  } ?>
				<?php  if($good_comment['sku_name']) { ?>
				<div class="comment_sku">
					<span><?php  echo $good_comment['sku_name'];?></span>
				</div>
				<?php  } ?>
			</li>
		</ul>
		<?php  } ?>
		<?php  if($comment_total) { ?>
		<div class="comment_more"><a href="<?php  echo $this->createMobileUrl('shopcomment',array('op'=>'list','goods_id'=>$id))?>" class="comment_more_lnk">查看全部评价<i class="iconfont icon-pull-right"></i></a></div>
		<?php  } else { ?>
		<div class="comment_more"><a class="comment_more_lnk">暂无任何评价内容</a></div>
		<?php  } ?>
	</div>
	<!-- 非微信接口图片预览 -->
	<div class="cmt-picture-view cmt-modal-mask">
		<div class="cmt-picture-view cmt-modal-main">
			<img src="" style="width:100%;">
		</div>
	</div>
</div>
<!-- 商品评价 E -->

<?php  if($like_list) { ?>
<!-- 猜您喜欢 S -->
<div class="goods_like_only goods_floor" id="goodsLike">
	<div class="detail_title">
		<h3 class="tit">猜您喜欢</h3>
	</div>
	<div class="goods-like-only-swiper">
		<div class="slider_like_page">
			<ul class="row">
				<?php  if(is_array($like_list)) { foreach($like_list as $item) { ?>
				<li>
					<a class="row_item guess_click" href="<?php  echo $this->createMobileUrl('shopgoods', array('id'=>$item['id']))?>">
						<div class="img_wrap">
							<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
						</div>
						<div class="name"><?php  echo $item['title'];?></div>
						<div class="buy_wrap">
							<span class="price">
								<?php  if($item['sell_type'] == 1) { ?>
									<span class="int"><?php  echo $item['integral'];?></span>积分
								<?php  } else if($item['sell_type'] == 2) { ?>
									¥<span class="int"><?php  echo $item['price'];?></span>
								<?php  } else if($item['sell_type'] == 3) { ?>
									¥<span class="int"><?php  echo $item['price'];?></span><span class="int">+<?php  echo $item['integral'];?></span>积分
								<?php  } ?>
							</span>
						</div>
					</a>
				</li>
				<?php  } } ?>
			</ul>
		</div>
		<div class="swiper-pagination swiper-pagination-goods-like"></div>
	</div>
</div>
<!-- 猜您喜欢 E -->
<?php  } ?>

<!-- 购买按钮 S -->
<div class="goods_btn_wrap">
	<div class="goods_row goods_btn_bar">
		<?php  if(is_array($footer_nav)) { foreach($footer_nav as $item) { ?>
		<a href="<?php  echo $item['url_link'];?>" class="icon_btn">
			<i class="icon" style="background-image:url(<?php  if(!$system_footer) { ?><?php  echo $_W['attachurl'];?><?php  } ?><?php  echo $item['menu_icon'];?>);"></i>
			<span class="txt"><?php  echo $item['nav_name'];?></span>
		</a>
		<?php  } } ?>
		<a href="<?php  echo $this->createMobileUrl('shopcart');?>" class="icon_btn icon_cart">
			<span class="add_num">+1</span>
			<i class="icon">
				<span class="num" id="cardNum" <?php  if(!$cart_total) { ?>style="display:none;"<?php  } ?>><?php  echo $cart_total;?></span>
			</i>
			<span class="txt">购物车</span>
		</a>
		<div class="btn_group">
			<div class="goods_row">
				<div class="btn btn_orange" id="addCart1">
					<span class="txt">加入购物车</span>
				</div>
				<div class="btn btn_m_red" id="buyBtn1">
					<span class="txt">立即购买</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 购买按钮 E -->

<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		var miniprogram_environment = false;
		wx.miniProgram.getEnv(function(res) {
			if(res.miniprogram) {
				miniprogram_environment = true;
			}
		})
		if((window.__wxjs_environment === 'miniprogram' || miniprogram_environment)) {
			wx.miniProgram.getEnv(function(res) {
				wx.miniProgram.postMessage({ 
					data: {
						'title': "<?php  echo $share_goods['title'];?>",
						'images': "<?php  echo $_W['attachurl'];?><?php  echo $share_goods['cover'];?>",
					}
				})
			});
		}
	});

	window.config = {
			 userAgent: "<?php  echo $userAgent;?>",
			 attachurl: "<?php  echo $_W['attachurl'];?>",
			addCarturl: "<?php  echo $this->createMobileUrl('shopaddcart')?>",
			  loginurl: "<?php  echo url('auth/login')?>&forward=",
		redir_goodsurl: "<?php echo str_replace('./index.php?', '', $this->createMobileUrl('shopgoods',array('id'=>$id)))?>",
			confirmurl: "<?php  echo $this->createMobileUrl('shopconfirm')?>",
		 live_lessonid: <?php  echo intval($_GPC['live_lessonid'])?>,
			  sku_list: <?php  echo json_encode($sku_list)?>,
		   attr_number: <?php  echo count($goods_attr)?>,
		   share_goods: <?php  echo json_encode($share_goods)?>,
	};
	window.goods = <?php  echo json_encode($goods)?>;
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/base64.js?v=<?php  echo $versions;?>"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/shopgoods.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>