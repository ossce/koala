<?php defined('IN_IA') or exit('Access Denied');?>			<?php  if(!in_array($_GPC['do'], array('shoplist','shopcategory','shopcart','shopaddress','shopqrcode'))) { ?>
			<div class="footer-copyright <?php  if($_GPC['do']=='shop') { ?>pad-b-70<?php  } ?>">
				<a href="<?php  echo $this->createMobileUrl('shop', array('t'=>1));?>"><?php  echo $shop_setting['copyright'];?></a>
				<?php  if($setting['site_icp']) { ?>
				<a href="http://beian.miit.gov.cn" class="mar-t-5"><?php  echo $setting['site_icp'];?></a>
				<?php  } ?>
			</div>
			<?php  } ?>

			<?php  if(in_array($_GPC['do'], array('shop','shopcart'))) { ?>
			<div id="footer-nav" class="footer-nav max-640">
				<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('diy/footnav', TEMPLATE_INCLUDEPATH)) : (include template('diy/footnav', TEMPLATE_INCLUDEPATH));?>
			</div>
			<?php  } ?>

			<?php  if($shop_setting['sysnc_right_menu']) { ?>
				<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('../../../fy_lessonv2/template/mobile/default/_rightMenu', TEMPLATE_INCLUDEPATH)) : (include template('../../../fy_lessonv2/template/mobile/default/_rightMenu', TEMPLATE_INCLUDEPATH));?>
			<?php  } else { ?>
			<div class="btn-scroll-up" id="backtop">
				<img src="<?php echo MODULE_URL;?>static/mobile/images/icon-back-top.png?v=1">
			</div>
			<?php  } ?>
		</section>

		<!-- 返回直播间 -->
		<div class="back_live-global" style="display:none;">
			<a href="javascript:;" id="live_lesson_url" class="back_live">
				<div class="back_icon"><i class="icon-back-live"></i>直播间</div>
			</a>
		</div>

		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_WeUIShowToast', TEMPLATE_INCLUDEPATH)) : (include template('_WeUIShowToast', TEMPLATE_INCLUDEPATH));?>
	<script>;</script><script type="text/javascript" src="https://xinglian.jiuxing.red/app/index.php?i=2&c=utility&a=visit&do=showjs&m=fy_lessonv2_plugin_shop"></script></body>
</html>

<script type="text/javascript">
	var curr_uniacid = "<?php  echo $uniacid;?>";
	var curr_uid = "<?php  echo $uid;?>";
	<?php  if($_GPC['do'] != 'shopaddress'){ ?>
		localStorage.removeItem(curr_uniacid + '_' + curr_uid + 'returnurl');
	<?php  } ?>
	
	$(".search-goods-keyword").keydown(function(event){
		event=event ||window.event;
		if(event.keyCode==13){
			var search_goods_keyword = $.trim($(".search-goods-keyword").val());
			document.location.href = "<?php  echo $this->createMobileUrl('shoplist')?>&keyword=" + encodeURIComponent(search_goods_keyword);
			return false;
		}
	});

	//返回顶部
	$(window).scroll(function(event) {
		if($(this).scrollTop() <= 200){
			$("#backtop").hide();
		}
		if($(this).scrollTop() > 200){
			$("#backtop").show();
		}
	});
	$("#backtop").on("click",function(){
		$('html, body').animate({scrollTop: 0},300);return false;
	});

	//返回直播间
	var page_live_lessonid = <?php  echo intval($_GPC['live_lessonid']); ?>;
	var live_lessonid = sessionStorage.getItem(curr_uniacid + "_live_lessonid");
	live_lessonid = page_live_lessonid > 0 ? page_live_lessonid : live_lessonid;
	if(live_lessonid > 0){
		$("#live_lesson_url").attr("href", "./index.php?i=<?php  echo $uniacid;?>&c=entry&do=lesson&play=1&m=fy_lessonv2&id=" + live_lessonid);
		$(".back_live-global").show();
	}
	$("#live_lesson_url").click(function(){
		sessionStorage.setItem(curr_uniacid + "_live_lessonid", "");
		if(live_lessonid > 0){
			window.location.href = "./index.php?i=<?php  echo $uniacid;?>&c=entry&do=lesson&play=1&m=fy_lessonv2&id=" + live_lessonid;
		}
	})

	//兼容iphoneX、XSMax、XR底部菜单
	var isIPhoneX = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 3 && window.screen.width === 375 && window.screen.height === 812;
	var isIPhoneXSMax = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 3 && window.screen.width === 414 && window.screen.height === 896;
	var isIPhoneXR = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 2 && window.screen.width === 414 && window.screen.height === 896;
	if(isIPhoneX || isIPhoneXSMax || isIPhoneXR){
		var iphonex_head = document.head || document.getElementsByTagName('head')[0];
		var iphonex_system = document.createElement('style');

		<?php  if(in_array($_GPC['do'], array('shop','shopcart'))){ ?>
			var footer_nav = document.getElementById("footer-nav");
			var footer_nav_height = (footer_nav.clientHeight || footer_nav.offsetHeight) * 1 + 20;
			iphonex_system.innerHTML = '.footer-nav{height:' + footer_nav_height + 'px;}.iphone-max-height{bottom:73px !important;}';
		<?php  } ?>

		<?php  if($_GPC['do']=='shopcart'){ ?>
			iphonex_system.innerHTML += '.total-wrap{bottom:71px !important;}';
		<?php  }elseif($_GPC['do']=='shopgoods'){ ?>
			iphonex_system.innerHTML += '.goods_btn_wrap{padding-bottom:20px !important;}';
		<?php  } ?>

		iphonex_head.appendChild(iphonex_system);
	}
</script>