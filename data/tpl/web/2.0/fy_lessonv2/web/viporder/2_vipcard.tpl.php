<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
	<form id="vipCardForm" action="<?php  echo $this->createWebUrl('viporder', array('op'=>'uploadVipCard'));?>" method="post" enctype="multipart/form-data">
		<input type="file" name="vipCardFile" id="vipCardFile" accept="application/vnd.ms-excel" onchange="changeVipCardFile();" style="display:none;">
		<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
	</form>
    <div class="panel panel-info">
        <div class="panel-heading">添加VIP服务卡</div>
        <div class="panel-body">
            <a href="<?php  echo $this->createWebUrl('viporder', array('op'=>'addVipCode'));?>" class="btn btn-primary" style="margin-right:10px;"><i class="fa fa-plus"></i> 在线生成</a>
			<a class="btn btn-default" href="javascript:;" id="uploadXlsx"><i class="fa fa-arrow-up"></i> 导入卡密</a>
			<a href="<?php echo MODULE_URL;?>static/web/xls/VipCardTpl.xls" download="服务卡密模板">(下载模板)</a>
        </div>
    </div>

	<div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" method="get" class="form-horizontal" role="form">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="module_name" value="fy_lessonv2" />
                <input type="hidden" name="do" value="viporder" />
                <input type="hidden" name="op" value="vipcard" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">订单编号</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="ordersn" type="text" value="<?php  echo $_GPC['ordersn'];?>">
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">拥有者uid</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="own_uid" type="text" value="<?php  echo $_GPC['own_uid'];?>" placeholder="用户uid">
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">服务卡号</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="id1" type="text" value="<?php  echo $_GPC['id1'];?>" style="display:inline-block;width:44%;"> 至 <input class="form-control" name="id2" type="text" value="<?php  echo $_GPC['id2'];?>" style="display:inline-block;width:44%;">
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">服务卡密</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="password" type="text" value="<?php  echo $_GPC['password'];?>">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">服务卡状态</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <select name="is_use" class="form-control">
                            <option value="">全部状态</option>
							<option value="0" <?php  if(in_array($_GPC['is_use'],array('0'))) { ?> selected="selected" <?php  } ?>>未使用</option>
							<option value="1" <?php  if($_GPC['is_use'] == 1) { ?> selected="selected" <?php  } ?>>已使用</option>
							<option value="-1" <?php  if($_GPC['is_use'] == -1) { ?> selected="selected" <?php  } ?>>已过期</option>
                        </select>
                    </div>
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">VIP等级</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <select name="level_id" class="form-control">
                            <option value="">全部等级</option>
                            <?php  if(is_array($vipname_list)) { foreach($vipname_list as $key => $item) { ?>
                            <option value="<?php  echo $key;?>" <?php  if($_GPC['level_id']==$key) { ?>selected<?php  } ?>><?php  echo $item;?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 100px;">使用时间</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
					<?php echo tpl_form_field_daterange('time', array('starttime'=>($starttime ? date('Y-m-d', $starttime) : false),'endtime'=> ($endtime ? date('Y-m-d', $endtime) : false)));?>
                    </div>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>&nbsp;&nbsp;
						<button type="submit" name="export" value="1" class="btn btn-success"><i class="fa fa-arrow-down"></i> 导出</button>&nbsp;&nbsp;
						<button type="submit" name="qrcode" value="1" class="btn btn-primary"><i class="fa fa-qrcode"></i> 下载二维码</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="panel panel-default">
		<div class="panel-heading">总数：<?php  echo $total;?></div>
        <form action="<?php  echo $this->createWebUrl('viporder', array('op'=>'delAllCard'));?>" method="post" class="form-horizontal form">
			<div class="table-responsive panel-body">
				<table class="table table-hover">
					<thead class="navbar-inner">
					<tr>
						<th style="width:4%;">全选</th>
						<th style="width:12%;">服务卡号</th>
						<th style="width:18%;">卡密</th>
						<th style="width:8%;">卡时长</th>
						<th style="width:12%;">有效期</th>
						<th style="width:10%;">卡状态</th>
						<th style="width:10%;">VIP等级</th>
						<th style="width:10%;">拥有者</th>
						<th style="width:16%;">使用订单号</th>
					</tr>
					</thead>
					<tbody style="font-size:13px;">
					<?php  if(is_array($list)) { foreach($list as $item) { ?>
					<tr>
						<td><input type="checkbox" name="ids[]" value="<?php  echo $item['id'];?>"></td>
						<td><?php  echo $item['id'];?></td>
						<td><?php  echo $item['password'];?></td>
						<td><?php  echo $item['viptime'];?> 天</td>
						<td><?php  echo date('Y-m-d H:i',$item['validity'])?></td>
						<td>
							<?php  if($item['is_use'] == 0 && time() > $item['validity']) { ?><span class="label label-default">已过期</span><?php  } ?>
							<?php  if($item['is_use'] == 0 && time() <= $item['validity']) { ?><span class="label label-success">未使用</span><?php  } ?>
							<?php  if($item['is_use'] == 1) { ?><span class="label label-warning">已使用</span><?php  } ?>
						</td>
						<td><?php  echo $vipname_list[$item['level_id']];?></td>
						<td>
							<?php  echo $item['own_nickname'];?>
							<?php  if($item['own_uid']) { ?>
								<br/>(uid: <?php  echo $item['own_uid'];?>)
							<?php  } ?>
						</td>
						<td><a href="<?php  echo $this->createWebUrl('viporder', array('ordersn'=>$item['ordersn'],'search'=>1));?>"><?php  echo $item['ordersn'];?></a></td>
					</tr>
					<?php  } } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="9">
								<input type="checkbox" class="checkItems">
								<a href="javascript:;" id="allocation-vipcard" class="btn btn-success" style="margin-left:13px;">分配给用户</a>
								<a href="javascript:;" id="cancel-vipcard" class="btn btn-danger" style="margin-left:13px;">取消分配</a>
								<input name="submit" type="submit" class="btn btn-warning" value="批量删除" onclick="return delAll()" style="margin-left:13px;">
							</td>
						</tr>
					</tfoot>
				</table>
				<?php  echo $pager;?>
			</div>
		</div>
    </form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_searchMemberModal', TEMPLATE_INCLUDEPATH)) : (include template('web/_searchMemberModal', TEMPLATE_INCLUDEPATH));?>

<script type="text/javascript">
	var ids = document.getElementsByName('ids[]');
	var selectAll = false;
	$(".checkItems").click(function(){
		selectAll = !selectAll;
		for(var i=0; i<ids.length; i++){
			ids[i].checked = selectAll;
		}
	});
	function checkStatus(){
		var checkids = "";
		for(var i=0; i<ids.length; i++){
			if(ids[i].checked){
				checkids += (checkids === '' ? ids[i].value : ',' + ids[i].value);
			}
		}
		if(checkids===''){
			util.message("未选中任何选项", "", "error");
			return false;
		}else{
			return checkids;
		}
	}

	//批量分配
	$("#allocation-vipcard").click(function(){
		var cardids = checkStatus();
		
		if(!cardids){  
			return false ;
		}

		$('#modal-select-member').modal();
	})

	//选择用户
	function selectMember(obj){
		var select_uid = $(obj).data('uid');

		if(!select_uid){
			util.message("获取用户信息失败，请刷新页面重试", "", "error");
			return false;
		}

		var cardids = checkStatus();
		if(cardids && confirm('确认批量操作?')){
			$.ajax({
				type: "POST",
				url: "<?php  echo $this->createWebUrl('viporder', array('op'=>'updateCard','type'=>'set'));?>",
				data: {cardids:cardids, own_uid:select_uid},
				dataType:"json",
				success: function(res){
					if(res.code===0){
						util.message(res.msg, "", "success");
						setTimeout(function(){
							location.reload();
						},2000);
					}else{
						util.message(res.msg, "", "error");
						return false;
					}
				},
				error: function(error){
					util.message("网络请求超时，请稍后重试", "", "error");
				}
			});
		}
	}

	//取消分配
	$("#cancel-vipcard").click(function(){
		var cardids = checkStatus();
		
		if(!cardids){  
			return false ;
		}
		if(cardids && confirm('确认批量取消分配?')){
			$.ajax({
				type: "POST",
				url: "<?php  echo $this->createWebUrl('viporder', array('op'=>'updateCard','type'=>'cancel'));?>",
				data: {cardids:cardids},
				dataType:"json",
				success: function(res){
					if(res.code===0){
						util.message(res.msg, "", "success");
						setTimeout(function(){
							location.reload();
						},2000);
					}else{
						util.message(res.msg, "", "error");
						return false;
					}
				},
				error: function(error){
					util.message("网络请求超时，请稍后重试", "", "error");
				}
			});
		}
	})

	$("input[name=kmember]").bind('keypress', function(e) {
		var ev = (typeof event!= 'undefined') ? window.event : e;
		if(ev.keyCode == 13) {
			searchMember();
			return false;
		}
	});

	//批量删除
	function delAll(){
		var flag = checkStatus();
		if(!flag){  
			return false ;
		}
		if(!confirm('该操作无法恢复，确定删除?')){
			return false;
		}

		return true;
	}

	//上传卡密
	$("#uploadXlsx").click(function(){
		document.getElementById("vipCardFile").click();
	})
	function changeVipCardFile(){
		$("#loadingToast").show();
		document.getElementById("vipCardForm").submit();
	}
</script>
