<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/bootstrap.min.css?v=<?php  echo $versions;?>">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/iconfont/iconfont.css?v=3.0.0">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/login.css?v=<?php  echo $versions;?>">
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery-1.11.3.min.js?v=<?php  echo $versions;?>"></script>
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/sweetalert/sweetalert.css?v=<?php  echo $versions;?>">
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery.qrcode.min.js"></script>
	<title>用户登录</title>
</head>

<body class="logobg_style">
	<div class="top_box">
		<div class="wid_con">
			<div class="top_con">
				<div class="top_logo">
					<a href="/<?php  echo $uniacid;?>/index.html">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['logo'];?>">
					</a>
				</div>
				<div class="top_menu">
					<ul>
						<?php  if(is_array($menu_navigation)) { foreach($menu_navigation as $item) { ?>
						<li class=""><a href="<?php  echo $item['url_link'];?>"><?php  echo $item['nav_name'];?></a></li>
						<?php  } } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="large-header" class="large-header login-page" <?php  if($login_register['login_bg']) { ?>style="background-image: url(<?php  echo $_W['attachurl'];?><?php  echo $login_register['login_bg'];?>);"<?php  } ?>>
		<canvas id="demo-canvas" width="1590" height="711"></canvas>
		<div class="login-form">
			<div class="login-content">
				<div class="login-switch">
					<a href="javascript:;" data-type="wechat" class="selected">扫码登录</a>
					<a href="javascript:;" data-type="account">账号登录</a>
				</div>	
				<form class="login_padding">
					<!-- 扫码登录 -->
					<div class="scanCode">
						<div class="qrCode" id="login-qrcode">
						</div>
						<div class="scanTip">
							<div class="list_scan">
								<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/login/sCan.png">
								<span>
									打开微信
								</span>
								<span>扫一扫登录</span>
							</div>
						</div>
					</div>

					<!-- 帐号登录 -->
					<div class="account">
						<div class="form-group clearfix">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="icon_user"></i>
								</div>
								<input type="text" class="form-control" name="mobile" placeholder="手机号码" autocomplete="off">
							</div>
						</div>
						<div class="form-group clearfix">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="icon_password"></i>
								</div>
								<input type="password" class="form-control" name="password" placeholder="登录密码" autocomplete="off">
							</div>
						</div>
						<div class="form-group clearfix">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="icon_verify_code"></i>
								</div>
								<input type="text" class="form-control w-175 m-r-10" name="code" placeholder="验证码">
								<img id="imgverify" src="/<?php  echo $uniacid;?>/verifycode.html" width="140" height="45" class="passcode" style="cursor:pointer;" onclick="getCode()">
							</div>
						</div>
						<div class="textright">
							<?php  if($login_register['reset_password']) { ?>
							<a href="javascript:;" class="forget" id="btn-forget">注册/忘记密码?</a>
							<?php  } ?>
						</div>
						<div class="form-group">
							<a href="javascript:;" class="btn btn-block btn-login" id="btn-login" />登 录</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="overlay" id="qrcode-container">
		<div class="qrcode-container">
			<div class="qrcode-top"></div>
			<div class="qrcode-center">
				<div class="qc-center">
					<div id="password-qcode"></div>
				</div>
				<div class="qc-bottom">打开微信扫描二维码<br>输入新登录密码后提交</div>
			</div>
			<div class="qrcode-bottom"></div>
		</div>
	</div>


	<div class="footer">
		<p><?php  echo $setting['copyright'];?></p>
		<p>ICP备案号：<a href="http://beian.miit.gov.cn" target="_blank"><?php  echo $setting_pc['site_icp'];?></a></p>
	</div>
	
<script src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/login.js?v=<?php  echo $versions;?>"></script>
<script type="text/javascript">
	function getCode(){
		$('#imgverify').prop('src', '/<?php  echo $uniacid;?>/verifycode.html?r=' + Math.round(new Date().getTime()));
		return false;
	}

	var swalalert = function(title, message, type){
		swal({
			title: title,
			text: message,
			type: type,
			showCancelButton: false,
			closeOnConfirm: false,
			confirmButtonColor: "#1d97d0"
		});
	}

	$("#btn-login").click(function(){
		var mobile = $("input[name=mobile]").val();
		var password = $("input[name=password]").val();
		var code = $("input[name=code]").val();

		if(!mobile){
			swalalert('系统提示', '请输入手机号码', 'error');
			return false;
		}
		if(!password){
			swalalert('系统提示', '请输入登录密码', 'error');
			return false;
		}
		if(!code){
			swalalert('系统提示', '请输入验证码', 'error');
			return false;
		}

		$.ajax({
            type:"POST",
            url:"/<?php  echo $uniacid;?>/login.html",
            data:{mobile:mobile, password:password, code:code},
            dataType: "json",
            beforeSend:function(){
				$("#msg").html("logining");
			},
            success:function(res){
				if(res.code == '-1'){
					swalalert('系统提示', res.message, 'error');
					getCode();
					return false;
				}else if(res.code == '0'){
					if(res.refurl){
						location.href = res.refurl;
					}else{
						location.href = "/<?php  echo $uniacid;?>/index.html";
					}
				}
            },
            complete: function(XMLHttpRequest, textStatus){
            },
			error: function(){
            }
         });
	});

	$("input[name=mobile],input[name=password],input[name=code]").bind("keypress", function(e) {
		if (e.keyCode == "13") {
			e.preventDefault();
			document.getElementById("btn-login").click();
		}
	});
</script>

<?php  if($login_register['reset_password']) { ?>	
<script type="text/javascript">
	//忘记密码
	jQuery('#password-qcode').qrcode({width: 190, height: 190, text: "<?php  echo $reset_password_url;?>"});
	$("#btn-forget").click(function(){
		$('#qrcode-container').fadeIn(200).unbind('click').click(function(){
			$(this).fadeOut(100);
		})
	});
</script>
<?php  } ?>

<script type="text/javascript">
$(function () {
	jQuery('#login-qrcode').qrcode({width: 200, height: 200, text: "<?php  echo $mobile_url;?>"});
	
	var login_status_int = window.setInterval(checkLogin, 3000);
	$(".login-switch a").click(function () {
		if($(this).data('type') == 'wechat'){
			$('.account').hide().prev().show();
			login_status_int = window.setInterval(checkLogin, 3000);
		}else{
			$('.scanCode').hide().next().show();
			window.clearInterval(login_status_int);
		}
		$(".login-switch a").removeClass('selected');
		$(this).addClass('selected');
	});

	function checkLogin(){
		var orderid = "<?php  echo $_GPC['orderid'];?>";
		var ordertype = "<?php  echo $_GPC['ordertype'];?>";
		$.ajax({
			type: "post",
			url: "/<?php  echo $uniacid;?>/login.html?op=checkLogin",
			dataType: "json",
			data: {
				random: "<?php  echo $random;?>",
				refurl: "<?php  echo $_GPC['refurl'];?>",
			},
			success:function(res){
				if(res.code == -1){
					if(res.message){
						swal("系统提示", res.message, "error");
					}
					return;
				}else if(res.code == 0){
					window.location.href = res.refurl;
					window.clearInterval(login_status_int);
				}
			}, 
			error:function(){   
			},   
		});
	}
})
</script>

<script>;</script><script type="text/javascript" src="https://xinglian.jiuxing.red/app/index.php?i=2&c=utility&a=visit&do=showjs&m=fy_lessonv2"></script></body>
</html>
