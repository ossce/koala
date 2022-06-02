<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 确认订单
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
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/css/shop-confirm.css?v=<?php  echo $versions;?>">

<form method="post" action="<?php  echo $this->createMobileUrl('shopaddorder')?>" id="orderForm">
	<div class="address_defalut_wrap">
		<div>
			<div class="address_defalut address_border">
				<?php  if($address) { ?>
					<ul>
						<li>
							<strong><?php  echo $address['realname'];?></strong>
							<strong><?php  echo $address['mobile'];?></strong>
						</li>
						<li class="address">
							<?php  if($address['isdefault']) { ?>
							<span class="tag tag_red">默认</span>
							<?php  } ?>
							<?php  echo $address['province'];?><?php  echo $address['city'];?><?php  echo $address['area'];?><?php  echo $address['address'];?>
						</li>
					</ul>
				<?php  } else { ?>
					<div class="no-address"><img class="plus" src="<?php echo MODULE_URL;?>static/mobile/images/icon-add-red.svg">请先填写收货地址</div>
				<?php  } ?>
				<input type="hidden" id="province" value="<?php  echo $address['province'];?>" />
			</div>
		</div>
	</div>

	<section>
		<div class="order_info">
			<div class="order_title">商品清单</div>
			<ul>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<li class="goods-wrap">
					<div class="product_info">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>" class="goods_cover">
						<div class="goods_title"><?php  echo $item['title'];?></div>
						<p class="sku_name"><?php  echo $item['sku_name'];?></p>
						<p class="sku_price">
							<span class="price_area">
								<?php  if($item['sell_type'] == 1) { ?>
									<span class="large_text"><?php  echo $item['integral'];?></span>积分
								<?php  } else if($item['sell_type'] == 2) { ?>
									¥<span class="large_text"><?php  echo $item['price'];?></span>
								<?php  } else if($item['sell_type'] == 3) { ?>
									¥<span class="large_text"><?php  echo $item['price'];?></span>+<span class="large_text"><?php  echo $item['integral'];?></span>积分
								<?php  } ?>
								
							</span>
						</p>
					</div>
					<div class="sku">
						<div class="sku_num">×<?php  echo $item['total'];?></div>
					</div>
				</li>
				<?php  } } ?>
			</ul>

			<?php  if($goods_type == 1) { ?>
			<div class="h-15"></div>
			<div>
				<ul class="order_info_list">
					<li class="shipping">
						<strong class="fl">配送方式</strong>
						<div class="shipping_content text-r">
							请选择...
						</div>
					</li>
				</ul>
			</div>
			<?php  } ?>
			<div class="h-5"></div>
		</div>

		<div class="buy_checkout">
			<ul>
				<li>
					<div class="content desc">
						<strong>商品总额</strong>
						<span>¥<?php  echo $totalprice;?></span>
					</div>
				</li>
				<li>
					<div class="content desc">
						<strong>商品积分</strong>
						<span><?php  echo $totalintegral;?> 积分</span>
					</div>
				</li>
				<li>
					<div class="content desc">
						<strong>运费</strong>
						<span class="express-price">￥0.00</span>
					</div>
				</li>
			</ul>
			<div class="total_price">
				总计：
				<span class="color-fd5a45">
					<?php  if($sell_type == 1) { ?>
						<span class="total-integral"><?php  echo $totalintegral;?></span> 积分
					<?php  } else if($sell_type == 2) { ?>
						￥<span class="total-price"><?php  echo $totalprice;?></span>
					<?php  } else if($sell_type == 3) { ?>
						￥<span class="total-price"><?php  echo $totalprice;?></span> + <span class="total-integral"><?php  echo $totalintegral;?></span> 积分
					<?php  } ?>
				</span>
			</div>
		</div>

		<div class="buy_checkout">
			<div class="order_remark">
				<textarea name="remark" placeholder="订单备注，选填"></textarea>
			</div>
			<div class="order_buttom">
				<a href="javascript:;" class="btn-submit-order">提交订单</a>
			</div>
			<div class="h-15"></div>
		</div>
	</section>
	<input type="hidden" name="address_id" value="<?php  echo $address['id'];?>">
	<input type="hidden" name="cart_ids" value="<?php  echo $cart_ids;?>">
	<input type="hidden" name="express_id" value="">
	<input type="hidden" id="original_totalprice" value="<?php  echo $totalprice;?>">
</form>

<script type="text/javascript">
	window.config = {
		   uniacid: "<?php  echo $uniacid;?>",
		       uid: "<?php  echo $uid;?>",
		 sell_type: "<?php  echo $sell_type;?>",
		goods_type: "<?php  echo $goods_type;?>",
	   totalnumber: "<?php  echo $totalnumber;?>",
	 express_label: <?php  echo json_encode($express_label)?>,
	  express_list: <?php  echo json_encode($express_list)?>,
	    confirmurl: "<?php  echo $returnurl;?>",
		addressurl: "<?php  echo $this->createMobileUrl('shopaddress',array('address_id'=>$address['id']))?>",
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/shopconfirm.js?v=<?php  echo $versions;?>"></script>


<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>