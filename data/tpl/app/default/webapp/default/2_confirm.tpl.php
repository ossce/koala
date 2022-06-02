<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/confirm.css?v=<?php  echo $versions;?>">

<div class="w-all w-minw bg-c-fcfcfc ovhidden">
	<div class="w-1200 m-auto">
		<div class="buyStepBook">
			<div class="bookDetail m-b-20">
				<div class="thead">
					<div class="flex flex-vc">
						<div class="cell text-c" style="width:48%">课程信息</div>
						<div class="cell text-c" style="width:26%">价格</div>
						<div class="cell text-c" style="width:26%">操作</div>
					</div>
				</div>
				<div class="tbody">
					<div class="goodstInfo">
						<ul class="flex flex-v">
							<li class="items flex flex-vc m-b-20">
								<div class="cell goodsName flex flex-vc" style="width:48%">
									<a class="img"><img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['images'];?>"></a>
									<div class="info flex flex-v">
										<a class="name"><?php  echo $lesson['bookname'];?></a>
										<div class="spec fs-12">
											课程规格：
											<?php  if($lesson['lesson_type']=='0' ||  $lesson['lesson_type']==3) { ?>
												<?php echo $spec['spec_day']==-1 ? '长期有效' : '有效期'.$spec['spec_day'].'天';?>
											<?php  } else { ?>
												<?php  echo $spec['spec_name'];?>
											<?php  } ?>
										</div>
									</div>
								</div>
								<div class="cell text-c fs-13" style="width:26%">¥<?php  echo $spec['spec_price'];?></div>
								<div class="cell text-c" style="width:26%"><a class="fs-12 ftc-525252" target="_blank" href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>">查看课程</a></div>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="buyBalance flex">
				<div class="balanceLeft flex flex-v">
					<div class="mb10">
						<div class="title">可选优惠券 (<i><?php  echo count($coupon_list);?></i> 张)</div>
						<div class="couponSelect">
							<div class="selectTerm flex flex-hw">
								<a href="javascript:;" class="selectBtn" id="showCoupon">请选择优惠券</a>
								<div class="couponHandle flex flex-vc">
									<a class="btnhandle" href="javascript:;" name="noUseCoupon">不使用</a>
								</div>
							</div>
						</div>
					</div>

					<?php  if($deduct_switch) { ?>
					<div class="mb10">
						<div class="title"><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>抵扣<span class="fs-12">（本次最多可使用<?php  echo $deduct_integral;?><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>，当前100<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>可抵扣<?php  echo $market['deduct_money']*100;?>元）</span></div>
						<div class="couponSelect">
							<div class="selectTerm flex flex-hw">
								<input type="text" class="w-100 p-l-10 p-r-10" id="deduct_integral" onblur="checkIntegral(this.value)" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
								<div class="couponHandle flex flex-vc fs-12 ftc-e9511b" id="notice"></div>
							</div>
						</div>
					</div>
					<?php  } ?>

					<?php  if($lesson['lesson_type']==1 && !empty($appoint_info)) { ?>
					<div class="buyMessage" id="appoint_div">
						<div class="title">【请填写以下信息】</div>
						<?php  if(is_array($appoint_info)) { foreach($appoint_info as $item) { ?>
						<div class="message">
							<span class="appoint_item fs-14"><?php  echo $item;?>：</span>
							<input type="text" class="p-l-10 p-r-10 hg-30" name="appoint_info[]" placeholder="请填写<?php  echo $item;?>">
						</div>
						<?php  } } ?>
					</div>
					<?php  } ?>
				</div>
				<div class="balanceRight flex flex-v">
					<div class="balancePrice">
						<ul class="flex flex-v">
							<li class="flex flex-vc flex-fd items">
								<span class="name">课程售价：</span>
								<div class="w180 of">
									<span class="price">¥ <?php  echo $spec['spec_price'];?></span>
								</div>
							</li>
							<li class="flex flex-vc flex-fd items">
								<span class="name">积分抵扣：</span>
								<div class="w180 of">
									-<span class="price">¥</span>
									<span class="price" id="deduct_money">0</span>
								</div>
							</li>
							<li class="flex flex-vc flex-fd items">
								<span class="name">VIP优惠：</span>
								<div class="w180 of">
									-<span class="price">¥</span>
									<span class="price" id="vipDiscount"><?php  echo $vipCoupon;?></span>
								</div>
							</li>
							<li class="flex flex-vc flex-fd items">
								<span class="name">优惠券：</span>
								<div class="w180 of">
									-<span class="price">¥</span>
									<span class="price" id="coupon_money">0</span>
								</div>
							</li>
						</ul>
					</div>

					<div class="totalAmount flex flex-fd">
						<span>应付总额 :</span>
						<span class="price">¥</span><span class="price" id="total"><?php  echo $price;?></span>
					</div>
					<div class="btns btns text-r">
						<input type="hidden" id="coupon_id" value="0"/>
						<input type="hidden" id="couponMoney" value="0"/>
						<input type="hidden" id="deducMoney" value="0"/>
						<a href="javascript:;" class="bgBtn btn-primary" id="goPayBtn">去付款</a>
					</div>

					<?php  if($setting['lesson_agreement']) { ?>
					<div class="flex flex-fd m-t-20">
						<div class="fs-14">
							<input type="checkbox" name="lesson_agreement" value="1" style="vertical-align:-2px;" checked=""> 我已阅读并同意<a href="javascript:;" id="view-lesson-agreement" class="ftc-c78800">《购买课程服务协议》</a>
						</div>
					</div>
					<div class="agreement-area-mask lesson-agreement-content" style="display:none;">
						<div class="agreement-area">
							<div class="close close-lesson-agreement">
								<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/btn-close.png" width="32" height="32">
							</div>
							<h3 class="notice-title">购买课程服务协议</h3>
							<ul class="notice-body text-l">
								<?php  echo htmlspecialchars_decode($setting['lesson_agreement'])?>
							</ul>
						</div>
					</div>
					<?php  } ?>

				</div>
			</div>
		</div>
	</div>
	<div class="hg-40"></div>
