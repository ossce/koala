<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 二维码推广
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/commission.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="<?php  echo $this->createMobileUrl('commission')?>" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
	<a href="<?php  echo $this->createMobileUrl('index', array('t'=>1))?>" class="ico go-index"></a>
</div>

<?php  if($sale_desc) { ?>
<div class="sharecard__entry-global sale_desc">
	<div class="sharecard__entry">
		<div class="sharecard__entry-icon">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-info-black.png" />
		</div>
		规则说明
	</div>
</div>
<div class="privacy_agreement_notice-mask" style="display: none;">
	<div class="privacy_agreement_notice">
		<div class="close close-agreement">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-close.png" width="32" height="32">
		</div>
		<h3 class="notice-title"><?php  echo $sale_desc['0'];?></h3>
		<ul class="notice-body">
			<?php  if(is_array($sale_desc)) { foreach($sale_desc as $key => $item) { ?>
				<?php  if($key>0) { ?>
					<p class="sale-content"><?php  echo $item;?></p>
				<?php  } ?>
			<?php  } } ?>
		</ul>
	</div>
</div>
<?php  } ?>

<div>
	<div class="qrcode_wrap">
		<div class="qrcode_inner">
			<img id="spreadImg" src="<?php  echo $imagepath;?>">
		</div>
	</div>
	<div class="qrcode_footer">
		<p class="tips">长按上方图片保存，分享给朋友</p>
		<div id="scroll" class="qrcode_thum_wrap" style="overflow: auto;">
			<?php  if(is_array($poster_list)) { foreach($poster_list as $key => $item) { ?>
			<div class="qrcode_thum" onclick="cutPoster(<?php  echo $key;?>);">
				<?php  if($item['poster_default']) { ?>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/posterbg.jpg">
				<?php  } else { ?>
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['poster_bg'];?>">
				<?php  } ?>
				<?php  if($key==$poster_no) { ?>
				<img src="<?php echo MODULE_URL;?>static/mobile/default/images/qrcode_checked_icon.png" class="checked">
				<?php  } ?>
			</div>
			<?php  } } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var clientH = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		if(clientH >= 812){
			var poster_height = clientH - 350;
			$("#spreadImg").css('margin','60px auto');
			document.getElementById('page-container').style.marginBottom = '0px';
		}else{
			var title_H = 0;
			<?php  if($userAgent){ ?>
				title_H = 45;
			<?php  } ?>
			var poster_height = clientH - 228 + title_H;
		}
		$("#spreadImg").css('height', poster_height + 'px');
	})

	function cutPoster(poster_no){
		$("#loadingToast").show();
		var url = "<?php echo $setting['poster_type']==2 ? $this->createMobileUrl('qrcoderec') : $this->createMobileUrl('qrcode');?>";
		window.location.href = url + "&poster_no=" + poster_no;
	}

	$(".sale_desc").click(function(){
		$(".privacy_agreement_notice-mask").animate({opacity: 'show'}, 'slow');
	});
	$(".close-agreement").click(function(){
		$(".privacy_agreement_notice-mask").animate({opacity: 'hide'}, 'slow');
	});

	wx.ready(function(){
		var shareData = {
			title: "<?php  echo $sharelink['title'];?>",
			desc: "<?php  echo $sharelink['desc'];?>",
			link: "<?php  echo $shareurl;?>",
			imgUrl: "<?php  echo $_W['attachurl'];?><?php  echo $sharelink['images'];?>",
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
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>