<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/css/lesson.css?v=<?php  echo $versions;?>">
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/js/jquery.qrcode.min.js"></script>

<div class="w-main m-auto">
	<div class="w-main fs-14 ftc-7a7a7a line-h45 m-t-10 m-b-10">
		当前位置：<a href="/<?php  echo $uniacid;?>/index.html" class="more ftc-414141">首页</a> &gt; <a href="/<?php  echo $uniacid;?>/search.html" class="more ftc-414141">全部课程</a> &gt; 《<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $lesson['id'];?>" class="more ftc-414141"><?php  echo $lesson['bookname'];?></a>》<?php  if($section['title']) { ?> &gt; <?php  echo $section['title'];?><?php  } ?>
	</div>

	<?php  if(!$sectionid) { ?>
	<!-- 封面图 -->
	<div class="course-cover">
		<div class="img-left-wrap">
			<img class="img-left" src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['images'];?>" alt="<?php  echo $lesson['bookname'];?>">

			<?php  if($lesson['lesson_type']!=1 || ($lesson['lesson_type']==1 && $lesson['appoint_dir'])) { ?>
			<div class="banner-cover" <?php  if($free_test || $show_isbuy) { ?>style="background-color: rgba(21,21,27,.5);"<?php  } ?>>
				<div class="banner-cover-live">
					<?php  if($show_isbuy) { ?>
						<a class="banner-cover-play" href="javascript:;" data-type="1"> <i class="iconfont icon-play"></i></a>
						<div><span class="banner-cover-live-tips">开始学习</span></div>
					<?php  } else { ?>
						<?php  if($free_section) { ?>
						<a class="banner-cover-play" href="javascript:;" data-type="0"> <i class="iconfont icon-play"></i></a>
						<div><span class="banner-cover-live-tips">免费试听</span></div>
						<?php  } ?>
					<?php  } ?>
				</div>
			</div>
			<?php  } ?>
		</div>

		<div class="text-right">
			<h1 class="book-title"><span class="title-main"><?php  if($lesson['section_status']==0) { ?><i class="section-status-btn">已完结</i> <?php  } ?><?php  echo $lesson['bookname'];?></span></h1>
			<div class="course-info">
				<?php  if($setting['show_study_number']) { ?>
				<span class="line-item">
					<?php  echo $lesson['buyTotal'];?>
					<?php  if($lesson['lesson_type'] == 0) { ?>
						<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
					<?php  } else if($lesson['lesson_type'] == 1) { ?>
						<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
					<?php  } ?>
				</span>
				<i class="icon-sep"></i>
				<?php  } ?>
				<?php  if($evaluate_score['score']) { ?>
				<span class="line-item"><?php  echo $evaluate_score['score'];?>%好评</span>
				<i class="icon-sep"></i>
				<?php  } ?>
				<div class="line-item item-share js_share">
					<i class="iconfont icon-share vertical-minus-1"></i> 分享
					<div class="hover-tips tips-share js_share_panel">
						<ul >
							<li class="share-qq" data-type="qq"></li>
							<li class="share-qzone" data-type="qzone"></li>
							<li class="share-wechat" data-type="wechat"></li>
							<li class="share-sina" data-type="sina"></li>
						</ul>
					</div>
				</div>
				<span class="line-item btn-favorite">
					<i class="iconfont <?php echo $collect ? 'icon-heart-o' : 'icon-heart';?> vertical-minus-1"></i>
					<span><?php echo $collect ? '已收藏' : '收藏';?></span>
				</span>
				<?php  if($poster_show) { ?>
				<span class="line-item sale-btn">
					<i class="sale-icon"></i>
					<span class="sale-content">
						<?php  if($commisson1_amount && $comsetting['is_sale']) { ?>
							<?php echo $lesson_page['shareIncome'] ? $lesson_page['shareIncome'] : '分享赚 ¥'.$commisson1_amount.'元';?>
						<?php  } else { ?>
							<?php echo $lesson_page['inviteCard'] ? $lesson_page['inviteCard'] : '邀请卡';?>
						<?php  } ?>
					</span>
				</span>
				<?php  } ?>
			</div>
			<div class="clear"></div>

			<?php  if($poster_show) { ?>
			<!-- 课程分销 -->
			<div class="overlay sale-modal-shade"></div>
			<div class="modal sale-modal" style="width: 832px; margin-top:-199px; margin-left:-416px;">
				<div class="modal-hd">
					<h3 class="hd-tt js-modal-title">课程分享信息</h3><a href="javascript:void(0);" class="btn-close" title="关闭">×</a>
				</div>
				<div class="modal-bd modal-bd--padding">
					<div class="sale-url-wrapper">
						<h3>
							<?php  if($commisson1_amount && $comsetting['is_sale']) { ?>
								邀请好友成为您的下级，每单最多收益<span class="hd-highlight"> ¥<?php  echo $commisson1_amount;?></span>
							<?php  } else { ?>
								邀请好友一起来学习
							<?php  } ?>
						</h3>
						<p class="sale-desc"></p>
						<div class="url-card-wrapper">
							<div class="url-card-info">
								<h3 class="url-card-tt">方式一：复制课程链接</h3>
								<input type="text" class="url-string" id="lesson-url" value="<?php  echo $share['link'];?>">
								<button class="btn-copy btn-m btn-default js-copy-url">复制</button>
								<p class="js-copy-tip gray-wordings"></p>
							</div>
							<div class="url-card-info url-card-info-qrcode">
								<h3 class="url-card-tt">方式二：分享课程海报</h3>
								<div class="url-qrcode" id="lesson-qrcode">
									
								</div>
								<p class="qrcode-tip">微信扫描二维码，长按课程海报后“发送给朋友”</p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-ft">
					<a href="javascript:void(0);" title="关闭" class="js-modal-yes btn-default">关闭</a>
				</div>
			</div>
			<script type="text/javascript">
				jQuery('#lesson-qrcode').qrcode({width: 110, height: 110, text: "<?php  echo $lesson_qrcode_url;?>"});
				$('.sale-btn').click(function(){
					if(!login()){
						return false;
					}
					$('.sale-modal-shade').show();
					$('.sale-modal').show();
				});
				$('.btn-close,.js-modal-yes').click(function(){
					$('.sale-modal-shade').hide();
					$('.sale-modal').hide();
				});
				$('.js-copy-url').click(function(){
					var input = document.getElementById("lesson-url");
					input.select();
					document.execCommand("copy");
					alert("复制成功");
				})
			</script>
			<?php  } ?>

			<?php  if($lesson['lesson_type']==1 && $buynow_info['appoint_validity'] && time() < strtotime($buynow_info['appoint_validity'])) { ?>
			<!-- 报名活动截至 S -->
			<div class="activity-banner lesson_appoint">
				<div class="activity-message">
					<?php echo $lesson_page['appoint_validity'] ? $lesson_page['appoint_validity'] : '距活动结束仅剩'?> <i class="time_days" id="time_d"></i>
							<span id="time_h">00</span>:
							<span id="time_m">00</span>:
							<span id="time_s">00</span>
				</div>
			</div>
			<script type="text/javascript">
				setTimeout("show_time()",1000);
				var time_d = document.getElementById("time_d");
				var time_h = document.getElementById("time_h");
				var time_m = document.getElementById("time_m");
				var time_s = document.getElementById("time_s");
				 
				var time_end = new Date("<?php  echo date('Y/m/d H:i:s', strtotime($buynow_info['appoint_validity']))?>"); // 设定结束时间
				time_end = time_end.getTime();
				 
				function show_time(){
					var time_now = new Date(); // 获取当前时间
					time_now = time_now.getTime();
					var time_distance = time_end - time_now; // 结束时间减去当前时间
					var int_day, int_hour, int_minute, int_second;
					if(time_distance >= 0){
						// 天时分秒换算
						int_day = Math.floor(time_distance/86400000)
						time_distance -= int_day * 86400000;
						int_hour = Math.floor(time_distance/3600000)
						time_distance -= int_hour * 3600000;
						int_minute = Math.floor(time_distance/60000)
						time_distance -= int_minute * 60000;
						int_second = Math.floor(time_distance/1000)

						// 时分秒为单数时、前面加零站位
						if(int_hour < 10)
						int_hour = "0" + int_hour;
						if(int_minute < 10)
						int_minute = "0" + int_minute;
						if(int_second < 10)
						int_second = "0" + int_second;

						// 显示时间
						time_d.innerHTML = int_day + '天';
						time_h.innerHTML = int_hour;
						time_m.innerHTML = int_minute;
						time_s.innerHTML = int_second;

						setTimeout("show_time()",1000);
					}else{
						window.location.reload();
					}
					if(!int_day){
						$('.time_days').hide();
					}
				}
			</script>
			<!-- 报名活动截至 E -->
			<?php  } else if($discount_lesson) { ?>
			<!-- 限时折扣 -->
			<div class="activity-banner">
				<div class="activity-type fl">
					<i class="iconfont icon-clock clock"></i> <strong>限时抢购</strong>
				</div>
				<div class="activity-message">
					距离结束 <i class="time_days" id="time_d"></i>
							<span id="time_h">00</span>:
							<span id="time_m">00</span>:
							<span id="time_s">00</span>
				</div>
			</div>
			<script type="text/javascript">
				setTimeout("show_time()",1000);
				var time_d = document.getElementById("time_d");
				var time_h = document.getElementById("time_h");
				var time_m = document.getElementById("time_m");
				var time_s = document.getElementById("time_s");
				 
				var time_end = new Date("<?php  echo $discount_endtime; ?>"); // 设定结束时间
				time_end = time_end.getTime();
				 
				function show_time(){
					var time_now = new Date(); // 获取当前时间
					time_now = time_now.getTime();
					var time_distance = time_end - time_now; // 结束时间减去当前时间
					var int_day, int_hour, int_minute, int_second;
					if(time_distance >= 0){
						// 天时分秒换算
						int_day = Math.floor(time_distance/86400000)
						time_distance -= int_day * 86400000;
						int_hour = Math.floor(time_distance/3600000)
						time_distance -= int_hour * 3600000;
						int_minute = Math.floor(time_distance/60000)
						time_distance -= int_minute * 60000;
						int_second = Math.floor(time_distance/1000)

						// 时分秒为单数时、前面加零站位
						if(int_hour < 10)
						int_hour = "0" + int_hour;
						if(int_minute < 10)
						int_minute = "0" + int_minute;
						if(int_second < 10)
						int_second = "0" + int_second;

						// 显示时间
						time_d.innerHTML = int_day + '天';
						time_h.innerHTML = int_hour;
						time_m.innerHTML = int_minute;
						time_s.innerHTML = int_second;

						setTimeout("show_time()",1000);
					}else{
						time_d.innerHTML = time_d.innerHTML;
						time_h.innerHTML = time_h.innerHTML;
						time_m.innerHTML = time_m.innerHTML;
						time_s.innerHTML = time_s.innerHTML;
					}
					if(!int_day){
						$('.time_days').hide();
					}
				}
			</script>
			<?php  } ?>

			<!-- 课程价格 -->
			<?php  if($show_specprice) { ?>
			<div class="course-price">
				<div class="summary-price-wrap">
					<div class="summary-price">
						<div class="dt"><?php echo $discount_lesson ? '折 扣 价' : '价 格';?></div>
						<div class="dd">
							<span class="p-price">
								<span>￥</span>
								<span class="price" id="spec_price">
									<?php  if($lesson['price']>0) { ?>
										<?php  echo $spec_list[0]['spec_price'];?>
										<?php  if(count($spec_list)>1) { ?>
											~ <?php  echo $spec_list[count($spec_list)-1]['spec_price']?>
										<?php  } ?>
									<?php  } else { ?>
										免费
									<?php  } ?>
								</span>
								<?php  if($discount_lesson && $lesson['price']>0) { ?>
								<span class="market-price" id="market_price">￥<?php  echo $spec_list[0]['market_price'];?>~<?php  echo $spec_list[count($spec_list)-1]['market_price']?></span>
								<?php  } ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php  } ?>

			<?php  if($spec_list && $show_specprice) { ?>			
			<!-- 课程规格 -->
			<div class="course-class">
				<div>
					<div class="class-content">
						<h2 class="hidden-clip">课程规格</h2>
						<div class="class-date">
							<?php  if($lesson['lesson_type']==1) { ?>
								<?php  if(is_array($spec_list)) { foreach($spec_list as $spec) { ?>
									<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['market_price'];?>','<?php  echo $spec['spec_day'];?>','<?php  echo $spec['spec_name'];?>')"><?php  echo $spec['spec_name'];?></a>
								<?php  } } ?>
							<?php  } else { ?>
								<?php  if(is_array($spec_list)) { foreach($spec_list as $spec) { ?>
									<?php  if($spec['spec_day']==-1) { ?>
									<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['market_price'];?>','<?php  echo $spec['spec_day'];?>','')">长期有效</a>
									<?php  } else { ?>
									<a class="a-item spec_<?php  echo $spec['spec_id'];?>" href="javascript:;" onclick="updateColorSizeSpec('<?php  echo $spec['spec_id'];?>','<?php  echo $spec['spec_price'];?>','<?php  echo $spec['market_price'];?>','<?php  echo $spec['spec_day'];?>','')">有效期<?php  echo $spec['spec_day'];?>天</a>
									<?php  } ?>
								<?php  } } ?>
							<?php  } ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<?php  } ?>

			<div class="oper-bar">
				<?php  if($lesson['lesson_type']==0) { ?>
					<?php  if($show_isbuy) { ?>
						<a href="javascript:;" class="btn-common btn-study"><?php echo $study_name ? $study_name : '开始学习';?></a>
					<?php  } else { ?>
						<?php  if($setting['stock_config']==1 && $lesson['stock']==0) { ?>
							<a href="javascript:;" class="btn-sellout">已售罄</a>
						<?php  } else { ?>
							<?php  if($setting['lesson_vip_status']!=1 || ($setting['lesson_vip_status']==1 && !$lesson_vip_list)) { ?>
								<a href="javascript:;" class="btn-buylesson"><?php echo $buynow_name ? $buynow_name : '立即购买';?></a>
							<?php  } ?>
							<?php  if($setting['lesson_vip_status']!=0 && $lesson_vip_list) { ?>
								<a href="javascript:;" class="btn-buyvip">
									开通VIP<i class="btn-buyvip-tip">开通VIP即可免费学习该课程和其他指定课程</i>
								</a>
							<?php  } ?>
						<?php  } ?>
					<?php  } ?>
				<?php  } ?>

				<?php  if($lesson['lesson_type']==1) { ?>
					<?php  if($show_qrcode) { ?>
						<a href="/<?php  echo $uniacid;?>/orderDetails.html?orderid=<?php  echo $apply_order['id'];?>" class="btn-common">查看二维码</a>
					<?php  } else { ?>
						<?php  if($setting['stock_config']==1 && $lesson['stock']==0) { ?>
							<a href="javascript:;" class="btn-sellout">已售罄</a>
						<?php  } else if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])) { ?>
							<a href="javascript:;" class="btn-sellout">已结束</a>
						<?php  } else { ?>
							<a href="javascript:;" class="btn-buylesson"><?php echo $buynow_name ? $buynow_name : '立即购买';?></a>
						<?php  } ?>
					<?php  } ?>
				<?php  } ?>

				<?php  if($teacher_price && !$show_isbuy) { ?>
					<a href="javascript:;" class="btn-buyteacher">
						购买讲师<i class="btn-buyteacher-tip">购买讲师后，可免费学习该讲师所有课程</i>
					</a>
				<?php  } ?>
				<input type="hidden" name="spec_id" id="spec_id" value="" />
			</div>
		</div>

		<div class="clear"></div>
	</div>
	<script type="text/javascript">
		//单规格课程自动选中    
		<?php  if(count($spec_list)==1){ ?>
			var spec_id = "<?php  echo $spec_list[0]['spec_id']; ?>";
			var spec_price = "<?php  echo $spec_list[0]['spec_price']; ?>";
			var market_price = "<?php  echo $spec_list[0]['market_price']; ?>";
			var spec_day = "<?php  echo $spec_list[0]['spec_day']; ?>";
			var spec_name = "<?php  echo $spec_list[0]['spec_name']; ?>";
			updateColorSizeSpec(spec_id, spec_price, market_price, spec_day, spec_name);
		<?php  } ?>

		//选择课程规格
		function updateColorSizeSpec(spec_id, spec_price, market_price, spec_day, spec_name){
			$(".a-item").removeClass("selected");
			$(".spec_"+spec_id).addClass("selected");
			$("#spec_id").val(spec_id);
			document.getElementById("spec_price").innerHTML = spec_price;
			<?php  if($discount_lesson) { ?>
			document.getElementById("market_price").innerHTML = '￥' + market_price;
			<?php  } ?>
		}

		//开始学习
		$(".banner-cover-play").click(function(){
			var check_mustinfo = checkMustInfo();
			var type = $(this).data('type');
			
			if(check_mustinfo){
				if(type == '1'){
					window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $lesson['id'];?>&sectionid=<?php  echo $first_section['id'];?>";
				}else{
					window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>&sectionid=<?php  echo $free_section['id'];?>";
				}
			}
		})

		//立即购买
		$('.btn-buylesson').click(function(){
			if(!login()){
				return false;
			}

			var check_mustinfo = checkMustInfo();
			if(check_mustinfo){
				var spec_id = $("#spec_id").val();
				if(!spec_id){
					swal("系统提示", "请选择课程规格");
					return false;
				}
				window.location.href = "/<?php  echo $uniacid;?>/confirm.html?id=<?php  echo $lesson['id'];?>&spec_id="+spec_id;
			}
		});

		//开通VIP
		$('.btn-buyvip').click(function(){
			if(!login()){
				return false;
			}

			var check_mustinfo = checkMustInfo();
			if(check_mustinfo){
				$("#buyvip-overlay").show();
				$(".buyvip-wrapper").show();
			}
		});

		//购买讲师服务
		$('.btn-buyteacher').click(function(){
			var check_mustinfo = checkMustInfo();
			if(check_mustinfo){
				window.location.href = "/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $lesson['teacherid'];?>";
			}
		});
		$('.btn-buyteacher').hover(function(){
			$('.btn-buyteacher-tip').show();
		},function(){
			$('.btn-buyteacher-tip').hide();
		});
	</script>
	<?php  } else { ?>
	<!-- 章节播放视频 -->
	<section class="section-course-video">
		<div class="inner-center clearfix">
			<div class="videotext-course">
				<div class="section-study">
					<div class="study-video-wrapper">
						<input type="hidden" id="realPlayTime" value="0" />
						<?php  if($section['password'] && !$_SESSION[$uniacid.'_'.$id.'_'.$sectionid]) { ?>
							<!-- 密码验证 -->
							<div class="password-wrap">
								<span class="enter-password"><?php echo $lesson_page['visit_password'] ? $lesson_page['visit_password'] : '请输入访问密码';?></span>
								<form class="form" action="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>&sectionid=<?php  echo $sectionid;?>" method="post">
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
									<video id="player-container-id" width="100%" height="490px" preload="auto" x5-playsinline playsinline webkit-playsinline></video>
									<script type="text/javascript">
										var player = TCPlayer('player-container-id', {
											fileID: "<?php  echo $section['videourl']; ?>",
											appID : "<?php  echo $qcloudvod['appid']; ?>",
										<?php  if(empty($qcloudvod['player_name'])){ ?>
											t	  : "<?php  echo $qcloudVodRes['t']; ?>",
											us	  : "<?php  echo $qcloudVodRes['us']; ?>",
											sign  : "<?php  echo $qcloudVodRes['sign']; ?>",
											exper : "<?php  echo $exper; ?>",
										<?php  }else{ ?>
											psign  : "<?php  echo $qcloudVodRes; ?>",
										<?php  } ?>
											autoplay: true,
											poster: "<?php  echo $_W['attachurl'];?><?php echo $section['images'] ? $section['images'] : $lesson['images']?>",
											plugins:{
												ContinuePlay: {
												   text:'上次播放至 ',
												   btnText: '恢复播放'
												},
												ContextMenu: {
													mirror: true
												},
											  }
										});

										var playing     = false;  /* 播放状态 */
										var submiting   = false;  /* 记录播放进度提交状态 */
										var r_submiting = false;  /* 记录播放时间提交状态 */
										var space_time  = 60;     /* 提交间隔(秒) */
										var uid	= "<?php  echo $uid;?>";
										var realPlayTime = document.getElementById("realPlayTime").value;
										var recordurl = "/<?php  echo $uniacid;?>/record.html?lessonid=<?php  echo $_GPC['id'];?>&sectionid=<?php  echo $_GPC['sectionid'];?>&playtoken=<?php  echo random(8)?>";

										function play(){
											playing = true;
										}
										function pause(){
											playing = false;
										}
										function timeUpdate(){
											//间隔指定时间记录一次播放时间
											var currentTime = Math.floor(player.currentTime());
											var duration = Math.floor(player.duration());
											if(currentTime>0 && currentTime%space_time==0 && !submiting && uid){
												submiting = true;
												$.get(recordurl, {duration:duration,currentTime:currentTime}, function (data){
													submiting = false;
												})
											}

											<?php  if($section['is_free']==1 && $section['test_time']>0){ ?>
											var test_time = <?php  echo $section['test_time'];?>;
											var plays = <?php echo $plays ? 1: 0;?>;
											if(currentTime >= test_time && !plays && playing){
												player.pause();
												playing = false;
												alert("<?php echo $lesson_page['auditionTip'] ? $lesson_page['auditionTip'] : '试听已结束，观看完整版本请购买'; ?>");
												location.reload();
											}
											<?php  } ?>
										}
										function ended(){
											var currentTime = Math.floor(player.duration());
											if(uid){
												if(!submiting){
													submiting = true;
													$.get(recordurl, {duration:currentTime,currentTime:currentTime}, function (data){
														submiting = false;
													})
												}

												if(!r_submiting){
													r_submiting = true;
													$.get(recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
														r_submiting = false;
													})
												}
											}
											
											var next_sectionid = "<?php  echo $next_sectionid;?>";
											if(next_sectionid){
												window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>&sectionid=<?php  echo $next_sectionid;?>";
											}
										}

										player.on('play', play);
										player.on('pause', pause);
										player.on('timeupdate', timeUpdate);
										player.on('ended', ended);

										setInterval(function(){
											if(playing){
												realPlayTime = parseInt(realPlayTime) + parseInt(1);
												$("#realPlayTime").val(realPlayTime);
											}
											if(realPlayTime!=0 && realPlayTime%space_time==0 && !r_submiting && uid){
												r_submiting = true;
												$.get(recordurl+'&op=realPlay', {realPlayTime:space_time}, function (data){
													r_submiting = false;
												})
											}
										},1000);

										$(document).ready(function (){
											player.play();
										});
									</script>

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

									<script>
										var player = new Aliplayer({
											id: "J_prismPlayer",
											width:"100%",
											height:"100%",
											autoplay: true,
											playsinline: true,
											preload: true,
											controlBarVisibility:"hover",
											useH5Prism: true,
											isLive: <?php  echo $section['is_live'];?>,
											showBarTime:"3000",
											useFlashPrism: false,
											x5_type:"",
											x5_video_position:"top",
											x5_fullscreen: true,
											vodRetry: 10,
											cover: "<?php  echo $_W['attachurl'];?><?php echo $section['images'] ? $section['images'] : $lesson['images']?>",
										<?php  if($section['savetype']==4){ ?>
											//阿里云点播
											vid: "<?php  echo $section['videourl']; ?>",
											playauth: "<?php  echo $playAuth; ?>",
										<?php  }else{ ?>
											//其他存储
											source: "<?php  echo $section['videourl'];?>",
										<?php  } ?>
											<?php  if($m3u8_format){ ?>
												format: "m3u8",
											<?php  } ?>
											components:[
											<?php  if(!$setting['repeat_record_lesson']){ ?>
												{
												  name: 'RateComponent',
												  type: AliPlayerComponent.RateComponent
												},
											<?php  } ?>
												{
													name: 'QualityComponent',
													type: AliPlayerComponent.QualityComponent
												},
												{
													name: 'RotateMirrorComponent',
													type: AliPlayerComponent.RotateMirrorComponent
												},
											<?php  if($setting_pc['video_watermark'] && $uid){ ?>
												{
													name: 'BulletScreenComponent',
													type: AliPlayerComponent.BulletScreenComponent,
													args: ["<?php  echo $uid;?>,<?php  echo $mobile;?>,<?php  echo $nickname;?>", {fontSize: '18px', color: '#eeeeee'}, 'random']
												},
											<?php  } ?>
											<?php  if(!$section['is_live']){ ?>
												{
													name: 'MemoryPlayComponent',
													type: AliPlayerComponent.MemoryPlayComponent,
												},
											<?php  } ?>
											],
											skinLayout:[
												{name: "bigPlayButton", align: "blabs", x: 30, y: 80},
												{name: "H5Loading", align: "cc"},
												{name: "errorDisplay", align: "tlabs", x: 0, y: 0},
												{name: "infoDisplay"},
												{name:"tooltip", align:"blabs",x: 0, y: 56},
												{name: "thumbnail"},
												{
												  name: "controlBar", align: "blabs", x: 0, y: 0,
												  children: [
													{name: "progress", align: "blabs", x: 0, y: 44},
													{name: "playButton", align: "tl", x: 15, y: 12},
													{name: "timeDisplay", align: "tl", x: 10, y: 7},
													{name: "fullScreenButton", align: "tr", x: 10, y: 12},
													{name: "volume",align: "tr",x: 5,y: 10}
												  ]
												}
											],
											<?php  if($audio){ ?>
												mediaType: "audio",
											<?php  } ?>
										},function (player) {
											player._switchLevel = 0;
											player.on('sourceloaded', function(params) {
												var paramData = params.paramData;
												var desc = paramData.desc;
												var definition = paramData.definition;
												player.getComponent('QualityComponent').setCurrentQuality(desc, definition);
											});
										});

										var playing     = false;  /* 播放状态 */
										var submiting   = false;  /* 记录播放进度提交状态 */
										var r_submiting = false;  /* 记录播放时间提交状态 */
										var space_time  = 60;     /* 提交间隔(秒) */
										var uid	= "<?php  echo $uid;?>";
										var realPlayTime = document.getElementById("realPlayTime").value;
										var recordurl = "/<?php  echo $uniacid;?>/record.html?lessonid=<?php  echo $_GPC['id'];?>&sectionid=<?php  echo $_GPC['sectionid'];?>&playtoken=<?php  echo random(8)?>";
										
										var playVideo = function(){
											playing = true;
										}
										var pauseVideo = function(){
											playing = false;
										}
										var timeUpdate = function(){
											//间隔指定时间记录一次播放时间
											var currentTime = Math.floor(player.getCurrentTime());
											var duration = Math.floor(player.getDuration());
											if(currentTime>0 && currentTime%space_time==0 && !submiting && uid){
												submiting = true;
												$.get(recordurl, {duration:duration,currentTime:currentTime}, function (data){
													submiting = false;
												})
											}

											<?php  if($section['is_free']==1 && $section['test_time']>0){ ?>
											var test_time = <?php  echo $section['test_time'];?>;
											var plays = <?php echo $plays ? 1: 0;?>;
											if(currentTime >= test_time && !plays && playing){
												player.pause();
												playing = false;
												alert("<?php echo $lesson_page['auditionTip'] ? $lesson_page['auditionTip'] : '试听已结束，观看完整版本请购买'; ?>");
												location.reload();
											}
											<?php  } ?>
										}
										var ended = function(e){
											var currentTime = Math.floor(player.getDuration());
											if(uid){
												if(!submiting){
													submiting = true;
													$.get(recordurl, {duration:currentTime,currentTime:currentTime}, function (data){
														submiting = false;
													})
												}

												if(!r_submiting){
													r_submiting = true;
													$.get(recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
														r_submiting = false;
													})
												}
											}

											var next_sectionid = "<?php  echo $next_sectionid;?>";
											if(next_sectionid){
												window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>&sectionid=<?php  echo $next_sectionid;?>";
											}
										}

										player.on('play', playVideo);
										player.on('pause', pauseVideo);
										player.on('timeupdate', timeUpdate);
										player.on('ended', ended);

										setInterval(function(){
											if(playing){
												realPlayTime = parseInt(realPlayTime) + parseInt(1);
												$("#realPlayTime").val(realPlayTime);
											}
											if(realPlayTime!=0 && realPlayTime%space_time==0 && !r_submiting && uid){
												r_submiting = true;
												$.get(recordurl+'&op=realPlay', {realPlayTime:space_time}, function (data){
													r_submiting = false;
												})
											}
										},1000)

										<?php  if($section['is_live']){ ?>
											$(document).ready(function (){
												$('.prism-live-display').text('正在直播');
											});
										<?php  } ?>
									</script>
								<?php  } ?>

								<?php  if(!$drag_play) { ?>
								<div class="verify_wrap_shade hide"></div>
								<div class="verify_wrap hide" id="verification">
								</div>

								<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/public/verification/verification.css">
								<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/verification/verification.js"></script>
								<script type="text/javascript">
									var verifying = false;
									var verifyTime = 0;
									window.setInterval(function(){
										verifyTime++;
										if (verifyTime > 0 && !verifying && playing && verifyTime%960==0) {
											verifying = true;
											verifyTime = 0;
											exitFullscreen();
											player.pause();
											$('#verification').html('');
											$('#verification').removeClass('hide');
											$('.verify_wrap_shade').removeClass('hide');
											verification();
										}
									}, 1000);

									var x ;
									var y ;
									//监听鼠标
									document.onmousemove = function (event) {
										var x1 = event.clientX;
										var y1 = event.clientY;
										if (x != x1 || y != y1) {
											verifyTime = 0;
										}
										x = x1;
										y = y1;
									};
									//监听键盘
									document.onkeydown = function () {
										verifyTime = 0;
									};

									function verification(){
										$('#verification').slideVerify({
											type: 2,
											vOffset: 3,
											vSpace: 5,
											imgUrl: "<?php echo MODULE_URL;?>static/public/verification/images/",
											imgName: ['1.jpg', '2.jpg'],
											imgSize: {
												width: '400px',
												height: '200px',
											},
											blockSize: {
												width: '40px',
												height: '40px',
											},
											barSize: {
												width: '400px',
												height: '40px',
											},
											ready: function () {
											},
											success: function () {
												verifying = false;
												$('#verification').addClass('hide');
												$('.verify_wrap_shade').addClass('hide');
												player.play();
											},
											error: function () {
											}
										});
									}
									function exitFullscreen() {
										if (document.exitFullscreen) {
											document.exitFullscreen();
										} else if (document.mozCancelFullScreen) {
											document.mozCancelFullScreen();
										} else if (document.webkitCancelFullScreen) {
											document.webkitCancelFullScreen();
										}else if (document.msExitFullscreen) {
											document.msExitFullscreen(); 
										}
									}
								</script>
								<?php  } ?>

								<script type="text/javascript">
									/* 视频开始播放和暂停事件 */
									var play_video = document.getElementsByTagName('video')[0];
									play_video.addEventListener("play", function(){
										 playing = true;
									});
									play_video.addEventListener("pause", function(){
										 playing = false;
									});

									<?php  if(!$drag_play){ ?>
									var future_time;
									var prev_playtime = <?php  echo intval($prev_record['playtime']); ?>;
									var seekPlay = setInterval(function(){
										<?php  if($section['savetype'] == 5){ ?>
											//腾讯云点播获取视频总时长
											var duration = Math.floor(player.duration());
										<?php  }elseif($section['savetype'] != 2){ ?>
											//阿里云点播获取视频总时长
											var duration = Math.floor(player.getDuration());
										<?php  } ?>

										if(prev_playtime >0 && prev_playtime < duration){
											<?php  if($section['savetype'] == 5){ ?>
												//腾讯云点播继续上次播放时间点
												player.currentTime(prev_playtime);
											<?php  }elseif($section['savetype'] != 2){ ?>
												//阿里云点播继续上次播放时间点
												player.seek(prev_playtime);
											<?php  } ?>

											clearInterval(seekPlay);
										}
										var dragPlay = setInterval("stopDrag()",500);
									},1000);

									function stopDrag() {
										if(play_video){
											var curr_time = play_video.currentTime;
											if(curr_time - future_time > 1){
												play_video.currentTime = future_time - 1;
											}
											future_time = play_video.currentTime;
										}
									}
									<?php  } ?>
								</script>
								
							<?php  } else if($section['sectiontype']==3) { ?>
								<!-- 音频播放器 -->
								<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/AudioPlay/audio.js?v=<?php  echo $versions;?>"></script>
								<link href="<?php echo MODULE_URL;?>static/public/AudioPlay/audio.css?v=<?php  echo $versions;?>" rel="stylesheet"/>
								<style type="text/css">
									.fylesson_audio{width: 100%;}
									.fylesson_audio .audio-wrapper,.fylesson_audio .audio-left{border-radius:0;}
								</style>
								<div class="fylesson_audio">
									<div class="cover" <?php  if($audio_bg_pic) { ?>style="background-image:url(<?php  echo $audio_bg_pic;?>)"<?php  } ?>></div>
									<div class="audio-wrapper">
										<audio id="section-audio" autoplay="autoplay">
											<source src="<?php  echo $section['videourl'];?>" type="audio/mp3" >
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

								<script>
									var ap1 = document.getElementsByTagName('audio')[0];
									var submiting   = false;  /* 记录播放进度提交状态 */
									var r_submiting = false;  /* 记录播放时间提交状态 */
									var space_time  = 60;     /* 提交间隔(秒) */
									var uid			= "<?php  echo $uid;?>";
									var playing		= false;
									var playend		= false;
									var realPlayTime = document.getElementById("realPlayTime").value;
									var recordurl = "/<?php  echo $uniacid;?>/record.html?lessonid=<?php  echo $_GPC['id'];?>&sectionid=<?php  echo $_GPC['sectionid'];?>&playtoken=<?php  echo random(8)?>";

									setInterval(function() {
										var currentTime = document.getElementById("current-ptime").value;
										var duration = Math.floor(ap1.duration);
									<?php  if($section['is_free']==1 && $section['test_time']>0){ ?>
										var test_time = <?php  echo $section['test_time'];?>;
										var plays = <?php echo $plays ? 1: 0;?>;
										if(currentTime >= test_time && !plays && playing){
											ap1.pause();
											alert("<?php echo $lesson_page['auditionTip'] ? $lesson_page['auditionTip'] : '试听已结束，观看完整版本请购买'; ?>");
											location.reload();
										}
									<?php  } ?>

										
										if(currentTime < duration && playing>0){
										
											realPlayTime = parseInt(realPlayTime) + parseInt(1);
											$("#realPlayTime").val(realPlayTime);
										}
										
										//间隔指定时间记录一次播放时间
										if(currentTime>0 && currentTime%space_time==0 && !submiting && uid){
											submiting = true;
											$.get(recordurl, {duration:duration,currentTime:currentTime}, function (data){
												submiting = false;
											})
										}

										if(currentTime>0 && realPlayTime%space_time==0 && !r_submiting && uid){
											r_submiting = true;
											$.get(recordurl+'&op=realPlay', {realPlayTime:space_time}, function (data){
												r_submiting = true;
											})
										}

										if(ap1.currentTime == ap1.duration){
											if(!playend && uid){
												playend = true;
												if(!submiting){
													submiting = true;
													$.get(recordurl, {duration:duration,currentTime:currentTime}, function (data){
														submiting = false;
													})
												}

												if(!r_submiting){
													r_submiting = true;
													$.get(recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
														r_submiting = false;
													})
												}
											}

											var next_sectionid = "<?php  echo $next_sectionid;?>";
											if(next_sectionid){
												window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>&sectionid=<?php  echo $next_sectionid;?>";
											}
										}
									},1000);

									/* 音频开始播放和暂停事件 */
									var play_audio = document.getElementsByTagName('audio')[0];
									play_audio.addEventListener("play", function(){
										 playing = true;
									});
									play_audio.addEventListener("pause", function(){
										 playing = false;
									});

									<?php  if(!$drag_play){ ?>
									var future_time;
									setInterval(function () {
										var curr_time = play_audio.currentTime;
										if(curr_time - future_time>1){
											play_audio.currentTime = future_time - 1;
										}
										future_time = play_audio.currentTime;
									},500);
									<?php  } ?>
								</script>
							<?php  } ?>
						<?php  } ?>
					</div>
					<div class="task-list-wrapper">
						<ol class="study-task-list">
						<?php  if($section_count) { ?>
							<?php  if(is_array($title_list)) { foreach($title_list as $title) { ?>
							<li class="sub-section">
								<h4 class="sub-section--tt"><?php  echo $title['title'];?>（<?php  echo count($title['section'])?>课时）</h4>
								<ol>
									<?php  if(is_array($title['section'])) { foreach($title['section'] as $sec) { ?>
									<li class="js-task-item task-item">
										<div class="task-item-prefix">
											<span class="task-item-type">
												<?php  if($sec['sectiontype']=='1') { ?>
													视频
												<?php  } else if($sec['sectiontype']=='2') { ?>
													图文
												<?php  } else if($sec['sectiontype']=='3') { ?>
													音频
												<?php  } else if($sec['sectiontype']=='4') { ?>
													外链
												<?php  } ?>
											</span>
										</div>
										<div class="task-item-tt play-section <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>" data-sectionid="<?php  echo $sec['id'];?>">
											<h5 class="task-item-text"><?php  echo $sec['title'];?></h5>
											<span class="task-item-suffix">
												<?php  if($sec['is_free']==1) { ?>
													<span class="free-play-box m-r-15">
														<i class="iconfont icon-play1"></i>
														<i class="free-play-font ft-s-n"><?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?></i>
													</span>
												<?php  } else { ?>
													<?php  if($lesson_page['playTip']) { ?>
													<span class="free-play-box m-r-15">
														<i class="free-play-font ft-s-n"><?php  echo $lesson_page['playTip'];?></i>
													</span>
													<?php  } ?>
												<?php  } ?>
												<i class="iconfont icon-clock vertical-minus-1"></i><?php  echo $sec['videotime'];?>
											</span>
										</div>
									</li>
									<?php  } } ?>
								</ol>
							</li>
							<?php  } } ?>

							<?php  if($section_list) { ?>
							<li class="sub-section">
								<h4 class="sub-section--tt"><?php  echo $lesson['bookname'];?>（<?php  echo $section_count;?>课时）</h4>
								<ol>
									<?php  if(is_array($section_list)) { foreach($section_list as $sec) { ?>
									<li class="js-task-item task-item">
										<div class="task-item-prefix">
											<span class="task-item-type">
												<?php  if($sec['sectiontype']=='1') { ?>
													视频
												<?php  } else if($sec['sectiontype']=='2') { ?>
													图文
												<?php  } else if($sec['sectiontype']=='3') { ?>
													音频
												<?php  } else if($sec['sectiontype']=='4') { ?>
													外链
												<?php  } ?>
											</span>
										</div>
										<div class="task-item-tt play-section <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>" data-sectionid="<?php  echo $sec['id'];?>">
											<h5 class="task-item-text">
												<?php  if(($sec['sectiontype']==1 || $sec['sectiontype']==3) && $sec['password']) { ?>
												<i class="iconfont icon-lock"></i>
												<?php  } ?>
												<?php  echo $sec['title'];?>
											</h5>
											<span class="task-item-suffix">
												<?php  if($sec['is_free']==1) { ?>
													<span class="free-play-box m-r-15">
														<i class="iconfont icon-play1"></i>
														<i class="free-play-font ft-s-n"><?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?></i>
													</span>
												<?php  } else { ?>
													<?php  if($lesson_page['playTip']) { ?>
													<span class="free-play-box m-r-15">
														<i class="free-play-font ft-s-n"><?php  echo $lesson_page['playTip'];?></i>
													</span>
													<?php  } ?>
												<?php  } ?>
												<i class="iconfont icon-clock vertical-minus-1"></i><?php  echo $sec['videotime'];?>
											</span>
										</div>
									</li>
									<?php  } } ?>
								</ol>
							</li>
							<?php  } ?>
						<?php  } else { ?>
							<li class="sub-section">
								<h4 class="sub-section--tt"><?php echo $lesson['lesson_type']==0 ? '抱歉，该课程没有找到任何内容~' : '请点击课程“详情”查看相关介绍哦~';?></h4>
							</li>
						<?php  } ?>
						</ol>
					</div>
				</div>
				<div class="section-bottom">
					<div class="course-action">
						<?php  if($show_isbuy) { ?>
						<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>" class="btn-backlesson">
							<i class="iconfont icon-back-o vertical-middle"></i>
							<span>返回课程首页</span>
						</a>
						<?php  } else { ?>
						<a href="javascript:;" class="btn-join">
							<i class="iconfont icon-cart vertical-middle"></i>
							<span><?php echo $buynow_name ? $buynow_name : '立即购买';?></span>
						</a>
						<?php  } ?>
					</div>
					<div class="course-info">
						<div class="course-title">
							<h3>
								<?php  echo $lesson['bookname'];?>
								<?php  if(!$show_isbuy && $setting['lesson_vip_status'] != 1) { ?>
									<span class="title-free">
									<?php  if($lesson['price']>0) { ?>
										￥<?php  echo $spec_list[0]['spec_price'];?> <em class="asking-price">起</em>
									<?php  } else { ?>
										免费
									<?php  } ?>
									</span>
								<?php  } ?>
							</h3>
						</div>
						<div class="course-hints">
							<?php  if($setting['show_study_number']) { ?>
							<span class="line-item">
								<span class="hint-data"><?php  echo $lesson['buyTotal'];?></span>
								<?php  if($lesson['lesson_type'] == 0) { ?>
									<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
								<?php  } else if($lesson['lesson_type'] == 1) { ?>
									<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
								<?php  } ?>
							</span>
							<?php  } ?>
							<span class="line-item">
								好评度
								<span class="hint-data"><?php  echo $evaluate_score['score'];?>%</span>
							</span>
							<div class="line-item item-share js_share">
								<i class="iconfont icon-share vertical-minus-1"></i> 分享
								<div class="hover-tips tips-share js_share_panel" style="bottom:-38px;left:-40px;">
									<ul >
										<li class="share-qq" data-type="qq"></li>
										<li class="share-qzone" data-type="qzone"></li>
										<li class="share-wechat" data-type="wechat"></li>
										<li class="share-sina" data-type="sina"></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php  } ?>
