<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 支付信息
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>

<?php  $this->pay($params);?>
<style type="text/css">
.mui-content{
	top: 46px;
}
.header-2{
	display: flex;
}
</style>

<?php  if($_GPC['ordertype'] == 'buygoods') { ?>
	<div class="header-2 cbox">
		<a onclick="history.go(-1)" class="ico go-back"></a>
		<div class="flex title">支付方式</div>
		<a href="./index.php?i=<?php  echo $uniacid;?>&c=entry&do=shop&m=fy_lessonv2_plugin_shop" class="ico go-index"></a>
	</div>
<?php  } else { ?>
	<div class="header-2 cbox">
		<a href="<?php  echo $this->createMobileUrl('mylesson')?>" class="ico go-back"></a>
		<div class="flex title">支付方式</div>
		<a href="<?php  echo $this->createMobileUrl('index', array('t'=>1))?>" class="ico go-index"></a>
	</div>
<?php  } ?>