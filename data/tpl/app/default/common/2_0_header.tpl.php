<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>
	<?php  if(!empty($title)) { ?> 
		<?php  echo $title;?> -
	<?php  } else if(!empty($_W['page']['title'])) { ?> 
		<?php  echo $_W['page']['title'];?> -
	<?php  } ?> 
	<?php  if(!empty($_W['page']['sitename'])) { ?>
		<?php  echo $_W['page']['sitename'];?> 
	<?php  } else { ?>
		<?php  echo $_W['account']['name'];?> 
	<?php  } ?> 
	<?php  if(IMS_FAMILY == 'v') { ?>
		- Powered by WE7.CC 
	<?php  } ?>
	</title>
	<meta name="format-detection" content="telephone=no, address=no">
	<meta name="apple-mobile-web-app-capable" content="yes" /> <!-- apple devices fullscreen -->
	<meta name="apple-touch-fullscreen" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<meta name="keywords" content="<?php  if(empty($_W['page']['keywords'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微擎,微信,微信公众平台,w7.cc<?php  } ?><?php  } else { ?><?php  echo $_W['page']['keywords'];?><?php  } ?>" />
	<meta name="description" content="<?php  if(empty($_W['page']['description'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>公众平台自助引擎（www.w7.cc），简称微擎，微擎是一款免费开源的微信公众平台管理系统，是国内最完善移动网站及移动互联网技术解决方案。<?php  } ?><?php  } else { ?><?php  echo $_W['page']['description'];?><?php  } ?>" />
	<link rel="shortcut icon" href="<?php  echo $_W['siteroot'];?><?php  echo $_W['config']['upload']['attachdir'];?>/<?php  if(!empty($_W['setting']['copyright']['icon'])) { ?><?php  echo $_W['setting']['copyright']['icon'];?><?php  } else { ?>images/global/wechat.jpg<?php  } ?>" />
	<script src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
	<?php  if($_W['container'] == 'baidu') { ?>
	<script src="https://xiongzhang.baidu.com/sdk/c.js?appid=<?php  echo $_W['account']['jssdkconfig']['appId'];?>&timestamp=<?php  echo $_W['account']['jssdkconfig']['timestamp'];?>&nonce_str=<?php  echo $_W['account']['jssdkconfig']['nonceStr'];?>&signature=<?php  echo $_W['account']['jssdkconfig']['signature'];?>&url=<?php  echo $_W['account']['jssdkconfig']['url'];?>"></script>
	<?php  } ?>
	<script type="text/javascript" src="<?php  echo $_W['siteroot'];?>app/resource/js/app/util.js"></script>
	<script src="<?php  echo $_W['siteroot'];?>app/resource/js/require.js"></script>
	<script type="text/javascript" src="<?php  echo $_W['siteroot'];?>app/resource/js/lib/jquery-1.11.1.min.js?v=20160906"></script>
	<script type="text/javascript" src="<?php  echo $_W['siteroot'];?>app/resource/js/lib/mui.min.js?v=20160906"></script>
	<script type="text/javascript" src="<?php  echo $_W['siteroot'];?>app/resource/js/app/common.js?v=20160906"></script>
	<link href="<?php  echo $_W['siteroot'];?>app/resource/css/bootstrap.min.css?v=20160906" rel="stylesheet">
	<link href="<?php  echo $_W['siteroot'];?>app/resource/css/common.min.css?v=20160906" rel="stylesheet">
	<script type="text/javascript">
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
	window.sysinfo = {
		<?php  if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php  echo $_W['uniacid'];?>',<?php  } ?>
		<?php  if(!empty($_W['acid'])) { ?>'acid': '<?php  echo $_W['acid'];?>',<?php  } ?><?php  if(!empty($_W['openid'])) { ?>'openid': '<?php  echo $_W['openid'];?>',<?php  } ?>
		<?php  if(!empty($_W['uid'])) { ?>'uid': '<?php  echo $_W['uid'];?>',<?php  } ?>
		'siteroot': '<?php  echo $_W['siteroot'];?>',
		'siteurl': '<?php  echo $_W['siteurl'];?>',
		'attachurl': '<?php  echo $_W['attachurl'];?>',
		'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',
		'attachurl_remote': '<?php  echo $_W['attachurl_remote'];?>',
		<?php  if(defined('MODULE_URL')) { ?>'MODULE_URL': '<?php echo MODULE_URL;?>',<?php  } ?>
		'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'}
	};
	// jssdk config 对象
	jssdkconfig = <?php  echo json_encode($_W['account']['jssdkconfig']);?> || {};
	// 是否启用调试
	jssdkconfig.debug = false;
	jssdkconfig.jsApiList = [
		'checkJsApi',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo',
		'hideMenuItems',
		'showMenuItems',
		'hideAllNonBaseMenuItem',
		'showAllNonBaseMenuItem',
		'translateVoice',
		'startRecord',
		'stopRecord',
		'onRecordEnd',
		'playVoice',
		'pauseVoice',
		'stopVoice',
		'uploadVoice',
		'downloadVoice',
		'chooseImage',
		'previewImage',
		'uploadImage',
		'downloadImage',
		'getNetworkType',
		'openLocation',
		'getLocation',
		'hideOptionMenu',
		'showOptionMenu',
		'closeWindow',
		'scanQRCode',
		'chooseWXPay',
		'openProductSpecificView',
		'addCard',
		'chooseCard',
		'openCard',
		'openAddress'
	];
	</script>
</head>
<body>
<div class="container container-fill">
<?php  if($_W['container'] !== 'wechat' && ($_GPC['c'] == 'mc' || $_GPC['c'] == 'activity')) { ?>
<header class="mui-bar mui-bar-nav">
	<button class="mui-btn mui-btn-link mui-btn-nav mui-pull-left mui-action-back">
		<span class="mui-icon mui-icon-left-nav"></span>
		返回
	</button>
	<h1 class="mui-title"><?php  if(!empty($title)) { ?><?php  echo $title;?><?php  } else if(!empty($_W['page']['title'])) { ?><?php  echo $_W['page']['title'];?><?php  } ?></h1>
</header>
<?php  } ?>
