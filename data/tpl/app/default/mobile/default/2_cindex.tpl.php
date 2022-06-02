<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 分销中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/cindex.css?v=<?php  echo $versions;?>" rel="stylesheet" />
<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<?php  if($setting['isfollow']>0 && $fans['follow']==0 && $userAgent) { ?>
<div class="follow_topbar" style="display:none;">
	<div class="headimg">
		<img src="<?php  echo $_W['attachurl'];?><?php  echo $setting['qrcode'];?>">
	</div>
	<div class="info">
		<div class="i"><?php  echo $_W['account']['name'];?></div>
		<div class="i"><?php  echo $setting['follow_word'];?></div>
	</div>
	<div class="sub" onclick="location.href='<?php  echo $this->createMobileUrl('follow');?>'">立即关注</div>
</div>
<?php  } ?>

<section class="com-scrollView">
	<div class="com-flex-box com-panel">
		<a href="javascript:void(0);" class="com-panel-cell">
			<div class="com-panel-cell-hd">
				<img src="<?php  echo $avatar;?>" alt="头像">
			</div>
			<div class="com-panel-cell-bd">
				<h4><?php  echo $member['nickname'];?></h4>
				<p>加入时间：<?php  echo date('Y-m-d', $member['addtime']);?></p>
			</div>
			<div class="com-panel-cell-fr btn-level-desc"><?php  echo $levelname;?> <?php  if($level_desc) { ?><i class="fa fa-info-circle"></i><?php  } ?></div>
		</a>
	</div>
	<?php  if($lately_cashlog) { ?>
	<div class="article_div flex0_1">
		<i class="flex_g0 share_cash_title"><?php echo $font['lately_cash'] ? $font['lately_cash'] : '最近提现';?></i>
		<ul class="article_div_list flex_all">
			<?php  if(is_array($lately_cashlog)) { foreach($lately_cashlog as $item) { ?>
			<li>
				<a href="javascript:;">
					<?php  echo $item['log'];?> <strong class="red-color"><?php  echo $item['cash_num'];?>元</strong>
				</a>
			</li>
			<?php  } } ?>
		</ul>
	</div>
	<?php  } ?>
	<div class="com-flex-box">
		<div class="com-flex-box-bd">
			<h2><?php echo $font['nopay_commission'] ? $font['nopay_commission'] : '可提现佣金';?>(元)</h2>
			<h3><?php  echo $member['nopay_commission'];?></h3>
			<?php  if($latelyIncome['change_num']) { ?>
			<p>最近收益 <em><?php  echo $latelyIncome['change_num'];?></em></p>
			<?php  } ?>
		</div>
	</div>
	<div class="divHeight"></div>
	<div class="com-icon-box">
		<a class="com-flex-box com-flex-box-title" href="<?php  echo $this->createMobileUrl('commission', array('op'=>'cash'));?>">
			<div class="com-flex-box-hd">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-cash.png?v=2" alt="我要提现">
			</div>
			<div class="com-flex-box-bd com-icon-box-title"><?php echo $font['cash'] ? $font['cash'] : '我要提现';?></div>
			<div class="com-flex-box-fr"></div>
		</a>
		<a class="com-flex-box com-flex-box-title" href="<?php  echo $this->createMobileUrl('commission', array('op'=>'cashlog'));?>">
			<div class="com-flex-box-hd">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-cash-log.png?v=2" alt="提现明细">
			</div>
			<div class="com-flex-box-bd com-icon-box-title"><?php echo $font['cash_log'] ? $font['cash_log'] : '提现明细';?></div>
			<div class="com-flex-box-fr"></div>
		</a>
		<div class="divHeight"></div>

		<a class="com-flex-box com-flex-box-title" href="<?php  echo $this->createMobileUrl('team', array('level'=>'1'));?>">
			<div class="com-flex-box-hd">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-team.png?v=2" alt="<?php echo $font['my_team'] ? $font['my_team'] : '我的团队';?>">
			</div>
			<div class="com-flex-box-bd com-icon-box-title"><?php echo $font['my_team'] ? $font['my_team'] : '我的团队';?></div>
			<div class="com-flex-box-fr"><?php  echo $total;?>个成员</div>
		</a>
		<a class="com-flex-box com-flex-box-title" href="<?php  echo $this->createMobileUrl('commission', array('op'=>'commissionlog'));?>">
			<div class="com-flex-box-hd">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-commission.png?v=2" alt="<?php echo $font['commission_log'] ? $font['commission_log'] : '佣金明细';?>">
			</div>
			<div class="com-flex-box-bd com-icon-box-title"><?php echo $font['commission_log'] ? $font['commission_log'] : '佣金明细';?></div>
			<div class="com-flex-box-fr"></div>
		</a>
		<a class="com-flex-box com-flex-box-title" href="<?php  echo $posterUrl;?>">
			<div class="com-flex-box-hd">
				<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/ico-qrcode.png?v=2" alt="<?php echo $font['poster'] ? $font['poster'] : '推广海报';?>">
			</div>
			<div class="com-flex-box-bd com-icon-box-title"><?php echo $font['poster'] ? $font['poster'] : '推广海报';?></div>
			<div class="com-flex-box-fr"></div>
		</a>
	</div>
</section>

<!-- 分销等级说明 -->
<div class="privacy_agreement_notice-mask" style="display: none;">
	<div class="privacy_agreement_notice">
		<div class="close close-agreement">
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/btn-close.png" width="32" height="32">
		</div>
		<h3 class="notice-title"><?php  echo $level_desc['0'];?></h3>
		<ul class="notice-body">
			<?php  if(is_array($level_desc)) { foreach($level_desc as $key => $item) { ?>
				<?php  if($key>0) { ?>
					<p class="sale-content"><?php  echo $item;?></p>
				<?php  } ?>
			<?php  } } ?>
		</ul>
	</div>
</div>

<script type="text/javascript">
	window.config = {
		level_desc: <?php echo $level_desc ? 1 : 0?>,
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/cindex.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>