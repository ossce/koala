<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
	<div class="alert alert-info">
	    1、各个VIP等级互相独立存在，可以同时购买多个VIP等级免费学习各个VIP等级指定的课程。<br/>
		2、请勿随意删除VIP等级，删除VIP等级会影响已购买该等级的会员使用。
	</div>
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
		<div class="panel panel-default">
			<div class="panel-heading">VIP等级设置</div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th style="width:5%;text-align:center">排序</th>
							<th style="width:14%; text-align:center;">等级名称</th>
							<th style="width:8%; text-align:center;">有效期</th>
							<th style="width:8%; text-align:center;">价格</th>
							<th style="width:9%; text-align:center;">赠送积分</th>
							<th style="width:10%; text-align:center;">购买课程折扣</th>
							<th style="width:10%; text-align:center;">开通/续费折扣</th>
							<th style="width:9%; text-align:center;">状态</th>
							<th style="width:12%; text-align:center;">
								一<?php  if($comsetting['level']>1) { ?>二<?php  } ?><?php  if($comsetting['level']>2) { ?>三<?php  } ?>级佣金
							</th>
							<th style="width:15%; text-align:right;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php  if(is_array($level_list)) { foreach($level_list as $item) { ?>
						<tr>
							<td><input type="text" class="form-control" name="sort[<?php  echo $item['id'];?>]" value="<?php  echo $item['sort'];?>" style="width:70px;display:inline-block;"></td>
							<td style="text-align:center;">[ID: <?php  echo $item['id'];?>] <?php  echo $item['level_name'];?></td>
							<td style="text-align:center;"><?php  echo $item['level_validity'];?>天</td>
							<td style="text-align:center;"><?php  echo $item['level_price'];?>元</td>
							<td style="text-align:center;"><?php echo $item['integral']>0 ? $item['integral'].'积分' : '无';?></td>
							<td style="text-align:center;"><?php  echo $item['discount'];?>%</td>
							<td style="text-align:center;"><?php  echo $item['open_discount'];?>% / <?php  echo $item['renew_discount'];?>%</td>
							<td style="text-align:center;"><?php echo $item['is_show']==1 ? '显示':'隐藏';?></td>
							<td style="text-align:center;">
								<span>
									<?php  if($item['com']['commission1']) { ?>
										<?php  echo $item['com']['commission1'];?><?php echo $item['com']['commission_type'] ? '元' : '%'?>
									<?php  } else { ?>
										空
									<?php  } ?>
								</span>
								<span <?php  if($comsetting['level']<2) { ?>class="hide"<?php  } ?>>
									/
									<?php  if($item['com']['commission2']) { ?>
										<?php  echo $item['com']['commission2'];?><?php echo $item['com']['commission_type'] ? '元' : '%'?>
									<?php  } else { ?>
										空
									<?php  } ?>
								</span>
								<span <?php  if($comsetting['level']<3) { ?>class="hide"<?php  } ?>>
									/
									<?php  if($item['com']['commission3']) { ?>
										<?php  echo $item['com']['commission3'];?><?php echo $item['com']['commission_type'] ? '元' : '%'?>
									<?php  } else { ?>
										空
									<?php  } ?>
								</span>
							</td>
							<td style="text-align:right;">
                            	<a class="btn btn-default" href="<?php  echo $this->createWebUrl('viporder', array('op'=>'vipLevel','level_id'=>$item['id']));?>">编辑</a>
                            	<a class="btn btn-default" href="<?php  echo $this->createWebUrl('viporder', array('op'=>'delVipLevel','level_id'=>$item['id']));?>" onclick="return confirm('确认删除此等级吗？');return false;">删除</a>
							</td>
						</tr>
						<?php  } } ?>
					</tbody>
				</table>
			</div>
			<div class="panel-footer">
				<input type="submit" name="submit" value="批量排序" class="btn btn-primary" />
				<a class="btn btn-default" href="<?php  echo $this->createWebUrl('viporder',array('op'=>'vipLevel'));?>" style="margin-left:15px;"><i class="fa fa-plus"></i> 添加新等级</a>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">购买VIP会员服务</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><strong style="color:red;">* </strong>VIP分销开关</label>
					<div class="col-sm-9">
						<label class="radio-inline"><input type="radio" name="vip_sale" value="1" <?php  if($comsetting['vip_sale']==1) { ?>checked<?php  } ?> /> 开启</label> &nbsp;
						<label class="radio-inline"><input type="radio" name="vip_sale" value="0" <?php  if($comsetting['vip_sale']==0) { ?>checked<?php  } ?> /> 关闭</label>
						<span class="help-block"><strong></strong>关闭后，下级购买或续费VIP等级服务时，上级不会获得佣金</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><strong style="color:red;">* </strong>VIP服务卡入口</label>
					<div class="col-sm-9">
						<label class="radio-inline"><input type="radio" name="vipcard_show" value="1" <?php  if($comsetting['vipcard_show']==1) { ?>checked<?php  } ?> /> 开启</label> &nbsp;
						<label class="radio-inline"><input type="radio" name="vipcard_show" value="0" <?php  if($comsetting['vipcard_show']==0) { ?>checked<?php  } ?> /> 关闭</label>
						<span class="help-block"><strong></strong>开启后，手机端VIP服务页面将显示“VIP服务卡密开通入口”</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">VIP服务介绍</label>
					<div class="col-sm-9">
						<?php  echo tpl_ueditor('vipdesc', $comsetting['vipdesc']);?>
						<div class="help-block">
							该描述不为空时，会显示在前端“VIP服务”页面
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">购买VIP服务协议</label>
					<div class="col-sm-9">
						<?php  echo tpl_ueditor('vip_agreement', $comsetting['vip_agreement']);?>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group col-sm-12">
			<input type="hidden" name="id" value="<?php  echo $comsetting['id'];?>" />
			<input type="submit" name="submit" value="保存设置" class="btn btn-primary col-lg-1" />
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</form>
</div>