</div>

<!-- 分享微信二维码 -->
<div class="overlay" id="qrcode-container">
	<div class="qrcode-container">
		<div class="qrcode-top"></div>
		<div class="qrcode-center">
			<div class="qc-center">
				<div id="rqcode"></div>
			</div>
			<div class="qc-bottom">打开微信扫描二维码<br>点击右上角进行分享</div>
		</div>
		<div class="qrcode-bottom"></div>
	</div>
</div>

<?php  if($lesson_vip_list) { ?>
<!-- 开通VIP免费学习 -->
<div class="overlay" id="buyvip-overlay"></div>
<div class="buyvip-wrapper" id="buyvip-wrapper">
	<div class="buyvip-header">
		<div class="buyvip-header">
			<div class="buyvip_mod_user">
				<div class="info">
					<h4 class="title">开通以下任意VIP等级即可免费学习本课程</h4>
				</div>
			</div>
			<a href="javascript:;" class="btn btn_close">
				<i class="iconfont icon-close"></i>
			</a>
		</div>
	</div>
	<div class="buyvip_mod_combo">
		<div class="combo_list_wp">
			<ul>
				<?php  if(is_array($lesson_vip_list)) { foreach($lesson_vip_list as $key => $item) { ?>
				<li class="list_item select_vip_level" data-level-id="<?php  echo $item['id'];?>">
					<h5 class="title"><?php  echo $item['level_name'];?></h5>
					<div class="price">
						￥<em class="em"><?php  echo round($item['level_price']*$item['open_discount']*0.01,2);?></em>
					</div>
					<p class="desc">有效期<?php  echo $item['level_validity'];?>天</p>
					<?php  if($item['integral']) { ?>
					<p class="desc">购买送<?php  echo $item['integral'];?>积分</p>
					<?php  } ?>
					<?php  if($item['discount'] && $item['discount'] < 100 ) { ?>
					<p class="desc">
						购买课程享受<?php  echo $item['discount'];?>%优惠
					</p>
					<?php  } ?>
					<p class="desc"><i class="iconfont icon-information vertical-middle"></i> <a href="/<?php  echo $uniacid;?>/viplesson.html?level_id=<?php  echo $item['id'];?>" target="_blank">查看可免费学习的课程</a></p>
					<i class="selected"></i>
					<?php  if($item['open_discount'] < 100) { ?>
					<i class="discount_tips"></i>
					<?php  } ?>
				</li>
				<?php  } } ?>
			</ul>

			<?php  if($comsetting['vip_agreement']) { ?>
			<div class="flex1 fs-14">
				<input type="checkbox" name="vip_agreement" value="1" style="vertical-align:-2px;" checked=""> 我已阅读并同意<a href="javascript:;" id="btn-agreement" class="ftc-c78800">《VIP服务协议》</a>
			</div>
			<div class="agreement-area-mask" style="display:none;">
				<div class="agreement-area">
					<div class="close">
						<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/btn-close.png" width="32" height="32">
					</div>
					<h3 class="notice-title">VIP服务协议</h3>
					<ul class="notice-body">
						<?php  echo htmlspecialchars_decode($comsetting['vip_agreement'])?>
					</ul>
				</div>
			</div>
			<?php  } ?>

		</div>
	</div>
	<div class="open-button">
		<a href="javascript:;" class="btn-buyvip-confirm">开通</a>
		<a href="javascript:;" class="btn-buyvip-cancel">取消</a>
		<input type="hidden" id="buyvip_level_id" value="" />
	</div>
</div>
<script type="text/javascript">
	//定位提示框
	if (window.innerHeight){
		winHeight = window.innerHeight;
	}else if ((document.body) && (document.body.clientHeight)){
		winHeight = document.body.clientHeight;
	}

	window.onload = function() { 
		var buyvipHeight = $('#buyvip-wrapper').height();
		var marginTop = (winHeight-buyvipHeight)/2;
		document.getElementById("buyvip-wrapper").style.top = marginTop + 'px';
	}

	//开通VIP服务提示
	$('.btn-buyvip').hover(function(){
		$('.btn-buyvip-tip').show();
	},function(){
		$('.btn-buyvip-tip').hide();
	});
	//VIP服务内容
	$("#btn-agreement").click(function(){
		$('.agreement-area-mask').show();
	});
	$(".close").click(function(){
		$('.agreement-area-mask').hide();
	});
	//取消
	$(".buyvip-wrapper .btn_close, .buyvip-wrapper .btn-buyvip-cancel").click(function(){
		$("#buyvip-overlay").hide();
		$(".buyvip-wrapper").hide();
	});
	//选择VIP
	$(".select_vip_level").click(function(){
		var level_id = $(this).data('level-id');
		$("#buyvip_level_id").val(level_id);

		var $currItem = $(this),index = $currItem.index();
		$(".combo_list_wp .list_item .selected").hide().eq(index).show();
	});
	//确认开通
	$(".btn-buyvip-confirm").click(function(){
		var level_id = $("#buyvip_level_id").val();
		if(!level_id){
			swal("系统提示", "请选择要开通的VIP等级");
			return false;
		}

		<?php  if($comsetting['vip_agreement']){ ?>
			var agreement = false;
			$("input[name='vip_agreement']").each(function () {
				if ($(this).is(":checked")) {
					agreement = true;
				}
			});
			if(!agreement){
				swal("系统提示", "请阅读并同意《VIP服务协议》");
				return false;
			}
		<?php  } ?>

		window.location.href = "/<?php  echo $uniacid;?>/vip.html?op=buyvip&level_id=" + level_id;
	});
</script>
<?php  } ?>

