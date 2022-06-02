<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
	<form>
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-hover">
					<thead class="navbar-inner">
						<tr>
							<th style="width:60px;">全选</th>
							<th style="width:10%;text-align:center;">预览</th>
							<th style="width:25%;">章节名称</th>
							<th style="width:11%;text-align:center;">试听章节</th>
							<th style="width:11%;text-align:center;">章节类型</th>
							<th style="width:11%;text-align:center;">章节状态</th>
							<th style="width:13%;text-align:center;">添加时间</th>
							<th style="width:14%;text-align:center;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php  if(is_array($list)) { foreach($list as $key => $sec) { ?>
						<tr>
							<td style="width:60px;">
								<input type="checkbox" name="ids[]" value="<?php  echo $sec['id'];?>">
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
								<input type="checkbox" id="selAll" style="margin-right:20px;">
								<a href="javascript:;" id="setStatus" class="btn btn-success" style="margin-right:20px;">批量审核通过</a>
							</td>
						</tr>
					</tbody>
				</table>
			 </div>
		 </div>
		 <?php  echo $pager;?>
	</form>
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

	function checkStatus(){
		var checkids = "";
		for(var i=0; i<ids.length; i++){
			if(ids[i].checked){
				checkids += (checkids === '' ? ids[i].value : ',' + ids[i].value);
			}
		}
		if(checkids===''){
			alert('未选中任何章节');
			return false;
		}else{
			return checkids;
		}
	}

	var sectionids = '';
	//批量修改状态
	$("#setStatus").click(function(){
		sectionids = checkStatus();

		if(sectionids && confirm('确认批量审核章节?')){
			$.ajax({
				type: "POST",
				url: "<?php  echo $this->createWebUrl('lesson',array('op'=>'auditsection'));?>",
				data: {sectionids:sectionids},
				dataType:"json",
				success: function(res){
					if(res.code===0){
						alert(res.msg);
						location.reload();
					}else{
						alert('系统繁忙，请稍后重试');
						return false;
					}
				},
				error: function(error){
					alert('网络请求超时，请稍后重试');
					return false;
				}
			});
		}
	});
</script>