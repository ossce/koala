<?php defined('IN_IA') or exit('Access Denied');?><?php  define('MUI', true);?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script>
	$(function(){
		$(document).on('input propertychange', '.js-mobile-val', function(){
			var mobile_value = $(this).val();
			if (mobile_value.length == '11') {
				$.post("<?php  echo url('auth/login/mobile_exist')?>", {'mobile' : mobile_value}, function(data) {
					data = $.parseJSON(data);
					if (data.message.errno == '1') {
						$('.js-check-mobile').addClass('send-code');
					} else if (data.message.errno == '2'){
						$('.js-check-mobile').removeClass('send-code');
						util.toast('手机号不存在', '', 'error');
						return;
					}
				});
			} else {
				$('.js-check-mobile').removeClass('send-code');
			}
		});
		$(document).on('click', '.login-code', function() {
			var username = $('#login-code input[name="username"]').val();
			var password = $('#login-code input[name="password"]').val();
			$.post(location.href, {'username' : username, 'password' : password, 'mode' : 'code'},function(data) {
				data = $.parseJSON(data);
				if(data.type != 'success') {
					util.toast(data.message, '', 'error');
				} else {
					util.toast('登录成功');
					location.reload();
				}
			})
		})
		$(document).on('click', '.send-code', function(){
			var username = $('#login-code input[name="username"]').val();
			option = {
				'btnElement' : $('.send-code'),
				'showElement' : $('.js-timer'),
				'btnTips' : '<a class="send-code">重新获取验证码</a>',
				'successCallback' : function(ret, message){
					if (ret == '0') {
						util.toast(message);
						$('.js-sendcode').hide();
						$('.js-codeverify').show();
					} else {
						util.toast(message);
						$('.js-sendcode').show();
						$('.js-codeverify').hide();
						return;
					}
				}
			};
			util.sendCode(username, option);
		});
	});
</script>
<div class="mui-content mc-login">
	<div class="avatar mui-text-center">
		<img src="<?php  if(tomedia('headimg_'.$_W['acid'].'.jpg')) { ?><?php  echo tomedia('headimg_'.$_W['acid'].'.jpg');?><?php  } else { ?>resource/images/MicroEngine.ico<?php  } ?>" class="mui-img-circle"/>
	</div>
	<?php  if($type == 'email') { ?>
	<?php  if(($item == 'mobile' && $ltype !== 'code') || ($item == 'email') || ($item == 'random')) { ?>
	<form action="<?php  echo url('auth/login/basic');?>" method="post" enctype="multipart/form-data" class="js-ajax-form">
	<div class="mui-control-content mui-active" id="login-basic">
		<div class="mui-input-group">
			<div class="mui-input-row">
				<label class="mui-label-icon"><i class="fa fa-user"></i></label>
				<input name ="username" type="text" placeholder="<?php  if($item == 'mobile') { ?>手机号<?php  } else if($item == 'email') { ?>邮箱<?php  } else { ?>手机号/邮箱<?php  } ?>"/>
			</div>
			<div class="mui-input-row mui-help">
				<label class="mui-label-icon"><i class="fa fa-lock"></i></label>
				<input name="password" type="password" placeholder="密码"/>
				<div class="mui-help-info mui-text-right"><a href="<?php  echo url('auth/forget', array('forward' => $_GPC['forward']));?>">忘记密码</a></div>
			</div>
		</div>
	</div>
	<div class="mui-content-padded">
		<input type="hidden" name="mode" value="basic">
		<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		<button class="mui-btn mui-btn-success mui-btn-block login-basic" type="submit" name="submit" value="提交">登录</button>
	</div>
	</form>
	<?php  } ?>
	<?php  } ?>
	<?php  if($type == 'mobile' || ($item == 'mobile' && $ltype == 'code')) { ?>
	<div class="mui-active mc-login-code" id="login-code">
		<div class="js-sendcode">
			<div class="mui-content-padded mui-text-muted">请输入手机号,以收取验证码</div>
			<div class="mui-input-group mui-mt15">
				<div class="mui-input-row">
					<label class="mui-label-icon"><i class="fa fa-user"></i></label>
					<input name="username" class="js-mobile-val" type="text" placeholder="手机号"/>
				</div>
			</div>
			<div class="mui-content-padded mui-text-center">
				<button class="mui-btn mui-btn-success mui-btn-block js-check-mobile" uniacid="<?php  echo $_W['uniacid'];?>">下一步</button>
			</div>
		</div>
		<div style="display:none;" class="js-codeverify">
			<div class="mui-content-padded mui-text-muted">您的手机号<span class="mui-text-success" ng-bind="ret.code.username"></span>会收到一条含有6位数字验证码的短信息</div>
			<div class="mui-input-group mui-mt15">
				<div class="mui-input-row">
					<label class="mui-label-icon"><i class="fa fa-key"></i></label>
					<input name="password" type="text" placeholder="验证码"/>
				</div>
			</div>
			<div class="mui-content-padded mui-text-center">
				<button class="mui-btn mui-btn-success mui-btn-block login-code" type="submit">确认</button>
				<div class="mui-mt15 mui-text-center">
					<span class="mui-text-muted js-timer">

					</span>
				</div>
			</div>
		</div>
	</div>
	<?php  } ?>
	<div class="mui-content-padded">
		<div class="mui-text-center mui-mt15">
			<?php  if($ltype != 'code' && $audit == '1') { ?>
			<a href="<?php  echo url('auth/register', array('type' => $item, 'forward' => $_GPC['forward']));?>">注册账号</a>
			<?php  } else { ?>
			<a href="<?php  echo url('auth/register', array('forward' => $_GPC['forward']));?>">注册账号</a>
			<?php  } ?>
			<?php  if($type == 'email') { ?>
			<?php  if(($item == 'mobile' && ($ltype =='hybird')) || ($item == 'random' && ($ltype !== 'password'))) { ?>
			<span class="mui-ml5 mui-mr5 mui-text-muted">|</span>
			<a href="<?php  echo url('auth/login', array('forward' => $_GPC['forward'], 'type' => 'mobile'));?>#wechat_redirect">无密码登录
			</a>
			<?php  } ?>
			<?php  } else { ?>
			<span class="mui-ml5 mui-mr5 mui-text-muted">|</span>
			<a href="<?php  echo url('auth/login', array('forward' => $_GPC['forward'], 'type' => 'email'));?>#wechat_redirect">邮箱登录
			</a>
			<?php  } ?>
		</div>
	</div>
</div>
