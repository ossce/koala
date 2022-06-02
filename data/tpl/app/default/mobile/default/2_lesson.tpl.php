<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 课程详情页【非直播课程】
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/qunService.css?v=<?php  echo $versions;?>" rel="stylesheet" />
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/lesson.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox" id="header-2">
	<a href="javascript:;" id="lesson-back" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
	<a href="<?php  echo $this->createMobileUrl('index', array('t'=>1))?>" class="ico go-index"></a>
</div>

<?php  if($poster_show && !$sectionid) { ?>
<!-- 赚取佣金 S -->
<div class="sharecard__entry-global max-width-640 ios-system" style="top:50px;" onclick="location.href='<?php  echo $this->createMobileUrl('lessonqrcode', array('lessonid'=>$id));?>'">
	<div class="sharecard__entry">
		<div class="sharecard__entry-icon">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/icon-lesson-share.png" />
		</div>
		<?php  if($commisson1_amount) { ?>
			<?php echo $lesson_page['shareIncome'] ? $lesson_page['shareIncome'] : '赚'.$commisson1_amount.'元';?>
		<?php  } else { ?>
			<?php echo $lesson_page['inviteCard'] ? $lesson_page['inviteCard'] : '邀请卡';?>
		<?php  } ?>
	</div>
</div>
<!-- 赚取佣金 E -->
<?php  } ?>