</div>

<!-- 优惠券弹出层 -->
<div class="coupon-modal-wrap"></div>
<div class="coupon-modal" id="couponModal">
	<div class="buyCouponSelect">
		<div class="modal-hd">
			<h3 class="hd-tt fs-16">选择优惠券</h3>
		</div>
		<ul class="coupons-list">
			<?php  if(is_array($coupon_list)) { foreach($coupon_list as $item) { ?>
			<li class="coupon-item" onclick="selectCoupon(this, <?php  echo $item['id'];?>, <?php  echo $item['amount'];?>);">
				<a href="javascript:;">
					<div class="fl">
						<div class="coupon-par"><em><?php  echo $item['amount'];?></em>元</div>
						<div class="coupon-name">课程优惠券</div>
					</div>
					<div class="couponSep"></div>
					<div class="fr">
						<div class="coupon-condition">
							<?php  if($item['conditions']>0) { ?>
								消费满<?php  echo $item['conditions'];?>元可用
							<?php  } else { ?>
								任意金额可用
							<?php  } ?>
						</div>
						<div class="coupon-time">有效期<?php  echo date('Y-m-d H:i', $v['validity']);?></div>
						<div class="coupon-other fs-12">
							<span class="info">
								<i class="iconfont icon-information-o vertical-middle"></i> 使用范围
								<div class="con"><?php  echo $item['category_name'];?>可使用</div>
							</span>
						</div>
					</div>
					<i class="selected"></i>
				</a>
			</li>
			<?php  } } ?>
			<div class="clear"></div>
		</ul>
	</div>
	<div class="modal-ft">
		<a href="javascript:;" title="取消" class="btn btn-default m-r-10" name="noUseCoupon">取消使用</a>
		<a href="javascript:;" title="确定" class="btn btn-confirm" name="useCoupon">确认使用</a>
	</div>
</div>


