<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 二维码推广
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/lessonqrcode.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
	<a href="<?php  echo $this->createMobileUrl('index', array('t'=>1))?>" class="ico go-index"></a>
</div>

<div class="sharecard__entry-global" style="top:50px;" onclick="location.href='<?php  echo $this->createMobileUrl('lessonqrcode', array('lessonid'=>$lessonid,'op'=>delete));?>'">
	<div class="sharecard__entry">
		<div class="sharecard__entry-icon">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/poster-refresh.png?v=1" />
		</div>
		更新海报
	</div>
</div>

<?php  if(empty($order) && $lesson['recommend_free_num']) { ?>
<div class="sharecard__entry-global btn-poster-rule" style="top:90px;">
	<div class="sharecard__entry">
		<div class="sharecard__entry-icon">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/poster-free-rule.png?v=1" />
		</div>
		免费学习规则
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
					<img src="<?php  echo $item['poster_bg'];?>">
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

<div class="act-group-notice-mask" style="display:none;">
	<div class="act-group-notice">
		<div class="close">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-close.png?v=1" width="22" height="22">
		</div>
		<h3 class="notice-title">活动规则</h3>
		<ul class="notice-body">
			<li class="notice-item">
				<p class="notice-sub-title">
					<i class="fa fa-caret-right"></i> 长按图片发送给好友，邀请好友一起进来学习。
				</p>
			</li>
			<li class="notice-item">
				<p class="notice-sub-title">
					<i class="fa fa-caret-right"></i> 通过课程海报成功邀请第一位好友时，表示您正式参加邀请好友享受免费学习该课程活动。
				</p>
			</li>
			<li class="notice-item">
				<p class="notice-sub-title">
					<i class="fa fa-caret-right"></i> 您需要在<span class="notice-number"><?php  echo $lesson['recommend_free_limit'];?></span>天内成功邀请<span class="notice-number"><?php  echo $lesson['recommend_free_num'];?></span>位好友即可获得免费学习该课程<span class="notice-number"><?php  echo $lesson['recommend_free_day'];?></span>天的奖励。
				</p>
			</li>
			<li class="notice-item">
				<p class="notice-sub-title">
					<i class="fa fa-caret-right"></i> 通过课程海报成功邀请第一位好友开始，<span class="notice-number"><?php  echo $lesson['recommend_free_limit'];?></span>天内未完成邀请好友任务，即表示邀请任务失败。任务失败后，已邀请的好友人数作废，您可以继续邀请好友重新创建邀请任务。
				</p>
			</li>
			<li class="notice-item">
				<p class="notice-sub-title">
					<i class="fa fa-caret-right"></i> 您可以点击<a href="<?php  echo $this->createMobileUrl('reclesson');?>" class="my-invite">我的邀请活动</a>查看您的任务跳转状态进度。
				</p>
			</li>
		</ul>
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
		var poster_height = clientH - 197 + title_H;
	}
	$("#spreadImg").css('height', poster_height + 'px');
})

function cutPoster(poster_no){
	$("#loadingToast").show();
	var url = "<?php  echo $this->createMobileUrl('lessonqrcode',array('lessonid'=>$lessonid));?>";
	window.location.href = url + "&poster_no=" + poster_no;
}

$(".btn-poster-rule").click(function(){
	$('.act-group-notice-mask').fadeIn(200).unbind('click').click(function(){
		$(this).fadeOut(100);
	})
})

wx.ready(function(){
	var shareData = {
		title: "<?php  echo $sharelesson['title'];?>",
		desc: "<?php  echo $sharelesson['desc'];?>",
		link: "<?php  echo $sharelesson['link'];?>",
		imgUrl: "<?php  echo $_W['attachurl'];?><?php  echo $sharelesson['images'];?>",
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