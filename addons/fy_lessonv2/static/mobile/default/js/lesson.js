var config  = window.config;
var lesson  = config.lesson;
var section = config.section;
var wxapp_audition_end = config.wxapp_audition_end;

var iosBuyTip = '';  /* ios端购买提醒 */

var playing    = false; /* 播放状态 */
var submiting  = false; /* 记录播放进度提交状态 */
var r_submiting= false; /* 记录播放时间提交状态 */
var space_time = 60;    /* 提交间隔(秒) */
var realPlayTime = document.getElementById("realPlayTime").value;

var get_status = true; //获取评论的状态
$(function () {
	/* 获取评论列表 START */
    var nowPage = 1;
    function getData(page) {
        if(get_status){
			nowPage++;
			$.get(config.evaluateurl, {page: page}, function (data) {
				var jsonObj = JSON.parse(data);
				if (jsonObj.length > 0) {
					insertDiv(jsonObj);
				}else{
					get_status = false;
					document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
				}
			});
		}
    } 
    //初始化加载第一页数据  
    getData(1);

    //生成数据html,append到div中  
    function insertDiv(result) {  
        var mainDiv =$("#evaluate_list");
        var chtml = '';  
        for (var j = 0; j < result.length; j++) {
			chtml += '<div class="evaluate-item">';
			chtml += '	<div class="item-head clearfix">';
			chtml += '		<div class="commenter-box clearfix">';
			chtml += '			<img src="' + result[j].avatar + '">';
			chtml += '			<span>' + result[j].nickname + '</span>';
			chtml += '			<p class="time">' + result[j].addtime + '</p>';
			chtml += '		</div>';
			chtml += '		<div class="item-head-right">';
			chtml += '			<span class="star-wrap clearfix">';
			chtml += '				<img src="' + result[j].grade_ico + '">';
			chtml += '				<i>' + result[j].grade + '</i>';
		if(result[j].global_score > 0){
			chtml += '				<i style="color:#FFA01E;">(' + result[j].global_score + ')</i>';
		}
			chtml += '			</span>';
			chtml += '		</div>';
			chtml += '	</div>';
			chtml += '	<p class="item-content content-pre">' + result[j].content + '</p>';
			if(result[j].reply !=null && result[j].reply !=''){
				chtml += '<p class="item-reply">讲师回复：' + result[j].reply + '</p>';
			}
			chtml += '</div>';
        }
		mainDiv.append(chtml);
    }  
  
    //定义鼠标滚动事件
	var scroll_loading = false;
    $(window).scroll(function(){
	　　var scrollTop = $(this).scrollTop();
	　　var scrollHeight = $(document).height();
	　　var windowHeight = $(this).height();
	　　if(scrollTop + windowHeight >= scrollHeight && !scroll_loading){
			scroll_loading = true;
			getData(nowPage);  
			scroll_loading = false;
	　　}
	});
    $("#btn_Page").click(function () {
        getData(nowPage);
    });
	/* 获取评论列表 END */


	/* 推广收益 START */
	var scroll_height = "40px";
	var tsq;
	var showidx = 0;

	var new_scroll = function () {
	  var len = $(".article_div_list li").length;
	  var m = $(".article_div_list li");
	  clearInterval(tsq);
	  if (len > 1) {
		tsq = setInterval(function () {
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear', function () {
			$(this).css("top", scroll_height);
		  });
		  showidx++;
		  if (showidx == len) {
			showidx = 0;
		  }
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear');
		}, 3000);
	  }
	}();
	/* 推广收益 END */


	/* 微信图片预览接口 START */
	if(config.userAgent){
		$("li.details img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('li.details img');
			let l=imgObj.length;
			for (let i = 0; i < l; i++) {
				imgs.push(imgObj[i].src);
			}

			WeixinJSBridge.invoke("imagePreview", {
				"urls": imgs,
				"current": this.src
			})
		})
	}

	/* 微信图片预览接口 START */

});


