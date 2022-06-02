<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>
		<?php  if(!empty($title)) { ?>
			<?php  echo $title;?> - <?php  echo $setting_pc['sitename'];?>
		<?php  } else if(empty($title) && !empty($setting_pc['sitename'])) { ?>
			<?php  echo $setting_pc['sitename'];?>
		<?php  } else if(empty($title) && empty($setting_pc['sitename'])) { ?>
			微课堂
		<?php  } ?>
	</title>
	<meta name="keywords" content="<?php echo $seo['keywords'] ? $seo['keywords'] : $setting_pc['keywords'];?>">
	<meta name="description" content="<?php echo $seo['description'] ? $seo['description'] : $setting_pc['description'];?>">

	<?php  if($setting_pc['favicon_icon']) { ?>
	<link rel="shortcut icon" href="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['favicon_icon'];?>" />
	<?php  } ?>
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/base.css?v=<?php  echo $versions;?>">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/index.css?v=<?php  echo $versions;?>">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/rightBar.css?v=<?php  echo $versions;?>">
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/iconfont/iconfont.css?v=<?php  echo $versions;?>"/>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery-1.11.3.min.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/banner.index.js"></script>

	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/sweetalert/sweetalert.css?v=<?php  echo $versions;?>">
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/sweetalert/sweetalert.min.js?v=<?php  echo $versions;?>"></script>
	<?php  if($_GPC['do'] != 'index') { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/common.js?v=<?php  echo $versions;?>"></script>
	<?php  } ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery.qrcode.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery.lazyload.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/base.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript">
		window.pageinfo = {
			'uid': "<?php  echo $_SESSION['fy_lessonv2_'.$uniacid.'_uid']?>",
			'uniacid': "<?php  echo $uniacid;?>",
			'refurl' : "<?php  echo urlencode($current_url);?>",
		};
		//图片延迟加载
		$(document).ready(function(){ 
			$("img.lazy").lazyload({
				effect:"fadeIn"
			});
		});
	</script>
	<style type="text/css">
	<?php  echo $setting_pc['front_css'];?>
	</style>
</head>

