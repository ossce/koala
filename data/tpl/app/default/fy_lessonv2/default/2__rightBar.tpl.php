<?php defined('IN_IA') or exit('Access Denied');?><div class="right-bar">
	<?php  if(in_array('vip',$self_item)) { ?>
	<div class="top-regbox">
		<a href="/<?php  echo $uniacid;?>/vip.html" target="_blank">
			<i class="iconfont icon-crown sideBar-animate"></i>VIP特权
		</a>
	</div>
	<?php  } ?>
	<div class="btm-cont">
		<?php  if($config['service_url']) { ?>
		<div class="qrBox">
			<a href="<?php  echo $config['service_url'];?>" target="_blank">
				<i class="iconfont icon-online-service"></i>
				<span>在线客服</span>
			</a>
		</div>
		<?php  } ?>
		<?php  if($config['teacher_qq']) { ?>
		<div class="qrBox">
			<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $config['teacher_qq'];?>&site=qq&menu=yes" target="_blank">
				<i class="iconfont icon-qq"></i>
				<span>QQ客服</span>
			</a>
		</div>
		<?php  } ?>
		<?php  if($config['teacher_qqgroup']) { ?>
		<div class="qrBox">
			<a href="<?php  echo $config['teacher_qqlink'];?>" target="_blank">
				<i class="iconfont icon-qqgroup"></i>
				<span>QQ群组</span>
			</a>
		</div>
		<?php  } ?>
		<?php  if($config['teacher_qrcode']) { ?>
		<div class="qrBox wechat-service">
			<a href="javascript:;">
				<i class="iconfont icon-wechat"></i>
				<span>微信客服</span>
			</a>
			<div class="introduce">
				<div>
                    <b class="ftc-e9511b fs-13">微信扫一扫咨询客服</b>
				</div>
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $config['teacher_qrcode'];?>">
			</div>
		</div>
		<?php  } ?>
		<?php  if(in_array('teachercenter',$self_item)) { ?>
		<div class="qrBox">
			<a href="/<?php  echo $uniacid;?>/applyTeacher.html" target="_blank">
				<i class="iconfont icon-teacher"></i>
				<span>讲师申请</span>
			</a>
		</div>
		<?php  } ?>
	</div>
	<div class="btm-cont">
		<div class="qrBox">
			<a href="javaScript:;" id="backtop">
				<i class="iconfont icon-backtop"></i>
				<span>返回顶部</span>
			</a>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#backtop").on("click",function(){
		$('html, body').animate({scrollTop: 0},500);return false;
	});

	$('.wechat-service').hover(function(){
		$('.introduce').show();
	}, function(){
		$('.introduce').hide();
	});
})
</script>