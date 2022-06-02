<?php defined('IN_IA') or exit('Access Denied');?><?php  define('MUI', true);?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
	<?php  if(strexists($msg, 'SQL Error:')) { ?>
	<div class="mui-content-padded">
		<div class="mui-message">
			<div class="mui-message-icon">
				<span class="mui-msg-error"></span>
			</div>
			<h4 class="title">系统错误</h4>
			<div class="mui-desc" style="color:#929292;">请及时联系管理员</div>
			<div class="mui-desc" style="color:#929292; margin-top:10px; text-align:left;"><?php  echo $msg;?></div>
			<div class="mui-button-area">
				<a href="javascript:;" onclick="history.go(-1);" class="mui-btn mui-btn-success mui-btn-block">确定</a>
			</div>
		</div>
	</div>
	<?php  } else { ?>
	<script type="text/javascript">
		util.message('<?php  echo $msg;?>', '<?php  echo $redirect;?>', '<?php  echo $type;?>');
	</script>
	<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