<div class="content-inner" style="min-height:100%;" onbeforeunload="goodbye()">
	<!-- 视频区域 S -->
	<input type="hidden" id="realPlayTime" value="0" />
	<div id="video-wrap" class="video-wrap max-width-640">
		<?php  if($_GPC['sectionid']>0) { ?>
			<?php  if($section['password'] && !$_SESSION[$uniacid.'_'.$id.'_'.$sectionid]) { ?>
				<!-- 密码验证 -->
				<div class="password-wrap">
					<span class="enter-password"><?php echo $lesson_page['visit_password'] ? $lesson_page['visit_password'] : '请输入访问密码';?></span>
					<form class="form" action="<?php  echo $_W['siteurl'];?>" method="post" id="password_form">
						<input type="password" name="visit_password" placeholder="输入访问密码" />
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
						<button type="submit">提交</button>
					</form>
				</div>
			<?php  } else { ?>
				<?php  if($section['sectiontype']==1) { ?>
					<?php  if($section['savetype']==2) { ?>
						<!-- 内嵌代码方式 -->
						<?php  echo htmlspecialchars_decode($section['videourl']);?>
					<?php  } else if($section['savetype']==5) { ?>
						<!-- 腾讯云点播 -->
						<style type="text/css">
							.video-js{width:100%;}
						</style>
						<?php  if(empty($qcloudvod['player_name'])) { ?>
							<link href="<?php echo MODULE_URL;?>static/public/tcplayer/tcplayer.css" rel="stylesheet">
							<script src="<?php echo MODULE_URL;?>static/public/tcplayer/hls.min.0.12.4.js"></script>
							<script src="<?php echo MODULE_URL;?>static/public/tcplayer/tcplayer.min.js"></script>
						<?php  } else { ?>
							<link href="<?php echo MODULE_URL;?>static/public/tcplayer/tcplayer.css" rel="stylesheet">
							<script src="<?php echo MODULE_URL;?>static/public/tcplayer/hls.min.0.13.2m.js"></script>
							<script src="<?php echo MODULE_URL;?>static/public/tcplayer/tcplayer.v4.1.min.js"></script>
						<?php  } ?>
						<video id="player-container-id" width="100%" height="220" preload="auto" x5-playsinline playsinline webkit-playsinline></video>
					<?php  } else { ?>
						<!-- 阿里云点播 -->
						<link rel="stylesheet" href="//g.alicdn.com/de/prismplayer/2.9.11/skins/default/aliplayer-min.css" />
						<script type="text/javascript" src="//g.alicdn.com/de/prismplayer/2.9.11/aliplayer-min.js"></script>
						<script type="text/javascript" src="//player.alicdn.com/aliplayer/presentation/js/aliplayercomponents.min.js"></script>
						<div class="prism-player" id="J_prismPlayer"></div>
						<style type="text/css">
						.prism-player .prism-big-play-btn{
							left: 50% !important;
							bottom: 50% !important;
							margin-left: -32px;
							margin-bottom: -32px;
						}
						.prism-player .prism-liveshift-progress, .prism-player .prism-progress{
							bottom: 45px!important;
						}
						</style>
					<?php  } ?>

					<?php  if(!$drag_play) { ?>
					<div class="verify_wrap_shade hide"></div>
					<div class="verify_wrap hide" id="verification">
					</div>

					<style type="text/css">
					.verify-img-panel{width:100% !important;}
					</style>
					<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/verification/verification.css">
					<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/verification/verification.js"></script>
					<?php  } ?>

				<?php  } else if($section['sectiontype']==3) { ?>
					<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/AudioPlay/audio.js?v=<?php  echo $versions;?>"></script>
					<link href="<?php echo MODULE_URL;?>static/public/AudioPlay/audio.css?v=<?php  echo $versions;?>" rel="stylesheet" />

					<div class="fylesson_audio">
						<div class="audio-wrapper">
							<audio id="section-audio" autoplay="autoplay">
								<source src="<?php  echo $section['videourl'];?>" type="audio/mpeg">
							</audio>
							<div class="audio-left">
								<img class="audio-bg-img playing" src="<?php  echo $_W['attachurl'];?><?php echo $section['images'] ? $section['images'] : $lesson['images'];?>">
								<img id="audioPlayer" class="audio-control" src="<?php echo MODULE_URL;?>static/public/AudioPlay/pause.png">
							</div>
							<div class="audio-right">
								<p style="max-width: 536px;"><?php  echo $section['title'];?></p>
								<div class="progress-bar-bg" id="progressBarBg">
									<span id="progressDot"></span>
									<div class="progress-bar" id="progressBar"></div>
								</div>
								<div class="audio-time">
									<span class="audio-length-current" id="audioCurTime">00:00</span>
									<span class="audio-length-total" id="audioDuration">00:00</span>
									<input type="hidden" id="current-ptime" value="0">
								</div>
							</div>
						</div>
					</div>
				<?php  } ?>
			<?php  } ?>
		<?php  } else { ?>
			<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['images'];?>" alt="<?php  echo $lesson['bookname'];?>" width="100%">
			<div class="cover-shade">
				<a href="<?php echo $study_link ? $study_link : $this->createMobileUrl('lesson', array('id'=>$lesson['id'],'sectionid'=>$first_section['id']));?>" class="section-play">
					<img src="<?php echo MODULE_URL;?>static/mobile/default/images/icon-section-play.png" width="64">
				</a>
			</div>
			
			<?php  if($setting['show_study_number']) { ?>
			<div class="learned">
				<?php  echo $lesson['buyTotal'];?>
				<?php  if($lesson['lesson_type'] == 0) { ?>
					<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
				<?php  } else if($lesson['lesson_type'] == 1) { ?>
					<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
				<?php  } ?>
			</div>
			<?php  } ?>

			<?php  if($virtual_buyinfo) { ?>
				<div class="newbuy_wrap">
					<div class="newbuy_info">
						<div class="newbuy_info_item"></div>
					</div>
				</div>
			<?php  } ?>
		<?php  } ?>
	</div>
	<!-- 视频区域 E -->

	<?php  if($lesson['lesson_type']==1 && $buynow_info['appoint_validity'] && time() < strtotime($buynow_info['appoint_validity'])) { ?>
	<!-- 报名活动截至 S -->
	<div id="appoint-banner" class="appoint-banner">
		<div class="appoint-message">
			<?php echo $lesson_page['appoint_validity'] ? $lesson_page['appoint_validity'] : '距活动结束仅剩'?> <i class="time_days" id="time_d">-天</i>
			<span id="time_h">--</span>:
			<span id="time_m">--</span>:
			<span id="time_s">--</span>
		</div>
	</div>
	<!-- 报名活动截至 E -->
	<?php  } else if($discount_endtime && !$show_isbuy) { ?>
	<!-- 限时折扣 S -->
	<div class="discount-limit-price ios-system">
		<div class="discount-limit-price-left">
			<div class="discount-sale-price weui-flex">
				<span class="discount-sale-num">
					<span class="discount-sale-num-text">￥</span>
					<span class="discount-sale-num-main"><?php  echo $diacount_price['0'];?></span>
					<span class="discount-sale-num-point">.</span>
					<span class="discount-sale-num-sub"><?php  echo $diacount_price['1'];?></span>
				</span>
			<div class="weui-flex-item text-left flash-sale-original">
				<div class="original-cost">￥<?php  echo $market_price;?></div>
					<span class="original-tips">限时折扣</span>
				</div>
			</div>
		</div>
		<div class="discount-limit-price-right discount-sale-countdown text-center">
			<p class="discount-sale-text">距离特价结束仅剩</p>
			<div class="time-item discount-sale-time">
				<span class="discount-sale-time-day" id="time_d">-天</span>
				<span class="discount-sale-time-item" id="time_h">--</span>
				<span class="discount-sale-time-mh">:</span>
				<span class="discount-sale-time-item" id="time_m">--</span>
				<span class="discount-sale-time-mh">:</span>
				<span class="discount-sale-time-item" id="time_s">--</span>
			</div>
		</div> 
	</div>
	<!--限时折扣 E -->
	<?php  } ?>

	<?php  if(!$sectionid && $commission_log && $lesson['price'] > 0) { ?>
	<div class="article_div flex0_1">
		<i class="flex_g0 share_cash_title"><?php echo $lesson_page['lately_commission'] ? $lesson_page['lately_commission'] : '推广收益';?></i>
		<ul class="article_div_list flex_all">
			<?php  if(is_array($commission_log)) { foreach($commission_log as $item) { ?>
			<li>
				<a href="javascript:;">
					<?php  echo $item['nickname'];?>获得推广佣金 <strong class="red-color"><?php  echo $item['change_num'];?>元</strong>
				</a>
			</li>
			<?php  } } ?>
		</ul>
	</div>
	<?php  } ?>
	
	<!-- 导航目录 S -->
	<ul id="course-tab-fixed" class="course-tab flex0_1 max-width-640">
			<li <?php  if($show_details) { ?>class="curr"<?php  } ?>><?php echo $lesson_page['details'] ? $lesson_page['details'] : '详情'?></li>
		<?php  if($lesson['lesson_type']!=1 || ($lesson['lesson_type']==1 && $lesson['appoint_dir'])) { ?>
			<li <?php  if($show_dir) { ?>class="curr"<?php  } ?>><?php echo $lesson_page['directory'] ? $lesson_page['directory'] : '目录'?></li>
		<?php  } ?>
		<?php  if(!empty($course_list) || !empty($examine_list)) { ?>
			<li><?php echo $lesson_page['test'] ? $lesson_page['test'] : '测验'?></li>
		<?php  } ?>
		<?php  if($like_goods_list) { ?>
			<li><?php echo $lesson_page['goods'] ? $lesson_page['goods'] : '商品'?></li>
		<?php  } ?>
		<?php  if($lesson_config['document'] && $document_list) { ?>
			<li><?php echo $lesson_page['document'] ? $lesson_page['document'] : '课件'?>(<?php  echo count($document_list)?>)</li>
		<?php  } ?>
		<?php  if($lesson_config['evaluate']) { ?>
			<li><?php echo $lesson_page['evaluate'] ? $lesson_page['evaluate'] : '评价'?>(<?php  echo $total;?>)</li>
		<?php  } ?>
	</ul>
	<!-- 导航目录 E -->

	<div id="course-container" class="course-container">
		<!-- 课程介绍 S -->
		<div class="js-tab" <?php  if($show_details) { ?>style="display: block;"<?php  } ?>>
			<ul class="course-intro">
				<?php  if(!$sectionid || !$section['content']) { ?>
					<li style="padding-bottom: 10px;">
						<a href="<?php  echo $this->createMobileUrl('lesson',array('id'=>$id));?>" class="title-bar__title"><?php  if($lesson['section_status']==0) { ?><span class="section-status-btn fs-14 vertical-botton">已完结</span><?php  } ?> <?php  echo $lesson['bookname'];?></a>
						<p class="lesson-bar clear">
							<span class="grid_info mar15-left fl ios-system">
								<?php  if($setting['lesson_vip_status']!=1 && !$discount_lesson) { ?>
									<span class="price index_price_lesson font-bold flex_g0 ios-system">
									<?php  if($lesson['price']>0) { ?>
										¥<?php  echo $lesson['price'];?>
									<?php  } else { ?>
										免费
									<?php  } ?>
									</span>
									<span class="mar5 ios-system">|</span>
								<?php  } ?>
								<?php  if($section_count) { ?>
									<span><?php  echo $section_count;?>节</span>
								<?php  } ?>
							</span>
							<?php  if($setting['lesson_vip_status']!=1) { ?>
							<span class="vnum fr">
								<?php  if($lesson['integral_rate']>0) { ?>
									赠<?php  echo $lesson['integral_rate'];?>倍<?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>
								<?php  } else if($lesson['integral']>0) { ?>
									赠<?php  echo $lesson['integral'];?><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '积分';?>
								<?php  } ?>
							</span>
							<?php  } ?>
						</p>
						<div class="flex1" style="margin: 5px 15px 0 15px;">
							<div class="grid_info flex0">
								<?php  echo $lesson['teacher'];?>
							</div>
							<span class="teacher flex_g0">
								<?php  if($setting['stock_config'] && $lesson['stock']>0) { ?>
									<span class="fl sale-on-btn">剩余<?php  echo $lesson['stock'];?>个名额</span>
								<?php  } else if($setting['stock_config'] && $lesson['stock']==0) { ?>
									已售罄
								<?php  } ?>
							</span>
						</div>
					</li>
					<?php  if($lesson['lesson_type']==1 && ($buynow_info['appoint_addres'] || $buynow_info['appoint_validity'])) { ?>
					<li class="details">
						<div class="weui-cells">
							<?php  if($buynow_info['appoint_addres']) { ?>
								<div class="weui-cell appoint_cell"><i class="fa fa-map-marker"></i><?php  echo $buynow_info['appoint_addres'];?></div>
							<?php  } ?>
							<?php  if($buynow_info['appoint_validity']) { ?>
								<div class="weui-cell appoint_cell"><i class="fa fa-clock-o"></i>报名截至时间：<?php  echo $buynow_info['appoint_validity'];?></div>
							<?php  } ?>
						</div>
					</li>
					<?php  } ?>
					<?php  if($lesson_config['lesson_all_qun']) { ?>
					<li class="details">
						<div class="teacher-main">
							<div class="teacher-avatar">
								<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['teacherphoto'];?>">
							</div>
							<div class="teacher-info">
								<div class="teacher-name"><?php  echo $lesson['teacher'];?><span class="all-lesson">(总共<?php  echo $lessonNumber;?><?php echo $lesson_page['lessonNum'] ? $lesson_page['lessonNum'] : '个课程'?>)</span></div>
								<div class="teacher-home">
									<a class="cell" href="<?php  echo $this->createMobileUrl('teacher', array('teacherid'=>$lesson['teacherid']));?>"><?php echo $lesson_page['allLesson'] ? $lesson_page['allLesson'] : '全部课程'?></a>
									<?php  if($userAgent && !empty($now_service)) { ?>
										<a id="join-qun" class="cell" href="javascript:;">粉丝群</a>
									<?php  } ?>
									<?php  if($userAgent && !$member['follow']) { ?>
									<a id="follow-wechat" class="cell" href="javascript:;">关注公众号</a>
									<?php  } ?>
								</div>
							</div>
						</div>
					</li>
					<?php  } ?>
					<li class="details">
						<div class="index_title bor flex1">
							<div class="img flex0">
								<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/lesson_introduce.png" style="width: 20px;">
							</div>
							<div class="flex_all"><?php echo $lesson_page['lessonIntroduce'] ? $lesson_page['lessonIntroduce'] : '课程介绍'?></div>
						</div>
						<div class="lesson-content chapter-content" id="lesson_desc" style="overflow:hidden;">
							<?php  echo htmlspecialchars_decode($lesson['descript']);?>
						</div>
						<div id="lesson_desc_more" style="display:none;">
							<span class="more">显示全部 <i class="fa fa-angle-double-down"></i></span>
						</div>
					</li>
					<?php  if($lesson_config['teacher_introduce']) { ?>
					<li class="details teacher_introduce">
						<div class="index_title bor flex1">
							<div class="img flex0">
								<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/lesson_teacher_introduce.png" style="width: 20px;">
							</div>
							<div class="flex_all"><?php echo $lesson_page['teacherIntroduce'] ? $lesson_page['teacherIntroduce'] : '讲师介绍'?></div>
						</div>
						<div class="chapter-content">
							<div>
								<?php  echo htmlspecialchars_decode($lesson['teacherdes']);?>
							</div>
						</div>
					</li>
					<?php  } ?>

					<?php  if($like_list) { ?>
					<div class="index_unit ">
						<div class="index_title flex1">
							<div class="img flex0">
								<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/icon_like_lesson.svg" style="width: 20px;">
							</div>
							<div class="flex_all index_recommend_title"><?php echo $lesson_page['like_lesson'] ? $lesson_page['like_lesson'] :'为您推荐';?></div>
						</div>
						<div class="small_grid">
							<?php  if(is_array($like_list)) { foreach($like_list as $item) { ?>
							<a href="<?php  echo $this->createMobileUrl('lesson',array('id'=>$item['id']))?>" class="small_grid_a pad-b-0">
								<div class="img-box">
									<div class="img">
										<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" />
										<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
									</div>
								</div>
								<div class="grid_title flex1"><?php  echo $item['bookname'];?> </div>
								<div class="grid_info flex0">
									<?php  if($item['price']==0) { ?>
										<span class="price flex_g0 index_price_lesson fs-14">免费</span>
									<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
										<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
									<?php  } else { ?>
										<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
									<?php  } ?>
								</div>
							</a>
							<?php  } } ?>
						</div>
					</div>
					<?php  } ?>
				<?php  } else { ?>
					<li class="details">
						<div class="index_title bor flex1">
							<div class="img flex0">
								<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/lesson_introduce.png" style="width: 20px;">
							</div>
							<div class="flex_all"><?php echo $lesson_page['sectionIntroduce'] ? $lesson_page['sectionIntroduce'] : '章节介绍'?></div>
						</div>
						<div class="lesson-content" style="padding:10px 15px; <?php  if($section['sectiontype']==1) { ?>overflow-y:auto; max-height:400px;<?php  } ?>">
							<?php  echo htmlspecialchars_decode($section['content']);?>
						</div>
					</li>
				<?php  } ?>
			</ul>
		</div>
		<!-- 课程介绍 E -->

		<!-- 章节目录 S -->
		<?php  if($lesson['lesson_type']!=1 || ($lesson['lesson_type']==1 && $lesson['appoint_dir'])) { ?>
		<div class="js-tab" <?php  if($show_dir) { ?>style="display: block;"<?php  } ?>>
			<ul class="course-chapter">
				<li>
					<?php  if($section_count) { ?>
						<?php  if(is_array($title_list)) { foreach($title_list as $key => $title) { ?>
						<h2 class="chapter-title" onclick="handleSection(this)"><i class="section-title-icon"></i><?php  echo $title['title'];?>[<?php  echo count($title['section'])?><?php echo $lesson_page['classHour'] ? $lesson_page['classHour'] : '课时'?>]<span class="<?php  if($sectionid) { ?><?php  if(!$title['play']) { ?>open<?php  } ?><?php  } else { ?><?php  if($key>4) { ?>open<?php  } ?><?php  } ?>"></span></h2>
						<ul class="course-sections <?php  if($sectionid) { ?><?php  if(!$title['play']) { ?>hide<?php  } ?><?php  } else { ?><?php  if($key>4) { ?>hide<?php  } ?><?php  } ?>">
							<?php  if(is_array($title['section'])) { foreach($title['section'] as $sec) { ?>
							<li>
								<a href="javascript:;" onclick="readSection(<?php  echo $sec['id'];?>);" class="title_wrap <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>">
									<div class="title">
										<i class="course-type">
											<?php  if($sec['sectiontype']==1) { ?>
												视频
											<?php  } else if($sec['sectiontype']==2) { ?>
												图文
											<?php  } else if($sec['sectiontype']==3) { ?>
												音频
											<?php  } else if($sec['sectiontype']==4) { ?>
												外链
											<?php  } ?>
										</i>
										<?php  if(($sec['sectiontype']==1 || $sec['sectiontype']==3) && $sec['password']) { ?>
										<i class="fa fa-lock"></i>
										<?php  } ?>
										<?php  echo $sec['title'];?>
									</div>
									<div class="free-time-content">
										<?php  if($sec['is_free']==1) { ?>
										<span class="free-play-box">
											<i class="fa fa-play-circle"></i>
											<i class="free-play-font"><?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?></i>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  } else { ?>
											<?php  if($lesson_page['playTip']) { ?>
												<span class="buy-play-box">
													<i class="fa fa-play-circle"></i>
													<i class="free-play-font"><?php  echo $lesson_page['playTip'];?></i>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
											<?php  } ?>
										<?php  } ?>

										<?php  if($sec['videotime']) { ?>
										<span class="section-time">
											<i class="fa fa-clock-o"></i>
											<i><?php  echo $sec['videotime'];?></i>
										</span>
										<?php  } ?>
									</div>
								</a>
							</li>
							<?php  } } ?>
						</ul>
						<?php  } } ?>
						
						<?php  if(!empty($section_list)) { ?>
							<?php  if(empty($title_list)) { ?>
								<h2 class="chapter-title" onclick="location.href='<?php  echo $this->createMobileUrl('lesson', array('id'=>$lesson['id']));?>'"><i class="section-title-icon"></i><?php echo $section_keyword ? '“'.$section_keyword.'”搜索结果' : $lesson['bookname']?>[<?php  echo $section_count;?>课时]</h2>
							<?php  } ?>
							<ul class="course-sections">
							<?php  if(is_array($section_list)) { foreach($section_list as $sec) { ?>
								<li>
									<a href="javascript:;" onclick="readSection(<?php  echo $sec['id'];?>);" class="title_wrap <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>">
										<div class="title">
											<i class="course-type">
												<?php  if($sec['sectiontype']=='1') { ?>
													视频
												<?php  } else if($sec['sectiontype']=='2') { ?>
													图文
												<?php  } else if($sec['sectiontype']=='3') { ?>
													音频
												<?php  } else if($sec['sectiontype']=='4') { ?>
													外链
												<?php  } ?>
											</i>
											<?php  if(($sec['sectiontype']==1 || $sec['sectiontype']==3) && $sec['password']) { ?>
											<i class="fa fa-lock"></i>
											<?php  } ?>
											<?php  echo $sec['title'];?>
										</div>
										<div class="free-time-content">
											<?php  if($sec['is_free']==1) { ?>
											<span class="free-play-box">
												<i class="fa fa-play-circle"></i>
												<i class="free-play-font"><?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?></i>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<?php  } else { ?>
												<?php  if($lesson_page['playTip']) { ?>
												<span class="buy-play-box">
													<i class="fa fa-play-circle"></i>
													<i class="free-play-font"><?php  echo $lesson_page['playTip'];?></i>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<?php  } ?>
											<?php  } ?>

											<?php  if($sec['videotime']) { ?>
											<span class="section-time">
												<i class="fa fa-clock-o"></i>
												<i><?php  echo $sec['videotime'];?></i>
											</span>
											<?php  } ?>
										</div>
									</a>
								</li>
								<?php  } } ?>
							</ul>
						<?php  } ?>
					<?php  } else { ?>
						<h2 class="chapter-title" onclick="location.href='<?php  echo $this->createMobileUrl('lesson', array('id'=>$lesson['id']));?>'"><i class="section-title-icon"></i><?php  echo $lesson['bookname'];?>[<?php  echo $section_count;?>课时]</h2>
						<ul class="course-sections">
							<li style="height:auto;padding:10%;">
								<a style="text-align:center;"><?php echo $lesson['lesson_type']==0 ? '未找到任何内容' : '请点击“详情”查看相关介绍';?></a>
							</li>
						</ul>
					<?php  } ?>
				</li>
			<ul>
		</div>
		<?php  } ?>
		<!-- 章节目录 E -->

		<?php  if(!empty($course_list) || !empty($examine_list)) { ?>
		<!-- 测验 S -->
		<div class="js-tab bg-fff">
			<?php  if(is_array($course_list)) { foreach($course_list as $item) { ?>
			<div class="lesson-wrapper">
				<a href="./index.php?i=<?php  echo $uniacid;?>&c=entry&op=&id=<?php  echo $item['id'];?>&lessonid=<?php  echo $id;?>&do=course&m=fy_lessonv2_plugin_exam" class="lesson-info-wrap">
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" class="lesson-img">
					<div class="lesson-detail">
						<div class="lesson-name"><?php  echo $item['title'];?></div>
					</div>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/icon_more_right.png" class="lesson-go-img">
				</a>
			</div>
			<?php  } } ?>
			<?php  if(is_array($examine_list)) { foreach($examine_list as $item) { ?>
			<div class="lesson-wrapper">
				<a href="./index.php?i=<?php  echo $uniacid;?>&c=entry&op=&id=<?php  echo $item['id'];?>&lessonid=<?php  echo $id;?>&do=examine&m=fy_lessonv2_plugin_exam" class="lesson-info-wrap">
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" class="lesson-img">
					<div class="lesson-detail">
						<div class="lesson-name"><?php  echo $item['title'];?></div>
					</div>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/icon_more_right.png" class="lesson-go-img">
				</a>
			</div>
			<?php  } } ?>
		</div>
		<!-- 测验 E -->
		<?php  } ?>

		<?php  if($like_goods_list) { ?>
		<!-- 商品 S -->
		<div class="js-tab bg-fff">
			<?php  if(is_array($like_goods_list)) { foreach($like_goods_list as $item) { ?>
			<div class="shop-recommend-one">
				<div class="shop-flex-2">
					<a href="./index.php?i=<?php  echo $uniacid;?>&c=entry&do=shopgoods&m=fy_lessonv2_plugin_shop&id=<?php  echo $item['id'];?>" class="shop-goods-img">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
					</a>
					<div class="shop-flex-box">
						<a href="./index.php?i=<?php  echo $uniacid;?>&c=entry&do=shopgoods&m=fy_lessonv2_plugin_shop&id=<?php  echo $item['id'];?>" class="ds-block">
							<h2><?php  echo $item['title'];?></h2>
							<h3>
								<em><?php  echo $item['show_price'];?></em>
								<?php  if($item['sell_type'] != 1 && $item['market_price']>0) { ?>
								<i>￥<?php  echo $item['market_price'];?></i>
								<?php  } ?>
							</h3>
						</a>
						<?php  if($item['show_sales']) { ?>
						<h4>
							<em>已售<?php  echo $item['show_sales'];?></em>
						</h4>
						<?php  } ?>
					</div>
				</div>
			</div>
			<?php  } } ?>
		</div>
		<!-- 商品 E -->
		<?php  } ?>

		<?php  if($lesson_config['document'] && $document_list) { ?>
		<!-- 课件列表 S -->
		<div class="js-tab">
			<ul class="course-chapter">
				<li>
					<?php  if(!empty($document_list)) { ?>
						<ul class="course-sections">
							<?php  if(is_array($document_list)) { foreach($document_list as $key => $document) { ?>
							<li>
								<a href="<?php  if($plays) { ?><?php  echo $this->createMobileUrl('downloadfile', array('fileid'=>$document['id']));?><?php  } else { ?>javascript:alert('请您先登录或购买课程');<?php  } ?>" class="wxapp-download-tip">
									<div>
										<i class="fa fa-file-text-o"></i>&nbsp;
										<?php  echo $key+1;?>、<?php  echo $document['title'];?>
									</div>
								</a>
							</li>
							<?php  } } ?>
						</ul>
					<?php  } else { ?>
						<ul class="course-sections">
							<li style="height:auto;padding:10%;">
								<a style="text-align:center;">暂时没有任何<?php echo $lesson_page['document'] ? $lesson_page['document'] : '课件'?>~</a>
							</li>
						</ul>
					<?php  } ?>
				</li>
			<ul>
		</div>
		<!-- 课件列表 E -->
		<?php  } ?>

		<?php  if($lesson_config['evaluate']) { ?>
		<!-- 评价列表 S -->
		<div class="js-tab">
			<div class="evaluate-wrap">
				<div class="evaluate-general" id="evaluate_general">
					<div class="score-general"><?php  echo $evaluate_score['score'];?>%</div>
					<div class="score-item">
						<p class="score-title"><?php echo $evaluate_page['global_score'] ? $evaluate_page['global_score'] : '综合评分';?></p>
						<p class="score-num"><?php  echo $evaluate_score['global_score'];?></p>
					</div>
					<div class="score-item">
						<p class="score-title"><?php echo $evaluate_page['content_score'] ? $evaluate_page['content_score'] : '内容实用';?></p>
						<p class="score-num"><?php  echo $evaluate_score['content_score'];?></p>
					</div>
					<div class="score-item">
						<p class="score-title"><?php echo $evaluate_page['understand_score'] ? $evaluate_page['understand_score'] : '通俗易懂';?></p>
						<p class="score-num"><?php  echo $evaluate_score['understand_score'];?></p>
					</div>
				</div>
				<?php  if($allow_evaluate) { ?>
				<div class="lesson-evaluate">
					<div class="reply-div">
						有什么想要说的呢？赶紧
						<a class="btn btn-default btn-sm" href="<?php  echo $evaluate_url;?>">去评价</a>
						吧~
					</div>
				</div>
				<?php  } ?>

				<div class="evaluate-list" id="evaluate_list">
				</div>
			</div>

			<div id="loading_div" class="loading_div">
				<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
			</div>
		</div>
		<!-- 评价列表 E -->
		<?php  } ?>
	</div>

	<?php  if(!empty($advs)) { ?>
	<div style="margin-top: 10px;;">
		<a href="<?php  echo $advs['link'];?>"><img src="<?php  echo $_W['attachurl'];?><?php  echo $advs['picture'];?>" style="width:100%;"></a>
	</div>
	<?php  } ?>

	<?php  if(!empty($hissection)) { ?>
	<!-- <div id="bottom_bar" class="bottom_bar" style="display: block;">
		<div class="close">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-close-o-white.png" />
		</div>
		<a href="<?php  echo $hisplayurl;?>" class="play">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-play-o-white.png" />
		</a>
		<div class="content">
			<div class="con1">上次学习章节：</div>
			<div class="con2"><?php  echo $hissection['title'];?></div>
		</div>
	</div> -->
	<?php  } ?>
