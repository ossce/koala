<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
.menu {
	border-left: 1px solid #EEE;
	border-right: 1px solid #EEE;
}
.menu:last-child {
	border-bottom: 1px solid #EEE;
}
.menu summary {
	height: 40px;
	line-height: 40px;
	text-indent: 10px;
	outline: none;
	font-size: 14px;
	font-weight: 700;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FEFEFE), color-stop(1, #EEEEEE));
	cursor: pointer;
}
.menu summary::-webkit-details-marker {
	display: none;
}
.menu summary:before {
	content: "+";
	display: inline-block;
	width: 16px;
	height: 16px;
	margin-right: 10px;
	font-size:18px;
	font-weight:700;
}
.menu[open] summary:before {
	content: "-";
}
.menu ul li a {
	display: block;
	color: #666;
}
.menu ul li a:hover {
	text-decoration: underline;
}
</style>

<div class="main">
	<form id="sectionListForm" action="<?php  echo $this->createWebUrl('lesson', array('op'=>'batchAddSection','pid'=>$lesson['id']));?>" method="post" enctype="multipart/form-data">
		<input type="file" name="sectionFile" id="sectionFile" accept="application/vnd.ms-excel" onchange="changeSectionFile();" style="display:none;">
		<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
	</form>
	<div class="panel panel-default">
        <div class="panel-body relative">
            <a class="btn btn-primary w-117 import-section-btn" href="javascript:;"><i class="fa fa-plus"></i> 添加章节</a>
			<ul class="pull-down-ul file-upload" style="left:15px;top:45px;">
				<li class="pull-down-ul__item">
					<a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'postsection','pid'=>$lesson['id']));?>">添加单个章节</a>
				</li>
				<li class="pull-down-ul__item upload-section-btn">
					<a href="javascript:;">批量导入章节</a>
				</li>
				<li class="pull-down-ul__item">
					<a href="<?php echo MODULE_URL;?>static/web/xls/SectionTpl_v2.xls" download="SectionTpl_v2">下载章节模板</a>
				</li>
			</ul>
			<a class="btn btn-warning mar-l-10" href="<?php  echo $this->createWebUrl('lesson', array('op'=>'sectionTitle','pid'=>$lesson['id']));?>"><i class="fa fa-list-ol"></i> 课程目录</a>
        </div>
    </div>
	<form id="myform" action="<?php  echo $this->createWebUrl('lesson', array('op'=>'addSectionToTitle'));?>" method="post" class="form-horizontal form">
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-hover">
					<thead class="navbar-inner">
						<tr>
							<th style="width:60px;">全选</th>
							<th style="width:8%;text-align:center;">排序</th>
							<th style="width:10%;text-align:center;">预览</th>
							<th style="width:25%;">章节名称</th>
							<th style="width:9%;text-align:center;">试听章节</th>
							<th style="width:9%;text-align:center;">章节类型</th>
							<th style="width:9%;text-align:center;">章节状态</th>
							<th style="width:13%;text-align:center;">添加时间</th>
							<th style="width:12%;text-align:center;">操作</th>
						</tr>
					</thead>
					<tbody>
						<!-- 已归纳课程目录 -->
						<?php  if(is_array($title_list)) { foreach($title_list as $key => $title) { ?>
						<tr>
							<td colspan="9" style="padding:0;">
								<details class="menu" <?php  if($key<5) { ?>open<?php  } ?>>
									<summary><?php  echo $title['title'];?> <span style="color:#16b9f2;">[课时: <?php  echo count($title['section']);?>]</span></summary>
									<?php  if(is_array($title['section'])) { foreach($title['section'] as $sec) { ?>
									<table class="table table-hover" style="margin-bottom:0;">
										<tbody>
											<tr>
												<td style="width:60px;">
													<input type="checkbox" name="ids[]" value="<?php  echo $sec['id'];?>">
												</td>
												<td style="width:8%;text-align:center;">
													<input type="text" class="form-control" name="sectionorder[<?php  echo $sec['id'];?>]" value="<?php  echo $sec['displayorder'];?>" style="width:70px;display:inline-block;">
												</td>
												<td style="width:10%;text-align:center;">
													<?php  if(in_array($sec['sectiontype'], array('1','3'))) { ?>
														<a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'previewVideo','id'=>$sec['id']));?>" target="_blank"><img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images//videoCover.png?v=5" width="90"/></a>
													<?php  } else { ?>
														<a><img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images//no-preview.png?v=5" width="90"/></a>
													<?php  } ?>
												</td>
												<td style="width:25%;word-break:break-all;">
													[ID:<?php  echo $sec['id'];?>]<?php  echo $sec['title'];?>
												</td>
												<td style="width:9%;text-align:center;">
													<?php  if($sec['is_free']==1) { ?>
														<span class="label label-default">试听</span>
													<?php  } else { ?>
														<span class="label label-warning">付费</span>
													<?php  } ?>
												</td>
												<td style="width:9%;text-align:center;">
													<?php  if($sec['sectiontype']==1) { ?>
														视频章节
													<?php  } else if($sec['sectiontype']==2) { ?>
														图文章节
													<?php  } else if($sec['sectiontype']==3) { ?>
														音频章节
													<?php  } else if($sec['sectiontype']==4) { ?>
														外链章节
													<?php  } ?>
												</td>
												<td style="width:9%;text-align:center;">
													<?php  if($sec['status']==1) { ?>
														<span class="label label-success">上架</span>
													<?php  } else if($sec['status']==2) { ?>
														<span class="label label-danger">审核中</span>
													<?php  } else if($sec['status']=='0') { ?>
														<span class="label label-default">下架</span>
													<?php  } ?>
												</td>
												<td style="width:13%;text-align:center;">
													<?php  echo date('Y-m-d H:i:s',$sec['addtime']);?>
												</td>
												<td style="width:12%;text-align:center;">
													<div class="btn-group btn-group-sm">
														<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="javascript:;">功能列表 <span class="caret"></span></a>
														<ul class="dropdown-menu dropdown-menu-left" role="menu" style="z-index:99999">
															<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'postsection','id'=>$sec['id'],'pid'=>$sec['parentid'],'refurl'=>base64_encode($_SERVER['QUERY_STRING'])));?>"><i class="fa fa-edit"></i> 编辑章节</a></li>
															<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'copysection','id'=>$sec['id'],'pid'=>$sec['parentid']));?>"><i class="fa fa-copy"></i> 复制章节</a></li>
															<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'informStudent','sectionid'=>$sec['id']));?>"><i class="fa fa-volume-up"></i> 开课提醒</a></li>
															<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'delete','cid'=>$sec['id']));?>" onclick="return confirm('确认删除此章节吗？');return false;"><i class="fa fa-remove"></i> &nbsp;删除章节</a></li>
														</ul>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									<?php  } } ?>
								</details>
							</td>
						</tr>
						<?php  } } ?>

						<!-- 未归纳课程目录 -->
						<?php  if(!empty($section_list)) { ?>
						<tr>
							<td colspan="9" style="padding:0;">
								<details class="menu">
									<summary>以下章节未加入任何课程目录 <span style="color:#16b9f2;">[课时: <?php  echo count($section_list);?>]</span></summary>
								</details>
							</td>
						</tr>
						<?php  } ?>
						<?php  if(is_array($section_list)) { foreach($section_list as $key => $sec) { ?>
						<tr>
							<td style="width:60px;">
								<input type="checkbox" name="ids[]" value="<?php  echo $sec['id'];?>">
							</td>
							<td style="width:8%;text-align:center;">
								<input type="text" class="form-control" name="sectionorder[<?php  echo $sec['id'];?>]" value="<?php  echo $sec['displayorder'];?>" style="width:70px;display:inline-block;">
							</td>
							<td style="width:10%;text-align:center;">
								<?php  if(in_array($sec['sectiontype'], array('1','3'))) { ?>
									<a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'previewVideo','id'=>$sec['id']));?>" target="_blank"><img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images//videoCover.png?v=5" width="90"/></a>
								<?php  } else { ?>
									<a><img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images//no-preview.png?v=5" width="90"/></a>
								<?php  } ?>
							</td>
							<td style="width:25%;word-break:break-all;">[ID:<?php  echo $sec['id'];?>]<?php  echo $sec['title'];?></td>
							<td style="width:9%;text-align:center;">
								<?php  if($sec['is_free']==1) { ?>
									<span class="label label-default">试听</span>
								<?php  } else { ?>
									<span class="label label-warning">付费</span>
								<?php  } ?>
							</td>
							<td style="width:9%;text-align:center;">
								<?php  if($sec['sectiontype']==1) { ?>
									视频章节
								<?php  } else if($sec['sectiontype']==2) { ?>
									图文章节
								<?php  } else if($sec['sectiontype']==3) { ?>
									音频章节
								<?php  } else if($sec['sectiontype']==4) { ?>
									外链章节
								<?php  } ?>
							</td>
							<td style="width:9%;text-align:center;">
								<?php  if($sec['status']==1) { ?>
									<span class="label label-success">上架</span>
								<?php  } else if($sec['status']==2) { ?>
									<span class="label label-danger">审核中</span>
								<?php  } else if($sec['status']=='0') { ?>
									<span class="label label-default">下架</span>
								<?php  } ?>
							</td>
							<td style="width:13%;text-align:center;"><?php  echo date('Y-m-d H:i:s',$sec['addtime']);?></td>
							<td style="width:12%;text-align:center;">
								<div class="btn-group btn-group-sm">
									<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="javascript:;">功能列表 <span class="caret"></span></a>
									<ul class="dropdown-menu dropdown-menu-left" role="menu" style="z-index:99999">
										<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'postsection','id'=>$sec['id'],'pid'=>$sec['parentid'],'refurl'=>base64_encode($_SERVER['QUERY_STRING'])));?>"><i class="fa fa-edit"></i> 编辑章节</a></li>
										<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'copysection','id'=>$sec['id'],'pid'=>$sec['parentid']));?>"><i class="fa fa-copy"></i> 复制章节</a></li>
										<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'informStudent','sectionid'=>$sec['id']));?>"><i class="fa fa-volume-up"></i> 开课提醒</a></li>
										<li><a href="<?php  echo $this->createWebUrl('lesson', array('op'=>'delete','cid'=>$sec['id']));?>" onclick="return confirm('确认删除此章节吗？');return false;"><i class="fa fa-remove"></i> &nbsp;删除章节</a></li>
									</ul>
								</div>
							</td>
						</tr>
						<?php  } } ?>
					</tbody>
				</table>
				<table class="table">
					<tbody>
						<tr>
							<td colspan="9" style="width:100px;">
								<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
								<input type="hidden" name="pid" value="<?php  echo $pid;?>">
								<input type="checkbox" id="selAll" style="margin-right:20px;">
								<a onclick="editDisplayOrder();" class="btn btn-primary" style="margin-right:20px;">批量排序</a>
								<a href="javascript:;" id="setStatus" class="btn btn-success" style="margin-right:20px;">批量修改</a>
								<a href="javascript:;" id="delAll" class="btn btn-danger" style="margin-right:20px;">批量删除</a>
								设置到
								<select name="title_id" class="" onchange="addToTitle(this.value);">
									<option value="">请选择课程目录...</option>
									<?php  if(is_array($title_list)) { foreach($title_list as $title) { ?>
									<option value="<?php  echo $title['title_id'];?>"><?php  echo $title['title'];?></option>
									<?php  } } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			 </div>
		 </div>
		 <?php  echo $pager;?>
	</form>

	<div class="modal fade in" id="sectionStatusModal" tabindex="-1">
		<form id="form-refund" action="" class="form-horizontal form" method="post">
			<div class="we7-modal-dialog modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<div class="modal-title">批量修改</div>
					</div>
					<div class="modal-body">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label">试听章节</label>
								<div class="col-sm-9">
									<label class="radio-inline"><input type="radio" name="is_free" value="1"> 是</label>
									&nbsp;&nbsp;&nbsp;
									<label class="radio-inline"><input type="radio" name="is_free" value="0"> 否</label>
									<span class="help-block">不更改状态请勿选择</span>
								</div>
							</div>
							<div class="form-group test_time" style="display:none;">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label">试听时间</label>
								<div class="col-sm-9">
									<div class="input-group">
										<input type="text" name="test_time" class="form-control">
										<span class="input-group-addon">秒</span>
									</div>
									<div class="help-block">0为试听整个章节，仅支持七牛云对象存储、腾讯云对象存储、阿里云点播、腾讯云点播和阿里云OSS存储方式</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label">密码访问</label>
								<div class="col-sm-9">
									<input type="text" name="sectionPassword" class="form-control" />
									<div class="help-block">不更改请留空，设置“-1”表示清空密码，仅支持音频和视频章节</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label" style="padding-top:0;">章节状态</label>
								<div class="col-sm-9">
									<label><input type="radio" name="sectionStatus" value="0" style="display:inline-block;"> 下架</label>&nbsp;&nbsp;
									<label><input type="radio" name="sectionStatus" value="1" style="display:inline-block;"> 上架</label>&nbsp;&nbsp;
									<label><input type="radio" name="sectionStatus" value="2" style="display:inline-block;"> 审核中</label>&nbsp;&nbsp;
									<span class="help-block">不更改状态请勿选择</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="submit-setstatus">确定</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript">
	$(':radio[name="is_free"]').click(function() {
		if($(this).val()==1){
			$(".test_time").show();
		}else{
			$(".test_time").hide();
		}
	});


	var ids = document.getElementsByName("ids[]");
	var selectAll = false;
	$("#selAll").click(function(){
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
			util.message('未选中任何章节');
			return false;
		}else{
			return checkids;
		}
	}

	var sectionids = '';
	//批量修改状态
	$("#setStatus").click(function(){
		sectionids = checkStatus();
		if(sectionids){
			$('#sectionStatusModal').modal();
		}
	});
	$("#submit-setstatus").click(function(){
		var is_free = $("input[name=is_free]:checked").val();
		var test_time = $("input[name=test_time]").val();
		var password = $("input[name=sectionPassword]").val();
		var status = $("input[name=sectionStatus]:checked").val();
		
		$.ajax({
			type: "POST",
			url: "<?php  echo $this->createWebUrl('lesson',array('op'=>'ajaxUpdateSection','pid'=>$pid,'type'=>'status'));?>",
			data: {sectionids:sectionids,is_free:is_free,test_time:test_time,password:password,status:status},
			dataType:"json",
			success: function(res){
				if(res.code===0){
					util.message(res.msg, "", "success");
					setTimeout(function(){
						location.reload();
					},2000)
				}else{
					util.message(res.msg);
					return false;
				}
			},
			error: function(error){
				util.message('网络请求超时，请稍后重试!');
			}
		});
	});

	//批量删除章节
	$("#delAll").click(function(){
		sectionids = checkStatus();
		if(sectionids && confirm('批量删除不可恢复，确认删除?')){
			$.ajax({
				type: "POST",
				url: "<?php  echo $this->createWebUrl('lesson',array('op'=>'ajaxUpdateSection','pid'=>$pid,'type'=>'delete'));?>",
				data: {sectionids:sectionids},
				dataType:"json",
				success: function(res){
					if(res.code===0){
						util.message(res.msg, "", "success");
						setTimeout(function(){
							location.reload();
						},2000)
					}else{
						util.message(res.msg);
						return false;
					}
				},
				error: function(error){
					util.message('网络请求超时，请稍后重试!');
				}
			});
		}
	});


	//批量修改章节排序
	function editDisplayOrder(){
		document.getElementById("myform").action="<?php  echo $this->createWebUrl('lesson', array('op'=>'viewsection','pid'=>$pid,'displayorder'=>1));?>";
		document.getElementById("myform").submit();
	}

	//把章节添加到课程目录
	function addToTitle(obj){
		if(obj!=''){
			var check = $("input[type=checkbox]:checked");
			if(check.length < 1){
				util.message('您还没有选择任何章节');
				return false;
			}
			document.getElementById("myform").submit();
		}
	}

	//添加章节
	$(".import-section-btn").click(function(){
		if ($('.file-upload').css("display") == "none") {
			$('.file-upload').fadeIn(200);
			return false;
		}
	})
	$(".upload-section-btn").click(function(){
		document.getElementById("sectionFile").click();
	})
	function changeSectionFile(){
		$("#loadingToast").show();
		document.getElementById("sectionListForm").submit();
	}
	$("body").click(function(){
		if ($('.file-upload').css("display") == "block") {
			$('.file-upload').fadeOut(100);
		}
	});
</script>