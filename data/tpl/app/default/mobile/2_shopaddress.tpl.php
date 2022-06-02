<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 收货地址
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
<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/mobile/css/shop-address.css?v=<?php  echo $versions;?>">

<?php  if($op == 'display') { ?>
	<div class="address_list">
		<?php  if($list) { ?>
			<?php  if(is_array($list)) { foreach($list as $item) { ?>
			<div class="address" data-id="<?php  echo $item['id'];?>">
				<ul class="<?php  if($_GPC['returnurl']) { ?>ready_select <?php  if($_GPC['address_id']==$item['id']) { ?>selected<?php  } ?><?php  } else { ?>nochoose<?php  } ?>">
					<div class="select-area">
						<i class="icon-select"></i>
						<li>
							<strong><?php  echo $item['realname'];?></strong>
							&nbsp;
							<strong><?php  echo $item['mobile'];?></strong>
						</li>
						<li>
							<?php  if($item['isdefault']) { ?>
								<span class="tag tag_red">默认</span>
							<?php  } ?>
							<?php  echo $item['province'];?><?php  echo $item['city'];?><?php  echo $item['area'];?><?php  echo $item['address'];?>
						</li>
					</div>
					<li class="edit btn-edit">编辑</li>
				</ul>
				<p class="act act_left"><span class="del btn-setting" data-type="default">设为默认</span></p>
				<p class="act"><span class="del btn-setting" data-type="delete">删除</span></p>
			</div>
			<?php  } } ?>
		<?php  } else { ?>
			<div class="my_empty">
				<div class="empty_bd my_course_empty">
					<p>您的收货地址竟然是空的</p>
				</div>
			</div>
		<?php  } ?>
	</div>

	<div class="btn-address-wrap fixed">
		<a href="<?php  echo $this->createMobileUrl('shopaddress', array('op'=>'editAddress','returnurl'=>$_GPC['returnurl']))?>" class="btn_address">新增收货地址</a>
	</div>

<?php  } else if($op=='editAddress') { ?>
	<form method="post" id="addressForm">
		<div class="bg-fff">
			<div class="shop-address-cell-item">
				<div class="shop-cell-name">收货人</div>
				<div class="shop-cell-input">
					<input type="text" name="realname" value="<?php  echo $address['realname'];?>" class="cell-input" placeholder="请填写收货人姓名" autocomplete="off">
				</div>
			</div>
			<div class="shop-address-cell-item">
				<div class="shop-cell-name">手机号码</div>
				<div class="shop-cell-input">
					<input type="tel" name="mobile" value="<?php  echo $address['mobile'];?>" class="cell-input" placeholder="请填写收货人手机号码" maxlength="11" autocomplete="off">
				</div>
			</div>
			<div class="shop-address-cell-item">
				<div class="shop-cell-name">所在地区</div>
				<div class="shop-cell-input cell-input-text">
					<input type="text" class="cell-input" value="<?php  echo $address['province'];?> <?php  echo $address['city'];?> <?php  echo $address['area'];?>" id="Region" placeholder="请选择所在地区" readonly>
				</div>
			</div>
			<div class="shop-address-cell-item">
				<div class="shop-cell-name">详细地址</div>
				<div class="shop-cell-input">
					<input type="text" name="address" value="<?php  echo $address['address'];?>" class="cell-input" placeholder="详细地址需填写楼栋或房间号" autocomplete="off">
				</div>
			</div>
			<div class="weui-cells_checkbox">
				<label class="weui-cell weui-cell_active weui-check__label" for="isdefault">
					<div class="weui-cell__hd">
						<input type="checkbox" class="weui-check" name="isdefault" value="<?php  echo $address['isdefault'];?>" id="isdefault" <?php  if($address['isdefault']) { ?>checked<?php  } ?> />
						<i class="weui-icon-checked"></i>
					</div>
					<div class="weui-cell__bd">
						<p>设置默认地址</p>
					</div>
				</label>
			</div>
			<div class="btn-address-wrap">
				<input type="hidden" value="<?php  echo $id;?>" id="address_id" />
				<a href="javascript:;" class="btn_address" id="btn-submit">保存</a>
			</div>
			<div class="h-10"></div>
		</div>
	</form>

	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/address/select-address.js?v=<?php  echo $versions;?>"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/public/address/district.data.min.js?v=<?php  echo $versions;?>"></script>
<?php  } ?>

<script type="text/javascript">
	window.config = {
		  uniacid: "<?php  echo $uniacid;?>",
		      uid: "<?php  echo $uid;?>",
		operation: "<?php  echo $op;?>",
		  editUrl: "<?php  echo $this->createMobileUrl('shopaddress',array('op'=>'editAddress'))?>",
		  operUrl: "<?php  echo $this->createMobileUrl('shopaddress',array('op'=>'operAddress'))?>",
		returnurl: "<?php  echo $_GPC['returnurl'];?>",
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/base64.js?v=<?php  echo $versions;?>"></script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/js/shopaddress.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>