</div>


<?php  if(!empty($lesson['weixin_qrcode'])) { ?>
<div id="cover" class="teacher-qrcode max-width-640">
	<div class="qrcode-content">
		<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['weixin_qrcode'];?>" />
		<p>二维码咨询方式</p>
	</div>
</div>
<?php  } ?>

<div id="bottom-contact" class="max-width-640 hide">
	<div class="contact-wrap">
		<div class="contact-wrap-title">咨询交流</div>
		<?php  if(!empty($lesson['weixin_qrcode'])) { ?>
		<div class="border-top layer-list_item">
			<a href="javascript:;" id="btn-qrcode">
				<div class="layer-list_item-icon">
					<img class="layer-list_item-img" src="<?php echo MODULE_URL;?>static/mobile/default/images/contact-wechat.png">
				</div>
				<p class="layer-list_item-name">微信咨询</p>
				<p class="layer-list_item-info">点击查看二维码</p>
				<div class="layer-list_item-go">
					<i class="icon-font i-v-right">&gt;</i>
				</div>
			</a>
		</div>
		<?php  } ?>
		<?php  if(!empty($config['tel'])) { ?>
		<div class="border-top layer-list_item">
			<a href="tel:<?php  echo $config['tel'];?>">
				<div class="layer-list_item-icon">
					<img class="layer-list_item-img" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/contact-tel.png?v=2">
				</div>
				<p class="layer-list_item-name">客服电话</p>
				<p class="layer-list_item-info"><?php  echo $config['tel'];?></p>
				<div class="layer-list_item-go">
					<i class="icon-font i-v-right">&gt;</i>
				</div>
			</a>
		</div>
		<?php  } ?>
		<?php  if(!empty($lesson['online_url'])) { ?>
		<div class="border-top layer-list_item">
			<a href="<?php  echo $lesson['online_url'];?>">
				<div class="layer-list_item-icon">
					<img class="layer-list_item-img" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/contact-online.png">
				</div>
				<p class="layer-list_item-name">在线客服</p>
				<p class="layer-list_item-info">点击咨询客服</p>
				<div class="layer-list_item-go">
					<i class="icon-font i-v-right">&gt;</i>
				</div>
			</a>
		</div>
		<?php  } ?>
		<?php  if(!empty($lesson['qq'])) { ?>
		<div class="border-top layer-list_item">
			<a id="qq-consult" href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $lesson['qq'];?>&site=qq&menu=yes">
				<div class="layer-list_item-icon">
					<img class="layer-list_item-img" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/contact-1v1.png?v=1">
				</div>
				<p class="layer-list_item-name">QQ咨询</p>
				<p class="layer-list_item-info">QQ:<?php  echo $lesson['qq'];?></p>
				<div class="layer-list_item-go">
					<i class="icon-font i-v-right">&gt;</i>
				</div>
			</a>
		</div>
		<?php  } ?>
		<?php  if(!empty($lesson['qqgroup'])) { ?>
		<div class="contact-wrap__qun border-top">
			<div class="contact-wrap-qun-title">加群交流<span class="contact-wrap-qun-desc">(获取资料、学员交流)</span></div>
			<ul>
				<li class="layer-list_item">
					<a id="qqgroup-consult" <?php  if(!empty($lesson['qqgroupLink'])) { ?>href="<?php  echo $lesson['qqgroupLink'];?>"<?php  } ?>>
						<div class="layer-list_item-icon">
							<img class="layer-list_item-img" src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['teacherphoto'];?>">
						</div>
						<p class="layer-list_item-name z-tail"><?php  echo $lesson['teacher'];?>讲师交流群</p>
						<p class="layer-list_item-info">QQ群:<?php  echo $lesson['qqgroup'];?></p>
						<div class="layer-list_item-go">
							<i class="icon-font i-v-right">&gt;</i>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<?php  } ?>
		<?php  if(empty($lesson['qq']) && empty($lesson['qqgroup']) && empty($lesson['weixin_qrcode']) && empty($lesson['online_url'])) { ?>
		<div class="contact-wrap__qun border-top" style="text-align:center;">
			<div class="contact-wrap-qun-title">抱歉，未找到任何交流方式~</div>
		</div>
		<?php  } ?>
	</div>
	<div class="layer-close">关闭</div>
