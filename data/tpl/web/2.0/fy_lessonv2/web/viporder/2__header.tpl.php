<?php defined('IN_IA') or exit('Access Denied');?><link href="<?php echo MODULE_URL;?>static/web/css/fycommon.css?v=<?php  echo $versions;?>" rel="stylesheet">
<ul class="nav nav-tabs">
	<li <?php  if($op=='display' || $op=='detail') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('viporder', array('status'=>1));?>">VIP订单管理</a></li>
	<li <?php  if($op=='vipMember') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('viporder', array('op'=>'vipMember'));?>">VIP会员管理</a></li>
	<li <?php  if($op=='vipservice' || $op=='vipLevel') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('viporder', array('op'=>'vipservice'));?>">VIP等级服务设置</a></li>
	<li <?php  if($op=='vipcard' || $op=='addVipCode') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('viporder',array('op'=>'vipcard'));?>">VIP服务卡</a></li>
	<li <?php  if($op=='createOrder') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('viporder',array('op'=>'createOrder'));?>">创建会员服务</a></li>
</ul>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_loadingToast', TEMPLATE_INCLUDEPATH)) : (include template('web/_loadingToast', TEMPLATE_INCLUDEPATH));?>