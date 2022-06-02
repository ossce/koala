<?php defined('IN_IA') or exit('Access Denied');?><div class="w-all w-minw bg-c-32333b p-t-15 ovhidden"> 
    <?php  if($bottom_navigation) { ?>
	<div class="w-main m-auto">
        <div class="fl w-65 fs-12 ftc-a9a9a9 line-h30 ft-w-b"><?php echo $friendly_link['font'] ? $friendly_link['font'] : '友情链接';?>：</div>
        <div class="fr">
            <ul class="ql_link">
				<?php  if(is_array($bottom_navigation)) { foreach($bottom_navigation as $item) { ?>
                <li><a href="<?php  echo $item['url_link'];?>" class="more" target="_blank"><?php  echo $item['nav_name'];?></a></li>
				<?php  } } ?>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
    <!--分割线 start-->
    <div class="w-main m-auto">
        <div class="w-main fl hg-12"></div>
        <div class="w-main fl hg-1 bg-c-505050"></div>
    </div>
	<?php  } ?>

    <div class="w-main m-auto m-b-10 ovhidden">
        <div class="m-t-12 fl">
			<?php  if($footer_group) { ?>
			<div class="footer-help fl m-t-5">
				<?php  if(is_array($footer_group)) { foreach($footer_group as $group) { ?>
				<dl>
					<dt><?php  echo $group['title'];?></dt>
					<div class="footer-help__item">
						<?php  if(is_array($group['article_list'])) { foreach($group['article_list'] as $item) { ?>
						<dd>
							<a href="/<?php  echo $uniacid;?>/article.html?op=details&aid=<?php  echo $item['id'];?>" target="_blank"><?php  echo $item['title'];?></a>
						</dd>
						<?php  } } ?>
					</div>
				</dl>
				<?php  } } ?>
			</div>
			<?php  } else { ?>
			<a href="/<?php  echo $uniacid;?>/index.html" class="m-t-25 fl">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['bottom_logo'];?>" width="235" height="70" alt=""/>
			</a>
			<?php  } ?>
            <div class="qlfooter_nav fl <?php  if($footer_group) { ?>m-l-20<?php  } else { ?>m-l-80<?php  } ?>">               
                <div class="ql_f_infp w-340 m-l-15 fl">
					<h4>联系方式</h4>
					<?php  if($company_info['tel']) { ?>
						<p>电话：<?php  echo $company_info['tel'];?></p>
					<?php  } ?>
					<?php  if($company_info['email']) { ?>
						<p>邮箱：<?php  echo $company_info['email'];?></p>
					<?php  } ?>
					<?php  if($company_info['address']) { ?>
						<p>地址：<?php  echo $company_info['address'];?></p>
					<?php  } ?>
                </div>
            </div>
        </div>
        <div class="w-340 text-c m-t-12 fr">
			<?php  if($setting_pc['mobile_qrcode']) { ?>
            <div class="w-160 m-l-10 fl m-t-10">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['mobile_qrcode'];?>" width="112" height="112" alt=""/>
                <p class="w-all fs-12 ftc-efefef text-c line-h25 fl">手机版</p>
            </div>
			<?php  } ?>
			<?php  if($setting['qrcode']) { ?>
            <div class="w-160 m-l-10 fl m-t-10">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting['qrcode'];?>" width="112" height="112" alt=""/>
                <p class="w-all fs-12 ftc-efefef text-c line-h25 fl">微信公众号</p>
            </div>
			<?php  } ?>
        </div>
    </div>
	<div class="w-main m-auto">
        <div class="w-main fl hg-12"></div>
        <div class="w-main fl hg-1 bg-c-505050"></div>
    </div>
</div>

<?php  include $this->template($template.'/_rightBar')?>

<div id="loadingToast" class="dsbnone">
	<div class="loading-mask_white"></div>
	<div class="loadEffect">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>
</div>

<div class="w-all w-minw bg-c-32333b p-t-20 p-b-20 ovhidden copyright">
    <div class="w-main m-auto text-c fs-12 ftc-a9a9a9 line-24">
		<p><?php  echo $setting['copyright'];?></p>
		<p class="ftc-737373">
			<?php  if($setting_pc['site_icp']) { ?>
			网站备案：<a href="http://beian.miit.gov.cn" target="_blank" class="ftc-737373"><?php  echo $setting_pc['site_icp'];?></a>
			<?php  } ?>
			<?php  if($setting_pc['site_added']) { ?>
			|&nbsp;&nbsp;增值电信业务经营许可证：<a class="ftc-737373"><?php  echo $setting_pc['site_added'];?></a>
			<?php  } ?>
			<?php  if($setting_pc['site_network']) { ?>
			|&nbsp;&nbsp;<img src="<?php echo MODULE_URL;?>static/webapp/default/images/bei.png"> <a href="http://www.beian.gov.cn/portal/registerSystemInfo" target="_blank" class="ftc-737373"><?php  echo $setting_pc['site_network'];?></a>
			<?php  } ?>
			<?php  if($setting_pc['site_culture']) { ?>
			|&nbsp;&nbsp;<a class="ftc-737373"><?php  echo $setting_pc['site_culture'];?></a>
			<?php  } ?>
		</p>
	</div>
</div>

<?php  if(!empty($config['statis_code'])) { ?>
	<div style="display:none;">
		<?php  echo html_entity_decode($config['statis_code']);?>
	</div>
<?php  } ?>

<script>
	$(document).ready(function(){
		var w = parseInt(($(window).width()-1200)/2);
		$(".qlban_ad").css("right",w);

		$("#btn-search").click(function(){
			var keyword = $("input[name=keyword]").val();
			var search_type = $("select[name=search_type] option:selected").val();

			if(search_type==1){
				window.location.href = "/<?php  echo $uniacid;?>/search.html?keyword=" + keyword;

			}else if(search_type==2){
				window.location.href = "/<?php  echo $uniacid;?>/teacherlist.html?keyword=" + keyword;
			}
		});
	});

	$("#search_keywords").bind('keypress', function(e) {
		if (e.keyCode == "13") {
			e.preventDefault();
			document.getElementById("btn-search").click();
		}
	});
</script>
<script>;</script><script type="text/javascript" src="https://xinglian.jiuxing.red/app/index.php?i=2&c=utility&a=visit&do=showjs&m=fy_lessonv2"></script></body>
</html>