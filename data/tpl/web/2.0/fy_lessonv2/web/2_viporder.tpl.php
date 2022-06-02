<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 会员VIP服务订单管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
-->

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/_header', TEMPLATE_INCLUDEPATH));?>

<?php  if($operation == 'display') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/display', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/display', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='detail') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/detail', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/detail', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='vipMember') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/vipMember', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/vipMember', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='vipservice') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/vipservice', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/vipservice', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='vipLevel') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/vipLevel', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/vipLevel', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='createOrder') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/createOrder', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/createOrder', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='vipcard') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/vipcard', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/vipcard', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='addVipCode') { ?>

	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/viporder/addVipCode', TEMPLATE_INCLUDEPATH)) : (include template('web/viporder/addVipCode', TEMPLATE_INCLUDEPATH));?>

<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>