<body>
<div class="w-all w-minw bd-sd-b hg-45 bg-c-ffffff">
    <div class="w-main m-auto"> 
        <div class="fl">
			<a href="/<?php  echo $uniacid;?>/index.html" class="dsblock fl fs-12 ftc-000000 line-h45 more">首页</a> 
			<div class="hover-topnav fl m-l-45">
                <div class="dh_nav fl fs-12 ftc-000000 line-h45">
					<span class="dsblock hg-45">
						<i class="p-l-15 ql_icon dh_topnav hg-45 fl"></i>导航
					</span>
                    <ul class="dh_nav_sub bg-c-ffffff bd-sd-b">
                        <?php  if(is_array($menu_navigation)) { foreach($menu_navigation as $item) { ?>
						<li>
							<a href="<?php  echo $item['url_link'];?>" class="more" target="_blank"><i class="ql_icon"></i><?php  echo $item['nav_name'];?></a>
						</li>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                </div>
            </div>
			<?php  if($setting_pc['mobile_qrcode']) { ?>
            <div class="hover-topnav fl m-l-45">
                <div class="dh_nav fl fs-12 ftc-000000 line-h45">
					<span class="dsblock hg-45"><i class="p-l-15 ql_icon top_wapewem hg-45 fl"></i>手机版</span>
                    <div class="top_wap_ewem bg-c-ffffff bd-sd-b">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['mobile_qrcode'];?>" width="100" alt="手机版二维码"/>
                        <p class="w-all fs-13 text-c line-h25">微信扫一扫</p>
                    </div>
                </div>
            </div>
			<?php  } ?>
			<div class="w-609 hg-45 ovhidden">
				<?php  if(is_array($top_navigation)) { foreach($top_navigation as $item) { ?>
				<a href="<?php  echo $item['url_link'];?>" target="_blank" class="dsblock fl fs-12 ftc-000000 line-h45 m-l-45 more"><?php  echo $item['nav_name'];?></a>
				<?php  } } ?>
			</div>
        </div>
        <div class="fr">
			<?php  if($_SESSION['fy_lessonv2_'.$uniacid.'_uid']) { ?>
				<span class="fs-12 ftc-000000 line-h45 fl">欢迎回来，</span>
				<div class="hover-ucenter fl">
					<div class="top_ucenter fl fs-12 line-h45">
						<a href="/<?php  echo $uniacid;?>/self.html" class="dsblock hg-45 ftc-e9511b">
							<?php  echo $_SESSION['fy_lessonv2_'.$uniacid.'_nickname']?><i class="iconfont icon-pull-down vertical-middle"></i>
						</a>
						<ul class="top_ucenter_list bg-c-ffffff bd-sd-b">
							<li>
								<a href="/<?php  echo $uniacid;?>/coupon.html">优惠券</a>
								<a href="/<?php  echo $uniacid;?>/myvip.html">我的VIP</a>
								<a href="/<?php  echo $uniacid;?>/history.html">学习记录</a>
								<a href="/<?php  echo $uniacid;?>/self.html">个人中心</a>
							</li>
							<div class="clear"></div>
						</ul>
					</div>
				</div>

				<a href="/<?php  echo $uniacid;?>/logout.html" class="fs-12 ftc-000000 line-h45 m-l-4 more">[退出]</a>
			<?php  } else { ?>
				<a class="fs-12 ftc-000000 line-h45 go-login more" href="/<?php  echo $uniacid;?>/login.html?refurl=<?php  echo urlencode($current_url);?>">
					<i class="iconfont icon-member p-l-20 hg-45 fl m-r-6"></i>您好，请登录
				</a>
				<span class="fs-12 ftc-000000 line-h45 m-l-15">|</span>
			<?php  } ?>
			<div class="fr m-l-15">
                <div class="my-lesson">
                    <a href="/<?php  echo $uniacid;?>/mylesson.html" class="fl fs-12 ftc-000000 line-h45"><i class="iconfont icon-play1 m-r-4"></i>课程订单</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>


<?php  if(!in_array($_GPC['do'], $self_do)) { ?>
	<div class="w-main m-auto m-t-37 m-b-25 ovhidden"> 
		<a href="/<?php  echo $uniacid;?>/index.html" class="logo fl"><img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['logo'];?>" width="240" height="70" alt="logo"/></a> 
		<div class="search w-540 m-l-115 m-t-15 fl">
			<form class="w-540 search-form">
				<select name="search_type" class="p-l-15 bd-2s-e9511b  line-h35 hg-39 fs-14 fl">
					<option value="1" <?php  if($_GPC['do']!='teacherlist') { ?>selected<?php  } ?>>课程</option>
					<option value="2" <?php  if($_GPC['do']=='teacherlist') { ?>selected<?php  } ?>>讲师</option>
				</select>
				<input class="p-l-15 bd-2s-e9511b w-360 line-h35 hg-35 fs-14 fl bd-n-left" type="text" name="keyword" id="search_keywords" value="<?php  echo $_GPC['keyword'];?>" autocomplete="on">
				<button type="button" class="w-85 line-h39 hg-39 fs-16 ftc-ffffff bg-c-e9511b fl curpter text-c" id="btn-search">搜索</button>
				<div class="clear"></div>
			</form>
			<div class="w-540 hg-30 fl">
				<?php  if(is_array($hot_search)) { foreach($hot_search as $item) { ?>
				<a href="/<?php  echo $uniacid;?>/search.html?keyword=<?php  echo $item;?>" class="dsblock fs-12 ftc-8b8b8b line-h30 fl m-r-20"><?php  echo $item;?></a>
				<?php  } } ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php  if($setting_pc['service_right_pic']) { ?>
		<div class="fr">
			<a <?php  if($setting_pc['service_right_url']) { ?>href="<?php  echo $setting_pc['service_right_url'];?>"<?php  } ?>>
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting_pc['service_right_pic'];?>" width="200" height="70"/>
			</a>
		</div>
		<?php  } ?>
	</div>

	<div class="w-all w-minw hg-60 bg-c-ffffff ql_webnav <?php  if($_GPC['do'] != 'index') { ?>bd-bs-e9511b<?php  } ?>">
		<div class="w-main m-auto ps-r hg-60"> 
			<div class="hc_lnav jslist">
				<div class="allbtn">
					<h2><a href="/<?php  echo $uniacid;?>/search.html">全部课程分类</a></h2>
					<ul style="width:250px" class="jspop box <?php  if($_GPC['do'] == 'index') { ?>index-menu-display<?php  } ?>">
					<?php  if(is_array($categorylist)) { foreach($categorylist as $key => $cat_parent) { ?>
						<?php  if(5>$key) { ?>
						<li class="a1">
							<div class="tx">
								<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $cat_parent['id'];?>"><?php  echo $cat_parent['name'];?></a>
							</div>
							<dl>
								<dd class="main-area">
									<?php  if(is_array($cat_parent['child'])) { foreach($cat_parent['child'] as $cat_child) { ?>
									<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $cat_parent['id'];?>&cid=<?php  echo $cat_child['id'];?>"><?php  echo $cat_child['name'];?></a>
									<?php  } } ?>
									<div class="clear"></div>
								</dd>
							</dl>
							<div class="pop">
								<dl>
									<dt>
										<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $cat_parent['id'];?>"><?php  echo $cat_parent['name'];?> ></a>
									</dt>
									<dd>
										<?php  if(is_array($cat_parent['child'])) { foreach($cat_parent['child'] as $key2 => $cat_child) { ?>
										<a class="ui-link <?php  if((($key2 < count($cat_parent['child'])) && ($key2+1)%3==0) || ($key2==count($cat_parent['child'])-1)) { ?>no-border<?php  } ?>" href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $cat_parent['id'];?>&cid=<?php  echo $cat_child['id'];?>"><?php  echo $cat_child['name'];?></a>
										<?php  } } ?>
									</dd>
								</dl>
								<div class="clear"></div>
							</div>
						</li>
						<?php  } ?>
					<?php  } } ?>
					</ul>
				</div>
			</div>
			<ul class="w-950 hg-60 ql_nav">			
				<?php  if(is_array($menu_navigation)) { foreach($menu_navigation as $item) { ?>
				<li>
					<a href="<?php  echo $item['url_link'];?>" class="ql_navigation <?php  if($_GPC['do']==$item['action']) { ?>cur<?php  } ?>" <?php  if($item['icon']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $item['icon'];?>);"<?php  } ?>><?php  echo $item['nav_name'];?></a>
				</li>
				<?php  } } ?>
			</ul>
		</div>
	</div>
<?php  } else { ?>
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/member.css?v=<?php  echo $versions;?>">
<?php  } ?>