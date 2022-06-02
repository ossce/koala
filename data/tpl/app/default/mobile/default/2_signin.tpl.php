<?php defined('IN_IA') or exit('Access Denied');?><!--
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/signin.css?v=<?php  echo $versions;?>" rel="stylesheet"/>

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12 clearPadding">
			<div class="sign-head flex flex-align-end flex-pack-center flex-warp">
				<?php  if($uid==1 || $uid==2 || $uid==1083) { ?>
				<div class="btn-poster">
					签到海报
				</div>
				<?php  } ?>
				<div class="sign-wrap flex flex-align-center flex-pack-center" id="signIn">
					<div class="sign-inner flex flex-align-center flex-pack-center">
						<div class="signBtn">
							<strong id="sign-txt"><?php echo $today_log ? '已签到' : '签到';?></strong>
							<span>连续<?php  echo $sign_count;?>天</span>
						</div>
					</div>
				</div>
				<div class="sign-tips"><?php  if($today_log) { ?>今天已签到，获得<?php  echo $today_log['award'];?>个积分<?php  } ?></div>
			</div>
			<div class="calendar">
				<div class="calenbox">
					<div id="calendar"></div>
				</div>
			</div>
			<div class="bg-fff mt5 pt20 pb20">
				<div class="explain">
					<div class="title"><span>签到规则</span></div>
				</div>
				<div class="explain-desc">
					<ul>
						<li>每日签到一次，连续签到奖励更多</li>
						<li><?php  echo $rule;?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/calendar.js?v=<?php  echo $versions;?>"></script>
<script type="text/javascript">

<?php  if(!$today_log) { ?>
	$("#signIn").click(function(){
		$.ajax({
			type: "post",
			url: "<?php  echo $this->createMobileUrl('signin', array('op'=>'sign'));?>",
			dataType: "json",
			success:function(res){
				if(res.code == -1){
					if(res.message){
						$(this).openWindow('系统提示',res.message,'["确定"]','javascript:;','javascript:;');
					}
					return;
				}else if(res.code == 0){
					$(this).openWindow('系统提示',res.message,'["确定"]','javascript:;', "<?php  echo $this->createMobileUrl('signin');?>");
				}
			}, 
			error:function(){
				$(this).openWindow('系统提示','网络异常，请稍候重试','["确定"]','javascript:;','javascript:;');
			},   
		});
	});
<?php  } ?>

	$(function(){
		calUtil.init(<?php  echo $signDay;?>);
	});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>