<div class="hg-22 bg-c-f4f4f4"></div>

<div class="w-all bg-c-f4f4f4" id="content-info">
	<div class="w-1200 m-auto">
		<div class="content lessons">
			<div class="lessons-tt-bar js_tab js-tab-nav">
					<h2 class="lessons-tt <?php  if($show_details) { ?>active<?php  } ?>"><?php echo $lesson_page['details'] ? $lesson_page['details'] : '详情'?></h2>
				<?php  if($lesson['lesson_type']!=1 || ($lesson['lesson_type']==1 && $lesson['appoint_dir'])) { ?>
					<h2 class="lessons-tt <?php  if($show_dir) { ?>active<?php  } ?>"><?php echo $lesson_page['directory'] ? $lesson_page['directory'] : '目录'?></h2>
				<?php  } ?>
				<?php  if(!empty($course_list) || !empty($examine_list)) { ?>
					<h2 class="lessons-tt"><?php echo $lesson_page['test'] ? $lesson_page['test'] : '测验'?></h2>
				<?php  } ?>
				<?php  if($like_goods_list) { ?>
					<h2 class="lessons-tt"><?php echo $lesson_page['goods'] ? $lesson_page['goods'] : '商品'?>(<?php  echo count($like_goods_list)?>)</h2>
				<?php  } ?>
				<?php  if($lesson_config['document'] && $document_list) { ?>
					<h2 class="lessons-tt"><?php echo $lesson_page['document'] ? $lesson_page['document'] : '课件'?><span class="num">(<?php  echo count($document_list)?>)</span></h2>
				<?php  } ?>
				<?php  if($lesson_config['evaluate']) { ?>
					<h2 class="lessons-tt"><?php echo $lesson_page['evaluate'] ? $lesson_page['evaluate'] : '评价'?><span class="num">(<?php  echo $evaluate_total;?>)</span></h2>
				<?php  } ?>
			</div>
			<div class="lessons-content hide" <?php  if($show_details) { ?>style="display: block;"<?php  } ?>>
				<?php  if($lesson['lesson_type']==1 && ($buynow_info['appoint_addres'] || $buynow_info['appoint_validity'])) { ?>
				<h3 class="section-content">【活动信息】</h3>
				<div class="intro-course bg-c-f3f3f3 p-t-10 p-l-10 p-b-10 p-r-10 fs-15">
					<?php  if($buynow_info['appoint_addres']) { ?>
						<div class="m-t-8 m-b-8"><i class="iconfont icon-location m-r-6"></i> <?php  echo $buynow_info['appoint_addres'];?></div>
					<?php  } ?>
					<?php  if($buynow_info['appoint_validity']) { ?>
						<div class="m-t-8 m-b-8"><i class="iconfont icon-clock-o m-r-6"></i> 报名截至时间： <?php  echo $buynow_info['appoint_validity'];?></div>
					<?php  } ?>
				</div>
				<div class="hg-22"></div>
				<?php  } ?>

				<?php  if($section['content']) { ?>
				<h3 class="section-content">【章节详情】</h3>
				<div class="intro-course">
					<?php  echo htmlspecialchars_decode($section['content']);?>
				</div>
				<div class="hg-22"></div>
				<?php  } ?>

				<h3 class="section-content">【课程详情】</h3>
				<div class="intro-course">
					<?php  echo htmlspecialchars_decode($lesson['descript']);?>
				</div>
			</div>

			<?php  if($lesson['lesson_type']!=1 || ($lesson['lesson_type']==1 && $lesson['appoint_dir'])) { ?>
			<div class="lessons-content hide" <?php  if($show_dir) { ?>style="display: block;"<?php  } ?>>
				<div class="p-t-20">
				<?php  if($section_count) { ?>
					<?php  if(is_array($title_list)) { foreach($title_list as $key => $title) { ?>
					<div class="task-part-item">
						<div class="task-part-hd">
							<h3 class="part-tt"><?php  echo $title['title'];?>[<?php  echo count($title['section'])?>课时]</h3>
						</div>
						<div class="task-task-list">
							<?php  if(is_array($title['section'])) { foreach($title['section'] as $sec) { ?>
							<a href="javascript:;" class="task-task-item task-item-jump play-section <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>" data-sectionid="<?php  echo $sec['id'];?>">
								<i class="iconfont <?php  echo $section_icon[$sec['sectiontype']];?> item-icon"></i>
								<p>
									<span class="task-tt-text">
										<?php  if($sec['is_free']==1) { ?>
											【<?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?>】
										<?php  } else { ?>
											<?php echo $lesson_page['playTip'] ? '【'.$lesson_page['playTip'].'】' : '';?>
										<?php  } ?>
									</span>
									<?php  if(($sec['sectiontype']==1 || $sec['sectiontype']==3) && $sec['password']) { ?>
									<i class="iconfont icon-lock"></i>
									<?php  } ?>
									<?php  echo $sec['title'];?>
									<?php  if($sec['videotime']) { ?>
									<span class="tt-suffix">(<?php  echo $sec['videotime'];?>)</span>
									<?php  } ?>
								</p>
							</a>
							<?php  } } ?>
						</div>
					</div>
					<?php  } } ?>

					<?php  if($section_list) { ?>
					<div class="task-part-item">
						<?php  if(empty($title_list)) { ?>
						<div class="task-part-hd">
							<h3 class="part-tt"><?php  echo $lesson['bookname'];?>[<?php  echo $section_count;?>课时]</h3>
						</div>
						<?php  } ?>
						<div class="task-task-list">
							<?php  if(is_array($section_list)) { foreach($section_list as $sec) { ?>
							<a href="javascript:;" class="task-task-item task-item-jump play-section <?php  if($sectionid==$sec['id']) { ?>section-active<?php  } ?>" data-sectionid="<?php  echo $sec['id'];?>">
								<i class="iconfont <?php  echo $section_icon[$sec['sectiontype']];?> item-icon"></i>
								<p>
									<span class="task-tt-text">
										<?php  if($sec['is_free']==1) { ?>
											【<?php echo $lesson_page['freeTip'] ? $lesson_page['freeTip'] : '免费试听';?>】
										<?php  } else { ?>
											<?php echo $lesson_page['playTip'] ? '【'.$lesson_page['playTip'].'】' : '';?>
										<?php  } ?>
									</span>
									<?php  if(($sec['sectiontype']==1 || $sec['sectiontype']==3) && $sec['password']) { ?>
									<i class="iconfont icon-lock"></i>
									<?php  } ?>
									<?php  echo $sec['title'];?>
									<?php  if($sec['videotime']) { ?>
									<span class="tt-suffix">(<?php  echo $sec['videotime'];?>)</span>
									<?php  } ?>
								</p>
							</a>
							<?php  } } ?>
						</div>
					</div>
					<?php  } ?>
				<?php  } else { ?>
					<div class="no-content">
						<div class="msg-inner">
							<div class="msg-text">
								<i class="iconfont i-info icon-information"></i> 该课程暂未发布任何章节
							</div>
						</div>
					</div>
				<?php  } ?>
				</div>
			</div>
			<?php  } ?>

			<?php  if(!empty($course_list) || !empty($examine_list)) { ?>
			<div class="lessons-content hide">
				<div class="task-task-list">
					<?php  if(is_array($course_list)) { foreach($course_list as $item) { ?>
					<a href="<?php  echo $pc_siteroot;?>app/index.php?i=<?php  echo $uniacid;?>&c=entry&op=&id=<?php  echo $item['id'];?>&lessonid=<?php  echo $id;?>&from=pc&do=course&m=fy_lessonv2_plugin_exam" class="task-task-item task-item-jump" style="background-color: rgb(244, 244, 244);">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" class="exam-img">
						<p class="exam-title">【练习】<?php  echo $item['title'];?></p>
					</a>
					<?php  } } ?>

					<?php  if(is_array($examine_list)) { foreach($examine_list as $item) { ?>
					<a href="<?php  echo $pc_siteroot;?>app/index.php?i=<?php  echo $uniacid;?>&c=entry&op=&id=<?php  echo $item['id'];?>&lessonid=<?php  echo $id;?>&from=pc&do=examine&m=fy_lessonv2_plugin_exam" class="task-task-item task-item-jump" style="background-color: rgb(244, 244, 244);">
						<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" class="exam-img">
						<p class="exam-title">【考试】<?php  echo $item['title'];?></p>
					</a>
					<?php  } } ?>
				</div>
			</div>
			<?php  } ?>

			<?php  if($like_goods_list) { ?>
			<div class="lessons-content hide">
				<ul class="goods-warp">
					<?php  if(is_array($like_goods_list)) { foreach($like_goods_list as $item) { ?>
					<li class="gl-item" onmouseover="showQrcode(this);" onmouseout="hideQrcode(this);">
						<div class="gl-i-wrap">
							<div class="p-img">
								<a href="javascript:;">
									<img width="220" height="220" src="<?php  echo $_W['attachurl'];?><?php  echo $item['cover'];?>">
								</a>
								<div class="picon" style="background-image:url(<?php  echo $dirpath.'goods_'.$item['id'].'.jpg';?>);"></div>
							</div>
							<div class="p-price">
									<strong><?php  echo $item['show_price'];?></strong>
								<?php  if($item['sell_type'] != 1 && $item['market_price']>0) { ?>
									<p class="market-price">￥<?php  echo $item['market_price'];?><p>
								<?php  } ?>
							</div>
							<div class="p-name">
								<a href="javascript:;"><?php  echo $item['title'];?></a>
							</div>
						</div>
					</li>
					<?php  } } ?>
					<div class="clear"></div>
				</ul>
			</div>
			<?php  } ?>
