<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 申请讲师
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/applyteacher.css?v=<?php  echo $versions;?>" rel="stylesheet" />
<link href="<?php echo MODULE_URL;?>static/public/photoClip/css/index.css?v=<?php  echo $versions;?>" rel="stylesheet">
<style type="text/css">
.tabbar_wrap {
	-webkit-overflow-scrolling: unset;
}
</style>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/photoClip/js/iscroll-zoom.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/photoClip/js/hammer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/photoClip/js/lrz.all.bundle.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/photoClip/js/jquery.photoClip.js" charset="utf-8"></script>
<?php  if($op=='display') { ?>
<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<form enctype="multipart/form-data" method="post" action="<?php  echo $this->createMobileUrl('applyteacher', array('op'=>'postteacher'));?>">
	<article class="info">
		<div class="welcome">
			<?php  if($font['nickname']) { ?>
				<p><?php  echo $font['nickname'];?></p>
			<?php  } else { ?>
				<p>尊敬的<span class="title"><?php  echo $lessonmember['mnickname'];?></span>:</p>
			<?php  } ?>
			<p class="descript"><?php echo $font['first'] ? $font['first'] : '欢迎申请成为讲师，认真填写以下各项信息有利于审核通过哦~'?></p>
		</div>
		<ul>
			<li>
				<div class="left">
					<?php echo $font['teacherName'] ? $font['teacherName'] : '讲师姓名'?>：
				</div>
				<div class="right">
					<input type="text" name="teacher" id="teacher" value="<?php  echo $teacherlog['teacher'];?><?php  if(!empty($teacherlog['teacher']) && $teacherlog['status']==1) { ?>[不可修改]<?php  } ?>" <?php  if(!empty($teacherlog['teacher']) && $teacherlog['status']==1) { ?>readonly<?php  } ?> />
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="left">
					<?php echo $font['mobile'] ? $font['mobile'] : '手机号码'?>：
				</div>
				<div class="right">
					<input type="tel" name="mobile" id="mobile" value="<?php  echo $teacherlog['mobile'];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
				</div>
				<div class="clear"></div>
			</li>
			<?php  if($sms['template_id']) { ?>
			<li>
				<div class="left">
					验证码：
				</div>
				<div class="right">
					<input type="tel" name="verify_code" class="code"/>
					<a href="javascript:;" id="weui_btn_send" class="send_code" onclick="sendcode()">获取验证码</a>
				</div>
				<div class="clear"></div>
			</li>
			<?php  } ?>
			<li>
				<div class="left">
					<?php echo $font['idcard'] ? $font['idcard'] : '身份证号'?>：
				</div>
				<div class="right">
					<input type="tel" name="idcard" value="<?php  echo $teacherlog['idcard'];?>" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')"/>
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="left">
					<?php echo $font['teacherQQ'] ? $font['teacherQQ'] : '讲师QQ'?>：
				</div>
				<div class="right">
					<input type="text" name="qq" id="qq" value="<?php  echo $teacherlog['qq'];?><?php  if(!empty($teacherlog['qq']) && $teacherlog['status']==1) { ?>[不可修改]<?php  } ?>" <?php  if(!empty($teacherlog['qq']) && $teacherlog['status']==1) { ?>readonly<?php  } ?> />
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="left">
					<?php echo $font['qqGroup'] ? $font['qqGroup'] : 'QQ群'?>：
				</div>
				<div class="right">
					<input type="text" name="qqgroup" id="qqgroup" value="<?php  echo $teacherlog['qqgroup'];?><?php  if(!empty($teacherlog['qqgroup']) && $teacherlog['status']==1) { ?>[不可修改]<?php  } ?>" <?php  if(!empty($teacherlog['qqgroup']) && $teacherlog['status']==1) { ?>readonly<?php  } ?> />
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="left">
					<?php echo $font['teacherDesc'] ? $font['teacherDesc'] : '讲师介绍'?>：
				</div>
				<div class="right">
					<textarea name="teacherdes" id="teacherdes"><?php  echo $teacherlog['teacherdes'];?></textarea>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</article>
	<section class="logo-license">
		<div class="half">
			<a class="logo" id="up-photo">
				<img id="teacherphoto_show" src="<?php echo $teacherlog['teacherphoto'] ? $_W['attachurl'].$teacherlog['teacherphoto'] : MODULE_URL.'template/mobile/'.$template.'/images/applyteacher-avatar.png';?>"/>
			</a>
			<p><?php echo $font['teacherPhoto'] ? $font['teacherPhoto'] : '上传讲师头像'?></p>
			<img id="view" class="img">
			<input type="file" id="teacherphoto_file" style="position:absolute;top:0;right:0;bottom:0;padding:0;margin:0;height:110px;width:100%;cursor:pointer;border:solid 1px #ddd;opacity:0;" />
			<input type="hidden" name="teacherphoto" id="teacherphoto_hide" value="<?php  echo $teacherlog['teacherphoto'];?>"/>
			<div class="cover-wrap">
				<div class="clipBgn" id="clipBgn" style="margin-top:0;width:100%;height:90%;">
					<div id="clipArea"></div>
					<div class="clipButton">
						<a href="javascript:;" id="bigImg">放大</a>
						<a href="javascript:;" id="smallImg">缩小</a>
						<a href="javascript:;" id="clipBtn">保存</a>
					</div>
				</div>
			</div>
		</div>
		<div class="half">
			<div class="uploader blue">
				<input type="text" class="filename" readonly value="<?php echo $font['teacherQrcode'] ? $font['teacherQrcode'] : '微信二维码'?>"/>
				<a class="license">
					<img id="qrcode_url_show" src="<?php echo $teacherlog['weixin_qrcode'] ? $_W['attachurl'].$teacherlog['weixin_qrcode'] : MODULE_URL.'template/mobile/'.$template.'/images/applyteacher-qrcode.png';?>"/>
				</a>
				<input id="uploadFile2" name="uploadFile" class="file-3" type="file" size="30" onchange="uploadImage(2);" accept="image/*" />
				<input type="hidden" name="weixin_qrcode" id="qrcode_url_hide" value="<?php  echo $teacherlog['weixin_qrcode'];?>"/>
			</div>
		</div>
		<div class="clear"></div>
		<div class="help-block">
			<?php echo $font['photoDesc'] ? $font['photoDesc'] : '头像和二维码建议尺寸200*200像素，格式为jpg'?>
		</div>
	</section>
	<section class="applyteacher_main">
		<div class="title"><?php echo $font['descTitle'] ? $font['descTitle'] : '讲师特权'?></div>
		<div class="vip_main">
			<div class="vip">
				<div class="ico1"><i class="fa fa-qrcode"></i></div>
				<div class="text">
					<div class="t1"><?php echo $font['incomeTitle'] ? $font['incomeTitle'] : '课程收入'?></div>
					<div class="t2"><?php echo $font['incomeDesc'] ? $font['incomeDesc'] : '课程每出售一次，即可收入课程佣金'?></div>
				</div>
			</div>
			<div class="vip">
				<div class="ico2"><i class="fa fa-cny"></i></div>
				<div class="text">
					<div class="t1"><?php echo $font['cashTitle'] ? $font['cashTitle'] : '自由提现'?></div>
					<div class="t2"><?php echo $font['cashDesc'] ? $font['cashDesc'] : '收入存入账户，可自由申请提现'?></div>
				</div>
			</div>
		</div>
	</section>
	<?php  if($setting['teacher_agreement']) { ?>
	<section class="teacher_agreement">
		<input type="checkbox" name="teacher_agreement" value="1" />
		我已阅读并同意<a href="javascript:;" class="btn-agreement">《讲师注册(隐私)协议》</a>
	</section>
	<div class="act-group-notice-mask" style="display:none;">
		<div class="act-group-notice">
			<div class="close">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-close.png?v=6" width="32" height="32">
			</div>
			<h3 class="notice-title">讲师注册(隐私)协议</h3>
			<ul class="notice-body">
				<?php  echo htmlspecialchars_decode($setting['teacher_agreement'])?>
			</ul>
		</div>
	</div>
	<script type="text/javascript">
	$(".btn-agreement").click(function(){
		$('.act-group-notice-mask').fadeIn(200).unbind('click').click(function(){
			$(this).fadeOut(100);
		})
	})
	</script>
	<?php  } ?>
	<article class="btn-1" style="text-align: center;">
		<button onclick="return checksubmit();">提交申请</button>
	</article>
</form>


<script type="text/javascript">
	function checksubmit() {
        var teacher = $("#teacher").val();
		var mobile = $("#mobile").val();
        var teacherdes = $("#teacherdes").val();
        var teacherphoto = $("input[name='teacherphoto']").val();

        if (teacher == "") {
            alert("请填写讲师名称");
            return false;
        }
		if (mobile == "") {
            alert("请填写手机号码");
            return false;
        }
        if (teacherdes == "") {
            alert("请填写讲师介绍");
           return false;
        }
		if (teacherphoto == "") {
            alert("请上传讲师相片");
            return false;
        }

		<?php  if($setting['teacher_agreement']) { ?>
			var agreement = false;
			$("input[name='teacher_agreement']").each(function () {
				if ($(this).is(":checked")) {
					agreement = true;
				}
			});
			if(!agreement){
				alert("请阅读并同意讲师注册(隐私)协议");
				return false;
			}
		<?php  } ?>

        document.getElementById("loadingToast").style.display = 'block';
        return true;
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
</script>

<script type="text/javascript">
	$("#up-photo").click(function(){
		document.getElementById("teacherphoto_file").click();
	});

	$("#clipArea").photoClip({
		size: [200, 200],
		outputSize:[200, 200],
		file: "#teacherphoto_file",
		ok: "#clipBtn",
		view:"#view",
		bigBtn: "#bigImg",
		smallBtn: "#smallImg",
		loadStart: function() {
			$('.cover-wrap').fadeIn();
			console.log("照片读取中");
		},
		loadComplete: function() {
			console.log("照片读取完成");
		},
		clipFinish: function(dataURL) {
			$("#loadingToast").show();
			$('.cover-wrap').fadeOut();
			uploadPhotoImage(dataURL);
		}
	});

	function uploadPhotoImage(dataURL){
		$.ajax({
			url: '<?php  echo $this->createMobileUrl("AjaxUploadImage", array("type"=>"base64"));?>',
			type: 'POST',
			data: {imageData: dataURL},
			dateType: "json",
			success:function(data){
				$("#loadingToast").hide();
				var res = JSON.parse(data);
				$("#teacherphoto_show").attr("src", "<?php  echo $_W['attachurl']; ?>" + res.path);
				$("#teacherphoto_hide").val(res.path);
			},
			error: function(data, status, e) {
				alert("网络错误，请稍候重试");
				$("#loadingToast").hide();
			}
		})
	}
</script>

<script type="text/javascript">
	function uploadImage(idx){
		$("#loadingToast").show();
		$.ajaxFileUpload({
				url:'<?php  echo $this->createMobileUrl("AjaxUploadImage");?>',
				secureuri:false,
				fileElementId:'uploadFile'+idx,
				dataType: 'json',
				success: function (data, status){
					if(data.success == true){
						if(idx==2){
							$("#qrcode_url_show").attr("src", "<?php  echo $_W['attachurl']; ?>" + data.path);
							$("#qrcode_url_hide").val(data.path);
						}
					}else{
						alert("上传失败，请稍候重试");
					}
					$("#loadingToast").hide();
				},
				error: function(data, status, e) {
					alert("网络错误，请稍候重试");
					$("#loadingToast").hide();
				}
		});
		return false;
	}
</script>

<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>