<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 个人中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/self.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<?php  if(!empty($page_data)) { ?>
	<?php  if(is_array($page_data)) { foreach($page_data as $key => $diy) { ?>
		<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/diy/'.$diy['name'], TEMPLATE_INCLUDEPATH)) : (include template($template.'/diy/'.$diy['name'], TEMPLATE_INCLUDEPATH));?>
	<?php  } } ?>
<?php  } else { ?>
	<div class="mine_head" style="background-image:url(<?php  echo $ucenter_bg;?>);">
		<?php  if(in_array('setting',$self_item)) { ?>
		<a class="aui-member-setting" href="<?php  echo url('mc/profile')?>">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-setting.png?v=2">
		</a>
		<?php  } ?>
		<div class="mine_head_body">
			<div class="tx get-memberInfo">
				<img src="<?php  echo $avatar;?>" alt="会员头像" />
			</div>
			<div class="nickname get-memberInfo">
				<?php  if($memberinfo['nickname']) { ?>
					<?php  echo $memberinfo['nickname'];?>
				<?php  } else { ?>
					<?php echo $userAgent ? '点击同步昵称' : '未设置';?>
				<?php  } ?>
			</div>
			<div class="idno">
				<?php  if($memberVipCount || in_array('vip',$self_item)) { ?>
				<a href="<?php  echo $this->createMobileUrl('vip')?>">
					<?php  if($memberVipCount) { ?>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-vip-ident-actived.png" class="vip_crown">
					<?php  } else { ?>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-vip-ident.png" class="vip_crown">
					<?php  } ?>
				</a>
				<?php  } ?>
				<?php echo $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';?>：<?php  echo $memberid;?>
			</div>
		</div>
		<?php  if(in_array('signin',$self_item)) { ?>
		<div class="signin-button">
			<a href="<?php  echo $this->createMobileUrl('signin');?>"><i class="icon icon-pic"></i><?php echo $signin_log ? '已签到' : '签到';?></a>
		</div>
		<?php  } ?>
	</div>

	<?php  if(in_array('credit',$self_item)) { ?>
	<div class="mine_info_grid flex0 ios-system">
		<a href="<?php  echo $this->createMobileUrl('credit', array('type'=>1))?>" class="mine_info flex-al1">
			<div class="num"><?php  echo $memberinfo['credit1'];?></div>
			<span><?php echo $common['self_page']['credit1'] ? $common['self_page']['credit1'] : '会员积分';?></span>
		</a>
		<a href="<?php  echo $this->createMobileUrl('credit', array('type'=>2))?>" class="mine_info flex-al1">
			<div class="num"><?php  echo $memberinfo['credit2'];?></div>
			<span><?php echo $common['self_page']['credit2'] ? $common['self_page']['credit2'] : '会员余额';?></span>
		</a>
		<?php  if(in_array('coupon',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('coupon');?>" class="mine_info flex-al1">
			<div class="num"><?php  echo $coupon_count;?></div>
			<span><?php echo $common['self_page']['coupon'] ? $common['self_page']['coupon'] : '优惠券';?></span>
		</a>
		<?php  } ?>
	</div>
	<?php  } ?>

	<?php  if(in_array('college',$self_item) || in_array('history',$self_item) || in_array('vip',$self_item) || in_array('order',$self_item)) { ?>
	<div class="mine_menu_grid li4 flex0_1">
		<?php  if(in_array('college',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('collect',array('ctype'=>1))?>" class="mine_menu">
			<div class="img">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-collect.png" />
			</div>
			<div><?php echo $common['self_page']['collect'] ? $common['self_page']['collect'] : '收藏';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('history',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('history')?>" class="mine_menu">
			<div class="img">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-record.png" />
			</div>
			<div><?php echo $common['self_page']['history'] ? $common['self_page']['history'] : '学习记录';?></div>
		</a>
		<?php  } ?>
		<?php  if($memberVipCount || in_array('vip',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('vip')?>" class="mine_menu">
				<div class="img">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-vip-crown.png?v=1" />
			</div>
			<div><?php echo $common['self_page']['vip'] ? $common['self_page']['vip'] : 'VIP服务';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('order',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('mylesson')?>" class="mine_menu">
			<div class="img">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-order.png?v=9" />
			</div>
			<div><?php echo $common['self_page']['myorder'] ? $common['self_page']['myorder'] : '课程订单';?></div>
		</a>
		<?php  } ?>
	</div>
	<?php  } ?>

	<?php  if(!empty($self_adv)) { ?>
	<div class="mine_adv">
		<a href="<?php  echo $self_adv['link'];?>">
			<img src="<?php  echo $_W['attachurl'];?><?php  echo $self_adv['picture'];?>" class="adv_img">
		</a>
	</div>
	<?php  } ?>

	<div class="mine_unit">
		<?php  if(in_array('subscribe',$self_item)) { ?>
		<a class="line-grid flex1" href="javascript:;" id="subscribe-btn">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-subscribe.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1">订阅消息</div>
			<i class="fa <?php echo $is_subscribe ? 'fa-toggle-on' : 'fa-toggle-off'?>" id="subscribe-status" data-subscribe="<?php  echo $is_subscribe;?>"></i>
		</a>
		<?php  } ?>
		<?php  if(in_array('myexamine',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('myexamine');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-my-examine.png" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['myexamine'] ? $common['self_page']['myexamine'] : '我的考试';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('myteacher',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('myteacher');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-teacher-buy.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['myteacher'] ? $common['self_page']['myteacher'] : '讲师服务';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('reclesson',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('reclesson');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-lesson-invite.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['reclesson'] ? $common['self_page']['reclesson'] : '课程邀请';?></div>
		</a>
		<?php  } ?>
	</div>

	<div class="mine_unit">
		<?php  if(in_array('teachercenter',$self_item) || !empty($teacher)) { ?>
		<a href="<?php  echo $this->createMobileUrl('teachercenter');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-collect-teachercenter.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['teacherCenter'] ? $common['self_page']['teacherCenter'] : '讲师中心';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('lessonCard',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('lessoncard');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-lesson-card.png" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['lessonCard'] ? $common['self_page']['lessonCard'] : '兑换课程';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('sale',$self_item) || ($comsetting['is_sale']==1 && (($comsetting['sale_rank']==1) || ($comsetting['sale_rank']==2 && $memberVipCount)))) { ?>
		<a href="<?php  echo $this->createMobileUrl('commission');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-commission.png?v=6" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $font['sale_center'] ? $font['sale_center']:'分销中心';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('studyDuration',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('studyduration');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-study-duration.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['studyDuration'] ? $common['self_page']['studyDuration'] : '学习时长';?></div>
		</a>
		<?php  } ?>
		<?php  if(in_array('coupon',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('coupon');?>" class="line-grid icon_right flex1 ios-system">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-coupon.png?v=6" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['coupon'] ? $common['self_page']['coupon'] : '优惠券';?></div>
		</a>
		<?php  } ?>
		<?php  if($company_teachers) { ?>
		<a href="<?php  echo $this->createMobileUrl('company');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-company.png?v=2" style="width: 16px;" />
			</div>
			<div class="flex-al1">机构中心</div>
		</a>
		<?php  } ?>
	</div>

	<div class="mine_unit">
		<?php  if(in_array('mobile',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('modifyMobile');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-modify-mobile.png?v=9" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $memberinfo['mobile'] ? '修改':'绑定';?>手机</div>
		</a>
		<?php  } ?>
		<?php  if(in_array('suggest',$self_item)) { ?>
		<a href="<?php  echo $this->createMobileUrl('suggest');?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/self-icon-suggest.png" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php echo $common['self_page']['suggest'] ? $common['self_page']['suggest'] : '投诉建议';?></div>
		</a>
		<?php  } ?>
	</div>

	<?php  if(!empty($self_menu)) { ?>
	<div class="mine_unit">
		<?php  if(is_array($self_menu)) { foreach($self_menu as $item) { ?>
		<a href="<?php  echo $item['url_link'];?>" class="line-grid icon_right flex1">
			<div class="img flex0">
				<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['unselected_icon'];?>" style="width: 16px;" />
			</div>
			<div class="flex-al1"><?php  echo $item['nav_name'];?></div>
		</a>
		<?php  } } ?>
	</div>
	<?php  } ?>
<?php  } ?>

<?php  if(!$userAgent) { ?>
<div class="logout">
	<a href="<?php  echo $this->createMobileUrl('logout')?>">退出登录</a>
</div>
<?php  } ?>

<script type="text/javascript">
$(".get-memberInfo").click(function(){
	var agent = <?php  echo intval($userAgent); ?>;
	if(!agent){
		showSingleDialog("请在微信里点击此处同步");
		return;
	}
	var sureUrl = "<?php  echo $this->createMobileUrl('self', array('upInfo'=>1));?>";
	$(this).openWindow('系统提示','同步微信昵称头像?','["取消","确定"]','javascript:;', sureUrl);
});

$("#subscribe-btn").click(function(){
	var subscribe = $("#subscribe-status").data("subscribe");
	var new_subscribe = subscribe ? 0 : 1;
	
	$.ajax({
		url: "<?php  echo $this->createMobileUrl('subscribemsg')?>",
		type: "POST",
		data:{
			subscribe: new_subscribe,
		},
		dataType: "json",
		success: function(res){
			if(res.code==0){
				$("#subscribe-status").data("subscribe", new_subscribe);
				if(new_subscribe){
					document.getElementById("subscribe-status").className = "fa fa-toggle-on";
				}else{
					document.getElementById("subscribe-status").className = "fa fa-toggle-off";
				}
			}
			$(this).openWindow('系统提示',res.msg,'["确定"]','javascript:;','javascript:;');
			return false;
		},
		error:function(err){
			$(this).openWindow('系统提示','网络请求出错，请稍候重试','["确定"]','javascript:;','javascript:;');
			console.log(err);
			return false;
		}
	});
});

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	var miniprogram_environment = false;
	wx.miniProgram.getEnv(function(res) {
		if(res.miniprogram) {
			miniprogram_environment = true;
		}
	})
	if(window.__wxjs_environment === 'miniprogram' || miniprogram_environment) {
		wx.miniProgram.postMessage({ 
			data: {
				'title': "<?php  echo $title;?>",
				'images': "<?php  echo $ucenter_bg;?>",
			}
		})
		$("#mc-profile").hide();
	}
});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>