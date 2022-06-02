<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no">
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<title><?php  echo $shop_setting['sitename'];?></title>
	<link href="<?php echo MODULE_URL;?>static/mobile/css/weui.2.4.4.css?v=<?php  echo $versions;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo MODULE_URL;?>static/mobile/css/base.css?v=<?php  echo $versions;?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo MODULE_URL;?>static/mobile/css/index.css?v=<?php  echo $versions;?>" rel="stylesheet" type="text/css">
	<link href="<?php echo MODULE_URL;?>static/mobile/css/swiper.fylesson.css?v=<?php  echo $versions;?>" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/iconfont/iconfont.css?v=<?php  echo $versions;?>"/>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/swiper.4.1.0.min.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/news.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/base.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/weui.min.js?v=<?php  echo $versions;?>"></script>
	<?php  echo register_jssdk(false);?>
	<script type="text/javascript">
		wx.ready(function(){
			var shareData = {
				title: '<?php echo $title ? $title : $share_shop["title"]?>',
				desc: '<?php  echo $share_shop["desc"];?>',
				link: '<?php echo $_W["siteurl"] ? $_W["siteurl"]."&uid=".$_W["member"]["uid"] : $shareurl;?>',
				imgUrl: '<?php  echo $_W["attachurl"];?><?php  echo $share_shop["images"];?>',
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

		document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
			var miniprogram_environment = false;
			wx.miniProgram.getEnv(function(res) {
				if(res.miniprogram) {
					miniprogram_environment = true;
				}
			})
			if((window.__wxjs_environment === 'miniprogram' || miniprogram_environment)) {
				wx.miniProgram.getEnv(function(res) {
					wx.miniProgram.postMessage({ 
						data: {
							'title': "<?php  echo $share_shop['title'];?>",
							'images': "<?php  echo $_W['attachurl'];?><?php  echo $share_shop['images'];?>",
						}
					})
				});
			}
		});
		</script>
</head>
<body>
	<section class="shopView max-640">
		<?php  if(!in_array($_GPC['do'], array('shop','shoplist','shopcategory','shopgoods','shopcart'))) { ?>
		<header class="shop-navBar bg-fff bor-bottom-eee">
			<a href="javascript:history.go(-1);" class="shop-navBar-item">
				<i class="icon-common icon-common-return"></i>
			</a>
			<div class="shop-center">
				<span class="shop-center-title"><?php  echo $title;?></span>
			</div>
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenu', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenu', TEMPLATE_INCLUDEPATH));?>
		</header>
		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_rightTopMenuList', TEMPLATE_INCLUDEPATH)) : (include template('_rightTopMenuList', TEMPLATE_INCLUDEPATH));?>
		<?php  } ?>