</div>
<div id="layer-bg" class="hide"></div>

<!-- 遮罩层 -->
<div class="flick-menu-mask hide"></div>
<!--课程规格start-->
<div class="spec-menu-content max-width-640 spec-menu-show hide">
	<div class="spec-menu-top bdr-b">
		<div class="spec-first-pic">
			<img id="spec_image" src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['images'];?>" onerror="imgErr(this)">
		</div>
		<a class="rt-close-btn-wrap spec-menu-close">
			<p class="flick-menu-close"></p>
		</a>
		<div class="spec-price" id="specJdPri" style="display: block">
			<span class="yang-pic spec-yang-pic"></span>
			<span id="spec_price"> ￥<?php  echo $lesson['price'];?> </span>
		</div>
	</div>
	<div class="spec-menu-middle">
		<div class="prod-spec" id="prodSpecArea">
			<!-- 已选 -->
			<div class="spec-desc">
				<span class="part-note-msg">已选</span>
				<div id="specDetailInfo_lesson" class="base-txt">
				</div>
			</div>
			<div class="nature-container" id="natureCotainer">
				<div class="pro-color">
					<span class="part-note-msg"> 规格 </span>
					<p id="color">
					<?php  if($lesson['lesson_type']==1) { ?>
						<?php  if(is_array($spec_list)) { foreach($spec_list as $spec) { ?>
							<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['spec_day'];?>','<?php  echo $spec['spec_name'];?>')"><?php  echo $spec['spec_name'];?></a>
						<?php  } } ?>
					<?php  } else { ?>
						<?php  if(is_array($spec_list)) { foreach($spec_list as $spec) { ?>
							<?php  if($spec['spec_day']==-1) { ?>
							<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['spec_day'];?>','')">长期有效</a>
							<?php  } else { ?>
							<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['spec_day'];?>','')">有效期<?php  echo $spec['spec_day'];?>天</a>
							<?php  } ?>
						<?php  } } ?>
					<?php  } ?>
					</p>
					<input type="hidden" id="spec_id" value=""/>
				</div>
			</div>
			<?php  if(!empty($teacher_price)) { ?>
			<div class="nature-container" style="margin-top:10px;">
				<span class="part-note-msg" style="width:60px; line-height:26px;">您也可以</span>
				<div class="base-txt">
					<?php  if(!empty($teacher_price)) { ?>
						<a href="<?php  echo $this->createMobileUrl('teacher', array('teacherid'=>$lesson['teacherid']))?>" class="buy-teacher">开通讲师服务免费<?php echo $lesson_page['studyFont'] ? $lesson_page['studyFont'] : '学习';?></a>
					<?php  } ?>
				</div>
			</div>
			<?php  } ?>
		</div>
	</div>
	<div class="flick-menu-btn spec-menu-btn">
		<a class="directorder" id="buy_now"><?php echo $buynow_name ? $buynow_name : '立即购买';?></a>
	</div>
