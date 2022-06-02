<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 课程管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
-->

<?php  if($op=='postsection') { ?>
	<?php  if($setting['savetype']==2) { ?>
		<script src="<?php echo MODULE_URL;?>library/Qcloud/cos-js-sdk-v4/crypto.js"></script>
		<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qcloud/cos-js-sdk-v4/cos-js-sdk-v4.js"></script>
	<?php  } else if($setting['savetype']==3) { ?>
		<script src="<?php echo MODULE_URL;?>library/aliyunVod/js/es6-promise.min.js"></script>
		<script src="<?php echo MODULE_URL;?>library/aliyunVod/js/aliyun-oss-sdk-5.2.0.min.js"></script>
		<script src="<?php echo MODULE_URL;?>library/aliyunVod/js/aliyun-upload-sdk-1.4.0.min.js"></script>
	<?php  } else if($setting['savetype']==4) { ?>
		<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
	<?php  } ?>
<?php  } ?>

<?php  if($op!='previewVideo') { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/_header', TEMPLATE_INCLUDEPATH));?>
<?php  } ?>


<?php  if($operation == 'display') { ?>
<!-- 课程列表 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/display', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/display', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'postlesson') { ?>
<!-- 发布课程 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/postlesson', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/postlesson', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'auditsection') { ?>
<!-- 待审核章节 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/auditsection', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/auditsection', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'viewsection') { ?>
<!-- 章节列表 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/viewsection', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/viewsection', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'postsection') { ?>
<!-- 发布章节 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/postsection', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/postsection', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'sectionTitle') { ?>
<!-- 章节目录列表 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/sectionTitle', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/sectionTitle', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'postSectionTitle') { ?>
<!-- 发布章节目录 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/postSectionTitle', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/postSectionTitle', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($operation == 'document') { ?>
<!-- 课件资料 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/document', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/document', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='attribute') { ?>
<!-- 课程属性列表 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/attribute', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/attribute', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='addAttribute') { ?>
<!-- 添加课程属性 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/addAttribute', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/addAttribute', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='updomain') { ?>
<!-- 更新音视频域名 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/updomain', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/updomain', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='inform') { ?>
<!-- 开课提醒列表 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/inform', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/inform', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='informStudent') { ?>
<!-- 发布开课课程 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/informStudent', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/informStudent', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='previewVideo') { ?>
<!-- 预览课程音视频 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/video/no-require-header', TEMPLATE_INCLUDEPATH)) : (include template('web/video/no-require-header', TEMPLATE_INCLUDEPATH));?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/_header', TEMPLATE_INCLUDEPATH));?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/previewVideo', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/previewVideo', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='qrcodeList') { ?>
<!-- 课程海报 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/qrcodeList', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/qrcodeList', TEMPLATE_INCLUDEPATH));?>

<?php  } else if($op=='editQrcode') { ?>
<!-- 课程海报 -->
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/lesson/editQrcode', TEMPLATE_INCLUDEPATH)) : (include template('web/lesson/editQrcode', TEMPLATE_INCLUDEPATH));?>

<?php  } ?>

<?php  if($op!='previewVideo') { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<?php  } ?>