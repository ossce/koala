<?php defined('IN_IA') or exit('Access Denied');?><link href="<?php echo MODULE_URL;?>static/web/css/fycommon.css?v=<?php  echo $versions;?>" rel="stylesheet">

<ul class="nav nav-tabs">
	<li <?php  if($op=='display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson');?>">课程列表</a></li>
	
	<?php  if($op=='postlesson') { ?>
	<li <?php  if($op=='postlesson') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'postlesson','id'=>$_GPC['pid']));?>"><?php  if($_GPC['id']>0) { ?>编辑<?php  } else { ?>添加<?php  } ?>课程</a></li>
	<?php  } ?>

	<li <?php  if($op=='auditsection') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'auditsection'));?>">待审核章节</a></li>

	<?php  if($op=='postsection') { ?>
	<li class="active"><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'postsection','pid'=>$_GPC['pid'],'id'=>$_GPC['id']));?>"><?php  if($_GPC['id']>0) { ?>编辑<?php  } else { ?>添加<?php  } ?>章节</a></li>
	<?php  } ?>

	<?php  if($op=='viewsection') { ?>
	<li class="active"><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'viewsection', 'pid'=>$_GPC['pid']));?>">章节列表</a></li>
	<?php  } ?>

	<?php  if($op=='sectionTitle' || $op=='postSectionTitle') { ?>
	<li class="active"><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'sectionTitle', 'pid'=>$_GPC['pid']));?>">课程目录</a></li>
	<?php  } ?>

	<?php  if($op=='document' || $op=='postDocument') { ?>
	<li class="active"><a href="">课件资料</a></li>
	<?php  } ?>

	<?php  if($op=='previewVideo') { ?>
	<li class="active"><a href="javascript:;">预览章节</a></li>
	<?php  } ?>

	<li <?php  if($op=='attribute') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'attribute'));?>">课程属性</a></li>
	<li <?php  if($op=='qrcodeList' || $op=='editQrcode') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'qrcodeList'));?>">课程海报</a></li>
	<li <?php  if($op=='inform' || $op=='informStudent') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'inform'));?>">开课提醒</a></li>
	<li <?php  if($op=='updomain') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'updomain'));?>">更新音视频域名</a></li>
</ul>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_loadingToast', TEMPLATE_INCLUDEPATH)) : (include template('web/_loadingToast', TEMPLATE_INCLUDEPATH));?>