</div>
<!--课程规格end-->

<!--VIP列表start-->
<div class="spec-menu-content vip-menu-show max-width-640 hide">
	<div class="spec-menu-top bdr-b">
		<div class="spec-first-pic">
			<img id="spec_image" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/lesson_buy_vip.png" onerror="imgErr(this)">
		</div>
		<a class="rt-close-btn-wrap spec-menu-close">
			<p class="flick-menu-close"></p>
		</a>
		<div class="spec-price vip-price-color" style="display: block">
			<span class="yang-pic spec-yang-pic"></span>
			<span id="discount_price"></span>
			<span id="level_price" style="color:#333;font-weight:normal;margin-left:8px;text-decoration:line-through;"></span>
			<div id="discount_info" style="display:none;color:#f36f0f;font-weight:normal;margin-left:6px;margin-top:6px;font-size:.8rem;"><i class="fa fa-info-circle"></i> 限时开通即可享受<span id="discount_number"></span>折优惠</div>
		</div>
	</div>
	<div class="spec-menu-middle">
		<div class="prod-spec">
			<div class="spec-desc" style="margin-bottom: 5px;">
				<span class="part-note-msg" style="width:55px;">已选</span>
				<div id="specDetailInfo_vip" class="base-txt">
				</div>
			</div>
			<div class="nature-container">
				<div class="pro-color">
					<span class="part-note-msg" style="width:55px;">VIP等级</span>
					<p>
					<?php  if(is_array($lesson_vip_list)) { foreach($lesson_vip_list as $item) { ?>
						<a class="a-item vip_<?php  echo $item['id'];?>" href="javascript:;" onclick="selectVipSpec('<?php  echo $item['id'];?>','<?php  echo $item['level_price'];?>','<?php  echo $item['discount_price'];?>','<?php  echo $item['open_discount'];?>','<?php  echo $item['level_validity'];?>','<?php  echo $item['level_name'];?>-<?php  echo $item['level_validity'];?>天')"><?php  echo $item['level_name'];?>-<?php  echo $item['level_validity'];?>天</a>
					<?php  } } ?>
					</p>
					<input type="hidden" id="vip_id" value=""/>
				</div>
			</div>
			<?php  if($comsetting['vip_agreement']) { ?>
			<div class="weui-cells weui-cells_checkbox">
				<label class="weui-cell weui-cell_active weui-check__label" for="vip_agreement">
					<div class="weui-cell__hd" style="padding-right:10px;">
						<input type="checkbox" class="weui-check" id="vip_agreement" checked />
						<i class="weui-icon-checked vip_agreement_checked"></i>
					</div>
					<div class="weui-cell__bd agreement_tips">
						<p>我已阅读并同意<a href="javascript:;" id="view-vip-agreement">《VIP服务协议》</a></p>
					</div>
				</label>
			</div>
			<div class="privacy_agreement_notice-mask" id="vip-agreement-content" style="display:none;">
				<div class="privacy_agreement_notice">
					<div class="close">
						<img src="<?php echo MODULE_URL;?>static/mobile/default/images/btn-close.png?v=6" width="32" height="32">
					</div>
					<h3 class="notice-title">VIP服务协议</h3>
					<ul class="notice-body">
						<?php  echo htmlspecialchars_decode($comsetting['vip_agreement'])?><p><br></p>
					</ul>
				</div>
			</div>
			<?php  } ?>
		</div>
	</div>
	<div class="flick-menu-btn spec-menu-btn">
		<a class="btn-buy-vip" id="buy_vip"><?php echo $config['buy_vip_name'] ? $config['buy_vip_name'] : '开通VIP'?></a>
	</div>