<script type="text/javascript">
	//定位提示框
	if (window.innerHeight){
		winHeight = window.innerHeight;
	}else if ((document.body) && (document.body.clientHeight)){
		winHeight = document.body.clientHeight;
	}
	window.onload = function() { 
		var top = (winHeight-427)/2;
		document.getElementById("couponModal").style.top = top + 'px';
	}

	//显示优惠券
	$("#showCoupon").click(function(){
		$(".coupon-modal-wrap").show();
		$(".coupon-modal").show();
	});
	//隐藏优惠券
	function hideCoupon(){
		$(".coupon-modal-wrap").hide();
		$(".coupon-modal").hide();
	}
	$("a[name=useCoupon]").click(function(){
		hideCoupon();
	});
	$("a[name=noUseCoupon]").click(function(){
		hideCoupon();
		selectCoupon(this, 0, 0);
		$('.coupon-item').removeClass('disabled');
		$("#showCoupon").removeClass('selected').text('请选择优惠券');
	});
	
	//选择优惠券
	function selectCoupon(o, id, amount){
		$('.coupon-item').removeClass('checked').addClass('disabled');
		$(o).addClass('checked').removeClass('disabled');

		$("#coupon_id").val(id);
		$("#couponMoney").val(amount);
		calculate();

		$("#showCoupon").addClass('selected').text('已选择优惠券');
	}

	//确认计算优惠券金额
	function calculate(){
		var couponMoney = parseFloat($("#couponMoney").val());
		var total = parseFloat(document.getElementById("total").innerHTML);
		var coupon_money = parseFloat(document.getElementById("coupon_money").innerHTML);
		var price = <?php echo $price ? $price : '0'?>;

		var lastTotal = (total + coupon_money - couponMoney).toFixed(2);
		if(lastTotal<=0){
			lastTotal = 0;
			$("#couponMoney").val(price);
			document.getElementById("coupon_money").innerHTML = price;
		}
		document.getElementById("coupon_money").innerHTML = couponMoney;
		document.getElementById("total").innerHTML = lastTotal;
	}

	//计算积分抵扣金额
	var credit1_font = "<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>";
	function checkIntegral(integral){
		var deduct_integral = <?php echo $deduct_integral ? $deduct_integral : 0?>;
		var deduct_money = <?php echo $market['deduct_money'] ? $market['deduct_money'] : 0?>;

		if(integral > deduct_integral){
			document.getElementById("notice").innerHTML = "当前最多可使用"+deduct_integral+"个" + credit1_font + "，请重新输入";
			return false;
		}else if(integral>0){
			document.getElementById("notice").innerHTML = "可帮您抵扣" + (integral*deduct_money).toFixed(2) + "元";
		}else if(integral==0){
			document.getElementById("notice").innerHTML = "";
		}

		var total = parseFloat(document.getElementById("total").innerHTML);
		var nowCouponAmount = (integral*deduct_money).toFixed(2);
		if(nowCouponAmount > total){
			document.getElementById("notice").innerHTML = "当前输入" + credit1_font + "抵消金额大于应付金额，请重新输入";
			return false;
		}

		document.getElementById("deduct_money").innerHTML = nowCouponAmount;

		var lastTotal = (total + parseFloat($("#deducMoney").val()) - nowCouponAmount).toFixed(2);
		if(lastTotal<=0){
			lastTotal = 0;
		}
		document.getElementById("total").innerHTML = lastTotal;
		$("#deducMoney").val(nowCouponAmount);
	}

	$("#goPayBtn").click(function(){
		var credit1 = <?php echo $member['credit1'] ? $member['credit1'] : 0?>;
		var deduct_integral = $("#deduct_integral").val();
		var lesson_integral = <?php echo $deduct_integral ? $deduct_integral : 0?>;
		if(deduct_integral > lesson_integral){
			swal("系统提示", "当前课程最多可使用"+lesson_integral+credit1_font+"，请重新输入", "error");
			return false;
		}
		if(deduct_integral > credit1){
			swal("系统提示", "您的"+credit1_font+"不足，请重新输入", "error");
			return false;
		}

		<?php  if(!empty($appoint_info) && $lesson['lesson_type']==1){ ?>
			var ipt = document.getElementById("appoint_div").getElementsByTagName("input");
			var appoint_info = <?php  echo $lesson['appoint_info']?>;
			for(var i = 0; i < ipt.length; i++){
				if(ipt[i].value.length == 0){
					swal("系统提示", "请填写"+appoint_info[i], "error");
					ipt[i].focus();
					return false;
				}
				var reg = /[^\u4e00-\u9fa5a-zA-Z0-9]/g; //正则非中英文数字
				var mail = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则邮箱 
				if(reg.test(ipt[i].value) && !mail.test(ipt[i].value)){
					swal("系统提示", appoint_info[i]+"不能包含中英文数字外的字符", "error");
					return false;
				}
			}
		<?php  } ?>

		<?php  if(!empty($setting['lesson_agreement'])){ ?>
			var lesson_agreement = false;
			$("input[name='lesson_agreement']").each(function () {
				if ($(this).is(":checked")) {
					lesson_agreement = true;
				}
			});
			if(!lesson_agreement){
				swal("系统提示", "请阅读并同意《购买课程服务协议》");
				return false;
			}
		<?php  } ?>

		//循环遍历报名课程信息
		var appoint_data = [];
		var i=0;
		$("input[name='appoint_info[]']").each(
			function(){
				appoint_data[i++] = $(this).val();
			}
		)

		$("#loadingToast").show();
		$.ajax({
			url: "/<?php  echo $uniacid;?>/addOrder.html",
			type:"post",
			dataType:"json",
			data: {
					id: "<?php  echo $id;?>",
					spec_id: "<?php  echo $spec_id;?>",
					coupon_id: $("#coupon_id").val(),
					deduct_integral: $("#deduct_integral").val(),
					appoint_info: appoint_data,
				  },
			success: function (res) {
				$("#loadingToast").hide();
				if(res.code==0){
					window.location.href = res.gourl;
				}else{
					swal("系统提示", res.message, "error");
					return false;
				}
			},
			error: function(e){
				$("#loadingToast").hide();
				swal("系统提示", "网络超时，请稍候重试", "error");
				return false;
			}
		});

	});

	//购买课程服务协议内容
	$("#view-lesson-agreement").click(function(){
		$('.lesson-agreement-content').show();
	});
	$(".close-lesson-agreement").click(function(){
		$('.lesson-agreement-content').hide();
	});
</script>

<?php  include $this->template($template.'/_footer')?>