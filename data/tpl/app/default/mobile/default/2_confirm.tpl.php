<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 确认下单
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/confirm.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div class="order-form">
	<form id="orderForm" method="post" action="<?php  echo $this->createMobileUrl('addtoorder');?>">
	<!-- 课程订单信息 -->
	<div class="confirm-order">
		<div class="addorder_good ">
			<div class="ico"><img src="<?php  echo $teacherphoto;?>" /></div>
			<div class="shop"><?php  echo $lesson['teacher'];?></div>
			<div class="good">
				<div class="img" onclick="location.href = '<?php  echo $lessonurl;?>'">
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['images'];?>" alt=""/>
				</div>
				<div class="info" onclick="location.href = '<?php  echo $lessonurl;?>'">
					<div class="inner">
						<div class="name"><?php  echo $lesson['bookname'];?></div>
						<p style="font-size:12px;color:#B3B3B3;">
							已选：
							<?php  if($lesson['lesson_type']=='0' || $lesson['lesson_type']=='3') { ?>
								<?php echo $spec['spec_day']==-1 ? '长期有效' : '有效期'.$spec['spec_day'].'天';?>
							<?php  } else { ?>
								<?php  echo $spec['spec_name'];?>
							<?php  } ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		
		<div class="addorder_price sel_coupon" style="margin-top:16px;">
			<div class="price" style="border:none;">
				<div class="line" style="line-height:32px;">
					优惠券 <i class="coupon"><?php  echo count($coupon_list);?>张可用</i><span>&gt;</span>
				</div>
			</div>
		</div>
		<?php  if($deduct_switch) { ?>
		<div class="addorder_price" style="margin-top:16px;">
			<div class="price" style="border:none;">
				<div class="line" style="line-height:32px;">
					<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>抵扣（您的<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>: <em style="color:red;"><?php  echo intval($mc_member['credit1'])?></em>）
				</div>
			</div>
			<div id="integral_div">
				<div class="coupon-code">
					<input type="text" name="deduct_integral" id="deduct_integral" onblur="checkIntegral(this.value)" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="最多可使用<?php  echo $deduct_integral;?><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>，当前100<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>可抵扣<?php  echo $market['deduct_money']*100;?>元">
					<br/>
					<span id="notice" style="font-size:12px;color:#f23030;font-weight:bold;"></span>
				</div>
			</div>
		</div>
		<?php  } ?>

		<?php  if($lesson['lesson_type']==1 && !empty($appoint_info)) { ?>
		<div class="weui-cells" id="appoint_div">
			<div class="weui-cell">
				【请填写以下信息】
			</div>
			<?php  if(is_array($appoint_info)) { foreach($appoint_info as $item) { ?>
			<div class="weui-cell">
				<div class="weui-cell__hd"><label class="weui-label"><?php  echo $item;?></label></div>
				<div class="weui-cell__bd">
					<input type="text" name="appoint_info[]" class="weui-input" placeholder="请填写<?php  echo $item;?>">
				</div>
			</div>
			<?php  } } ?>
		</div>
		<?php  } ?>

		<div class="addorder_price">
			<div class="price" style="border:none;">
				<div class="line">
					课程金额
					<span>￥<span class="goodsprice"><?php  echo $spec['spec_price'];?></span></span>
				</div>
				<?php  if($vipCoupon>0) { ?>
				<div class="line">
					VIP折扣
					<span>-￥<span class="goodsprice"><?php  echo $vipCoupon;?></span></span>
				</div>
				<?php  } ?>
				<?php  if(count($coupon_list)>0) { ?>
				<div class="line" id="integral-div">
					优惠券抵扣
					<span>-￥<span class="goodsprice" id="coupon_money">0</span></span>
				</div>
				<?php  } ?>
				<?php  if($deduct_switch) { ?>
				<div class="line" id="integral-div"><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>抵扣<span>-￥<span class="goodsprice" id="deduct_money">0</span></span></div>
				<?php  } ?>
				<?php  if($apply_price>0) { ?>
				<div class="line">
					报名课程优惠
					<span>-￥<span class="goodsprice"><?php  echo $apply_price;?></span></span>
				</div>
				<?php  } ?>
				<div class="line" style="color:#f23030;">
					应付金额
					<span class="total" id="total" style="font-size:18px;"><?php  echo $price;?></span>
				</div>
			</div>
		</div>
		<?php  if($setting['lesson_agreement']) { ?>
		<div class="weui-cells weui-cells_checkbox">
			<label class="weui-cell weui-cell_active weui-check__label" for="lesson_agreement">
				<div class="weui-cell__hd" style="padding-right:10px;">
					<input type="checkbox" class="weui-check" id="lesson_agreement" checked="">
					<i class="weui-icon-checked vip_agreement_checked"></i>
				</div>
				<div class="weui-cell__bd agreement_tips">
					<p>我已阅读并同意<a href="javascript:;" id="view-lesson-agreement">《购买课程服务协议》</a></p>
				</div>
			</label>
		</div>
		<div class="privacy_agreement_notice-mask" id="lesson-agreement-content" style="display:none;">
			<div class="privacy_agreement_notice">
				<div class="close">
					<img src="<?php echo MODULE_URL;?>static/mobile/default/images/btn-close.png?v=6" width="32" height="32">
				</div>
				<h3 class="notice-title">购买课程服务协议</h3>
				<ul class="notice-body">
					<?php  echo htmlspecialchars_decode($setting['lesson_agreement'])?><p><br></p>
				</ul>
			</div>
		</div>
		<?php  } ?>
		<input type="hidden" name="id" value="<?php  echo $lesson['id'];?>"/>
		<input type="hidden" name="spec_id" value="<?php  echo $spec_id;?>"/>
		<input type="hidden" name="coupon_id" id="coupon_id" value="0"/>
		<input type="hidden" id="couponMoney" value="0"/>
		<input type="hidden" id="deducMoney" value="0"/>
		<div class="paysub" onclick="subForm()">立即支付</div>
		<div class="h-1">
			
		</div>
	</div>

	<!-- 优惠券列表 -->
	<div class="common-wrapper" style="display:none;">
		<div class="tab-con">
			<div class="new-coupon" onclick="useCoupon(this, 0, 0);">
				<div class="new-bdcolor bd-bd">
					<div class="newCou-bg newCou-bg"></div>
					<div class="newCou-item" style="color:#a9a9a9;">
						<div class="newCou-content cf" style="padding-bottom: 15px;">
							<div class="fl">
								<div class="ci-left">
									<strong class="pic-ch"></strong>
								</div>
								<div class="newCou-l">
									<div class="newCou-pri-content myf-newCou-pri-content">
										<span class="newCou-price myf-price">不使用优惠券</span>
									</div>
								</div>
							</div>
							<div class="newCou-r">
								<span class="newCou-date-name">&nbsp;</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php  if(is_array($coupon_list)) { foreach($coupon_list as $item) { ?>
			<div class="new-coupon" onclick="useCoupon(this, <?php  echo $item['id'];?>, <?php  echo $item['amount'];?>);">
				<div class="new-bdcolor bd-bd">
					<div class="newCou-bg myf-bg"></div>
					<div class="newCou-item yf-icon-color">
						<div class="newCou-title">优惠券</div>
						<div class="newCou-content cf">
							<div class="fl">
								<div class="ci-left">
									<strong class="pic-ch"></strong>
								</div>
								<div class="newCou-l">
									<div class="newCou-pri-content myf-newCou-pri-content">
										<span class="newCou-price myf-price">抵扣<?php  echo $item['amount'];?>元</span>
									</div>
								</div>
							</div>
							<div class="newCou-r">
								<span class="newCou-date-name">课程金额满<?php  echo $item['conditions'];?>元，<?php  echo $item['category_name'];?>可使用</span>
								<span class="newCou-date-content"><?php  echo date('Y-m-d',$item['addtime']);?>-<?php  echo date('Y-m-d',$item['validity']);?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php  } } ?>
		</div>
		<div class="btn-bar" id="submitDiv">
			<a href="javascript:;" id="confirm-coupon" class="bb-btn02 button-change-w">确定</a>
		</div>
	</div>
	</form>
