<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 个人中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/vip.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>


<?php  if($op=='display') { ?>
	<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/qunService.css?v=<?php  echo $versions;?>" rel="stylesheet" />
	<div>
		<div class="myvip-bg cbox disabled">
			<div class="vip-back">
				<img src="<?php  echo $vip_bg;?>">
			</div>
			<?php  if($virtual_buyinfo) { ?>
				<div class="newbuy_wrap">
					<div class="newbuy_info">
						<div class="newbuy_info_item"></div>
					</div>
				</div>
			<?php  } ?>
		</div>

		<?php  if($commission_log) { ?>
		<div class="article_div flex0_1">
			<i class="flex_g0 share_cash_title"><?php echo $common['vip_page']['lately_commission'] ? $common['vip_page']['lately_commission'] : '推广收益'?></i>
			<ul class="article_div_list flex_all">
				<?php  if(is_array($commission_log)) { foreach($commission_log as $item) { ?>
				<li>
					<a href="javascript:;">
						<?php  echo $item['nickname'];?>获得推广佣金 <strong class="red-color"><?php  echo $item['change_num'];?>元</strong>
					</a>
				</li>
				<?php  } } ?>
			</ul>
		</div>
		<?php  } ?>

		<div class="vip-menu mb_10">
			<ul class="am-avg-sm-5 vip-title-table">
				<li class="w-col-5 active"><?php echo $common['vip_page']['myvip'] ? $common['vip_page']['myvip'] : '我的VIP'?></li>
				<li class="w-col-5"><?php echo $common['vip_page']['order_record'] ? $common['vip_page']['order_record'] : '订单记录'?></li>
				<li class="w-col-5"><?php echo $common['vip_page']['vipcard'] ? $common['vip_page']['vipcard'] : 'VIP服务卡密'?></li>
				<div class="clear"></div>
			</ul>
		</div>
		
		<!-- 我的VIP -->
		<div class="content-tab my-vip-info">
			<!-- VIP状态 -->
			<div class="vip-prompt">
				<?php  if(!empty($memberVip_list)) { ?>
				<span class="green"><?php echo $common['vip_page']['myvip'] ? $common['vip_page']['myvip'] : '我的VIP'?>:已开通</span>
				<?php  } else { ?>
				<span class="red">未开通</span>
				<?php  } ?>
			</div>
			<!-- 已开通VIP -->
			<?php  if(!empty($memberVip_list)) { ?>
			<div class="vip-list buy-vip-list">
				<ul class="myvip-list" style="height: 30px;">
					<li class="align" style="width:32%;"><?php echo $common['vip_page']['level_name'] ? $common['vip_page']['level_name'] : '等级名称'?></li>
					<li class="align" style="width:28%;"><?php echo $common['vip_page']['buy_discount'] ? $common['vip_page']['buy_discount'] : '购买课程折扣'?></li>
					<li class="align" style="width:40%;"><?php echo $common['vip_page']['vip_validity'] ? $common['vip_page']['vip_validity'] : '有效期'?></li>
					<div class="clear"></div>
				</ul>
				<?php  if(is_array($memberVip_list)) { foreach($memberVip_list as $item) { ?>
				<ul class="myvip-list">
					<li class="align" style="width:32%;"><?php  echo $item['level']['level_name'];?></li>
					<li class="align" style="width:28%;"><?php echo $item['discount']>0 && $item['discount']!=100 ? $item['discount'].'%' : '无';?></li>
					<li class="align" style="width:40%;">
						<?php  echo date('Y-m-d',$item['validity']);?><br/>
						<a href="<?php  echo $this->createMobileUrl('viplesson', array('level_id'=>$item['level_id']))?>" class="start-study"><?php echo $common['vip_page']['go_study'] ? $common['vip_page']['go_study'] : '去学习'?></a>
					</li>
					<div class="clear"></div>
				</ul>
				<?php  } } ?>
			</div>
			<?php  } ?>
			<!-- VIP列表 -->
			<div class="buyvip">
				<?php  if(!empty($level_list)) { ?>
				<ul class="ios-system">
					<?php  if(is_array($level_list)) { foreach($level_list as $vip) { ?>
					<li>
						<div class="vip-icon"><img src="<?php echo $vip['level_icon'] ? $_W['attachurl'].$vip['level_icon'] : MODULE_URL.'static/mobile/'.$template.'/images/vip-buyicon.png'?>"></div>
						<div class="notice_active">
							<div>￥<span class="price"> <?php  echo $vip['level_price'];?></span> <?php  if($vip['market_price']) { ?><span class="market-price">￥<?php  echo $vip['market_price'];?></span><?php  } ?></div>
							<div class="vip-name"><?php  echo $vip['level_name'];?></div>
							<div class="vip-time">有效期限：<?php  echo $vip['level_validity'];?>天</div>
							<a href="<?php  echo $this->createMobileUrl('viplesson',array('level_id'=>$vip['id']));?>" class="show-lesson"><?php echo $common['vip_page']['free_lesson'] ? $common['vip_page']['free_lesson'] : '查看可免费学习课程';?></a>
							<?php  if($vip['market_price']) { ?>
							<div class="discount-desc">
								<i class="fa fa-info-circle"></i> 
								<?php  if($vip['renew']) { ?>
									现在续费，还可享受<?php  echo $vip['renew_discount']*0.1;?>折优惠
								<?php  } else { ?>
									限时开通即可享受<?php  echo $vip['open_discount']*0.1;?>折优惠
								<?php  } ?>
							</div>
							<?php  } ?>
						</div>
						<div class="buybtn">
							<a href="javaScript:;" onclick="buyvip(<?php  echo $vip['id'];?>);"><?php echo $vip['renew'] ? '续费' : '开通';?></a>
						</div>
					</li>
					<?php  } } ?>
					<div class="clear"></div>
				</ul>
					<?php  if($comsetting['vip_agreement']) { ?>
					<div class="weui-cells weui-cells_checkbox">
						<label class="weui-cell weui-cell_active weui-check__label" for="vip_agreement">
							<div class="weui-cell__hd" style="padding-right:10px;">
								<input type="checkbox" class="weui-check" id="vip_agreement" checked />
								<i class="weui-icon-checked vip_agreement_checked"></i>
							</div>
							<div class="weui-cell__bd agreement_tips">
								<p>我已阅读并同意<a href="javascript:;" id="view-vip-agreement">《VIP服务协议》</a></p>
							</div>
						</label>
					</div>
					<div class="privacy_agreement_notice-mask" id="vip-agreement-content" style="display:none;">
						<div class="privacy_agreement_notice">
							<div class="close">
								<img src="<?php echo MODULE_URL;?>static/mobile/default/images/btn-close.png?v=6" width="32" height="32">
							</div>
							<h3 class="notice-title">VIP服务协议</h3>
							<ul class="notice-body">
								<?php  echo htmlspecialchars_decode($comsetting['vip_agreement'])?><p><br></p>
							</ul>
						</div>
					</div>
					<?php  } ?>
				<?php  } else { ?>
					<div class="no-content">
						<div>没找到任何会员服务价格表哦~</div>
					</div>
				<?php  } ?>
			</div>
			<div class="vipdesc ios-system">
				<div class="content"><?php  echo $comsetting['vipdesc'];?></div>
			</div>
		</div>

		<!-- 订单记录 -->	
		<div class="content-tab vip-order-list" style="display:none;">
			<div id="orderList" class="mar10-top">
				<?php  if(!$order_total) { ?>
				<div style="padding:50px 0; text-align:center; background:#fff;">没有找到任何订单</div>
				<?php  } ?>
			</div>
			
			<div id="loading_order" class="loading_div">
				<?php  if($order_total) { ?>
				<a href="javascript:void(0);" id="btn_order_page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
				<?php  } ?>
			</div>
		</div>

		<!-- VIP服务卡 -->
		<div class="content-tab vip-order-list" style="display:none;">
			<?php  if($comsetting['vipcard_show']) { ?>
			<div class="open-vip-box">
				<div class="open-vip-box-bg">
					<input type="text" id="card_password" class="cart-input" placeholder="请输入<?php echo $common['vip_page']['vipcard'] ? $common['vip_page']['vipcard'] : 'VIP服务卡密'?>">
					<a href="javascript:;" id="btn-card-password" class="submit-btn">立即开通</a>
				</div>
			</div>
			<?php  } ?>
			<div class="card-list-status">
				<label class="f-radio" data-id="0"><i class="icon-radio"></i><span class="f-rc-text">未使用(<span id="nouse_total"><?php  echo $nouse_total;?></span>)</span></label>
				<label class="f-radio" data-id="1"><i class="icon-radio"></i><span class="f-rc-text">已使用(<span id="used_total"><?php  echo $used_total;?></span>)</span></label>
				<label class="f-radio" data-id="2"><i class="icon-radio"></i><span class="f-rc-text">已过期(<span id="pass_total"><?php  echo $pass_total;?></span>)</span></label>
			</div>
			<div id="cardList" class="mar10-top">
				<div style="padding:50px 0; text-align:center; background:#fff;<?php  if($card_total) { ?>display:none;<?php  } ?>">没有找到任何<?php echo $common['vip_page']['vipcard'] ? $common['vip_page']['vipcard'] : 'VIP服务卡密'?></div>
			</div>
			
			<div id="loading_card" class="loading_div">
				<?php  if($card_total) { ?>
				<a href="javascript:void(0);" id="btn_card_page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
				<?php  } ?>
			</div>
		</div>
	</div>
	<input type="hidden" id="nowPage" />
	<input type="hidden" id="card_status" />

	<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/writemsg', TEMPLATE_INCLUDEPATH)) : (include template($template.'/writemsg', TEMPLATE_INCLUDEPATH));?>

	<div class="aui-dialog" <?php  if(!empty($now_service)) { ?>style="display:block"<?php  } ?>>
		<div class="listInformation  background_default " style="background: rgb(245, 245, 245);"><span class="listInformationImg listInformationImg2"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['avatar'];?>"></span>
			<div class="listInformationWord">
				<p class="t2 c8" style="width: 100%; text-align: left;">HI，我是 <?php  echo $now_service['nickname'];?></p>
				<p class="t1 c2" style="width: 100%; text-align: left;"><?php echo $common['other_page']['invite_join'] ? $common['other_page']['invite_join'] : '邀请你加入用户粉丝群'?></p>
			</div>
		</div>
		<div class="textCenter" style="padding-top: 15px;">
			<p style="padding-top: 10px;"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['qrcode'];?>" class="erweima"></p>
			<p class="c3 p-b-10">微信里长按识别二维码</p>
		</div>
		<div class="listInformationBtn t2 c3 flagDiv"><span onclick="closeTip()" class="listInformationBtnChild">关闭</span></div>
	</div>
	<div class="aui-mask" <?php  if(!empty($now_service)) { ?>style="display:block"<?php  } ?>></div>

<?php  } else if($op=='vipcard') { ?>
	<div class="login_wrap">
		<div class="weui-cells">
			<div class="weui-cell">
				<div class="weui-cell__hd"><label class="weui-label">服务卡密</label></div>
				<div class="weui-cell__bd">
					<input type="text" class="weui-input" id="card_password" value="<?php  echo $_GPC['code'];?>" placeholder="请输入<?php echo $common['vip_page']['vipcard'] ? $common['vip_page']['vipcard'] : 'VIP服务卡密'?>">
				</div>
			</div>
			<?php  if($comsetting['vip_agreement']) { ?>
			<div class="weui-cells weui-cells_checkbox">
				<label class="weui-cell weui-cell_active weui-check__label" for="vip_agreement">
					<div class="weui-cell__hd" style="padding-right:10px;">
						<input type="checkbox" class="weui-check" id="vip_agreement" checked />
						<i class="weui-icon-checked vip_agreement_checked"></i>
					</div>
					<div class="weui-cell__bd agreement_tips">
						<p>我已阅读并同意<a href="javascript:;" id="view-vip-agreement">《VIP服务协议》</a></p>
					</div>
				</label>
			</div>
			<div class="privacy_agreement_notice-mask" id="vip-agreement-content" style="display:none;">
				<div class="privacy_agreement_notice">
					<div class="close">
						<img src="<?php echo MODULE_URL;?>static/mobile/default/images/btn-close.png?v=6" width="32" height="32">
					</div>
					<h3 class="notice-title">VIP服务协议</h3>
					<ul class="notice-body">
						<?php  echo htmlspecialchars_decode($comsetting['vip_agreement'])?><p><br></p>
					</ul>
				</div>
			</div>
			<?php  } ?>
		</div>
		<div class="weui-btn-area">
			<a href="javascript:;" id="btn-card-password" class="weui-btn weui-btn_primary w90-per">立即开通</a>
		</div>
	</div>
<?php  } ?>

<script type="text/javascript">
	window.config = {
			 op: "<?php  echo $op;?>",
		setting: <?php  echo json_encode($setting)?>,
     comsetting: <?php  echo json_encode($comsetting)?>,
	   writemsg: <?php  echo intval($writemsg)?>,
	  v_buyinfo: <?php  echo json_encode($virtual_buyinfo)?>,
         vipurl: "<?php  echo $this->createMobileUrl('vip')?>",
   vipcard_text: "<?php echo $common['vip_page']['vipcard'] ? $common['vip_page']['vipcard'] : 'VIP服务卡密'?>",
	};
</script>
<script type="text/javascript" src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/vip.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>