</div>
<!--VIP列表end-->

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/writemsg', TEMPLATE_INCLUDEPATH)) : (include template($template.'/writemsg', TEMPLATE_INCLUDEPATH));?>

<ul class="d-buynow max-width-640">
	<li class="btn-qq <?php  if(!$lesson_vip_list || $show_isbuy || $setting['lesson_vip_status']<=1 || $lesson['lesson_type'] || ($setting['stock_config'] && !$lesson['stock'])) { ?>one-btn-qq<?php  } ?>">
		<a href="<?php echo $config['service_url'] ? $config['service_url'] : 'javascript:;';?>" id="<?php echo $config['service_url'] ? 'all-consult' : 'btn-qq';?>"><i class="ico ico-lessonqq"></i>咨询</a>
	</li>
	<li class="btn-collect <?php  if(!$lesson_vip_list || $show_isbuy || $setting['lesson_vip_status']<=1 || $lesson['lesson_type'] || ($setting['stock_config'] && !$lesson['stock'])) { ?>one-btn-collect<?php  } ?>" id="btn-collect">
		<a href="javascript:;" <?php  if(!empty($collect)) { ?>class="cur"<?php  } ?>><i class="ico ico-collect"></i>收藏</a>
	</li>

	<?php  if($lesson['lesson_type']==0) { ?>
		<?php  if($show_isbuy) { ?>
			<li class="btn-buy one-btn-buy"><a href="javascript:;" class="buy buy_now study"><p class="num"><em class="money"></em><?php echo $study_name ? $study_name : '开始学习';?></p></a></li>
		<?php  } else { ?>
			<?php  if($setting['stock_config'] && !$lesson['stock']) { ?>
				<li class="btn-buy one-btn-buy"><a href="javascript:;" class="buy buy_now gray" style="background-color:#7D7D7D;"><p class="num"><em class="money"></em>已售罄</p></a></li>
			<?php  } else { ?>
				<?php  if($setting['lesson_vip_status']==0) { ?>
					<!-- 仅显示立即购买 -->
					<li class="btn-buy one-btn-buy no-ios" <?php  if(empty($buynow_link) || $buynow_link=='#') { ?>id="buy-now"<?php  } ?>><a href="<?php  if(!empty($buynow_link) && $buynow_link!='#') { ?><?php  echo $buynow_link;?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
					<li class="btn-buy one-btn-buy is-ios" style="display:none;"><a href="javascript:;" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
				<?php  } else if($setting['lesson_vip_status']==1) { ?>
					<!-- 仅显示开通VIP -->
					<?php  if($lesson_vip_list) { ?>
						<li class="btn-buy one-btn-buy no-ios" <?php  if(empty($config['buy_vip_link'])) { ?>id="buy-vip"<?php  } ?>><a href="<?php  if(!empty($config['buy_vip_link'])) { ?><?php  echo $config['buy_vip_link'];?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now orange"><p class="num"><?php echo $config['buy_vip_name'] ? $config['buy_vip_name'] : '开通VIP'?></p></a></li>
						<li class="btn-buy one-btn-buy is-ios" style="display:none;"><a href="javascript:;" class="buy buy_now orange"><p class="num"><?php echo $config['buy_vip_name'] ? $config['buy_vip_name'] : '开通VIP'?></p></a></li>
					<?php  } else { ?>
						<li class="btn-buy one-btn-buy no-ios" <?php  if(empty($buynow_link) || $buynow_link=='#') { ?>id="buy-now"<?php  } ?>><a href="<?php  if(!empty($buynow_link) && $buynow_link!='#') { ?><?php  echo $buynow_link;?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
						<li class="btn-buy one-btn-buy is-ios" style="display:none;"><a href="javascript:;" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
					<?php  } ?>
				<?php  } else if($setting['lesson_vip_status']==2) { ?>
					<!-- 显示开通VIP和立即购买 -->
					<?php  if($lesson_vip_list) { ?>
						<li class="btn-buy two-btn-buy no-ios"  <?php  if(empty($config['buy_vip_link'])) { ?>id="buy-vip"<?php  } ?>><a href="<?php  if(!empty($config['buy_vip_link'])) { ?><?php  echo $config['buy_vip_link'];?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now orange"><p class="num"><?php echo $config['buy_vip_name'] ? $config['buy_vip_name'] : '开通VIP'?></p></a></li>
						<li class="btn-buy two-btn-buy is-ios" style="display:none;"><a href="javascript:;" class="buy buy_now orange"><p class="num"><?php echo $config['buy_vip_name'] ? $config['buy_vip_name'] : '开通VIP'?></p></a></li>
					<?php  } ?>
					<li class="btn-buy <?php  if($lesson_vip_list) { ?>two-btn-buy<?php  } else { ?>one-btn-buy<?php  } ?> no-ios" <?php  if(empty($buynow_link) || $buynow_link=='#') { ?>id="buy-now"<?php  } ?>><a href="<?php  if(!empty($buynow_link) && $buynow_link!='#') { ?><?php  echo $buynow_link;?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
					<li class="btn-buy <?php  if($lesson_vip_list) { ?>two-btn-buy<?php  } else { ?>one-btn-buy<?php  } ?> is-ios" style="display:none;"><a href="javascript:;" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
				<?php  } ?>
			<?php  } ?>
		<?php  } ?>
	<?php  } ?>

	<?php  if($lesson['lesson_type']==1) { ?>
		<?php  if($show_qrcode) { ?>
			<li class="btn-buy one-btn-buy"><a href="<?php  echo $this->createMobileUrl('orderdetail', array('orderid'=>$apply_order['id']))?>" class="buy buy_now blue"><p class="num"><em class="money"></em><?php echo $study_name ? $study_name : '查看二维码';?></p></a></li>
		<?php  } else { ?>
			<?php  if($setting['stock_config']==1 && $lesson['stock']==0) { ?>
				<li class="btn-buy one-btn-buy"><a href="javascript:;" class="buy buy_now gray" style="background-color:#7D7D7D;"><p class="num"><em class="money"></em>已售罄</p></a></li>
			<?php  } else if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])) { ?>
				<li class="btn-buy one-btn-buy"><a href="javascript:;" class="buy buy_now gray" style="background-color:#7D7D7D;"><p class="num"><em class="money"></em>已结束</p></a></li>
			<?php  } else { ?>
				<li class="btn-buy one-btn-buy" <?php  if(empty($buynow_link) || $buynow_link=='#') { ?>id="buy-now"<?php  } ?>><a href="<?php  if(!empty($buynow_link) && $buynow_link!='#') { ?><?php  echo $buynow_link;?><?php  } else { ?>javascript:;<?php  } ?>" class="buy buy_now red"><p class="num"><?php echo $buynow_name ? $buynow_name : '立即购买';?></p></a></li>
			<?php  } ?>
		<?php  } ?>
	<?php  } ?>
