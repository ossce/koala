<?php defined('IN_IA') or exit('Access Denied');?><link href="<?php echo MODULE_URL;?>static/web/css/fycommon.css" rel="stylesheet">

<div class="main">
	<div class="panel panel-info">
		<div class="panel-heading">筛选</div>
		<div class="panel-body">
			<form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
				<input type="hidden" name="c" value="site" />
				<input type="hidden" name="a" value="entry" />
				<input type="hidden" name="module_name" value="fy_lessonv2" />
				<input type="hidden" name="do" value="agent" />
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户昵称</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<input type="text" class="form-control" name="nickname" value="<?php  echo $_GPC['nickname'];?>" placeholder="用户昵称/真实名字/手机号码"/>
					</div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户ID</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<input type="text" class="form-control"  name="uid" value="<?php  echo $_GPC['uid'];?>" placeholder='用户ID'/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">推荐人ID</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<input type="text" class="form-control" name="parentid" value="<?php  echo $_GPC['parentid'];?>" placeholder='推荐人ID'/>
					</div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">分销级别</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<select name='agent_level' class='form-control'>
							<option value="">全部级别</option>
							<option value="0" <?php  if(in_array($_GPC['agent_level'],array('0'))) { ?>selected<?php  } ?>>默认级别</option>
							<?php  if(is_array($levelList)) { foreach($levelList as $key => $level) { ?>
							<option value="<?php  echo $key;?>" <?php  if($_GPC['agent_level']==$key) { ?>selected<?php  } ?>><?php  echo $level;?></option>
							<?php  } } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">分销状态</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<select name='status' class='form-control'>
							<option value=''>不限</option>
							<option value='1' <?php  if($_GPC['status']=='1') { ?>selected<?php  } ?>><?php  echo $agentStatusList['1'];?></option>
							<option value='0' <?php  if($_GPC['status']=='0') { ?>selected<?php  } ?>><?php  echo $agentStatusList['0'];?></option>
						</select>
					</div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员身份</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<select name='vip' class='form-control'>
							<option value=''>不限</option>
							<option value='1' <?php  if($_GPC['vip']=='1') { ?>selected<?php  } ?>>VIP会员</option>
							<option value='0' <?php  if($_GPC['vip']=='0') { ?>selected<?php  } ?>>普通会员</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">加入时间</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<?php echo tpl_form_field_daterange('time', array('start'=>($starttime ? date('Y-m-d', $starttime) : false),'end'=> ($endtime ? date('Y-m-d', $endtime) : false)));?>
					</div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">排序依据</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<label class="radio-inline"><input type="radio" name="orderby" value="uid" <?php  if($_GPC['orderby']=='uid' || !$_GPC['orderby']) { ?>checked<?php  } ?>>会员ID</label>&nbsp;&nbsp;
						<label class="radio-inline"><input type="radio" name="orderby" value="addtime" <?php  if($_GPC['orderby']=='addtime') { ?>checked<?php  } ?>>加入时间</label>
					</div>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>&nbsp;&nbsp;&nbsp;
						<button type="submit" name="export" value="1" class="btn btn-success"><i class="fa fa-arrow-down"></i> 导出</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">总数：<?php  echo $total;?></div>
		<div class="panel-body">
			<table class="table table-hover table-responsive">
				<thead class="navbar-inner" >
				<tr>
					<th style='width:12%;'>推荐人</th>
					<th style='width:8%;'>用户ID</th>
					<th style='width:12%;'>粉丝</th>
					<th style='width:12%;'>真实名字<br/>手机号码</th>
					<th style='width:10%;'>未结佣金<br/>累计佣金</th>
					<th style='width:10%;'>分销级别</th>
					<th style='width:11%;'>分销状态</th>
					<th style='width:13%;'>加入时间</th>
					<th style='width:10%'>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $row) { ?>
				<tr>
					<td>
					<?php  if($row['parentid']==0) { ?>
					<label class='label label-primary'>总店</label>
					<?php  } else { ?>
						<img src="<?php  echo $row['parent']['avatar'];?>" style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
						<?php echo $row['parent']['nickname']?$row['parent']['nickname']:'未设置';?>
					<?php  } ?>
					</td>
					<td><?php  echo $row['uid'];?></td>
					<td>
						<img src="<?php  echo $row['avatar'];?>" style="width:30px;height:30px;padding1px;border:1px solid #ccc"/>
						<?php echo $row['nickname']?$row['nickname']:'未设置';?>
					</td>
					<td><?php  echo $row['realname'];?> <br/><?php  echo $row['mobile'];?></td>
					<td><?php  echo $row['nopay_commission'];?> 元<br/><?php  echo $row['pay_commission']+$row['nopay_commission'];?> 元</td>
					<td><?php  echo $row['agent'];?></td>
					<td>
						<?php  if($row['status']==1) { ?>
							<span class="label label-success"><?php  echo $agentStatusList[$row['status']];?></span>
						<?php  } else { ?>
							<span class="label label-default"><?php  echo $agentStatusList[$row['status']];?></span>
						<?php  } ?>
					</td>
					<td><?php  echo date('Y-m-d H:i',$row['addtime']);?></td>
					<td style="overflow:visible;">
						<div class="btn-group btn-group-sm">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
								<li><a href="<?php  echo $this->createWebUrl('agent', array('op'=>'detail', 'uid'=>$row['uid'],'refurl'=>base64_encode($_SERVER['QUERY_STRING'])));?>"><i class="fa fa-pencil"></i> 编辑</a></li>
								<?php  if($row['teachers']) { ?>
									<li><a href="<?php  echo $this->createWebUrl('agent', array('op'=>'myteacher','uid'=>$row['uid']));?>"><i class="fa fa-bank"></i> 查看下级讲师</a></li>
								<?php  } ?>
								<li><a href="<?php  echo $this->createWebUrl('agent', array('op'=>'relation','uid'=>$row['uid']));?>"><i class="fa fa-external-link-square"></i> 历史绑定关系</a></li>
								<li><a href="<?php  echo $this->createWebUrl('team', array('uid'=>$row['uid']));?>"><i class="fa fa-list"></i> 查看下级成员</a></li>
								<li><a href="<?php  echo $this->createWebUrl('team', array('op'=>'export','uid'=>$row['uid']));?>"><i class="fa fa-download"></i> 导出下级成员</a></li>
								<li><a href="<?php  echo $this->createWebUrl('agent', array('op'=>'delete', 'uid'=>$row['uid'],'refurl'=>base64_encode($_SERVER['QUERY_STRING'])));?>" onclick="return confirm('该操作无法撤销恢复，确定删除?');"><i class="fa fa-remove"></i> &nbsp;删除分销成员</a></li>
							</ul>
						</div>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
			</table>
			<?php  echo $pager;?>
		</div>
	</div>
</div>