</div>


<script type="text/javascript">
	//选择优惠券
	$(".sel_coupon").click(function(){
		$(".confirm-order").hide();
		$(".common-wrapper").show();
	});

	$("#confirm-coupon").click(function(){
		$(".confirm-order").show();
		$(".common-wrapper").hide();
	});

	function useCoupon(o, couponId, amount){
		$(".pic-ch").removeClass("pic-ched");
		$(o).find("strong").addClass("pic-ched");
		$("#coupon_id").val(couponId);
		$("#couponMoney").val(amount);
	}
	//计算优惠券金额
	$("#confirm-coupon").click(function(){
		var couponMoney = parseFloat($("#couponMoney").val());
		var total = parseFloat(document.getElementById("total").innerHTML);
		var coupon_money = parseFloat(document.getElementById("coupon_money").innerHTML);
		var price = <?php echo $price ? $price : '0'?>;

		document.getElementById("coupon_money").innerHTML = couponMoney;

		var lastTotal = (total + coupon_money - couponMoney).toFixed(2);
		if(lastTotal<=0){
			lastTotal = 0;
			$("#couponMoney").val(price);
			document.getElementById("coupon_money").innerHTML = price;
		}

		document.getElementById("total").innerHTML = lastTotal;
		 
	});

	//计算积分抵扣金额
	var credit1_font = "<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>";
	function checkIntegral(integral){
		var deduct_integral = <?php echo $deduct_integral ? $deduct_integral : 0?>;
		var deduct_money = <?php echo $market['deduct_money'] ? $market['deduct_money'] : 0?>;

		if(integral > deduct_integral){
			document.getElementById("notice").innerHTML = "当前最多可使用"+deduct_integral+"个" + credit1_font + "，请重新输入";
			return false;
		}else{
			document.getElementById("notice").innerHTML = "可帮您抵扣" + (integral*deduct_money).toFixed(2) + "元";
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

	function subForm(){
		var credit1 = <?php echo $member['credit1'] ? $member['credit1'] : 0?>;
		var deduct_integral = $("#deduct_integral").val();
		var lesson_integral = <?php echo $deduct_integral ? $deduct_integral : 0?>;
		if(deduct_integral > lesson_integral){
			alert("当前课程最多可使用"+lesson_integral+credit1_font+"，请重新输入");
			return false;
		}
		if(deduct_integral > credit1){
			alert("您的"+credit1_font+"不足，请重新输入");
			return false;
		}

		<?php  if(!empty($appoint_info) && $lesson['lesson_type']==1){ ?>
			var ipt = document.getElementById("appoint_div").getElementsByTagName("input");
			var appoint_info = <?php  echo $lesson['appoint_info']?>;
			for(var i = 0; i < ipt.length; i++){
				if(ipt[i].value.length == 0){
					alert("请填写"+appoint_info[i]);
					ipt[i].focus();
					return false;
				}
				var reg = /[^\u4e00-\u9fa5a-zA-Z0-9]/g; //正则非中英文数字
				var mail = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则邮箱 
				if(reg.test(ipt[i].value) && !mail.test(ipt[i].value)){
					alert(appoint_info[i]+"不能包含中英文数字外的字符");
					return false;
				}
			}
		<?php  } ?>

		<?php  if(!empty($setting['lesson_agreement'])){ ?>
			if(!$("#lesson_agreement").is(':checked')){
				alert("请阅读并同意《购买课程服务协议》");
				return false;
			}
		<?php  } ?>

		document.getElementById("loadingToast").style.display = 'block';
		document.getElementById("orderForm").submit();
	}

	//查看VIP服务协议
	$("#view-lesson-agreement").click(function(){
		$('#lesson-agreement-content').fadeIn(200).unbind('click').click(function(){
			$(this).fadeOut(100);
		})
	});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>