<script type="text/javascript">
	function showQrcode(obj){
		$(obj).children(":first").children(":first").find('.picon').show(); 
	}
	function hideQrcode(obj){
		$(obj).children(":first").children(":first").find('.picon').hide();
	}
</script>
			<?php  if($lesson_config['document'] && $document_list) { ?>
			<div class="lessons-content hide" <?php  if($show_dir) { ?>style="display: block;"<?php  } ?>>
				<div class="p-t-20">
				<?php  if(!empty($document_list)) { ?>
					<div class="task-part-item">
						<div class="task-task-list">
							<?php  if(is_array($document_list)) { foreach($document_list as $key => $document) { ?>
							<a <?php  if($plays) { ?>href="/<?php  echo $uniacid;?>/downloadFile.html?fileid=<?php  echo $document['id'];?>"<?php  } ?> class="task-task-item task-item-jump <?php  if(!$plays) { ?>document-tip<?php  } ?>">
								<i class="iconfont icon-file item-icon"></i>
								<p>
									<?php  echo $key+1;?>、<?php  echo $document['title'];?>
								</p>
							</a>
							<?php  } } ?>
						</div>
					</div>
				<?php  } else { ?>
					<div class="no-content">
						<div class="msg-inner">
							<div class="msg-text">
								<i class="iconfont i-info icon-information"></i> 暂时没有任何<?php echo $lesson_page['document'] ? $lesson_page['document'] : '课件'?>
							</div>
						</div>
					</div>
				<?php  } ?>
				</div>
			</div>
			<?php  } ?>

			<?php  if($lesson_config['evaluate']) { ?>
			<div class="lessons-content hide">
				<div class="evaluation-info">
					<div class="evaluation-title fl">综合<br>好评</div>
					<div class="evaluation-score fl">
						<?php  echo $evaluate_score['score'];?>%
					</div>
					<ul>
						<li><?php echo $evaluate_page['global_score'] ? $evaluate_page['global_score'] : '综合评分';?><span><?php  echo $evaluate_score['global_score'];?></span></li>
						<li><?php echo $evaluate_page['content_score'] ? $evaluate_page['content_score'] : '内容实用';?><span><?php  echo $evaluate_score['content_score'];?></span></li>
						<li><?php echo $evaluate_page['understand_score'] ? $evaluate_page['understand_score'] : '通俗易懂';?><span><?php  echo $evaluate_score['understand_score'];?></span></li>
					</ul>
					<!-- <div class="f-rc-list">
						<label class="f-radio checked" data-id="0">
							<i class="icon-radio"></i>
							<span class="f-rc-text">全部评价(<?php  echo $evaluate_total;?>)</span>
						</label>
						<label class="f-radio" data-id="1">
							<i class="icon-radio"></i>
							<span class="f-rc-text">好评(0)</span>
						</label>
						<label class="f-radio" data-id="2">
							<i class="icon-radio"></i>
							<span class="f-rc-text">中评(0)</span>
						</label>
						<label class="f-radio" data-id="3">
							<i  class="icon-radio"></i>
							<span class="f-rc-text">差评(0)</span>
						</label>
					</div> -->
				</div>
				<div class="comment-list fs-14" id="comment-list">
				</div>
				<div id="pager">
				</div>
			</div>
			<?php  } ?>
		</div>

		<div class="aside-right">
			<div class="aside-blocks">
				<div class="aside-block block-agency">
					<div class="block-bd">
						<h4 class="agency-tt">
							<div class="tt-cover-url">
								<a href="/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $lesson['teacherid'];?>" target="_blank">
									<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['teacherphoto'];?>" width="64" height="64">
								</a>
							</div>
							<div class="tt-cover-name">
								<a class="tt-link js-agency-name" href="/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $lesson['teacherid'];?>" target="_blank"><?php  echo $lesson['teacher'];?></a>
								<span class="all-lesson"><i class="iconfont icon-information"></i> 总共<?php  echo $lessonNumber;?><?php echo $lesson_page['lessonNum'] ? $lesson_page['lessonNum'] : '个课程'?></span>
							</div>
						</h4>
						<div class="agency-summary"><?php  echo htmlspecialchars_decode($lesson['teacherdes']);?></div>
					</div>
				</div>

				<?php  if($setting_pc['teacher_contact'] && ($lesson['qq'] || $lesson['qqgroup'] || $lesson['weixin_qrcode'] || $lesson['online_url']) ) { ?>
				<div class="aside-block block-contact">
					<h3 class="block-tt">联系方式</h3>
					<div class="block-bd">
						<?php  if($lesson['qq']) { ?>
						<ul class="contact-list">
							<li>
								<i class="iconfont icon-qq"></i>
								<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $lesson['qq'];?>&site=qq&menu=yes" class="item-tt" target="_blank">
									QQ咨询
								</a>
								<p class="remark">QQ: <?php  echo $lesson['qq'];?></p>
							</li>
						</ul>
						<?php  } ?>
						<?php  if($lesson['qqgroup']) { ?>
						<ul class="contact-list">
							<li>
								<i class="iconfont icon-qqgroup"></i>
								<a <?php  if($lesson['qqgroupLink']) { ?>href="<?php  echo $lesson['qqgroupLink'];?>"<?php  } ?> class="item-tt" target="_blank">
									<?php  echo $lesson['teacher'];?>讲师交流群
								</a>
								<p class="remark">群号: <?php  echo $lesson['qqgroup'];?></p>
							</li>
						</ul>
						<?php  } ?>
						<?php  if($lesson['online_url']) { ?>
						<ul class="contact-list">
							<li>
								<i class="iconfont icon-online"></i>
								<a href="<?php  echo $lesson['online_url'];?>" class="item-tt" target="_blank">
									在线客服
								</a>
								<a href="<?php  echo $lesson['online_url'];?>" class="remark dsblock" target="_blank">点击咨询在线客服</a>
							</li>
						</ul>
						<?php  } ?>
						<?php  if($lesson['weixin_qrcode']) { ?>
						<ul class="contact-list">
							<li class="weixin_qrcode">
								<i class="iconfont icon-wechat"></i>
								<a href="javascript:;" class="item-tt">
									微信咨询
								</a>
								<div class="teacher-weixin">
									<div>
										<b class="ftc-e9511b fs-13">打开微信扫一扫</b>
									</div>
									<img src="<?php  echo $_W['attachurl'];?><?php  echo $lesson['weixin_qrcode'];?>">
								</div>
							</li>
						</ul>
						<?php  } ?>
					</div>
				</div>
				<?php  } ?>
				<?php  if($like_list) { ?>
				<div class="aside-block block-contact">
					<h3 class="block-tt"><?php echo $lesson_page['like_lesson'] ? $lesson_page['like_lesson'] :'为您推荐';?></h3>
					<div class="block-bd">
						<ul class="course-card-list">
							<?php  if(is_array($like_list)) { foreach($like_list as $item) { ?>
							<li class="course-card-item">
								<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['id'];?>" target="_blank">
									<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" title="<?php  echo $item['bookname'];?>" class="item-img" width="254" height="154">
									<div class="icon-live <?php  echo $item['icon_live_status'];?>" style="top:7px;width:254px;height:154px;"></div>
									<?php  if($item['ico_name']) { ?>
										<?php  if($item['ico_name']=='ico-vip' && $item['vipview']) { ?>
											<i class="ico_common ico-vip"></i>
										<?php  } else { ?>
											<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
										<?php  } ?>
									<?php  } ?>
								</a>
								<h4 class="item-tt m-b-3">
									<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['id'];?>" target="_blank" title="<?php  echo $item['bookname'];?>"><?php  echo $item['bookname'];?></a>
								</h4>
								<div class="item-line item-line-bottom">
									<span class="line-cell item-user ftc-909090">
										<i class="iconfont icon-eye vertical-middle"></i> <?php  echo $item['buynum_total'];?>
									</span>
									<span class="line-cell item-price line-h30 item-user-right price">
										<strong>
											<?php  if($item['price']==0) { ?>
												免费
											<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
												VIP免费
											<?php  } else { ?>
												¥ <?php  echo $item['price'];?>
											<?php  } ?>
										</strong>
									</span>
								</div>
							</li>
							<?php  } } ?>
						</ul>
					</div>
				</div>
				<?php  } ?>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div class="hg-22 bg-c-f4f4f4"></div>

