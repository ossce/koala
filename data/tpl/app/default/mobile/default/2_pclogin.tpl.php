<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * PC端授权登陆
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<title>授权登陆</title>
		<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/weui.2.4.4.css?v=<?php  echo $versions;?>" />
		<style type="text/css">body{background-color:#fff;}.weui-msg{padding-top:36px;}.weui-icon_msg{font-size:110px;}.weui-msg__title{font-weight:normal;}.weui-msg__text-area{margin-bottom:15px;}.weui-btn{font-size:18px;font-weight:normal;line-height:1.6;}.w90-per{width:90%;}</style>
	</head>

	<body>
	<?php  if($op=='display') { ?>
		<div class="weui-msg js_msg">
			<div class="weui-msg__icon-area"><i class="weui-icon-waiting weui-icon_msg"></i></div>
			<div class="weui-msg__text-area">
				<h2 class="weui-msg__title">你确认要登录网站<br><?php  echo $setting_pc['sitename'];?>吗?</h2>
			</div>
			<div class="weui-msg__opr-area">
				<form action="" method="post">
					<p class="weui-btn-area">
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
						<input type="hidden" name="login_token" value="<?php  echo $_GPC['login_token'];?>">
						<input type="submit" name="submit" class="weui-btn weui-btn_primary w90-per" value="确定">
						<a href="<?php  echo $this->createMobileUrl('pclogin', array('op'=>'return','return_type'=>'warn', 'message'=>'您取消了微信扫码登录'))?>" class="weui-btn weui-btn_default w90-per">取消</a>
					</p>
				</form>
			</div>
		</div>
	<?php  } else if($op=='return') { ?>
		<?php  if($_GPC['return_type']=='warn') { ?>
		<style type="text/css">.weui-msg__title{font-size: 18px;}</style>
		<?php  } ?>
		<div class="weui-msg js_msg">
			<div class="weui-msg__icon-area"><i class="<?php  if($_GPC['return_type']=='success') { ?>weui-icon-success<?php  } else { ?>weui-icon-warn<?php  } ?> weui-icon_msg"></i></div>
			<div class="weui-msg__text-area">
				<h2 class="weui-msg__title"><?php  echo $_GPC['message'];?></h2>
			</div>
			<div class="weui-msg__opr-area">
				<p class="weui-btn-area">
					<a class="weui-btn weui-btn_primary w90-per" id="closeWindow">确定</a>
				</p>
			</div>
		</div>
		<script type="text/javascript">
			var ua = navigator.userAgent.toLowerCase();
			if (ua.match(/MicroMessenger/i) == "micromessenger") {
				document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
					document.getElementById("closeWindow").addEventListener('click', closeWechatWindow, false);
				});
			}else{
				document.getElementById("closeWindow").addEventListener('click', goIndex, false);
			}

			//关闭微信内置浏览器
			function closeWechatWindow(){
				WeixinJSBridge.call('closeWindow');
			}
			//跳转到首页
			function goIndex(){
				window.location.href = "<?php  echo $this->createMobileUrl('index', array('t'=>1));?>";
			}
		</script>
	<?php  } ?>
	<script>;</script><script type="text/javascript" src="https://xinglian.jiuxing.red/app/index.php?i=2&c=utility&a=visit&do=showjs&m=fy_lessonv2"></script></body>
</html>