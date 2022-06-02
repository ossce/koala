<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 商城首页
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 -->

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<?php  if(!empty($diy_data)) { ?>
	<?php  if(is_array($diy_data)) { foreach($diy_data as $key => $diy) { ?>
		<?php  if($diy['name']!='footnav') { ?>
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('diy/'.$diy['name'], TEMPLATE_INCLUDEPATH)) : (include template('diy/'.$diy['name'], TEMPLATE_INCLUDEPATH));?>
		<?php  } ?>
	<?php  } } ?>
<?php  } else { ?>
	<div class="mui-backdrop">
		<div class="mui-content-padded">
			<div class="mui-message">
				<div class="mui-message-icon">
					<span class="mui-msg-warning"></span>
				</div>
				<h4 class="title">请先到后台“基本设置 >> 页面模板”里创建首页模板，并设置为默认模板</h4>
			</div>
		</div>
	</div>
	
<?php  } ?>

<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/shop.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>