</ul>
<?php  if($isfollow['follow_lesson'] && $member['follow']==0 && $userAgent) { ?>
<div class="force-contact-main max-width-640 follow_qrcode">
	<div class="force-contact-content_new">
		<p class="force-contact-tips"><?php echo $setting['lesson_follow_title'] ? $setting['lesson_follow_title'] : '长按指纹识别二维码, 关注公众号';?></p>
		<p class="force-contact-desc"><?php echo $setting['lesson_follow_desc'] ? $setting['lesson_follow_desc'] : '关注后继续学习, 可接收最新课程通知';?></p>
		<img class="receive-red-packet-contact-touch" src="<?php  echo $dirpath;?>lesson_<?php  echo $id;?>.jpg">
	</div>
	<?php  if($isfollow['follow_lesson_close']) { ?>
	<div class="follow_lesson_close">
		<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/index-coupon-close.png">
	</div>
	<?php  } ?>
</div>
<?php  } ?>

<div class="aui-dialog follow-wechat">
	<div class="listInformation background_default" style="padding: 18px 0;background: rgb(245, 245, 245);">
		<div class="listInformationWord">
			<p class="t6 color-ff5b35" style="width:100%;text-align:center;"><?php echo $common['follow_page']['first_title'] ? $common['follow_page']['first_title'] : '您可关注公众号方便下次学习'?></p>
		</div>
	</div>
	<div class="textCenter" style="padding-top: 0.3rem;">
		<p style="padding:10px 0;"><img src="<?php  echo $_W['attachurl'];?><?php  echo $setting['qrcode'];?>" class="erweima"></p>
		<p class="c3 p-b-10"><?php echo $common['follow_page']['app_tip'] ? $common['follow_page']['app_tip'] : '长按识别关注公众号，方便下次进入学习'?></p>
	</div>
	<div class="listInformationBtn t2 c3 flagDiv"><span onclick="closeTip()" class="listInformationBtnChild">关闭</span></div>
