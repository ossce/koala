<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="format-detection" content="telephone=no">
		<meta name="full-screen" content="yes">
		<meta name="browsermode" content="application">
		<meta name="x5-orientation" content="portrait">
		<meta name="x5-fullscreen" content="true">
		<meta name="x5-page-mode" content="app">
		<title>
			<?php  if(!empty($title)) { ?>
				<?php  echo $title;?>
			<?php  } else if(empty($title) && !empty($setting['sitename'])) { ?>
				<?php  echo $setting['sitename'];?>
			<?php  } else if(empty($title) && empty($setting['sitename'])) { ?>
				微课学堂
			<?php  } ?>		
		</title>
		<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/weui.2.4.4.css?v=<?php  echo $versions;?>" />
		<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/fontawesome/font-awesome.min.css?v=<?php  echo $versions;?>"/>
		<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/index.css?v=<?php  echo $versions;?>"/>
		<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/alert/alert.css?v=<?php  echo $versions;?>" rel="stylesheet" />
		
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/jquery.min.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/swiper.3.4.1.min.js"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/jquery.qrcode.min.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/alert/alert.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/base.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/weui.min.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/lrz.min.js?v=<?php  echo $versions;?>"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/jquery.lazyload.js?v=<?php  echo $versions;?>"></script>


		<?php  echo register_jssdk(false);?>
		<script type="text/javascript">
		wx.ready(function(){
			var shareData = {
				title: '<?php echo $title ? $title : $sharelink["title"]?>',
				desc: '<?php  echo $sharelink["desc"];?>',
				link: '<?php echo $_W["siteurl"] ? $_W["siteurl"]."&uid=".$_W["member"]["uid"] : $shareurl;?>',
				imgUrl: '<?php  echo $_W["attachurl"];?><?php  echo $sharelink["images"];?>',
				trigger: function (res) {},
				complete: function (res) {},
				success: function (res) {},
				cancel: function (res) {},
				fail: function (res) {}
			};
			wx.onMenuShareTimeline(shareData);
			wx.onMenuShareAppMessage(shareData);
			wx.onMenuShareQQ(shareData);
			wx.onMenuShareWeibo(shareData);
			wx.onMenuShareQZone(shareData);
		});

		$(function() {
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
				var miniprogram_environment = false;
				wx.miniProgram.getEnv(function(res) {
					if(res.miniprogram) {
						miniprogram_environment = true;
					}
				})
				if((window.__wxjs_environment === 'miniprogram' || miniprogram_environment)) {
					wx.miniProgram.getEnv(function(res) {
						<?php  if(!in_array($_GPC['do'], array('index','lesson','teacher'))){ ?>
						wx.miniProgram.postMessage({ 
							data: {
								'title': "<?php  echo $title;?>",
								'images': "",
							}
						})
						<?php  } ?>

						//小程序内显示标题
						$(".header-2").css('display', 'flex');
						$(".header-2").css('display', '-webkit-flex');
						$(".header-2").css('display', '-webkit-box');
					});

					//隐藏ios小程序支付价格
					<?php  if($systemType=='ios'){ ?>
						var ios_head = document.head || document.getElementsByTagName('head')[0];
						var ios_system = document.createElement('style');
						ios_system.innerHTML = '.ios-system{display:none !important;}';
						ios_head.appendChild(ios_system);
					<?php  } ?>
				}
			});

			//隐藏ios公众号支付价格
			<?php  if($userAgent && $systemType=='ios' && !$setting['ios_pay']){ ?>
				var ios_head = document.head || document.getElementsByTagName('head')[0];
				var ios_system = document.createElement('style');
				ios_system.innerHTML = '.ios-system{display:none !important;}';
				ios_head.appendChild(ios_system);
			<?php  } ?>
			
			<?php  if(!$userAgent){ ?>
				//非微信浏览器显示标题
				$(".header-2").css('display', 'flex');
				$(".header-2").css('display', '-webkit-flex');
				$(".header-2").css('display', '-webkit-box');
			<?php  } ?>
		})
		</script>
		<style type="text/css">
		<?php  echo $setting['front_color'];?>
		</style>
	</head>
	<body>
		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_WeUIShowToast', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_WeUIShowToast', TEMPLATE_INCLUDEPATH));?>
		<div class="page-container" id="page-container">
