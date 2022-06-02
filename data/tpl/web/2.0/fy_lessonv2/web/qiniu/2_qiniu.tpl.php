<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
    <div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" method="get" class="form-horizontal" role="form">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="module_name" value="fy_lessonv2" />
                <input type="hidden" name="do" value="video" />
                <input type="hidden" name="op" value="qiniu" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">视频名称</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="keyword" type="text" value="<?php  echo $_GPC['keyword'];?>">
                    </div>
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">讲师编号</label>
                    <div class="col-sm-2 col-lg-3">
                        <input class="form-control" name="teacherid" type="text" value="<?php  echo $_GPC['teacherid'];?>" placeholder="留空将检索后台上传的视频">
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">分类信息</label>
                    <div class="col-xs-12 col-lg-2">
						<select class="form-control" id="pid" name="pid" onchange="renderCategory(this.value)">
							<option value="0" selected="selected">请选择一级分类</option>
							<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
							<option value="<?php  echo $item['id'];?>"><?php  echo $item['name'];?></option>
							<?php  } } ?>
						</select>
					</div>
					<div class="col-xs-12 col-lg-2">
						<select class="form-control" id="cid" name="cid" onchange="renderSecondCategory(this.value)">
							<option value="0">请选择二级分类</option>
						</select>
					</div>
					<div class="col-xs-12 col-lg-2">
						<select class="form-control" id="ccid" name="ccid">
							<option value="0">请选择三级分类</option>
						</select>
					</div>
                </div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 100px;">上传时间</label>
                    <div class="col-sm-8 col-lg-3 col-xs-12">
                        <?php echo tpl_form_field_daterange('time', array('starttime'=>($starttime ? date('Y-m-d', $starttime) : false),'endtime'=> ($endtime ? date('Y-m-d', $endtime) : false)));?>
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
						&nbsp;&nbsp;
						<a href="<?php  echo $this->createWebUrl('video',array('op'=>'upqiniu'));?>" class="btn btn-success"><i class="fa fa-arrow-up"></i> 上传视频</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="panel panel-default">
        <form action="" method="post" class="form-horizontal form" >
        <div class="table-responsive panel-body">
            <table class="table table-hover">
                <thead class="navbar-inner">
                <tr>
					<th style="width:70px;">全选</th>
					<th style="width:15%;text-align:center;">分类名称</th>
                    <th style="width:10%;text-align:center;">视频预览</th>
                    <th style="width:15%;text-align:center;">视频名称</th>
                    <th style="width:10%;text-align:center;">视频大小</th>
					<th style="width:15%;text-align:center;">上传时间</th>
                    <th style="width:22%;text-align:center;">文件链接</th>
					<th style="width:8%;text-align:right;">操作</th>
                </tr>
                </thead>
                <tbody style="font-size: 13px;">
                <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                <tr>
					<td>
						<input type="checkbox" name="ids[]" value="<?php  echo $item['id'];?>">
					</td>
					<td style="text-align:center;">
						<?php  if($item['pname']) { ?>
							<?php  echo $item['pname'];?>
							<?php  if($item['cname']) { ?>
								<br/><?php  echo $item['cname'];?>
								<?php  if($item['ccname']) { ?>
									<br/><?php  echo $item['ccname'];?>
								<?php  } ?>
							<?php  } ?>
						<?php  } else { ?>
							未分类
						<?php  } ?>
					</td>
                    <td style="text-align:center;">
						<a href="<?php  echo $this->createWebUrl('video', array('op'=>'qiniuPreview','id'=>$item['id']));?>"><img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images//videoCover.png?v=1" width="100"/></a>
					</td>
                    <td style="word-break:break-all;text-align:center;"><?php  echo $item['name'];?></td>
					<td style="text-align:center;"><?php echo round(($item['size']/1024)/1024,2)?round(($item['size']/1024)/1024,2):0.01;?> MB</td>
					<td style="text-align:center;"><?php  echo date('Y-m-d H:i:s', $item['addtime'])?></td>
                    <td style="text-align:center;">
                        <textarea style="width:300px;height:85px; border-radius:5px;" id="content<?php  echo $key;?>" style="overflow-y:auto;" onclick="selectTxt(this)"><?php  echo $qiniu['url'];?><?php  echo $item['com_name'];?></textarea>
                    </td>
					<td style="text-align:right;">
                        <a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('video',array('op'=>'delQiniu','id'=>$item['id'],'refurl'=>base64_encode($_SERVER['QUERY_STRING'])));?>" title="删除" onclick="return confirm('确认删除？');return false;"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                <?php  } } ?>
                </tbody>
            </table>
			<table class="table relative">
				<tbody>
					<tr>
						<td>
							<input type="checkbox" id="selAll" style="margin-right:10px;">
							<a href="javascript:;" class="btn btn-warning batch-category">批量设置分类</a>
						</td>
					</tr>
				</tbody>
			</table>
            <?php  echo $pager;?>
        </div>
    </div>
    </form>
</div>

