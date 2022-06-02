<?php defined('IN_IA') or exit('Access Denied');?><div class="writemsg_shade" style="display:none;"></div>
<div class="writemsg_wrap"  style="display:none;">
	<div class="weui-cells weui-cells_form" style="border-radius:10px; padding-bottom:20px;">
		<h3 style="padding:15px 0; text-align:center; font-size:18px;">完善信息</h3>
		<?php  if($setting['mustinfo']!=2 || !$_GPC['sectionid']) { ?>
		<a href="javascript:;" onclick="closeBox();" style="width:20px;height:20px;color:#aaa;position:absolute;right:15px;top:17px;"><i class="fa fa-close fa-lg"></i></a>
		<?php  } ?>
		<div id="writemsg_fields" style="max-height:240px;overflow-y:auto;">
		<?php  if(is_array($common_member_fields)) { foreach($common_member_fields as $item) { ?>
			<?php  if(in_array($item['field_short'],$user_info)) { ?>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label"><?php  echo $item['field_name'];?></label></div>
					<div class="weui-cell__bd">
						<input type="text" class="weui-input" name="<?php  echo $item['field_short'];?>" placeholder="请输入<?php  echo $item['field_name'];?>" value="<?php  echo $member[$item['field_short']];?>">
					</div>
				</div>
				<?php  if($item['field_short']=='mobile' && $sms['template_id']) { ?>
				<div class="weui-cell weui-cell_vcode">
					<div class="weui-cell__hd">
						<label class="weui-label">验证码</label>
					</div>
					<div class="weui-cell__bd">
						<input type="tel" class="weui-input" name="verify_code" placeholder="请输入验证码">
					</div>
					<div class="weui-cell__ft">
						<a href="javascript:;" class="weui-vcode-btn" id="weui_btn_send" onclick="sendcode()">获取验证码</a>
					</div>
				</div>
				<?php  } ?>
			<?php  } ?>
		<?php  } } ?>
		</div>

		<?php  if($setting['privacy_agreement']) { ?>
		<div class="weui-cells weui-cells_checkbox">
			<label class="weui-cell weui-cell_active weui-check__label" for="privacy_agreement">
				<div class="weui-cell__hd" style="padding-right:10px;">
					<input type="checkbox" class="weui-check" id="privacy_agreement" checked />
					<i class="weui-icon-checked privacy_agreement_checked"></i>
				</div>
				<div class="weui-cell__bd agreement_tips">
					<p>我已阅读并同意<a href="javascript:;" id="view_writemgs_agreement">《用户服务(隐私)协议》</a></p>
				</div>
			</label>
		</div>
		<?php  } ?>
		<div class="weui-btn-area" style="margin-top: 16px;">
			<a href="javascript:;" class="weui-btn weui-btn_primary btn-submit-writemsg">提交</a>
		</div>
	</div>
</div>

<div class="privacy_agreement_notice-mask" id="privacy-agreement-content" style="display:none;">
	<div class="privacy_agreement_notice">
		<div class="close">
			<img src="<?php echo MODULE_URL;?>static/mobile/default/images/btn-close.png?v=6" width="32" height="32">
		</div>
		<h3 class="notice-title">用户服务(隐私)协议</h3>
		<ul class="notice-body">
			<?php  echo htmlspecialchars_decode($setting['privacy_agreement'])?><p><br></p>
		</ul>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var window_height = $(window).height();
		var writemsg_height = $('.writemsg_wrap').outerHeight();
		var writems_margin_top = (window_height - writemsg_height)/2;
		$('.writemsg_wrap').css("top", writems_margin_top + "px");
	})

	function closeBox(){
		$(".writemsg_shade").hide();
		$(".writemsg_wrap").hide();
	}

	var countdown = 60;
	function sendcode() {
		var result = checkMobile();
		if(!result){
			return;
		}
		if ($('#weui_btn_send').hasClass('has_send')) {
			return false;
		}

		var mobile = $('input[name="mobile"]').val();
		$.ajax({
			type:"post",
			dataType:"json",
			url: "<?php  echo $this->createMobileUrl('sendcode');?>",
			data: {mobile:mobile},
			success: function (data) {
				if(data.code==0){
					settime($("#weui_btn_send"));
					$("#weui_btn_send").addClass("grey-color");
				}else{
					alert(data.msg);
				}
			},
			error: function(e){
			}
		});
		
	}
	function settime(obj) { //发送验证码倒计时
		if(countdown == 0) {
			$('#weui_btn_send').removeClass('has_send').text('重新发送');
			countdown = 60;
			return;
		} else {
			$('#weui_btn_send').addClass('has_send').text('重新获取(' + countdown + ')');
			countdown--;
		}
		setTimeout(function() {
			settime(obj)
		}, 1000)
	}
	//校验手机号是否合法
	function checkMobile() {
		var mobile = $('input[name="mobile"]').val();
		var myreg = /^((1)+\d{10})$/;
		if(!myreg.test(mobile)) {
			alert('请输入有效的手机号码');
			return false;
		} else {
			return true;
		}
	}

	//查看用户隐私协议
	$("#view_writemgs_agreement").click(function(){
		$('#privacy-agreement-content').fadeIn(200).unbind('click').click(function(){
			$(this).fadeOut(100);
		})
	});

	//提交信息
	$(".btn-submit-writemsg").click(function(){
		<?php  if($setting['privacy_agreement']){ ?>
		if(!$("#privacy_agreement").is(':checked')){
			alert("请阅读并同意协议");
			return false;
		}
		<?php  } ?>

		<?php  if(is_array($common_member_fields)) { foreach($common_member_fields as $key => $item) { ?>
			<?php  if(in_array($item['field_short'], $user_info)) { ?>
				var msg_<?php  echo $item['field_short'];?> = $("input[name=<?php  echo $item['field_short'];?>]").val();
				if(msg_<?php  echo $item['field_short'];?> == ''){
					alert("请填写<?php  echo $item['field_name'];?>");
					return false;
				}

				<?php  if($item['field_short']=='mobile' && $sms['template_id']) { ?>
					if($("input[name=verify_code]").val() == ''){
						alert("请输入验证码");
						return false;
					}
				<?php  } ?>
			<?php  } ?>
		<?php  } } ?>

		var writemsg_fields = document.getElementById("writemsg_fields");
		var writemsg_inputs = writemsg_fields.getElementsByTagName("input");
		var writemsg_object = new Object();
		for(var i=0; i<writemsg_inputs.length; i++){
			writemsg_object[writemsg_inputs[i].getAttribute("name")] = writemsg_inputs[i].value;
		}
		$("#loadingToast").show();
		$.ajax({
            type: "POST",
            url:"<?php  echo $this->createMobileUrl('writemsg')?>",
            data:{writemsg:writemsg_object},
            dataType: "json",
            success:function(res){
				$("#loadingToast").hide();
				if(res.code == '-1'){
					alert(res.errmsg);
					return false;
				}else if(res.code == '0'){
					alert("提交成功");
					location.reload();
				}
            },
			error: function(){
				$("#loadingToast").hide();
				alert('网络繁忙，请稍后再试');
				return false;
            }
         });
	})
</script>