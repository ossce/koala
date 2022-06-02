<?php defined('IN_IA') or exit('Access Denied');?><link rel="stylesheet" type="text/css" href="<?php echo MODULE_URL;?>static/web/css/fycommon.css?v=<?php  echo $versions;?>">

<ul class="nav nav-tabs">
    <li <?php  if($_GPC['do']=='agent' || $_GPC['do']=='team') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('agent');?>">分销(用户)管理</a></li>
	<li <?php  if($_GPC['do']=='commission' && $op=='commissionlog') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('commission', array('op'=>'commissionlog'));?>">分销佣金明细</a></li>
	<li <?php  if($_GPC['do']=='commission' && $op=='statis') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('commission', array('op'=>'statis'));?>">分销佣金统计</a></li>
	<li <?php  if($_GPC['do']=='commission' && $op=='level') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('commission', array('op'=>'level'));?>">分销商等级</a></li>
	<li <?php  if($_GPC['do']=='comsetting' && $op=='display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('comsetting');?>">分销设置</a></li>
	<li <?php  if($_GPC['do']=='poster') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('poster');?>">分销海报</a></li>
	<li <?php  if($_GPC['do']=='comsetting' && $op=='font') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('comsetting', array('op'=>'font'));?>">分销文字</a></li>
</ul>