<!-- 批量设置音视频分类 -->
<div class="modal fade in" id="categoryModal" tabindex="-1">
	<form class="form-horizontal form">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">×</span>
						<span class="sr-only">Close</span>
					</button>
					<div class="modal-title">批量设置课程分类</div>
				</div>
				<div class="modal-body">
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;">分类信息</label>
							<div class="col-sm-9">
								<select class="form-control" id="batchPid" onchange="firstCategory(this.value)">
									<option value="">请选择一级分类</option>
									<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
									   <option value="<?php  echo $item['id'];?>"><?php  echo $item['name'];?></option>
									<?php  } } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;"></label>
							<div class="col-sm-9">
								<select class="form-control" id="batchCid" onchange="secordCategory(this.value)">
									<option value="">请选择二级分类</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width:100px;"></label>
							<div class="col-sm-9">
								<select class="form-control" id="batchCcid">
									<option value="">请选择三级分类</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="btn-setcategory">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	function selectTxt(obj){
		$(obj).select();
	}

	/* 选择分类信息 START */
	var category = <?php  echo json_encode($category_list)?>;
	/* 选择分类信息 END */

	/* 按分类搜索视频 START */
	var pid		 = <?php  echo intval($_GPC['pid'])?>;
	var cid		 = <?php  echo intval($_GPC['cid'])?>;
	var ccid	 = <?php  echo intval($_GPC['ccid'])?>;

	$(function(){
		$("#pid").find("option[value='"+pid+"']").attr("selected",true);
		document.getElementById("pid").onchange();
	});
	//选择一级分类
	function renderCategory(pid){
		var chtml2 = '<option value="0">请选择二级分类</option>';
		if(pid){
			for(var i in category){
				if(category[i].id==pid){
					var second = category[i].second;
					for(var j in second){
						if(second[j].id==cid){
							chtml2 += '<option value="' + second[j].id+'" selected>' + second[j].name + '</option>';
						}else{
							chtml2 += '<option value="' + second[j].id+'">' + second[j].name + '</option>';
						}
					}
					$("#cid").html(chtml2);
				}
			}
		}else{
			$("#cid").html(chtml2);
		}

		if(pid){
			renderSecondCategory();
		}
	}
	//选择二级分类
	function renderSecondCategory(){
		pid = $("#pid").val() ? $("#pid").val() : pid;
		cid = $("#cid").val() ? $("#cid").val() : cid;

		var chtml3 = '<option value="0">请选择三级分类</option>';
		if(pid && cid){
			for(var i in category){
				if(category[i].id==pid){
					var second = category[i].second;
					for(var j in second){
						if(second[j].id==cid){
							var third = category[i].second[j].third;
							for(var k in third){
								if(third[k].id==ccid){
									chtml3 += '<option value="' + third[k].id+'" selected>' + third[k].name + '</option>';
								}else{
									chtml3 += '<option value="' + third[k].id+'">' + third[k].name + '</option>';
								}
							}
						}
					}
					$("#ccid").html(chtml3);
				}
			}
		}else{
			$("#ccid").html(chtml3);
		}
	}
	/* 按分类搜索视频 END */


	
	//批量设置选择一级分类
	function firstCategory(pid){
		var chtml2 = '<option value="0">请选择二级分类</option>';
		var chtml3 = '<option value="0">请选择三级分类</option>';
		
		if(pid > 0){
			for(var i in category){
				if(category[i].id==pid){
					var second = category[i].second;
					for(var j in second){
						chtml2 += '<option value="' + second[j].id+'">' + second[j].name + '</option>';
					}
					$("#batchCid").html(chtml2);
					$("#batchCcid").html(chtml3);
				}
			}
		}else{
			$("#batchCid").html(chtml2);
			$("#batchCcid").html(chtml3);
		}
	}
	//批量选择二级分类
	function secordCategory(cid){
		var pid = $("#batchPid").val();
		var chtml3 = '<option value="0">请选择三级分类</option>';
		
		if(pid>0 && cid>0){
			for(var i in category){
				if(category[i].id==pid){
					var second = category[i].second;
					for(var j in second){
						if(second[j].id==cid){
							var third = category[i].second[j].third;
							for(var k in third){
								chtml3 += '<option value="' + third[k].id+'">' + third[k].name + '</option>';
							}
						}
					}
					$("#batchCcid").html(chtml3);
				}
			}
		}else{
			$("#batchCcid").html(chtml3);
		}
	}

	/* 批量选择视频 START */
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
			util.message('未选中任何音视频', '', 'error');
			return false;
		}else{
			return checkids;
		}
	}
	/* 批量选择视频 END */

	/* 批量设置视频分类 START */
	var videoids = "";
	$(".batch-category").click(function(){
		videoids = checkStatus();
		if(videoids){
			$('#categoryModal').modal();
		}
	});

	$("#btn-setcategory").click(function(){
		videoids  = checkStatus();
		var batch_pid = $("#batchPid").val();
		var batch_cid = $("#batchCid").val();
		var batch_ccid = $("#batchCcid").val();

		$.ajax({
			type: "POST",
			url: "<?php  echo $this->createWebUrl('video',array('op'=>'batchCategory','savetype'=>'qiniu'));?>",
			data: {videoids:videoids,batch_pid:batch_pid,batch_cid:batch_cid,batch_ccid:batch_ccid},
			dataType:"json",
			success: function(res){
				if(res.code===0){
					util.message(res.msg, '', 'success');
					setTimeout(function(){
						location.reload();
					},1500);
				}else{
					util.message(res.msg, '', 'error');
					return false;
				}
			},
			error: function(error){
				util.message('网络请求超时，请稍后重试', '', 'error');
			}
		});
	});
	/* 批量设置视频分类 END */
</script>