</div>

<div class="aui-dialog lesson-qun" <?php  if($show_service) { ?>style="display:block;"<?php  } ?>>
	<div class="listInformation background_default " style="background: rgb(245, 245, 245);"><span class="listInformationImg listInformationImg2"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['avatar'];?>"></span>
		<div class="listInformationWord">
			<p class="t2 c8" style="width: 100%; text-align: left;">HI，我是 <?php  echo $now_service['nickname'];?></p>
			<p class="t1 c2" style="width: 100%; text-align: left;"><?php echo $common['other_page']['invite_join'] ? $common['other_page']['invite_join'] : '邀请你加入用户粉丝群'?></p>
		</div>
	</div>
	<div class="textCenter" style="padding-top: 0.3rem;">
		<p style="padding-top: 0.3rem;"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['qrcode'];?>" class="erweima"></p>
		<p class="c3 p-b-10">微信里长按识别二维码</p>
	</div>
	<div class="listInformationBtn t2 c3 flagDiv"><span onclick="closeTip()" class="listInformationBtnChild">关闭</span></div>
</div>
<div class="aui-mask" <?php  if($show_service) { ?>style="display:block;"<?php  } ?>></div>


<script type="text/javascript">
	var wxapp_audition_end = "";
	var isMiniProgram = false;
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		var miniprogram_environment = false;
		wx.miniProgram.getEnv(function(res) {
			if(res.miniprogram) {
				miniprogram_environment = true;
			}
		})
		if((window.__wxjs_environment === 'miniprogram' || miniprogram_environment)) {
			wxapp_audition_end = "试听已结束";
			isMiniProgram = true;
			wx.miniProgram.getEnv(function(res) {
				wx.miniProgram.postMessage({ 
					data: {
						'title': "<?php  echo $sharelesson['title'];?>",
						'images': "<?php  echo $sharelesson['images'];?>",
					}
				})
			});
			$("#qq-consult").attr("href","javascript:;");
			$("#qqgroup-consult").attr("href","javascript:;");

			<?php  if(!strstr($config['service_url'], $_W['siteroot'])){ ?>
				$("#all-consult").attr("href","javascript:;");
			<?php  } ?>
			$(".follow_qrcode").hide();

			<?php  if($systemType=='ios'){ ?>
				$('.no-ios').hide();
				$('.is-ios').show();
				$('.is-ios a p').html('不支持');
			<?php  } ?>

			$(".wxapp-download-tip").click(function(){
				showSingleDialog("小程序环境暂不支持下载");
			})
		}
	});

	window.config = {
		  attachurl: "<?php  echo $_W['attachurl'];?>",
		 systemType: "<?php  echo $systemType;?>",
		  userAgent: "<?php  echo $userAgent;?>",
 wxapp_audition_end: wxapp_audition_end,
		     common: <?php  echo json_encode($common)?>,
			    uid: <?php  echo intval($uid)?>,
			 member: <?php  echo json_encode($member)?>,
			  plays: <?php  echo intval($plays)?>,
		  sectionid: <?php  echo intval($sectionid)?>,
	 next_sectionid: <?php  echo intval($next_sectionid)?>,
			 lesson: <?php  echo json_encode($lesson)?>,
		lesson_page: <?php  echo json_encode($lesson_page)?>,
	  lesson_config: <?php  echo json_encode($lesson_config)?>,
			section: <?php  echo json_encode($section)?>,
		  qcloudvod: <?php  echo json_encode($qcloudvod)?>,
       qcloudVodRes: <?php  echo json_encode($qcloudVodRes)?>,
		   playAuth: "<?php  echo $playAuth;?>",
		m3u8_format: "<?php  echo $m3u8_format;?>",
		      audio: "<?php  echo $audio;?>",
		  drag_play: <?php  echo intval($drag_play)?>,
	  prev_playtime: <?php  echo intval($prev_record['playtime'])?>,
		drag_imgurl: "<?php echo MODULE_URL;?>static/public/verification/images/",
		buynow_info: <?php  echo json_encode($buynow_info)?>,
   appoint_validity: <?php  echo intval(strtotime($buynow_info['appoint_validity']))?>,
		    nowtime: <?php  echo time()?>,
   discount_endtime: "<?php  echo $discount_endtime;?>",
         show_isbuy: <?php  echo intval($show_isbuy)?>,
repeat_record_lesson: <?php  echo intval($setting['repeat_record_lesson'])?>,
	setting_ios_pay: <?php  echo intval($setting['ios_pay'])?>,
   setting_mustinfo: <?php  echo intval($setting['mustinfo'])?>,
		 hissection: <?php  echo json_encode($hissection)?>,
		   writemsg: <?php  echo intval($writemsg)?>,
		  spec_list: <?php  echo json_encode($spec_list)?>,
    virtual_buyinfo: <?php  echo json_encode($virtual_buyinfo)?>,
	    sharelesson: <?php  echo json_encode($sharelesson)?>,
	      recordurl: "<?php  echo $this->createMobileUrl('record', array('lessonid'=>$_GPC['id'],'sectionid'=>$_GPC['sectionid'],'playtoken'=>random(8)));?>",
		  lessonurl: "<?php  echo $this->createMobileUrl('lesson', array('id'=>$id));?>",
		evaluateurl: "<?php  echo $this->createMobileUrl('getevaluate', array('id'=>$id));?>",
		 sectionurl: "<?php  echo $this->createMobileUrl('sectionstudystatus')?>",
		 confirmurl: "<?php  echo $this->createMobileUrl('confirm', array('id'=>$lesson['id']));?>",
		  buyvipurl: "<?php  echo $this->createMobileUrl('vip', array('op'=>'buyvip'));?>",
		 collecturl: "<?php  echo $this->createMobileUrl('updatecollect', array('ctype'=>'lesson'));?>",
		   studyurl: "<?php echo $study_link ? $study_link : $this->createMobileUrl('lesson', array('id'=>$lesson['id'],'sectionid'=>$first_section['id']));?>",
     sharecouponurl: "<?php  echo $this->createMobileUrl('sharecoupon');?>",
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/lesson.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>