<?php  if($setting['isfollow']==2 && $uid && !$member['follow']) { ?>
<div class="force-contact-main follow_qrcode">
	<div class="force-contact-content_new">
		<p class="force-contact-tips"><?php echo $setting['lesson_follow_title'] ? $setting['lesson_follow_title'] : '打开微信扫一扫, 关注公众号';?></p>
		<p class="force-contact-desc"><?php echo $setting['lesson_follow_desc'] ? $setting['lesson_follow_desc'] : '关注后继续学习, 可接收最新课程通知';?></p>
		<img class="receive-red-packet-contact-touch" src="<?php  echo $_W['attachurl'];?><?php  echo $setting['qrcode'];?>">
	</div>
</div>
<?php  } ?>


<?php  if($lesson_config['evaluate']){ ?>
<script type="text/javascript">
	var ajaxurl = "/<?php  echo $uniacid;?>/getevaluate.html?id=<?php  echo $id;?>";
	$(function () {
		function getData(page) {
			$.get(ajaxurl, {page: page}, function (data) {
				$("#loadingToast").hide();

				var jsonObj = JSON.parse(data);			
				document.getElementById('pager').innerHTML = jsonObj.pager_html;
				insertDiv(jsonObj.evaluate_list);
			});
		} 

		//生成数据html
		function insertDiv(result) {  
			var chtml = '';  
			for (var j = 0; j < result.length; j++) {
				chtml += '<div class="comment-item">';
				chtml += '	<div class="item-left">';
				chtml += '		<img class="user-avatar" src="' + result[j].avatar + '" width="40" height="40">';
				chtml += '		<p class="user-name fs-12">' + result[j].nickname + '</p>';
				chtml += '	</div>';
				chtml += '	<div class="item-right">';
				chtml += '		<div class="evaluate-icon">';
				chtml += '			<img src="' + result[j].grade_ico + '" />';
				chtml += '			<i>' + result[j].grade + '</i>';
			if(result[j].global_score > 0){
				chtml += '			<i style="color:#FFA01E;">(' + result[j].global_score + ')</i>';
			}
				chtml += '		</div>';
				chtml += '		<div class="comment-bd">' + result[j].content + '</div>';
				chtml += '		<div class="comment-ft">';
				chtml += '			<span class="fs-13">' + result[j].addtime + '</span>';
				chtml += '		</div>';
				if(result[j].reply !=null && result[j].reply !=''){
					chtml += '	<div class="comment-reply">';
					chtml += '		<div class="reply-item item-agency">';
					chtml += '			<div class="reply-bd"><strong class="reply-tt">讲师回复：</strong>' + result[j].reply + '</div>';
					//chtml += '			<div class="comment-ft">';
					//chtml += '				<span class="fs-13">2019-03-14</span>';
					//chtml += '			</div>';
					chtml += '		</div>';
					chtml += '	</div>';
				}
				chtml += '	</div>';
				chtml += '</div>';
			}
			document.getElementById('comment-list').innerHTML = chtml;
		}
		getData(1);
		
		$(document).on("click", "#pager ul li a",function(){
			$("#loadingToast").show();
			getData($(this).data('page'));
		})
	});
</script>
<?php  } ?>

