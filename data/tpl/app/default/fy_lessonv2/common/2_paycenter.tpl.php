<?php defined('IN_IA') or exit('Access Denied');?><?php  define('MUI', true);?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="mui-content pay-method">
	<h5 class="mui-desc-title mui-pl10">订单详情</h5>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			商品名称<span class="mui-pull-right mui-text-muted"><?php  echo $params['title'];?></span>
		</li>
		<li class="mui-table-view-cell">
			订单编号<span class="mui-pull-right mui-text-muted"><?php  echo $params['ordersn'];?></span>
		</li>
		<li class="mui-table-view-cell">
			商家名称<span class="mui-pull-right mui-text-muted"><?php  echo $_W['account']['name'];?></span>
		</li>
		<?php  if(!empty($mine)) { ?>
		<li class="mui-table-view-cell">
			优惠信息<span class="mui-pull-right mui-text-muted"><?php  echo $mine['name'];?></span>
		</li>
		<?php  } ?>
		<li class="mui-table-view-cell">
			商品价格<span class="mui-pull-right mui-text-success mui-big mui-rmb"><?php  echo $params['fee'];?> 元</span>
		</li>
	</ul>
	<?php  if(!empty($token) || !empty($coupon)) { ?>
	<h5 class="mui-desc-title mui-pl10">可用卡券</h5>
	<ul class="mui-table-view ">
		<?php  if(!empty($token)) { ?>
		<li class="mui-table-view-cell mui-table-view-chevron">
			<a href="javascript:;" class="mui-navigate-right js-token">
				代金券
				<?php  if(!empty($token)) { ?>
				<span class="used-num"><?php  echo count($token);?>张可用</span>
				<?php  } ?>
				<span class="mui-pull-right mui-mr10">
					<span class="mui-mr10 js-card-info"><?php  if(!empty($token)) { ?>未使用<?php  } else { ?>无可用<?php  } ?></span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($coupon)) { ?>
		<li class="mui-table-view-cell mui-table-view-chevron">
			<a href="javascript:;" class="mui-navigate-right js-coupon">
				折扣券
				<?php  if(!empty($coupon)) { ?>
				<span class="used-num"><?php  echo count($coupon);?>张可用</span>
				<?php  } ?>
				<span class="mui-pull-right mui-mr10">
					<span class="mui-mr10 js-card-info"><?php  if(!empty($coupon)) { ?>未使用<?php  } else { ?>无可用<?php  } ?></span>
				</span>
			</a>
		</li>
		<?php  } ?>
	</ul>
	<?php  } ?>
	<?php  if($pay['mix']['switch'] && $credtis[$setting['creditbehaviors']['currency']] > 0 && $credtis[$setting['creditbehaviors']['currency']] < $params['fee']) { ?>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-table-view-chevron">
			<input type="checkbox" name="mix" checked>
			混合支付<span class="mui-pull-right mui-text-success mui-big" data-price="<?php  echo sprintf('%.2f', $credtis[$setting['creditbehaviors']['currency']]);?>">余额支付<?php  echo sprintf('%.2f', $credtis[$setting['creditbehaviors']['currency']]);?> 元</span>
		</li>
	</ul>
	<?php  } ?>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-table-view-chevron">
			还需支付<span class="mui-pull-right mui-text-success mui-big mui-rmb js-need-pay" data-price="<?php  echo sprintf('%.2f', $params['fee']);?>"><?php  echo sprintf('%.2f', $params['fee']);?> 元</span>
		</li>
	</ul>
	<h5 class="mui-desc-title mui-pl10">选择支付方式</h5>
	<ul class="mui-table-view mui-table-view-chevron pay-style">
		<?php  if(!empty($pay['credit']['switch'])) { ?>
		<li class="mui-table-view-cell credit">
			<a class="mui-navigate-right mui-media js-pay credit-js-pay" href="javascript:;">
				<form action="<?php  echo url('mc/cash/credit');?>" method="post" id="credit_pay">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
				</form>
				<img src="resource/images/money.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					余额
					<span class="mui-block mui-text-muted mui-rmb mui-mt5"> <?php  echo sprintf('%.2f', $credtis[$setting['creditbehaviors']['currency']]);?></span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($pay['baifubao']['switch'])) { ?>
		<li class="mui-table-view-cell">
			<a class="mui-navigate-right mui-media js-pay" href="javascript:;">
				<form action="<?php  echo url('mc/cash/baifubao');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
				</form>
				<img src="resource/images/baidu-pay.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					百度钱包
					<span class="mui-block mui-text-muted mui-mt5">百度安全支付服务</span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($pay['unionpay']['switch'])) { ?>
		<li class="mui-table-view-cell">
			<a class="mui-navigate-right mui-media js-pay" href="javascript:;">
				<form action="<?php  echo url('mc/cash/unionpay');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
				</form>
				<img src="resource/images/yl-icon.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					银联支付
					<span class="mui-block mui-text-muted mui-mt5">银联安全支付服务</span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($pay['wechat']['switch']) && intval($pay['wechat']['switch']) != 4) { ?>
		<li class="mui-table-view-cell mui-disabled js-wechat-pay">
			<a class="mui-navigate-right mui-media" href="javascript:;">
				<form action="<?php  echo url('mc/cash/wechat');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
					<input type="hidden" name="mix_pay" value="" />
				</form>
				<img src="resource/images/wx-icon.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					<span id="wetitle">微信支付</span>
					<span class="mui-block mui-text-muted mui-mt5">微信支付,安全快捷(只能在微信内使用)</span>
				</span>
			</a>
		</li>
		<?php  } ?>


		<li class="mui-table-view-cell mui-disabled js-webwxapp-pay hide">
			<a class="mui-navigate-right mui-media" href="javascript:;">

				<img src="resource/images/wx-icon.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					<span id="wetitle">微信支付</span>
					<span class="mui-block mui-text-muted mui-mt5">微信支付,安全快捷</span>
				</span>
			</a>
		</li>


		<?php  if(!empty($pay['jueqiymf']['switch'])) { ?>
		<li class="mui-table-view-cell mui-disabled js-pay">
			<a class="mui-navigate-right mui-media" href="javascript:;">
				<form action="<?php  echo url('mc/cash/jueqiymf');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
				</form>
				<img src="resource/images/wx-icon.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					<span id="wetitle">一码付</span>
					<span class="mui-block mui-text-muted mui-mt5">一码多用</span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($pay['alipay']['switch'])) { ?>
		<li class="mui-table-view-cell">
			<a class="mui-navigate-right mui-media js-pay" href="javascript:;">
				<form action="<?php  echo url('mc/cash/alipay');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
					<input type="hidden" name="mix_pay" value="" />
				</form>
				<img src="resource/images/zfb-icon.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					支付宝
					<span class="mui-block mui-text-muted mui-mt5">简单、安全、快速</span>
				</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if(!empty($pay['delivery']['switch'])) { ?>
		<li class="mui-table-view-cell">
			<a class="mui-navigate-right mui-media js-pay" href="javascript:;">
				<form action="<?php  echo url('mc/cash/delivery');?>" method="post">
					<input type="hidden" name="params" value="<?php  echo base64_encode(json_encode($params));?>" />
					<input type="hidden" name="code" value="" />
					<input type="hidden" name="coupon_id" value="" />
				</form>
				<img src="resource/images/sum-recharge.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					货到付款
					<span class="mui-block mui-text-muted mui-mt5">线下当面交易，到店付款，货到付款</span>
				</span>
			</a>

		<?php  } ?>
		<?php  if(!empty($pay['line']['switch'])) { ?>
		<li class="mui-table-view-cell mui-disabled">
			<div class="mui-navigate-right mui-media" style="white-space: normal;">
				<img src="resource/images/icon-vip.png" alt="" class="mui-media-object mui-pull-left"/>
				<span class="mui-media-body mui-block">
					线下汇款
					<span class="mui-block mui-text-muted mui-mt5"><?php  echo htmlspecialchars_decode($pay['line']['message'])?></span>
				</span>
			</div>
		</li>
		<?php  } ?>
	</ul>
</div>
<?php  if(!empty($cards)) { ?>
<div id="pay-select-coupon-modal" class="mui-modal">
	<header class="mui-bar mui-bar-nav">
		<a class="mui-icon mui-icon-close mui-pull-right" href="javascript:;"></a>
		<h1 class="mui-title">请选择卡券</h1>
	</header>
	<nav class="mui-bar mui-bar-footer">
		<div class="js-coupon-submit">
			<input type="hidden" name="couponid" value="">
			<button class="mui-btn mui-btn-success mui-btn-block js-submit">确定</button>
		</div>
	</nav>
	<div class="mui-content">
		<div class="pay-select-coupon">
			<div class="js-coupon-show">
				<?php  if(is_array($coupon)) { foreach($coupon as $li) { ?>
				<div class="mui-input-row mui-radio">
					<label>
						<div class="coupon-panel">
							<div class="mui-row">
								<div class="mui-col-xs-4 mui-text-center">
									<div class="coupon-panel-left">
										<?php  echo $li['icon'];?>
									</div>
								</div>
								<div class="mui-col-xs-8">
									<div class="store-title mui-ellipsis"><?php  echo $li['title'];?></div>
									<div class="date"><?php  echo $li['extra_date_info'];?></div>
								</div>
							</div>
						</div>
						<input type="radio" name="coupon" value="<?php  echo $li['id'];?>" />
					</label>
					<ol class="coupon-rules" style="display:none;">
						<?php  if(empty($li['description'])) { ?>
						暂无说明
						<?php  } else { ?>
						<?php  echo htmlspecialchars_decode($li['description'])?>
						<?php  } ?>
					</ol>
					<div class="scan-rules js-scan-rules">折扣券使用规则<span class="fa fa-angle-up"></span></div>
				</div>
				<?php  } } ?>
			</div>
		</div>
	</div>
</div>

<div id="pay-select-token-modal" class="mui-modal">
	<header class="mui-bar mui-bar-nav">
		<a class="mui-icon mui-icon-close mui-pull-right" href="javascript:;"></a>
		<h1 class="mui-title">请选择卡券</h1>
	</header>
	<nav class="mui-bar mui-bar-footer">
		<div class="js-coupon-submit">
			<input type="hidden" name="couponid" value="">
			<button class="mui-btn mui-btn-success mui-btn-block js-submit">确定</button>
		</div>
	</nav>
	<div class="mui-content">
		<div class="pay-select-coupon">
			<div class="js-token-show">
				<?php  if(is_array($token)) { foreach($token as $li) { ?>
				<div class="mui-input-row mui-radio">
					<label>
						<div class="coupon-panel">
							<div class="mui-row">
								<div class="mui-col-xs-4 mui-text-center">
									<div class="coupon-panel-left">
										<?php  echo $li['icon'];?>
									</div>
								</div>
								<div class="mui-col-xs-8">
									<div class="store-title mui-ellipsis"><?php  echo $li['title'];?></div>
									<div class="date"><?php  echo $li['extra_date_info'];?></div>
								</div>
							</div>
						</div>
						<input type="radio" name="coupon" value="<?php  echo $li['id'];?>" />
					</label>
					<ol class="coupon-rules" style="display:none;">
						<?php  if(empty($li['description'])) { ?>
						暂无说明
						<?php  } else { ?>
						<?php  echo htmlspecialchars_decode($li['description'])?>
						<?php  } ?>
					</ol>
					<div class="scan-rules js-scan-rules">代金券使用规则<span class="fa fa-angle-up"></span></div>
				</div>
				<?php  } } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on('click', '.js-scan-rules', function() {
		$(this).prev().toggle();
		$(this).find('span').toggleClass('fa-angle-up');
		$(this).find('span').toggleClass('fa-angle-down');
	});
	$(document).on('click', 'input[type="radio"]', function() {
		hidden_couponid = $('input[name="couponid"]').val();
		var couponid = $(this).val();
		if (!hidden_couponid) {
			$('input[type="radio"]').prop('checked', false);
			$(this).prop('checked', true);
			$('input[name="couponid"]').val(couponid);
		} else {
			if (hidden_couponid == couponid) {
				$(this).prop('checked', false);
				$('input[name="couponid"]').val('');
			} else {
				$('input[type="radio"]').prop('checked', false);
				$(this).prop('checked', true);
				$('input[name="couponid"]').val(couponid);
			}
		}
	});
	var cards_str = '<?php  echo $cards_str;?>';
	if (cards_str) {
		cards_str = $.parseJSON(cards_str);
	} else {
		cards_str = {};
	}
	$(document).on('click', '.js-coupon', function() {
		$('#pay-select-coupon-modal').addClass('mui-active');
	});
	$(document).on('click', '.js-token', function() {
		$('#pay-select-token-modal').addClass('mui-active');
	});
	$(document).on('click', '.mui-icon-close', function() {
		$('#pay-select-coupon-modal').removeClass('mui-active');
		$('#pay-select-token-modal').removeClass('mui-active');
	});
	$(document).on('click', '.js-submit', function() {
		var checked_card = $('input[name="couponid"]').val();
		var price = $('.js-need-pay').data('price');
		if(checked_card && cards_str[checked_card]) {
			need_pay = (price - cards_str[checked_card].discount_cn).toFixed(2);
			if (cards_str[checked_card].type == '1') {
				$('.js-coupon .js-card-info').html('已抵用'+cards_str[checked_card].discount_cn+'元');
				$('.js-token .js-card-info').html('未使用');
			};
			if (cards_str[checked_card].type == '2') {
				$('.js-token .js-card-info').html('已抵用'+cards_str[checked_card].discount_cn+'元');
				$('.js-coupon .js-card-info').html('未使用');
			};
			$('.js-need-pay').html(need_pay + ' 元');
			$('.js-pay input[name="coupon_id"]').val(checked_card);
			$('.js-pay input[name="code"]').val(cards_str[checked_card]['code']);
		} else {
			$('.js-need-pay').html(price + ' 元');
			$('.js-token .js-card-info').html('未使用');
			$('.js-coupon .js-card-info').html('未使用');
			$('.js-pay input[name="coupon_id"]').val(0);
		}
		$('#pay-select-coupon-modal').removeClass('mui-active');
		$('#pay-select-token-modal').removeClass('mui-active');
		$('.mui-backdrop').remove('.mui-backdrop');
		$('.pay-method').removeAttr('style');
	})
</script>
<?php  } ?>
<script type="text/javascript">
	check_password = '';
	$('.credit-js-pay').click(function() {
		<?php  if(empty($credit_pay_setting)) { ?>
			$(this).find('form').submit();
			return true;
			<?php  } ?>
				mui.prompt('','','请输入6位数的密码',['<div id="submit_password">确定</div>'],function(){
					$.post("<?php  echo url('mc/cash/check_password');?>", {'password' : $(".mui-popup-input input").val()}, function(data) {
						data = $.parseJSON(data);
						if (data.message == 0) {
							check_password = 'pass';
							$('#credit_pay').submit();
							return false;
						} else {
							alert('密码输入错误');
							return false;
						}
					});
				},'div')
				document.querySelector('.mui-popup-input input').type='password';
				return false;
			});
		<?php  if($pay['mix']['switch'] && $credtis[$setting['creditbehaviors']['currency']] > 0 && $credtis[$setting['creditbehaviors']['currency']] < $params['fee']) { ?>
		var price = parseFloat($('.js-need-pay').data('price'));
		var currency = parseFloat("<?php  echo $credtis[$setting['creditbehaviors']['currency']];?>");
		$(function() {
			$('[name="mix_pay"]').val(true);
			$('.credit').hide();
			fee = (price * 100 - currency * 100)/100;
			$('.js-need-pay').data('price', fee);
			$('.js-need-pay').html(fee + '元');
		})
		<?php  } ?>
		$('[name="mix"]').click(function() {
			if ($(this).prop('checked') === true) {
				$('[name="mix_pay"]').val(true);
				fee = (price * 100 - currency * 100)/100;
				$('.credit').hide();
			} else {
				$('[name="mix_pay"]').val(false);
				fee = (price * 100)/100;
				$('.credit').show();
			}
			$('.js-need-pay').data('price', fee);
			$('.js-need-pay').html(fee + '元');
		});
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		var miniprogram_environment = false;
		if(wx && wx.miniProgram && wx.miniProgram.getEnv) {
			wx.miniProgram.getEnv(function(res) {
				if (res.miniprogram) {
					miniprogram_environment = true;
				}
			})
		}
		if(window.__wxjs_environment === 'miniprogram' || miniprogram_environment) {
			$('.pay-style li').hide();
			$('.js-webwxapp-pay').removeClass('hide');
			$('.pay-style .js-webwxapp-pay').show();
			$('.js-webwxapp-pay').click(function(){
				wx.miniProgram.navigateTo({
					url: "/wxapp_web/pages/view/pay?orderid=<?php  echo $params['tid'];?>&module_name=<?php  echo $params['module'];?>&title=<?php  echo $params['title'];?>"
				})
			});
		}
		$('.js-wechat-pay').removeClass('mui-disabled');
		$('.js-wechat-pay a').addClass('js-pay');
		$('#wetitle').html('微信支付');
	});

	$(document).on('click', '.js-pay', function() {
		$(this).prop('disabled', true);
		$(this).find('form').submit();
	})
</script>


