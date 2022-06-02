<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
    <div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" method="get" class="form-horizontal" role="form">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="module_name" value="fy_lessonv2" />
                <input type="hidden" name="do" value="viporder" />
                <input type="hidden" name="op" value="display" />
				<input type="hidden" name="search" value="1" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">订单编号</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="ordersn" type="text" value="<?php  echo $_GPC['ordersn'];?>">
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">用户信息</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="nickname" type="text" value="<?php  echo $_GPC['nickname'];?>" placeholder="昵称/姓名/手机号码">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">订单状态</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <select name="status" class="form-control">
                            <option value="">不限</option>
							<?php  if(is_array($orderStatus)) { foreach($orderStatus as $key => $item) { ?>
							<option value="<?php  echo $key;?>" <?php  if($_GPC['status']=="$key") { ?>selected="selected"<?php  } ?>><?php  echo $item;?></option>
							<?php  } } ?>
                        </select>
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">支付方式</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <select name="paytype" class="form-control">
                            <option value="">不限</option>
							<?php  if(is_array($orderPayType)) { foreach($orderPayType as $key => $item) { ?>
							<option value="<?php  echo $key;?>" <?php  if($_GPC['paytype']=="$key") { ?>selected="selected"<?php  } ?>><?php  echo $item;?></option>
							<?php  } } ?>
                        </select>
                    </div>
                </div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">VIP等级</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <select name="level_id" class="form-control">
                            <option value="">不限</option>
							<?php  if(is_array($level_list)) { foreach($level_list as $item) { ?>
							<option value="<?php  echo $item['id'];?>" <?php  if($_GPC['level_id']==$item['id']) { ?>selected="selected"<?php  } ?>><?php  echo $item['level_name'];?></option>
							<?php  } } ?>
                        </select>
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 100px;">时间类型</label>
					<div class="col-sm-8 col-lg-3 col-xs-12">
						<label class="radio-inline" style="padding-top:0;"><input type="radio" name="timetype" value="1" <?php  if($timetype==1) { ?>checked<?php  } ?>>下单</label>
						<label class="radio-inline" style="padding-top:0;"><input type="radio" name="timetype" value="2" <?php  if($timetype==2) { ?>checked<?php  } ?>>付款</label>
						<?php echo tpl_form_field_daterange('time', array('starttime'=>($starttime ? date('Y-m-d', $starttime) : false),'endtime'=> ($endtime ? date('Y-m-d', $endtime) : false)));?>
					</div>
                    <div class="col-sm-3 col-lg-3">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="submit" name="export" value="1" class="btn btn-success"><i class="fa fa-arrow-down"></i> 导出</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="panel panel-default">
		<div class="panel-heading">订单总数：<?php  echo $total;?><span style="margin-left:40px;">订单总额：<?php  echo $statis[0]['vipmoney'];?></span></div>
        <div class="table-responsive panel-body">
            <table class="table table-hover">
                <thead class="navbar-inner">
                <tr>
					<th style="width:60px;">全选</th>
                    <th style="width:15%;">订单编号</th>
                    <th style="width:13%;">昵称/姓名/手机</th>
                    <th style="width:12%;">服务内容</th>
                    <th style="width:9%;">服务价格</th>
					<th style="width:11%;">一二三级佣金</th>
                    <th style="width:9%;">支付方式</th>
                    <th style="width:9%;">订单状态</th>
                    <th style="width:10%;">下单时间</th>
                    <th style="text-align:right;">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php  if(is_array($list)) { foreach($list as $item) { ?>
                <tr>
					<td><input type="checkbox" name="ids[]" value="<?php  echo $item['id'];?>"></td>
                    <td><?php  if($item['paytype']=='vipcard') { ?><a href="<?php  echo $this->createWebUrl('viporder', array('op'=>'vipcard','ordersn'=>$item['ordersn']));?>"><?php  echo $item['ordersn'];?></a><?php  } else { ?><?php  echo $item['ordersn'];?><?php  } ?></td>
                    <td><?php  echo $item['nickname'];?><br/><?php echo $item['realname'] ? $item['realname'].'，' : ''?><?php  echo $item['mobile'];?></td>
                    <td><?php  echo $level_name_list[$item['level_id']];?>-<?php  echo $item['viptime'];?>天</td>
                    <td><?php  echo $item['vipmoney'];?> 元</td>
					<td><?php  echo $item['commission1'];?> / <?php  echo $item['commission2'];?> / <?php  echo $item['commission3'];?></td>
                    <td>
						<span class="label label-info">
						<?php  if($item['paytype']) { ?>
							<?php  echo $orderPayType[$item['paytype']];?>
						<?php  } else { ?>
							无
						<?php  } ?>
						</span>
                    </td>
                    <td>
                        <?php  if($item['status'] == 0) { ?><span class="label label-danger">待付款</span><?php  } ?>
						<?php  if($item['status'] == 1) { ?><span class="label label-success">已付款</span><?php  } ?>
						<?php  if($item['status'] == -2) { ?><span class="label label-default">已退款</span><?php  } ?>
                    </td>
                    <td><?php  echo date('Y-m-d H:i:s', $item['addtime'])?></td>
                    <td style="text-align:right;">
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('viporder', array('op' => 'detail', 'id' => $item['id']))?>" data-toggle="tooltip" data-placement="bottom" data-original-title="查看订单"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
                <?php  } } ?>
                </tbody>
            </table>
			<table class="table">
				<tbody>
					<tr>
						<td>
							<!-- <input type="checkbox" id="selAll" style="margin-right:10px;">
							<input type="button" class="btn btn-danger" id="delAll" value="批量删除"> -->
						</td>
					</tr>
				</tbody>
			</table>
            <?php  echo $pager;?>
        </div>
    </div>
</div>

<script type="text/javascript">
	var ids = document.getElementsByName("ids[]");
	var selectAll = false;
	$("#selAll").click(function(){
		selectAll = !selectAll;
		for(var i=0; i<ids.length; i++){
			ids[i].checked = selectAll;
		}
	});

	$("#delAll").click(function(){
		var checkids = "";
		for(var i=0; i<ids.length; i++){
			if(ids[i].checked){
				checkids += (checkids === '' ? ids[i].value : ',' + ids[i].value);
			}
		}
		if(checkids===''){
			alert('请选择要操作的订单');
			return;
		}

		if(!confirm('确定批量删除订单?')){
			return;
		}
		
		$.ajax({
			type: 'post',
			url: "<?php  echo $this->createWebUrl('viporder', array('op'=>'delAll'))?>",
			data: {ids:checkids},
			dataType:'json',
			success: function(res){
				if(res.code===0){
					alert(res.msg);
					location.reload();
				}else{
					alert('网络请求超时，删除失败');
				}
			},
			error: function(error){
				alert('网络请求超时，请稍后重试!');
			}
		});
	});
</script>