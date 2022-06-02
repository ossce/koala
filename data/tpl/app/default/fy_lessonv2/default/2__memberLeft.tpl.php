<?php defined('IN_IA') or exit('Access Denied');?><div class="memberLeft">
	<div class="avator-wapper">
		<div class="avator-mode">
			<img class="avator-img" src="<?php  echo $_SESSION['fy_lessonv2_'.$uniacid.'_avatar'];?>" width="92" height="92">
			<div class="update-avator" style="bottom: -30px;">
				<p><a href="/<?php  echo $uniacid;?>/memberInfo.html" class="js-avator-link">更换头像</a></p>
			</div>
		</div>
		<div class="des-mode">
			<p><?php  echo $_SESSION['fy_lessonv2_'.$uniacid.'_nickname'];?></p>
			<span>
				<?php  if($is_vip) { ?>
				<a href="/<?php  echo $uniacid;?>/myvip.html">
					<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/self-icon-vip-ident-actived.png" class="vip-crown">
				</a>
				<?php  } else { ?>
				<a href="/<?php  echo $uniacid;?>/vip.html">
					<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/self-icon-vip-ident.png" class="vip-crown">
				</a>
				<?php  } ?>
				<?php echo $common['self_page']['studentno'] ? $common['self_page']['studentno'] : '学号';?>：<?php  echo $_SESSION['fy_lessonv2_'.$uniacid.'_uid'];?>
			</span>
		</div>
	</div>

	<div id="memberLeft-menu" class="menu_list">
		<h3 class="menu_head current">常用菜单</h3>
		<div class="menu_body <?php  if(in_array($_GPC['do'], $often_menu)) { ?>dsblock<?php  } else { ?>hide<?php  } ?>">
			<?php  if(in_array('signin',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='signin') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/signin.html">签到</a>
			<?php  } ?>
			<?php  if(in_array('coupon',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='coupon') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/coupon.html"><?php echo $common['self_page']['coupon'] ? $common['self_page']['coupon'] : '优惠券';?></a>
			<?php  } ?>
				<a class="<?php  if($_GPC['do']=='mylesson' || $_GPC['do']=='orderDetails') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/mylesson.html">课程订单</a>
			<?php  if(in_array('vip',$self_item) || $is_vip) { ?>
				<a class="<?php  if($_GPC['do']=='myvip') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/myvip.html"><?php echo $common['self_page']['vip'] ? $common['self_page']['vip'] : 'VIP服务';?></a>
			<?php  } ?>
			<?php  if(in_array('lessonCard',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='lessoncard') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/lessoncard.html"><?php echo $common['self_page']['lessonCard'] ? $common['self_page']['lessonCard'] : '兑换课程';?></a>
			<?php  } ?>
			<?php  if(in_array('myteacher',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='myteacher') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/myteacher.html"><?php echo $common['self_page']['myteacher'] ? $common['self_page']['myteacher'] : '讲师服务';?></a>
			<?php  } ?>
			<?php  if(in_array('reclesson',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='reclesson') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/reclesson.html"><?php echo $common['self_page']['reclesson'] ? $common['self_page']['reclesson'] : '课程邀请';?></a>
			<?php  } ?>
			<?php  if(in_array('studyDuration',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='studyduration') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/studyduration.html"><?php echo $common['self_page']['studyDuration'] ? $common['self_page']['studyDuration'] : '学习时长';?></a>
			<?php  } ?>
			<?php  if(in_array('history',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='history') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/history.html"><?php echo $common['self_page']['history'] ? $common['self_page']['history'] : '学习记录';?></a>
			<?php  } ?>
			<?php  if(in_array('college',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='collect') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/collect.html?type=1"><?php echo $common['self_page']['collect'] ? $common['self_page']['collect'] : '我的收藏';?></a>
			<?php  } ?>
			<?php  if(in_array('suggest',$self_item)) { ?>
				<a class="<?php  if($_GPC['do']=='suggest') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/suggest.html"><?php echo $common['self_page']['suggest'] ? $common['self_page']['suggest'] : '投诉建议';?></a>
			<?php  } ?>
		</div>

		<?php  if(in_array('teachercenter',$self_item) || $global_teacher_id) { ?>
		<h3 class="menu_head">讲师中心</h3>
		<div class="menu_body <?php  if(in_array($_GPC['do'], $teacher_menu)) { ?>dsblock<?php  } else { ?>hide<?php  } ?>">
			<?php  if($global_teacher_id) { ?>
				<a class="<?php  if($_GPC['do']=='teacher') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $global_teacher_id;?>" target="_blank"><?php echo $teacher_page['mylesson'] ? $teacher_page['mylesson'] : '我的主页';?></a>
				<a class="<?php  if($_GPC['do']=='teacherIncome') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/teacherIncome.html"><?php echo $teacher_page['incomeLog'] ? $teacher_page['incomeLog'] : '讲师收入';?></a>
				<a class="<?php  if($_GPC['do']=='lessonCashLog') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/lessonCashLog.html"><?php echo $teacher_page['cashLog'] ? $teacher_page['cashLog'] : '提现记录';?></a>
				<a class="<?php  if($_GPC['do']=='teacherAccount') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/teacherAccount.html"><?php echo $teacher_page['account'] ? $teacher_page['account'] : '讲师帐号';?></a>
				<?php  if($teacher_platform['url']) { ?>
				<a href="<?php  echo $teacher_platform['url'];?>" target="_blank"><?php echo $teacher_platform['name'] ? $teacher_platform['name'] : '讲师平台';?></a>
				<?php  } ?>
			<?php  } else { ?>
				<a class="<?php  if($_GPC['do']=='applyTeacher') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/applyTeacher.html">申请入驻</a>
			<?php  } ?>
		</div>
		<?php  } ?>

		<?php  if(in_array('sale',$self_item) || ($comsetting['is_sale']==1 && (($comsetting['sale_rank']==1) || ($comsetting['sale_rank']==2 && $is_vip)))) { ?>
		<h3 class="menu_head">分销中心</h3>
		<div class="menu_body <?php  if(in_array($_GPC['do'], $commission_menu)) { ?>dsblock<?php  } else { ?>hide<?php  } ?>">
			<a class="<?php  if($_GPC['do']=='myTeam') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/myTeam.html"><?php echo $salefont['my_team'] ? $salefont['my_team'] : '我的团队';?></a>
			<a class="<?php  if($_GPC['do']=='commissionLog') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/commissionLog.html"><?php echo $salefont['commission_log'] ? $salefont['commission_log'] : '佣金明细';?></a>
			<a class="<?php  if($_GPC['do']=='cashLog') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/cashLog.html"><?php echo $salefont['cash_log'] ? $salefont['cash_log'] : '提现明细';?></a>
			<a class="<?php  if($_GPC['do']=='qrcode' || $_GPC['do']=='qrcodeRec') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/<?php echo $setting['poster_type']==1 ? 'qrcode' : 'qrcodeRec';?>.html"><?php echo $salefont['poster'] ? $salefont['poster'] : '推广海报';?></a>
		</div>
		<?php  } ?>

		<h3 class="menu_head">帐号管理</h3>
		<div class="menu_body <?php  if(in_array($_GPC['do'], $account_menu)) { ?>dsblock<?php  } else { ?>hide<?php  } ?>">
			<a class="<?php  if($_GPC['do']=='memberInfo') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/memberInfo.html">个人信息</a>
			<a class="<?php  if($_GPC['do']=='account') { ?>actived<?php  } ?>" href="/<?php  echo $uniacid;?>/account.html">帐号安全</a>
		</div>

		<?php  if($self_navigation) { ?>
		<h3 class="menu_head">其他菜单</h3>
		<div class="menu_body hide">
			<?php  if(is_array($self_navigation)) { foreach($self_navigation as $item) { ?>
			<a href="<?php  echo $item['url_link'];?>" target="_blank"><?php  echo $item['nav_name'];?></a>
			<?php  } } ?>
		</div>
		<?php  } ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#memberLeft-menu h3.menu_head').click(function(){
			$(this).addClass('current').next('div.menu_body').slideToggle(300).siblings('div.menu_body').slideUp('slow');
			$(this).siblings().removeClass('current');
		});

		$('.avator-mode').hover(function(){
			$('.update-avator').css('bottom', '0px');
		},function(){
			$('.update-avator').css('bottom', '-30px');
		})
	});
</script>