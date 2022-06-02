<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery.qrcode.min.js"></script>
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/payment.css?v=<?php  echo $versions;?>">

<div class="w-all w-minw bg-c-fcfcfc ovhidden">
    <div class="w-main m-auto"> 
        <div class="w-1200 bg-c-ffffff fl">
            <div class="payContent flex flex-v">
				<div class="payOrders flex flex-v">
					<h1>选择付款方式</h1>
					<div class="flex flex-vc m-t-10">
						<p class="flex1 ordersNum">订单编号：<?php  echo $params['tid'];?></p>
					</div>
					<div class="flex flex-vc m-t-10">
						<p class="flex1 ordersNum">消费详情：<?php  echo $params['title'];?></p>
					</div>
					<div class="flex flex-vc m-t-10">
						<p class="totalPrice">
							应付金额：<span class="price"><span class="yuan">¥ <?php  echo $params['fee'];?></span>
						</p>
					</div>
				</div>
				<div class="flex flex-v payment">
					<p class="title">支付方式</p>
					<div class="payMode flex flex-v">
					<?php  if($op=='display') { ?>
						<ul class="flex flex-vc">
							<?php  if($pc_payment['credit']['pay_switch']) { ?>
							<li class="items flex flex-vc" data-payment-code="credit">
								<div class="flex flex-vc flex-hc">
									<i id="credit" class="fakeInput"></i>
									<i class="payIcon credit2"></i>
								</div>
							</li>
							<?php  } ?>
							<?php  if($pc_payment['alipay']['pay_switch']) { ?>
							<li class="items flex flex-vc" data-payment-code="alipay">
								<div class="flex flex-vc flex-hc">
									<i id="alipay" class="fakeInput"></i>
									<i class="payIcon alipay"></i>
								</div>
							</li>
							<?php  } ?>
							<?php  if($pc_payment['wechat']['pay_switch']) { ?>
							<li class="items flex flex-vc" data-payment-code="wechat">
								<div class="flex flex-vc flex-hc">
									<i id="wechat" class="fakeInput"></i>
									<i class="payIcon wxpay"></i>
								</div>
							</li>
							<?php  } ?>
						</ul>
						<?php  if($pc_payment['credit']['pay_switch']) { ?>
						<div class="deposit flex flex-v" style="display: flex;">
							<i class="arrow"></i>
							<p class="remainder">当前余额：<span class="price"><span class="yuan">¥ <?php  echo $member['credit2'];?></span></p>
						</div>
						<?php  } ?>
					<?php  } else if($op=='gopay') { ?>
						<?php  if($paytype=='wechat') { ?>
						<div class="pay-weixin">
							<div class="p-w-hd">微信支付</div>
							<div class="p-w-bd" style="position:relative">
								<div class="wechatInfo" style="position:absolute;top:-36px;left:130px;">距离二维码过期还剩<span class="font-red">60</span>秒，过期后请刷新页面重新获取二维码。</div>
								<div class="p-w-box">
									<div class="pw-box-hd" id="wechatPayImage">
									</div>
									<div class="pw-box-ft">
										<p>请使用微信扫一扫</p>
										<p>扫描二维码支付</p>
									</div>
								</div>
								<div class="p-w-sidebar"></div>
							</div>
						</div>
						<script type="text/javascript">
							jQuery('#wechatPayImage').qrcode({width: 298, height: 298, text: "<?php  echo $wechat_result['code_url'];?>"});
							
							var countdown = 59;
							setInterval(function() {
								if(countdown>0){
									$(".font-red").text(countdown);
									countdown--;
								}else{
									$('.wechatInfo').addClass('font-red').text('二维码已过期，请刷新页面重新获取二维码。');
									document.getElementById("wechatPayImage").innerHTML = '<img src="<?php echo MODULE_URL;?>static/webapp/default/images/expire_qrcode.png"></img>';
									return;
								}
							}, 1000);

							var timing = 0;
							var jump_url = '';
							function pay_status(){
								if(timing >= 65){
									window.clearInterval(pay_status_int);
									return;
								}
								timing += 3;

								var orderid = "<?php  echo $_GPC['orderid'];?>";
								var ordertype = "<?php  echo $_GPC['ordertype'];?>";
								$.ajax({
									type: "post",
									url: "/<?php  echo $uniacid;?>/getOrderStatus.html",
									dataType: "json",
									data: {orderid:orderid, ordertype:ordertype},
									success:function(res){
										if(res.code==0 && res.orderstatus>0){
											jump_url = res.url;

											if(res.ordertype=='liveaward'){
												window.clearInterval(pay_status_int);
												window.location.href = jump_url;
												return;
											}else{
												swal("支付成功", "恭喜您，您的订单已支付成功", "success");
												window.clearInterval(pay_status_int);
												return;
											}
										}
									}, 
									error:function(){   
									},   
								});
							}
							var pay_status_int = self.setInterval(function(){pay_status()},3000);

							$(document).on("click",".swal-confirm",function(){
								if(jump_url){
									window.location.href = jump_url;
								}
							});
						</script>
						<?php  } ?>
					<?php  } ?>
					</div>
				</div>
				<div class="btns m-b-33 text-r">
				<?php  if($op=='display') { ?>
					<input type="hidden" id="paytype" value="">
					<a href="javascript:;" class="bgBtn btn-primary" id="goToPay">立即付款</a>
				<?php  } else if($op=='gopay') { ?>
					<a href="javascript:;" class="bgBtn btn-ghost" onClick="javascript:history.back(-1);">返回</a>
				<?php  } ?>
				</div>
			</div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(".payMode ul li").click(function(){
		var payment_code = $(this).attr("data-payment-code");
		var order_price = "<?php  echo $params['fee'];?>";
		var member_credit = "<?php  echo $member['credit2'];?>";

		if(payment_code=='credit'  && (order_price*1) > (member_credit*1)){
			swal("系统提示", "您的可用余额不足，请选择其他支付方式", "error");
			return false;
		}

		$('.fakeInput').removeClass('checked');
		$("#"+payment_code).addClass('checked');
		$("#paytype").val(payment_code);
	});

	$("#goToPay").click(function(){
		var paytype = $("#paytype").val();
		if(paytype==''){
			swal("系统提示", "请选择支付方式", "error");
			return false;
		}

		$("#loadingToast").show();
		window.location.href = "/<?php  echo $uniacid;?>/payment.html?op=gopay&orderid=<?php  echo $orderid;?>&ordertype=<?php  echo $_GPC['ordertype'];?>&paytype="+paytype;
	});
</script>

<?php  include $this->template($template.'/_footer')?>