<script type="text/javascript">
$(function(){
	//分享按钮
    $('.js_share').hover(function() {
        $('.js_share_panel').show();
    }, function() {
        $('.js_share_panel').hide();
    });

	//分享课程
	var shareTo = function(stype){
		var title = "<?php  echo $share['title'];?>";
		var desc  = "<?php  echo $share['desc'];?>";
		var image = "<?php  echo $share['images'];?>";
		var link = "<?php  echo urlencode($share['link'])?>";

		if(stype == 'qq'){
			window.open('http://connect.qq.com/widget/shareqq/index.html?url=' + link + '&sharesource=qzone&title=' + title + '&pics=' + image + '&summary=' + desc + '&desc=');
		}
		if(stype=='qzone'){
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + link + '&sharesource=qzone&title=' + title + '&pics=' + image + '&summary=' + desc);
		}
		if(stype=='wechat'){
			jQuery('#rqcode').qrcode({width: 190, height: 190, text: "<?php  echo $wap_url;?>"});
			$('#qrcode-container').fadeIn(200).unbind('click').click(function(){
				$(this).fadeOut(100);
			})
		}
		if(stype=='sina'){
			window.open('http://service.weibo.com/share/share.php?url=' + link + '&sharesource=weibo&title=' + title + '&pic=' + image + '&appkey=2706825840');
		}
	}
	$('.js_share_panel ul li').on('click',function(){
		shareTo($(this).data("type"));
	});

	//收藏课程
	$('.btn-favorite').click(function(){
		if(!login()){
			return false;
		}
		$.ajax({
			type:'post',
			url: "/<?php  echo $uniacid;?>/updateCollect.html?id=<?php  echo $id;?>&uid=<?php  echo $uid;?>&ctype=lesson",
			data:{},
			dataType:'json',     
			success:function(res){
			console.log(res);
				if(res.code==0){
					if(res.type==1){
						$(".btn-favorite i").addClass("icon-heart-o");
						$(".btn-favorite i").removeClass("icon-heart");
						$(".btn-favorite span").text("已收藏");

					}else if(res.type==2){
						$(".btn-favorite i").addClass("icon-heart");
						$(".btn-favorite i").removeClass("icon-heart-o");
						$(".btn-favorite span").text("收藏");
					}

					swalalert('系统提示', res.message, 'success');
					return false;
				
				}else{
					swalalert('系统提示', res.message, 'error');
					return false;

				}
			},
			error: function(error){
				swalalert('系统提示', '网络繁忙，请稍候重试', 'error');
				return false;
			}
		});
	});

	//课程目录切换
	$(".js-tab-nav h2").click(function() {
		var $currItem = $(this),
		index = $currItem.index();

		$currItem.addClass('active').siblings().removeClass('active');
		$(".lessons-content").hide().eq(index).show();

	});

	//点击章节
	$('.play-section').click(function(){
		var settiong_must = "<?php  echo intval($setting['mustinfo']);?>";
		if(settiong_must == 2){
			var check_mustinfo = checkMustInfo();
		}else{
			var check_mustinfo = true;
		}
		
		if(check_mustinfo){
			var lessonid = "<?php  echo $lesson['id'];?>";
			var sectionid = $(this).data('sectionid');
			$("#loadingToast").show();

			$.ajax({
				type: 'get',
				url: "/<?php  echo $uniacid;?>/sectionStudyStatus.html",
				data: {id:lessonid, sectionid:sectionid},
				dataType: 'json',     
				success: function(data){
					$("#loadingToast").hide();
					if(data.code==0){
						window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=" + lessonid + "&sectionid=" + sectionid;
					}else{
						swal("系统提示", data.msg);
					}
				},
				error: function(error){
					$("#loadingToast").hide();
					swal("系统提示", "网络繁忙，请稍候重试");
				}
			});
		}
	});

	//开始学习
	$('.btn-study').click(function(){
		var settiong_must = "<?php  echo intval($setting['mustinfo']);?>";
		if(settiong_must == 2){
			var check_mustinfo = checkMustInfo();
		}else{
			var check_mustinfo = true;
		}

		if(check_mustinfo){
			window.location.href = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $lesson['id'];?>&sectionid=<?php  echo $first_section['id'];?>";
		}
	})

	//播放章节时立即购买
	$(".btn-join").click(function(){
		window.pageinfo.refurl = "/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $id;?>";
		if(!login()){
			return false;
		}
		window.location.href = window.pageinfo.refurl;
	});

	//鼠标移过章节背景色
	$('.task-item-tt').hover(function(){
		$(this).find('.free-play-box').css('border', '1px solid #e9511b');
	},function(){
		$(this).find('.free-play-box').css('border', '1px solid #c7c7c7');
		$('.section-active .task-item-suffix .free-play-box').css('border', '1px solid #e9511b');
	});
	$('.task-task-item').hover(function(){
		$(this).css('background-color', '#e0e0e0');
	},function(){
		$(this).css('background-color', '#f4f4f4');
	});

	//讲师微信咨询
	$('.weixin_qrcode').hover(function(){
		$('.teacher-weixin').show();
	}, function(){
		$('.teacher-weixin').hide();
	});

	//未购买课程下载课件提醒
	$(".document-tip").click(function(){
		swal("系统提示", "您还没有购买课程，无法下载");
	});
});

//检查用户是否完善信息
function checkMustInfo(){
	var mustinfo = "<?php  echo intval($mustinfo);?>";
	if(mustinfo == 1){
		swal("系统提示", "请先完善您的信息");
		setTimeout(function(){
			window.location.href = "<?php  echo $jumpurl;?>";
		},2500)
		return false;
	}else{
		return true;
	}
}
</script>

<?php  include $this->template($template.'/_footer')?>