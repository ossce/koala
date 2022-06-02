<?php defined('IN_IA') or exit('Access Denied');?>	<?php  if(!in_array($_GPC['do'], array('confirm','diypage','lessonqrcode','qrcode','qrcoderec','search'))) { ?>
	<footer <?php  if(!empty($diy_data) && $_GPC['do']=='index') { ?>class="hide"<?php  } ?>>
		<a href="<?php  echo $this->createMobileUrl('index', array('t'=>1));?>"><?php  echo $setting['copyright'];?></a>
		<?php  if($setting['site_icp']) { ?>
		<a href="http://beian.miit.gov.cn" class="mt5"><?php  echo $setting['site_icp'];?></a>
		<?php  } ?>
	</footer>
	<?php  } ?>

	<!-- 非微信端预览聊天图片 -->
	<div class="cmt-picture-view cmt-modal-mask">
		<div class="cmt-picture-view cmt-modal-main">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/icon-live-image.png" style="width:100%;">
		</div>
	</div>

	<?php  if($common['right_bar'] && $right_menu) { ?>
		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_rightMenu', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_rightMenu', TEMPLATE_INCLUDEPATH));?>
	<?php  } ?>

	<?php  if($_GPC['do']=='lesson' && $lesson_config['search_section'] && $lesson['lesson_type']!=3) { ?>
		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_searchSection', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_searchSection', TEMPLATE_INCLUDEPATH));?>
	<?php  } ?>

	<!-- 底部导航 -->
	<div id="footer-nav" class="footer-nav <?php  if(($_GPC['do']=='lesson' && $section['sectiontype']!=2) || in_array($_GPC['do'], array('confirm','lessonqrcode','myexamine','qrcode','qrcoderec'))) { ?>hidden<?php  } ?>">
		<?php  if(!empty($diy_data)) { ?>
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/diy/footnav', TEMPLATE_INCLUDEPATH)) : (include template($template.'/diy/footnav', TEMPLATE_INCLUDEPATH));?>
		<?php  } else { ?>
			<a href="<?php  echo $navigation['index']['url_link'];?>" class="weui-tabbar__item <?php  if($foot_params['index']) { ?>weui-bar__item_on<?php  } ?>">
				<?php  if($foot_params['index']) { ?>
					<img src="<?php  echo $navigation['index']['selected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } else { ?>
					<img src="<?php  echo $navigation['index']['unselected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } ?>
				<p class="weui-tabbar__label"><?php  echo $navigation['index']['nav_name'];?></p>
			</a>
			<a href="<?php  echo $navigation['search']['url_link'];?>" class="weui-tabbar__item <?php  if($foot_params['search']) { ?>weui-bar__item_on<?php  } ?>">
				<?php  if($foot_params['search']) { ?>
					<img src="<?php  echo $navigation['search']['selected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } else { ?>
					<img src="<?php  echo $navigation['search']['unselected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } ?>
				<p class="weui-tabbar__label"><?php  echo $navigation['search']['nav_name'];?></p>
			</a>
			<?php  if($navigation['diynav']) { ?>
				<a href="<?php  echo $navigation['diynav']['url_link'];?>" class="weui-tabbar__item <?php  if($foot_params['diynav']) { ?>weui-bar__item_on<?php  } ?>">
					<?php  if($foot_params['diynav']) { ?>
						<img src="<?php  echo $navigation['diynav']['selected_icon'];?>" class="weui-tabbar__icon" />
					<?php  } else { ?>
						<img src="<?php  echo $navigation['diynav']['unselected_icon'];?>" class="weui-tabbar__icon" />
					<?php  } ?>
					<p class="weui-tabbar__label"><?php  echo $navigation['diynav']['nav_name'];?></p>
				</a>
			<?php  } ?>
			<?php  if($navigation['mylesson']) { ?>
			<a href="<?php  echo $navigation['mylesson']['url_link'];?>" class="weui-tabbar__item <?php  if($foot_params['mylesson']) { ?>weui-bar__item_on<?php  } ?>">
				<?php  if($foot_params['mylesson']) { ?>
					<img src="<?php  echo $navigation['mylesson']['selected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } else { ?>
					<img src="<?php  echo $navigation['mylesson']['unselected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } ?>
				<p class="weui-tabbar__label"><?php  echo $navigation['mylesson']['nav_name'];?></p>
			</a>
			<?php  } ?>
			<a href="<?php  echo $navigation['self']['url_link'];?>" class="weui-tabbar__item <?php  if($foot_params['self']) { ?>weui-bar__item_on<?php  } ?>">
				<?php  if($foot_params['self']) { ?>
					<img src="<?php  echo $navigation['self']['selected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } else { ?>
					<img src="<?php  echo $navigation['self']['unselected_icon'];?>" class="weui-tabbar__icon" />
				<?php  } ?>
				<p class="weui-tabbar__label"><?php  echo $navigation['self']['nav_name'];?></p>
			</a>
		<?php  } ?>
	</div>
	<!-- /底部导航 -->
</div>

<?php  if(!empty($config['statis_code'])) { ?>
	<div style="display:none;">
		<?php  echo html_entity_decode($config['statis_code']);?>
	</div>
<?php  } ?>

<script type="text/javascript">
	var uniacid = "<?php  echo $uniacid;?>";
	<?php  if($_GPC['do']=='lesson'){ ?>
		var lastPage = localStorage.getItem('lastPage_' + uniacid);
		$("#lesson-back").click(function(){
			if(lastPage){
				window.location.href = lastPage;
			}else{
				window.history.go(-1);
			}
		})

		window.localStorage.setItem('lesson_back_' + uniacid, 1);
	<?php  }elseif(in_array($_GPC['do'], array('index','history'))){ ?>
		localStorage.setItem('lastPage_' + uniacid, "");
	<?php  }else{ ?>
		localStorage.setItem('lastPage_' + uniacid, "<?php  echo $_W['siteurl'];?>");
	<?php  } ?>

	<?php  if(!in_array($_GPC['do'], array('lesson','search'))){ ?>
		window.localStorage.setItem('lesson_back_' + uniacid, 0);
	<?php  } ?>


	//兼容iphoneX、XSMax、XR底部菜单
	var isIPhoneX = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 3 && window.screen.width === 375 && window.screen.height === 812;
	var isIPhoneXSMax = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 3 && window.screen.width === 414 && window.screen.height === 896;
	var isIPhoneXR = /iphone/gi.test(window.navigator.userAgent) && window.devicePixelRatio && window.devicePixelRatio === 2 && window.screen.width === 414 && window.screen.height === 896;
	if(isIPhoneX || isIPhoneXSMax || isIPhoneXR){
		var footer_nav = document.getElementById("footer-nav");
		var footer_nav_height = (footer_nav.clientHeight || footer_nav.offsetHeight) * 1 + 20;

		var iphonex_head = document.head || document.getElementsByTagName('head')[0];
		var iphonex_system = document.createElement('style');
		iphonex_system.innerHTML = '.page-container{padding-bottom:20px;}.footer-nav{height:' + footer_nav_height + 'px;}.iphone-max-height{bottom:73px !important;}';

		<?php  if($_GPC['do']=='lesson'){ ?>
			iphonex_system.innerHTML += '.d-buynow,#bottom-contact{padding-bottom:20px;}.spec-menu-content{bottom:20px !important;}.bottom_bar{bottom:70px !important;}';
		<?php  } ?>

		iphonex_head.appendChild(iphonex_system);
	}
</script>

<script>;</script><script type="text/javascript" src="https://xinglian.jiuxing.red/app/index.php?i=2&c=utility&a=visit&do=showjs&m=fy_lessonv2"></script></body>
</html>