if(section){
	if(config.setting_mustinfo == 2 && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}

	//试听结束提示
	if(config.lesson_page){
		wxapp_audition_end = config.lesson_page.auditionTip ? config.lesson_page.auditionTip : "试听已结束，观看完整版本请购买";
	}else{
		wxapp_audition_end = "试听已结束，观看完整版本请购买";
	}
	
	//视频章节，且不为内嵌代码存储方式
	if(section.sectiontype == 1 && section.savetype != 2){
		var play_video = '';
		if(section.savetype == 5){
			//腾讯云点播
			var player = TCPlayer('player-container-id', {
				fileID: section.videourl,
				appID : config.qcloudvod.appid,
				t	  : config.qcloudVodRes.t ? config.qcloudVodRes.t : '',
				us	  : config.qcloudVodRes.us ? config.qcloudVodRes.us : '',
				sign  : config.qcloudVodRes.sign ? config.qcloudVodRes.sign : '',
				exper : "",
				psign  : config.qcloudVodRes,
				autoplay: true,
				poster: config.attachurl + section.images ? section.images : lesson.images,
				plugins:{
					ContinuePlay: {
					   text:'上次播放至 ',
					   btnText: '恢复播放'
					},
					
					ContextMenu: {
						mirror: true,
					},
				}
			},function(){
				play_video = document.getElementsByTagName('video')[0];
				$('#player-container-id>video').removeAttr("x5-playsinline").attr({"x5-video-player-type":"h5-page","x5-video-player-fullscreen":"true"}).get(0).addEventListener("x5videoexitfullscreen", function(){player.play();});
			});

			//开始播放事件
			function play(){
				playing = true;
			}
			//暂停播放事件
			function pause(){
				playing = false;
			}
			//播放事件更新时间
			function timeUpdate(){
				//间隔指定时间记录一次播放时间
				var currentTime = Math.floor(player.currentTime());
				var duration = Math.floor(player.duration());
				if(currentTime > 0 && currentTime%space_time == 0 && !submiting && config.uid > 0){
					submiting = true;
					$.get(config.recordurl, {duration:duration,currentTime:currentTime}, function (data){});
					setTimeout(function(){
						submiting = false;
					},2000);
				}

				if(section.is_free == 1 && section.test_time > 0){
					if(currentTime >= section.test_time && !config.plays && playing){
						player.pause();
						playing = false;
						alert(wxapp_audition_end);
						location.reload();
					}
				}
			}
			//播放结束事件
			function ended(){
				if(config.uid >0 ){
					var currentTime = Math.floor(player.duration());
					var duration = Math.floor(player.duration());
					if(!submiting){
						submiting = true;
						$.get(config.recordurl, {duration:duration,currentTime:currentTime}, function (data){});
						setTimeout(function(){
							submiting = false;
						},2000);
					}

					if(!r_submiting){
						r_submiting = true;
						$.get(config.recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
							r_submiting = false;
						})
					}
				}
				
				if(config.next_sectionid > 0){
					window.location.href = config.lessonurl + "&sectionid=" + config.next_sectionid;
				}
			}

			player.on('play', play);
			player.on('pause', pause);
			player.on('timeupdate', timeUpdate);
			player.on('ended', ended);

			var recordRealPlayTime = setInterval(function(){
				if(playing){
					realPlayTime = parseInt(realPlayTime) + parseInt(1);
					$("#realPlayTime").val(realPlayTime);
				}
				if(realPlayTime != 0 && realPlayTime%space_time == 0 && !r_submiting && config.uid > 0){
					r_submiting = true;
					$.get(config.recordurl+'&op=realPlay', {realPlayTime:space_time}, function (data){
						r_submiting = false;
					})
				}
			},1000);

			//IOS系统下，在微信内自动播放
			if(config.systemType=='ios'){
				document.addEventListener("WeixinJSBridgeReady", function () {
					document.getElementById('player-container-id_html5_api').play();
				}, false);
			}
		}else{
			//七牛云对象存储、腾讯云对象存储、阿里云点播、阿里云OSS
			var watermark_info = '';
			if(config.uid > 0){
				watermark_info = config.uid + ',' + config.member.mobile + ',' + config.member.nickname;
			}

			var player = new Aliplayer({
				id: "J_prismPlayer",
				width:"100%",
				height:"220px",
				autoplay: true,
				playsinline: true,
				preload: true,
				controlBarVisibility:"click",
				useH5Prism: true,
				showBarTime:"3000",
				useFlashPrism: false,
				x5_type:"",
				x5_video_position:"center",
				x5_fullscreen: false,
				vodRetry: 5,
				cover: config.attachurl + section.images ? section.images : lesson.images,
				//阿里云点播
				vid: section.savetype == 4 ? section.videourl : '',
				playauth: section.savetype == 4 ? config.playAuth : '',
				//其他存储
				source: section.savetype != 4 ? section.videourl : '',
				format: config.m3u8_format && section.savetype == 4 ? "m3u8" : "",

				components:[
					{
					  name: 'RateComponent',
					  type: config.repeat_record_lesson ? AliPlayerComponent.ProgressComponent : AliPlayerComponent.RateComponent,
					},
					{
						name: 'RotateMirrorComponent',
						type: config.common.rotate_mirror ? AliPlayerComponent.RotateMirrorComponent : AliPlayerComponent.ProgressComponent,
					},
					{
						name: 'QualityComponent',
						type: (!config.common.rotate_mirror && section.savetype == 4) ? AliPlayerComponent.QualityComponent : AliPlayerComponent.ProgressComponent,
					},
					{
						name: 'BulletScreenComponent',
						type: config.common.video_watermark && config.uid > 0 ? AliPlayerComponent.BulletScreenComponent : AliPlayerComponent.ProgressComponent,
						args: [watermark_info, {fontSize: '16px', color: '#eeeeee'}, 'random']
					},
					{
						name: 'MemoryPlayComponent',
						type: config.drag_play > 0 ? AliPlayerComponent.MemoryPlayComponent : AliPlayerComponent.ProgressComponent,
					},
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
					  ]
					}
				  ],			
			},function (player) {
				play_video = document.getElementsByTagName('video')[0];
				player._switchLevel = 0;
				$('#J_prismPlayer>video').removeAttr("x5-playsinline").attr({"x5-video-player-type":"h5-page","x5-video-player-fullscreen":"true"}).get(0).addEventListener("x5videoexitfullscreen", function(){player.play();});

				if(config.userAgent){
					document.addEventListener("WeixinJSBridgeReady", function () {
						play_video.play();
					}, false);
				}
			});

			//开始播放事件
			var playVideo = function(){
				playing = true;
			}
			//暂停播放事件
			var pauseVideo = function(){
				playing = false;
			}
			//播放事件更新时间
			var timeUpdate = function(){
				//间隔指定时间记录一次播放时间
				var currentTime = Math.floor(player.getCurrentTime());
				var duration = Math.floor(player.getDuration());
				if(currentTime > 0 && currentTime%space_time == 0 && !submiting && config.uid > 0){
					submiting = true;
					$.get(config.recordurl, {duration:duration, currentTime:currentTime}, function (data){});
					setTimeout(function(){
						submiting = false;
					},2000);
				}

				if(section.is_free == 1 && section.test_time > 0){
					if(currentTime >= section.test_time && !config.plays && playing){
						player.pause();
						playing = false;
						alert(wxapp_audition_end);
						location.reload();
					}
				}
			}
			//播放结束事件
			var ended = function(e){
				if(config.uid > 0){	
					var currentTime = Math.floor(player.getDuration());
					var duration = Math.floor(player.getDuration());
					if(!submiting){
						submiting = true;
						$.get(config.recordurl, {duration:duration, currentTime:currentTime}, function (data){});
						setTimeout(function(){
							submiting = false;
						},2000);
					}

					if(!r_submiting){
						r_submiting = true;
						$.get(config.recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
							r_submiting = false;
						})
					}
				}

				if(config.next_sectionid > 0){
					window.location.href = config.lessonurl + "&sectionid=" + config.next_sectionid;
				}
			}

			player.on('play', playVideo);
			player.on('pause', pauseVideo);
			player.on('timeupdate', timeUpdate);
			player.on('ended', ended);

			var recordRealPlayTime = setInterval(function(){
				if(playing){
					realPlayTime = parseInt(realPlayTime) + parseInt(1);
					$("#realPlayTime").val(realPlayTime);
				}
				if(realPlayTime != 0 && realPlayTime%space_time == 0 && !r_submiting && config.uid > 0){
					r_submiting = true;
					$.get(config.recordurl+'&op=realPlay', {realPlayTime:space_time}, function (data){
						r_submiting = false;
					})
				}
			},1000)
		}

		if(config.drag_play == 0){
			/* 播放一段时间后无操作，则出现图形验证 START */
			var verifying = false;
			var verifyTime = 0;
			window.setInterval(function(){
				if(playing){
					verifyTime++;
				}
				if (verifyTime > 0 && !verifying && playing && verifyTime%960 ==0 ) {
					verifying = true;
					verifyTime = 0;
					exitFullscreen();
					player.pause();
					$('#verification').html('');
					$('#verification').removeClass('hide');
					$('.verify_wrap_shade').removeClass('hide');
					verification();
				}
				if(play_video && verifying){
					play_video.addEventListener("play", function(){
						verifying = false;
						$('#verification').addClass('hide');
						$('.verify_wrap_shade').addClass('hide');
					});
				}
			}, 1000);

			var x ;
			var y ;
			//监听鼠标移动
			document.onmousemove = function (event) {
				var x1 = event.clientX;
				var y1 = event.clientY;
				if (x != x1 || y != y1) {
					verifyTime = 0;
				}
				x = x1;
				y = y1;
			};
			//监听鼠标点击
			document.onmousedown = function (event) {
				verifyTime = 0;
			};
			//监听键盘按下
			document.onkeydown = function () {
				verifyTime = 0;
			};

			function verification(){
				$('#verification').slideVerify({
					type: 2,
					vOffset: 3,
					vSpace: 5,
					imgUrl: config.drag_imgurl,
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
			$(".verify_wrap_shade").click(function(){
				verifying = false;
				$('#verification').addClass('hide');
				$('.verify_wrap_shade').addClass('hide');
				player.play();
			})

			var future_time;
			var seekPlay = setInterval(function(){
				if(section.savetype == 5){
					//腾讯云点播获取视频总时长
					var duration = Math.floor(player.duration());
				}else{
					//阿里云点播获取视频总时长
					var duration = Math.floor(player.getDuration());
				}

				if(config.prev_playtime >0 && config.prev_playtime < duration){
					if(section.savetype == 5){
						//腾讯云点播继续上次播放时间点
						player.currentTime(config.prev_playtime);
					}else{
						//阿里云点播继续上次播放时间点
						player.seek(config.prev_playtime);
					}
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
			/* 播放一段时间后无操作，则出现图形验证 END */
		}else{
			var continuePrevPlay = setInterval(function(){
				if(section.savetype == 5){
					//腾讯云点播获取视频总时长
					var duration = Math.floor(player.duration());
				}else{
					//阿里云点播获取视频总时长
					var duration = Math.floor(player.getDuration());
				}

				if(config.prev_playtime >0 && config.prev_playtime < duration){
					if(section.savetype == 5){
						//腾讯云点播继续上次播放时间点
						player.currentTime(config.prev_playtime);
					}else{
						//阿里云点播继续上次播放时间点
						player.seek(config.prev_playtime);
					}
					clearInterval(continuePrevPlay);
				}
			},1000);
		}

		//按16:9动态计算视频容器高度
		var screen_width = document.getElementById('video-wrap').clientWidth;
		var video_height = (screen_width * 9)/16;
		$('#J_prismPlayer').css('height', video_height + 'px');
		$('#player-container-id').css('height', video_height + 'px');
	}

	//音频章节
	if(section.sectiontype == 3){
		var ap1 = document.getElementById("section-audio");
		var playing = false;
		var playend = false;

		setInterval(function() {
			var currentTime = document.getElementById("current-ptime").value;
			currentTime = parseInt(currentTime);
			var duration = Math.floor(ap1.duration);

			if(currentTime < duration && playing){
				realPlayTime = parseInt(realPlayTime) + parseInt(1);
				$("#realPlayTime").val(realPlayTime);
			}
			
			if(section.is_free == 1 && section.test_time > 0 && playing){
				if(currentTime >= section.test_time && !config.plays){
					ap1.pause();
					alert(wxapp_audition_end);
					location.reload();
				}
			}
			
			//间隔指定时间记录一次播放时间
			if(currentTime > 0 && currentTime%space_time == 0 && !submiting && config.uid > 0){
				submiting = true;
				$.get(config.recordurl, {duration:duration,currentTime:currentTime}, function (data){
					submiting = false;
				})
			}

			if(currentTime > 0 && realPlayTime%space_time == 0 && !r_submiting && config.uid > 0){
				r_submiting = true;
				$.get(config.recordurl+'&op=realPlay', {realPlayTime:realPlayTime}, function (data){
					r_submiting = false;
				})
			}

			if(ap1.currentTime == ap1.duration){
				if(!playend && config.uid > 0){
					playend = true;
					if(!submiting){
						submiting = true;
						$.get(config.recordurl, {duration:duration,currentTime:currentTime}, function (data){
							submiting = false;
						})
					}

					if(!r_submiting){
						r_submiting = true;
						$.get(config.recordurl+'&op=realPlay', {realPlayTime:(realPlayTime%space_time)}, function (data){
							r_submiting = false;
						})
					}
				}
				
				if(config.next_sectionid > 0){
					window.location.href = config.lessonurl + "&sectionid=" + config.next_sectionid;
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

		if(!config.drag_play){
			var future_time;
			setInterval(function () {
				var curr_time = play_audio.currentTime;
				if(curr_time - future_time>1){
					play_audio.currentTime = future_time - 1;
				}
				future_time = play_audio.currentTime;
			},500);
		}

		//兼容ios自动播放
		var audio_plays = function(){
			ap1.play();
			document.removeEventListener("touchstart", audio_plays, false);
		};
		ap1.play();
		document.addEventListener("WeixinJSBridgeReady", function () {
			audio_plays();
		}, false);
		document.addEventListener("touchstart", audio_plays, false);
	}
}


/* 报名活动截至时间倒计时 START */
if(lesson.lesson_type ==1 && config.buynow_info.appoint_validity && config.nowtime < config.appoint_validity){
	setTimeout("show_appoint_time()",1000);
	var time_d = document.getElementById("time_d");
	var time_h = document.getElementById("time_h");
	var time_m = document.getElementById("time_m");
	var time_s = document.getElementById("time_s");
	
	//截至日期
	var validity_date = config.buynow_info.appoint_validity.replace('-', '/');
	validity_date = validity_date.replace('-', '/');
 
	var time_end = new Date(validity_date); // 设定结束时间
	time_end = time_end.getTime();
	 
	function show_appoint_time(){
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

			setTimeout("show_appoint_time()",1000);
		}else{
			window.location.reload();
		}
		if(!int_day){
			$('.time_days').hide();
		}
	}
}
/* 报名活动截至时间倒计时 END */


/* 限时折扣 START */
if(config.discount_endtime != '' && !config.show_isbuy){
	setTimeout("show_discount_time()",1000);
	var time_d = document.getElementById("time_d");
	var time_h = document.getElementById("time_h");
	var time_m = document.getElementById("time_m");
	var time_s = document.getElementById("time_s");

	//截至日期
	var validity_date = config.discount_endtime.replace('-', '/');
	validity_date = validity_date.replace('-', '/');
	 
	var time_end = new Date(validity_date); // 设定结束时间
	time_end = time_end.getTime();
	 
	function show_discount_time(){
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

			setTimeout("show_discount_time()",1000);
		}else{
			time_d.innerHTML = time_d.innerHTML;
			time_h.innerHTML = time_h.innerHTML;
			time_m.innerHTML = time_m.innerHTML;
			time_s.innerHTML = time_s.innerHTML;
		}
	}
}
/* 限时折扣 END */


//显示和关闭粉丝群、关注公众号
$("#follow-wechat").on('click', function(){
	$(".follow-wechat").show();
	$(".aui-mask").show();
})
$("#join-qun").on('click', function(){
	$(".lesson-qun").show();
	$(".aui-mask").show();
})
function closeTip(){
	$(".follow-wechat").hide();
	$(".lesson-qun").hide();
	$(".aui-mask").hide();
}

//展开和关闭章节
function handleSection(obj){
	$(obj).find('span').toggleClass("open");
	$(obj).next('ul').toggleClass("hide");
}

//展开咨询
$("#btn-qq").click(function() {
	$("#bottom-contact").removeClass("hide");
	$("#layer-bg").removeClass("hide");
});
//关闭咨询
$(".layer-close").click(function() {
	$("#bottom-contact").addClass("hide");
	$("#layer-bg").addClass("hide");

});

//微信二维码
$('#btn-qrcode').click(function(){
	$('#cover').fadeIn(200).unbind('click').click(function(){
		$(this).fadeOut(100);
	})
});

if(config.userAgent && config.systemType=='ios' && !config.setting_ios_pay){
	iosBuyTip = config.lesson_page.iosBuyTip ? config.lesson_page.iosBuyTip : "根据公众号相关运营规范，iOS端公众号不支持开通VIP或购买课程";
	$('.no-ios').hide();
	$('.is-ios').show();
	$('.is-ios').click(function(){
		showSingleDialog(iosBuyTip);
	})
}

//点击章节标题事件
function readSection(sectionid){
	if(config.setting_mustinfo == 2 && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}else{
		var lessonid = "{$lesson['id']}";
		$("#loadingToast").show();

		$.ajax({
			type: "GET",
			url: config.sectionurl,
			data: {id:config.lesson.id, sectionid:sectionid},
			dataType: "json",     
			success: function(data){
				$("#loadingToast").hide();
				if(data.code==0){
					location.href = config.lessonurl + "&sectionid=" + sectionid;
				}else if(data.code==-99){
					if(config.systemType=='ios'){
						if(isMiniProgram){
							showSingleDialog('不支持该操作类型');
						}else{
							showSingleDialog(iosBuyTip ? iosBuyTip : data.msg);
						}
					}else{
						showSingleDialog(iosBuyTip ? iosBuyTip : data.msg);
					}
				}else{
					showSingleDialog(data.msg);
				}
			},
			error: function(error){
				$("#loadingToast").hide();
				showSingleDialog('网络繁忙，请稍候重试');
			}
		});
	}
}

//关闭关注公众号二维码
$(".follow_lesson_close").click(function(){
	$(".follow_qrcode").hide();
})

// “章节”、“课程详情”tab切换
$(".course-tab").on("click", 'li', function() {
	var $currItem = $(this),
	index = $currItem.index();

	$currItem.addClass('curr').siblings().removeClass('curr');
	$(".js-tab").hide().eq(index).show();

});

//上次学习章节提示
if(config.hissection){
	var scroll_before = scroll_after = 0;
	var recordshow = true;
	window.onscroll = function() {
		var scroll_after = document.documentElement.scrollTop || document.body.scrollTop;
		if(scroll_before > scroll_after) {
			if(recordshow) {
				$("#bottom_bar").animate({opacity: 'show'}, 'slow');
			}
			scroll_before = scroll_after;
		} else {
			$("#bottom_bar").animate({opacity: 'hide'}, 'slow');
			scroll_before = scroll_after;
		}
	}
	$("#bottom_bar .close").on('click', function(){
		recordshow = false;
		$("#bottom_bar").animate({opacity: 'hide'}, 'slow');
	})
}

//点击立即购买课程，如果没有完善信息，先弹出完善信息，否则弹出规格信息或进入确认订单页面
$("#buy-now").click(function() {
	if(config.setting_mustinfo && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}else{
		if(config.spec_list.length ==1){
			var spec_id = config.spec_list[0].spec_id;
			location.href = config.confirmurl + "&spec_id="+spec_id;
		}else{
			$(".flick-menu-mask").removeClass('hide');
			$(".spec-menu-show").removeClass('hide');
		}
	}
});

//选择课程规格
function updateColorSizeSpec(spec_id, spec_price, spec_day, spec_name){
	$(".a-item").removeClass("selected");
	$(".spec_"+spec_id).addClass("selected");
	$("#spec_id").val(spec_id);
	document.getElementById("spec_price").innerHTML = "￥"+spec_price;

	if(config.lesson.lesson_type == 1){
		document.getElementById("specDetailInfo_lesson").innerHTML = spec_name;
	}else{
		document.getElementById("specDetailInfo_lesson").innerHTML = spec_day==-1 ? '长期有效' : "有效期"+spec_day+"天";
	}
}

//规格里的立即购买按钮事件
$("#buy_now").click(function(){
	var spec_id = $("#spec_id").val();
	if(!spec_id){
		showSingleDialog("请选择课程规格");
		return false;
	}
	location.href = config.confirmurl + "&spec_id="+spec_id;
});

//关闭课程和VIP规格
$(".spec-menu-close,.flick-menu-mask").click(function(){
	$(".flick-menu-mask").addClass('hide');
	$(".spec-menu-show").addClass('hide');
	$(".vip-menu-show").addClass('hide');
})

//查看VIP服务协议
$("#view-vip-agreement").click(function(){
	$('#vip-agreement-content').fadeIn(200).unbind('click').click(function(){
		$(this).fadeOut(100);
	})
});

//开通VIP按钮事件
$("#buy-vip").click(function() {
	if(config.setting_mustinfo && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}else{
		$(".flick-menu-mask").removeClass('hide');
		$(".vip-menu-show").removeClass('hide');
	}
});

//选择vip规格
function selectVipSpec(level_id, level_price, discount_price, open_discount, level_validity, level_name){
	$(".a-item").removeClass("vip-selected");
	$(".vip_"+level_id).addClass("vip-selected");
	$("#vip_id").val(level_id);
	$("#discount_price").html('￥' + discount_price);
	if(level_price*1 != discount_price){
		$("#level_price").html('￥' + level_price);
		$("#discount_info").show();
		$("#discount_number").html(open_discount*0.1);
	}else{
		$("#level_price").html('');
		$("#discount_info").hide();
	}

	$("#specDetailInfo_vip").html(level_name);
}

//提交VIP订单
$("#buy_vip").click(function(){
	var vip_id = $("#vip_id").val();
	if(!vip_id){
		showSingleDialog("请选择要购买的VIP等级");
		return false;
	}

	if($("#vip_agreement").length > 0){
		if(!$("#vip_agreement").is(':checked')){
			showSingleDialog("请阅读并同意VIP服务协议");
			return false;
		}
	}

	location.href = config.buyvipurl + "&level_id="+vip_id;
});

//收藏按钮
if(config.uid > 0){
	$("#btn-collect").click(function(){
		$.ajax({
			type: "POST",
			url: config.collecturl,
			data: {id:config.lesson.id},
			dataType: "json",     
			success:function(data){
				if(data=='1'){
					$("#btn-collect a").addClass("cur");
				}else if(data=='2'){
					$("#btn-collect a").removeClass("cur");
				}
			}
		});
	});
}

//开始学习按钮
$(".study").click(function(){
	if(config.setting_mustinfo == 2 && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}else{
		var study_tips = config.lesson_page_study_tips ? config.lesson_page_study_tips : '如没有播放，请点击音视频的播放按钮';
		if(config.sectionid == 0){
			window.location.href = config.studyurl + "&clear=1";
		}else{
			showSingleDialog(study_tips);
		}
	}
})


//课程介绍查看更多
if((!config.sectionid || !config.section.content) && (config.lesson_config && config.lesson_config.hide_lesson_descript)){
	$(function(){
		var lesson_desc_more = document.getElementById('lesson_desc_more');
		var lesson_desc = document.getElementById('lesson_desc');
		if(lesson_desc.clientHeight > 500){
			lesson_desc.style.height = 500 + 'px';
			lesson_desc_more.style.display = 'block';
		}

		$("#lesson_desc_more").click(function(){
			lesson_desc.style.height = 'auto';
			lesson_desc_more.style.display = 'none';
		})
	});
}

//进入章节下拉时，自动锁定音频或视频播放器
if(config.sectionid > 0){
	if(config.section.sectiontype == 1){config.section
		var video_wrap_height = ((document.getElementById('video-wrap').clientWidth * 9)/16);
		var video_container_height = ((document.getElementById('video-wrap').clientWidth * 9)/16)+43;
		var course_tab_fixed= document.getElementById('course-tab-fixed');
		var course_container = document.getElementById('course-container');
		$(window).scroll(function(event) {
			if($(this).scrollTop() > 80){
				course_tab_fixed.setAttribute('style','position:fixed;top:'+video_wrap_height+'px;');
				course_container.setAttribute('style','margin-top:'+video_container_height+'px;');
				$("#video-wrap").addClass('video-wrap-fixed');
			}
			if($(this).scrollTop()<=80){
				course_tab_fixed.setAttribute('style','position:relative;top:0px;');
				course_container.setAttribute('style','margin-top:0px;');
				$("#video-wrap").removeClass('video-wrap-fixed');
			}
		});
	}else if(config.section.sectiontype == 3){
		var course_tab_fixed= document.getElementById('course-tab-fixed');
		var course_container = document.getElementById('course-container');
		$(window).scroll(function(event) {
			if($(this).scrollTop() > 80){
				course_tab_fixed.setAttribute('style','position:fixed;top:101px;');
				course_container.setAttribute('style','margin-top:145px;');
				$("#video-wrap").addClass('video-wrap-fixed');
			}
			if($(this).scrollTop()<=80){
				course_tab_fixed.setAttribute('style','position:relative;top:0px;');
				course_container.setAttribute('style','margin-top:0px;');
				$("#video-wrap").removeClass('video-wrap-fixed');
			}
		});
	}
}

if(config.virtual_buyinfo){
	var buyinfo = config.virtual_buyinfo;
	var time = buyinfo_serial = 0;
	var timer = setInterval(function(){
		if(time == 0 || time%15 == 0){
			$(".newbuy_info_item").html(buyinfo[buyinfo_serial]);
			$(".newbuy_wrap").animate({opacity: 'show'}, 'slow');
			buyinfo_serial ++;

			setTimeout(function(){
				$(".newbuy_wrap").animate({opacity: 'hide'}, 'slow');
			}, 8000);
		}
		if(buyinfo_serial >= buyinfo.length){
			clearInterval(timer);
		}
		time++;
	},1000);

	if(config.lesson.lesson_type == 1 && config.buynow_info.appoint_validity && config.nowtime > config.appoint_validity){
		clearInterval(timer);
	}
}

//删除练习考试返回课程参数
sessionStorage.setItem(config.lesson.uniacid + "_exam_lessonid", "");


//微信分享接口
wx.ready(function(){
	var shareData = {
		title: config.sharelesson.title,
		desc: config.sharelesson.desc,
		link: config.sharelesson.link,
		imgUrl: config.sharelesson.images,
		trigger: function (res) {},
		complete: function (res) {},
		success: function (res) {
			$.post(config.sharecouponurl);
		},
		cancel: function (res) {},
		fail: function (res) {}
	};
	wx.onMenuShareTimeline(shareData);
	wx.onMenuShareAppMessage(shareData);
	wx.onMenuShareQQ(shareData);
	wx.onMenuShareWeibo(shareData);
	wx.onMenuShareQZone(shareData);
});