<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>

<div class="baseWidth w-1200">
	<div class="w-main fs-12 ftc-7a7a7a line-h45 m-b-10">
		当前位置：<a href="/<?php  echo $uniacid;?>/index.html" class="more ftc-414141">首页</a> &gt; <a href="/<?php  echo $uniacid;?>/self.html" class="more ftc-414141">个人中心</a> &gt; <?php  echo $title;?>
	</div>
	<div class="memberContent flex">
		<?php  include $this->template($template.'/_memberLeft')?>
		<div class="memberRightLayout">
			<div class="orderListContainer memberContent">
				<form method="get" id="ordersForm">
					<div class="filter flex flex-vc">
						<ul class="flex flex-vc innerWrap">
							<li class="items <?php  if(!$_GPC['status']) { ?>actived<?php  } ?>"><a href="/<?php  echo $uniacid;?>/mylesson.html">全部订单</a></li>
							<li class="items <?php  if($_GPC['status']=='nopay') { ?>actived<?php  } ?>"><a href="/<?php  echo $uniacid;?>/mylesson.html?status=nopay">待付款 </a> </li>
							<li class="items <?php  if($_GPC['status']=='payed') { ?>actived<?php  } ?>"><a href="/<?php  echo $uniacid;?>/mylesson.html?status=payed">已付款 </a> </li>
							<li class="items <?php  if($_GPC['status']=='allow_verify') { ?>actived<?php  } ?>"><a href="/<?php  echo $uniacid;?>/mylesson.html?status=allow_verify">可核销</a></li>
							<li class="items <?php  if($_GPC['status']=='no_evaluate') { ?>actived<?php  } ?>"><a href="/<?php  echo $uniacid;?>/mylesson.html?status=no_evaluate">待评价</a></li>
						</ul>
						<div class="searchWrap flex flex-vc m-t-10">
							<div class="selectCon m-r-6">
								<select class="w-90 fs-12" name="ordersType">
									<option value="" selected>全部</option>
									<option value="0" <?php  if($_GPC['ordersType']=='0') { ?>selected<?php  } ?>>普通课程</option>
									<option value="1" <?php  if($_GPC['ordersType']=='1') { ?>selected<?php  } ?>>报名课程</option>
									<option value="3" <?php  if($_GPC['ordersType']=='3') { ?>selected<?php  } ?>>直播课程</option>
								</select>
							</div>
							<div class="searchCon">
								<input type="text" value="<?php  echo $_GPC['keyword'];?>" name="keyword" placeholder="课程名称/订单编号" class="w-180">
								<input type="submit" class="searchBtn" value="搜索" />
							</div>
						</div>
					</div>
				</form>

				<div class="myOrderTable">
				
				<?php  if($list) { ?>
					<div class="myOrderTableTitle flex flex-vc">
						<div class="text-c" style="width:46%">课程信息</div>
						<div class="text-c" style="width:12%"></div>
						<div class="text-c" style="width:15%">订单金额</div>
						<div class="text-c" style="width:13%">状态</div>
						<div class="text-c" style="width:12%">操作</div>
					</div>

					<?php  if(is_array($list)) { foreach($list as $item) { ?>
					<div class="flex flex-v">
						<div class="thead">
							<div class="tr flex flex-vc">
								<div class="cell flex1 flex flex-vc">
									<span class="m-r-30 titleCon">下单时间：<?php  echo date('Y-m-d H:i:s', $item['addtime'])?></span>
									<span class="m-r-15 titleCon">订单号: <?php  echo $item['ordersn'];?></span>
								</div>
							</div>
						</div>
						<div class="tbody">
							<div class="tr flex">
								<div class="cell goodsCell flex flex-vc" style="width:62%">
									<div class="img">
										<a href="/<?php  echo $uniacid;?>/orderDetails.html?orderid=<?php  echo $item['id'];?>"><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>"></a>
									</div>
									<div class="goodsInfo flex1">
										<a href="/<?php  echo $uniacid;?>/orderDetails.html?orderid=<?php  echo $item['id'];?>" class="name" title="<?php  echo $item['bookname'];?>"><?php  echo $item['bookname'];?></a>
										<p class="spec">
											课程类型：
											<?php  if($item['lesson_type']==0) { ?>
												普通课程
											<?php  } else if($item['lesson_type']==1) { ?>
												报名课程
											<?php  } else if($item['lesson_type']==3) { ?>
												直播课程
											<?php  } ?>
										</p>
										<p class="spec">
											<?php  if($item['lesson_type']==1) { ?>
												课程规格：<?php  echo $item['spec_name'];?>
											<?php  } else if($item['lesson_type']==0 || $item['lesson_type']==3) { ?>
												课程规格：<?php echo $item['spec_day']>0 ? $item['spec_day'].'天' : '长期有效';?>
											<?php  } ?>
										</p>
										<p class="spec">
											<?php  if($item['lesson_type']==0 && $item['validity'] && $item['status']>0) { ?>
												有效期限：<?php  echo date('Y-m-d H:i:s', $item['validity'])?>
											<?php  } else if($item['lesson_type']==1 && $item['status']>0) { ?>
												核销状态：
												<?php  if($item['is_verify']==0) { ?>
													<span class="ftc-0e9a0c">未核销</span>
												<?php  } else if($item['is_verify']==1) { ?>
													<span class="ftc-e9511b">已核销<?php  echo $item['verify_num'];?>次</span>
												<?php  } else if($item['is_verify']==2) { ?>
													核销完成(<?php  echo $item['verify_num'];?>)次
												<?php  } ?>
											<?php  } ?>
										</p>
									</div>
									<div class="goodsCommonPrice">
										<p class="fs-14">¥<?php  echo $item['marketprice'];?></p>
									</div>
								</div>
								<div class="cell goodsPrice flex flex-v flex-ai flex-hc" style="width:14%">
									<div class="fs-14 flex flex-hc ft-w-b ftc-e9511b">¥<?php  echo $item['price'];?></div>
								</div>
								<div class="cell flex-ai flex flex-v flex-hc goodsState" style="width:12%">
									<p class="state flex flex-hc <?php  echo $item['classname'];?>"><?php  echo $order_status[$item['status']];?></p>
									<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['lessonid'];?>" target="_blank" class="gotoOrdersDetail flex flex-hc">查看课程</a>
									<a href="/<?php  echo $uniacid;?>/orderDetails.html?orderid=<?php  echo $item['id'];?>" class="gotoOrdersDetail flex flex-hc">订单详情</a>
								</div>
								<div class="cell flex-ai flex flex-v flex-hc goodsHandle" style="width:12%">
									<?php  if($item['status']==0) { ?>
									<a class="otherHandle btn-status btn-cancel fs-12" href="/<?php  echo $uniacid;?>/mylesson.html?op=cancel&status=<?php  echo $_GPC['status'];?>&orderid=<?php  echo $item['id'];?>" onclick="return confirm('确定取消订单?');">取消订单</a>
									<a href="/<?php  echo $uniacid;?>/payment.html?orderid=<?php  echo $item['id'];?>&ordertype=buylesson" class="otherHandle btn-status btn-gopay fs-12">立即支付</a>
									<?php  } ?>

									<?php  if($item['status']==-1) { ?>
									<a class="otherHandle btn-status btn-cancel fs-12" href="/<?php  echo $uniacid;?>/mylesson.html?op=cancel&is_delete=1&status=<?php  echo $_GPC['status'];?>&orderid=<?php  echo $item['id'];?>" onclick="return confirm('该操作不可恢复，确定删除?');">删除订单</a>
									<?php  } ?>

									<?php  if($item['status']==1) { ?>
									<a class="otherHandle btn-status btn-evaluate fs-12" href="/<?php  echo $uniacid;?>/evaluate.html?orderid=<?php  echo $item['id'];?>">立即评价</a>
									<?php  } ?>

									<?php  if($item['show_qrcode']) { ?>
									<a class="otherHandle btn-status btn-verify fs-12" href="/<?php  echo $uniacid;?>/orderDetails.html?orderid=<?php  echo $item['id'];?>">核销二维码</a>
									<?php  } ?>
								</div>
							</div>
							<div class="interval"></div>
						</div>
					</div>
					<?php  } } ?>

					<?php  if($pager) { ?>
					<div class="tfoot clearfix m-t-40">
						<div class="tfoot-page">
							<div class="pagination">
								<?php  echo $pager;?>
							</div>
						</div>
					</div>
					<?php  } ?>
				<?php  } else { ?>
					<div class="empty">
						<div class="block">
							<i class="orders"></i>
							<p>没有找到符合条件的订单</p>
						</div>
					</div>
				<?php  } ?>
				</div>
			</div>
		</div>
	</div>

	<?php  if(!empty($now_service)) { ?>
	<link rel="stylesheet" href="<?php echo MODULE_URL;?>static/webapp/default/css/qunService.css?v=<?php  echo $versions;?>">
	<div class="aui-dialog">
		<div class="listInformation background_default" style="background: rgb(245, 245, 245);"><span class="listInformationImg listInformationImg2"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['avatar'];?>"></span>
			<div class="listInformationWord">
				<p class="t2 c8" style="width: 100%; text-align: left;">HI，我是 <?php  echo $now_service['nickname'];?></p>
				<p class="t1 c2" style="width: 100%; text-align: left;"><?php echo $common['other_page']['invite_join'] ? $common['other_page']['invite_join'] : '邀请你加入用户粉丝群'?></p>
			</div>
		</div>
		<div class="textCenter" style="padding-top: 0.3rem;">
			<p style="padding-top: 0.3rem;"><img src="<?php  echo $_W['attachurl'];?><?php  echo $now_service['qrcode'];?>"
				 class="erweima"></p>
			<p class="t3 c3 padding3" style="line-height: 1.5;">
				微信扫一扫二维码<br>加我为微信好友，拉您入群
			</p>
		</div>
		<div class="listInformationBtn t2 c3 flagDiv"><span onclick="closeTip()" class="listInformationBtnChild">关闭</span></div>
	</div>
	<div class="aui-mask"></div>
	<script type="text/javascript">
		function closeTip(){
			$(".aui-dialog").hide();
			$(".aui-mask").hide();
		}
	</script>
	<?php  } ?>
</div>

<?php  include $this->template($template.'/_footer')?>