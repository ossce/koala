<?php defined('IN_IA') or exit('Access Denied');?><div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">章节信息</div>
            <div class="panel-body">
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">当前课程</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php  echo $lesson['bookname'];?>" readonly/>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">课程目录</label>
                    <div class="col-sm-9">
                        <select name="title_id" class="form-control">
							<option value="">请选择目录...</option>
							<?php  if(is_array($title_list)) { foreach($title_list as $title) { ?>
								<option value="<?php  echo $title['title_id'];?>" <?php  if($title['title_id']==$sections['title_id']) { ?>selected<?php  } ?>><?php  echo $title['title'];?></option>
							<?php  } } ?>
						</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>章节名称</label>
                    <div class="col-sm-9">
                        <input type="text" name="title" class="form-control" value="<?php  echo $sections['title'];?>" />
						<div class="help-block">例如 第一节：初步认识HTML、1-1 初步认识HTML</div>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">章节封面</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_form_field_image('images', $sections['images'])?>
                        <span class="help-block">建议尺寸 600 * 385px，也可根据自己的实际情况做图片尺寸</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>章节类型</label>
                    <div class="col-sm-9">
						<label class="radio-inline"><input type="radio" name="sectiontype" value="1" <?php  if($sections['sectiontype'] == 1) { ?>checked<?php  } ?> /> 视频章节</label>&nbsp;&nbsp;&nbsp;
						<label class="radio-inline"><input type="radio" name="sectiontype" value="3" <?php  if($sections['sectiontype'] == 3) { ?>checked<?php  } ?> /> 音频章节</label>&nbsp;&nbsp;&nbsp;
                        <label class="radio-inline"><input type="radio" name="sectiontype" value="2" <?php  if($sections['sectiontype'] == 2) { ?>checked<?php  } ?> /> 图文章节</label>
                        <label class="radio-inline"><input type="radio" name="sectiontype" value="4" <?php  if($sections['sectiontype'] == 4) { ?>checked<?php  } ?> /> 外链章节</label>
                    </div>
                </div>
				<div class="form-group videoaudio" <?php  if(!in_array($sections['sectiontype'], array('1','3'))) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>存储方式</label>
                    <div class="col-sm-9">
						<?php  if(is_array($saveList)) { foreach($saveList as $key => $item) { ?>
						<label class="radio-inline" id="savetype<?php  echo $key;?>"><input type="radio" name="savetype" value="<?php  echo $key;?>" <?php  if(!empty($sections)) { ?><?php  if($sections['savetype'] == $key) { ?>checked<?php  } ?><?php  } else { ?><?php  if($key<2) { ?><?php  if($setting['savetype']==$key) { ?>checked<?php  } ?><?php  } else if($key>2) { ?><?php  if($setting['savetype']+1==$key) { ?>checked<?php  } ?><?php  } ?><?php  } ?> class="checkSaveType"/> <?php  echo $item;?></label>&nbsp;
						<?php  } } ?>
                    </div>
                </div>
				<div class="form-group videoaudio" <?php  if(!in_array($sections['sectiontype'], array('1','3'))) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>【视频/音频URL】<br/>【点播VideoId】</label>
                    <div class="col-sm-9">
						<textarea id="videourl" name="videourl" class="form-control" style="min-height:70px;"><?php  echo $sections['videourl'];?></textarea>
						<div class="help-block">
							<?php  if($setting['savetype'] != '0') { ?>
							<a id="upload-btn" class="fa-pull-left btn btn-info col-lg-1" style="margin-right:20px;" onclick="popwin = $('#modal-module-upload').modal();">上传音视频</a>
							<?php  } ?>
							其他存储、七牛云存储、腾讯云存储和阿里云OSS请填写音视频url，视频请用mp4，音频请用mp3格式；阿里云点播和腾讯云点播请填写VideoId
						</div>
                    </div>
                </div>
				<?php  if($setting['savetype']==1) { ?>
					<div id="modal-module-upload"  class="modal fade" tabindex="-1">
						<div class="modal-dialog" style="width: 920px;">
							<div class="modal-content">
								<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>上传音视频</h3></div>
								<div class="modal-body" >
									<div class="row">
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane fade in active" id="demo" aria-labelledby="demo-tab">
												<div class="row" style="margin-top: 20px;">
													<input type="hidden" id="domain" value="<?php  echo $qiniu['url'];?>">
													<input type="hidden" id="uptoken_url" value="uptoken">
													<div class="col-md-12">
														<div id="container" style="position: relative;">
															<input type="file" id="pickfiles" class="btn btn-default">
														</div>
													</div>
													<div style="display:none" id="success" class="col-md-12">
														<div class="alert-success" style="margin:20px auto;padding:5px 10px;">所有文件队列处理完成</div>
													</div>
													<div class="col-md-12 ">
														<table class="table table-striped table-hover text-left" style="display:none">
															<thead>
																<tr>
																	<th class="col-md-4">文件名称</th>
																	<th class="col-md-2">文件大小</th>
																	<th class="col-md-6">上传详情</th>
																</tr>
															</thead>
															<tbody id="fsUploadProgress"></tbody>
														</table>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="code" aria-labelledby="code-tab">
											</div>
										</div>
									</div>
									<div id="module-member" style="padding-top:5px;"></div>
								</div>
								<div class="modal-footer" style="padding-top:0;"><a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
							</div>
						</div>
					</div>
				<?php  } else if($setting['savetype']==2) { ?>
					<div id="modal-module-upload"  class="modal fade" tabindex="-1">
						<div class="modal-dialog" style="width: 920px;">
							<div class="modal-content">
								<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>上传音视频</h3></div>
								<div class="modal-body" >
									<div class="row">
										<input type="file" id="file" accept="audio/mp3,video/*" style="display:none;">
										<input type="hidden" id="file_size" value="0" style="display:none;">
										<div style="margin-left:5px;">
											<div class="field">
												<input type="button" class="btn btn-default" id="sliceUploadFile" value="上传音视频">
											</div>
										</div>
										<div class="row" style="padding:20px;">
											<textarea id="result" class="form-control" rows="8"></textarea>
										</div>
									</div>
									<div id="module-member" style="padding-top:5px;"></div>
								</div>
								<div class="modal-footer" style="padding-top:0;"><a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
							</div>
						</div>
					</div>
				<?php  } else if($setting['savetype']==3) { ?>
					<div id="modal-module-upload"  class="modal fade" tabindex="-1">
						<div class="modal-dialog" style="width: 920px;">
							<div class="modal-content">
								<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>上传音视频</h3></div>
								<div class="modal-body" >
									<div class="row">
										<input type="file" id="files" accept="video/*,audio/mp3" class="btn btn-default"/>
										<br/>
										<textarea id="textarea" rows="10" style="margin-top:15px;width:98%;border-color:#ddd;" readonly></textarea>
									</div>
									<div id="module-member" style="padding-top:5px;"></div>
								</div>
								<div class="modal-footer" style="padding-top:0;"><a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
							</div>
						</div>
					</div>
				<?php  } else if($setting['savetype']==4) { ?>
					<div id="modal-module-upload"  class="modal fade" tabindex="-1">
						<div class="modal-dialog" style="width: 920px;">
							<div class="modal-content">
								<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>上传音视频</h3></div>
								<div class="modal-body" style="padding-top: 0;">
									<div class="row">
										<input id="uploadVideoNow-file" type="file" accept="video/*,audio/mp3" style="display:none;">
										<input type="hidden" id="file_size"  value="0">
										<input type="hidden" id="filename"  value="">
										<div style="margin:10px auto;">
											<div class="field">
												<input type="button" class="btn btn-default" id="uploadVideoNow" value="上传视频">
											</div>
										</div>
										<div class="row" id="resultBox" style="height: 200px; border: 1px solid #ccc; overflow: auto; margin: 0; padding:10px;"></div>
									</div>
									<div id="module-member" style="padding-top:5px;"></div>
								</div>
								<div class="modal-footer" style="padding-top:0;"><a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
							</div>
						</div>
					</div>
				<?php  } else if($setting['savetype']==5) { ?>
					<div id="modal-module-upload"  class="modal fade" tabindex="-1">
						<div class="modal-dialog" style="width: 920px;">
							<div class="modal-content">
								<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>上传音视频</h3></div>
								<div class="modal-body" >
									<div class="row">
										<input type="radio" name="myradio" value="local_name" checked style="display:none;"/>
										<h4 style="font-size:15px;">您所选择的文件列表：</h4>
										<div id="ossfile">你的浏览器不支持flash或Silverlight！</div>
										<br/>
										<div id="container">
											<a id="selectfiles" href="javascript:void(0);" class='btn btn-default' style="border-color:#d8d8d8; margin-right:10px;">选择文件</a>
											<a id="postfiles" href="javascript:void(0);" class='btn btn-primary'>开始上传</a>
										</div>
										<pre id="console" style="margin-top:25px; display:none;"></pre>
									</div>
									<div id="module-member" style="padding-top:5px;"></div>
								</div>
								<div class="modal-footer" style="padding-top:0;"><a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
							</div>
						</div>
					</div>
				<?php  } ?>
				<div class="form-group videoaudio" <?php  if(!in_array($sections['sectiontype'], array('1','3'))) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">密码访问</label>
                    <div class="col-sm-9">
						<input type="text" name="password" class="form-control" value="<?php  echo $sections['password'];?>" />
						<div class="help-block">设置密码访问后，必须输入密码才能进入该章节，仅支持音频和视频章节</div>
                    </div>
                </div>
				<div class="form-group videoaudio" <?php  if(!in_array($sections['sectiontype'], array('1','3'))) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">视频/音频时长</label>
                    <div class="col-sm-9">
                        <input type="text" name="videotime" id="videotime" class="form-control fa-pull-left" value="<?php  echo $sections['videotime'];?>" style="width:150px;margin-right:10px;" placeholder="如13:48, 95:32"/>
						<a id="checkMediaTime" class="fa-pull-left btn btn-info col-lg-1" <?php  if(!in_array($sections['savetype'], array('4','5')) && !in_array($setting['savetype'], array('3','4'))) { ?>style="display:none;"<?php  } ?>>自动填写</a>
                    </div>
                </div>
                <div class="form-group linkurl" <?php  if($sections['sectiontype']!=4) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>外链URL</label>
                    <div class="col-sm-9">
						<textarea  name="linkurl" class="form-control" style="min-height:100px;"><?php  echo $sections['videourl'];?></textarea>
						<div class="help-block">外链章节将跳转到其他页面，填写完整的外链链接url，包括http://</div>
                    </div>
                </div>
				<div class="form-group scontent" <?php  if($sections['sectiontype']==4) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">章节内容</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_ueditor('content', $sections['content']);?>
						<div class="help-block">请填写章节内容</div>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9">
                        <input type="text" name="displayorder" class="form-control" value="<?php  echo $sections['displayorder'];?>" />
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>试听章节</label>
                    <div class="col-sm-9">
                        <label class="radio-inline"><input type="radio" name="is_free" value="1" <?php  if($sections['is_free']==1) { ?>checked<?php  } ?> /> 是</label>
                        &nbsp;&nbsp;&nbsp;
                        <label class="radio-inline"><input type="radio" name="is_free" value="0" <?php  if(empty($sections) || $sections['is_free'] == 0) { ?>checked<?php  } ?> /> 否</label>
                        <span class="help-block"></span>
                    </div>
                </div>
				<div class="form-group test_time " <?php  if($sections['is_free']!=1) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">试听时间</label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" name="test_time" value="<?php  echo $sections['test_time'];?>" class="form-control">
							<span class="input-group-addon">秒</span>
						</div>
						<div class="help-block">
							0为试听整个章节，图文章节填写大于0表示仅试读部分内容，音视频章节仅支持七牛云对象存储、腾讯云对象存储、阿里云点播、腾讯云点播和阿里云OSS存储方式
						</div>
					</div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red;font-weight:bolder;">*</span>章节状态</label>
                    <div class="col-sm-9">
                        <label class="radio-inline"><input type="radio" name="status" value="1" <?php  if(empty($sections) || $sections['status'] == 1) { ?>checked<?php  } ?> /> 上架</label>
                        <label class="radio-inline"><input type="radio" name="status" value="0" <?php  if(!empty($sections) && $sections['status'] == 0) { ?>checked<?php  } ?> /> 下架</label>
                        <label class="radio-inline"><input type="radio" name="status" value="2" <?php  if($sections['status'] == 2) { ?>checked<?php  } ?> /> 审核中</label>
                    </div>
                </div>
				<div class="form-group auto-show" <?php  if(empty($sections) || $sections['status']) { ?>style="display:none;"<?php  } ?>>
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">定时上架</label>
                    <div class="col-sm-9">
                        <label class="radio-inline"><input type="radio" name="auto_show" value="0" <?php  if(empty($sections) || $sections['auto_show'] == 0) { ?>checked<?php  } ?> /> 关闭</label>
						&nbsp;&nbsp;&nbsp;
						<label class="radio-inline"><input type="radio" name="auto_show" value="1" <?php  if($sections['auto_show'] == 1) { ?>checked<?php  } ?> /> 开启</label>
						<label><?php echo _tpl_form_field_date('show_time', $sections['show_time'] >0 ? date('Y-m-d H:i:s', $sections['show_time']) : '请选择', true);?></label>
						<span class="help-block">开启后，当前时间大于上架时间时，章节会自动显示</span>
                    </div>
                </div>
				<?php  if($sections['id']) { ?>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">章节链接(手机端)</label>
                    <div class="col-sm-9">
                        <div style="padding-top:8px;font-size: 14px;"><a href="javascript:;" id="copy-btn"><?php  echo $_W['siteroot'];?>app/<?php  echo str_replace("./", "", $this->createMobileUrl('lesson', array('id'=>$lesson['id'])));?>&amp;sectionid=<?php  echo $sections['id'];?></div>
					</div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">章节链接(PC端)</label>
                    <div class="col-sm-9">
                        <div style="padding-top:8px;font-size: 14px;"><a href="javascript:;" id="copy-pc-btn"><?php  echo $setting_pc['site_root'];?><?php  echo $uniacid;?>/lesson.html?id=<?php  echo $lesson['id'];?>&amp;sectionid=<?php  echo $sections['id'];?></a></div>
                    </div>
                </div>
				<?php  } ?>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			<input type="hidden" name="id" value="<?php  echo $id;?>" />
			<input type="hidden" name="pid" value="<?php  echo $pid;?>" />
        </div>
    </form>
</div>
<script type="text/javascript">
require(['jquery', 'util'], function($, util){
	$(function(){
		util.clip($("#copy-btn")[0], $("#copy-btn").text());
		util.clip($("#copy-pc-btn")[0], $("#copy-pc-btn").text());
	});
});

$(".checkSaveType").click(function(){
	//内嵌代码方式自动添加iframe
	if(this.value==2){
		document.getElementById("videourl").value = "<iframe  frameborder=0  width=100%  height=220px  src=这里替换内嵌视频地址  allowfullscreen></iframe>";
	}

	//阿里云点播和腾讯云点播显示自动填写音视频时长
	if(this.value==4 || this.value==5){
		document.getElementById("checkMediaTime").style.display = 'block';
	}else{
		document.getElementById("checkMediaTime").style.display = 'none';
	}

	//当前默认存储方式支持上传音视频
	var setting_savetype = "<?php  echo $setting['savetype'];?>";
	if((this.value==1 && setting_savetype==1) || (this.value==3 && setting_savetype==2) || (this.value==4 && setting_savetype==3) || (this.value==5 && setting_savetype==4) || (this.value==6 && setting_savetype==5)){
		$("#upload-btn").show();
	}else{
		$("#upload-btn").hide();
	}
});

$(function() {
	$(':radio[name="sectiontype"]').click(function() {
		if($(this).val() == '1') {
			//视频章节
			$(".videoaudio").show();
			$(".videotype").show();
			$(".scontent").show();
			$(".linkurl").hide();
			$("#savetype2").show();
		} else if($(this).val() == '2') {
			//图文章节
			$(".videoaudio").hide();
			$(".videotype").hide();
			$(".scontent").show();
			$(".linkurl").hide();
		} else if($(this).val() == '3') {
			//音频章节
			$(".videoaudio").show();
			$(".videotype").hide();
			$(".scontent").show();
			$(".linkurl").hide();
			$("#savetype2").hide();
		} else if($(this).val() == '4') {
			//外链章节
			$(".videoaudio").hide();
			$(".videotype").hide();
			$(".scontent").hide();
			$(".linkurl").show();
		}
	});

	$(':radio[name="status"]').click(function() {
		if($(this).val()==0){
			$(".auto-show").show();
		}else{
			$(".auto-show").hide();
		}
	});

	$(':radio[name="is_free"]').click(function() {
		var sectiontype = $("input[name='sectiontype']:checked").val();

		if($(this).val()==1){
			$(".test_time").show();
		}else{
			$(".test_time").hide();
		}
	});
});

//音频章节隐藏内嵌代码方式
<?php  if($sections['sectiontype'] == 3) { ?>
	$("#savetype2").hide();
<?php  } ?>

$("#checkMediaTime").click(function(){
	 var savetype = document.getElementsByName("savetype");
	 var videoid = document.getElementById("videourl").value;
	 var select_savetype = null;
	 for(var i=0;i<savetype.length;i++){
		if(savetype[i].checked==true) {
			select_savetype = savetype[i].value;
			break;
		}
	}
	if(select_savetype!=4 && select_savetype!=5){
		alert('当前所选存储方式不支持自动填写，请手动填写');
		return false;
	}

	$.ajax({
		url: "<?php  echo $this->createWebUrl('lesson', array('op'=>'postsection'))?>",
		type: "GET",
		data:{
			pid:"<?php  echo $pid;?>",
			getMediaTime:1,
			savetype: select_savetype,
			videoid: videoid,
		},
		dataType: "json",
		success: function(res){
			if(res.code==0){
				document.getElementById("videotime").value = res.duration;
			}else{
				alert('获取音视频时长失败，请手动输入');
				console.log(res.msg);
				return false;
			}
		},
		error:function(err){
			alert('获取音视频时长失败，请手动输入');
			console.log('网络请求出错，请稍候重试');
			return false;
		}
	});
});
</script>

<script type="text/javascript">
	window.uploadConfig = <?php  echo json_encode($uploadConfig);?>;
</script>

<?php  if($setting['savetype']==1) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/moxie.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/plupload.full.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/zh_CN.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/ui.js?v=<?php  echo $versions;?>"></script>
	<?php  if($qiniu['qiniu_area']==1) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/qiniu_huadong.js"></script>
	<?php  } else if($qiniu['qiniu_area']==2) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/qiniu_huabei.js"></script>
	<?php  } else if($qiniu['qiniu_area']==3) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/qiniu_huanan.js"></script>
	<?php  } else if($qiniu['qiniu_area']==4) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/qiniu_beimei.js"></script>
	<?php  } ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/highlight.js"></script>
	<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/web/js/qiniu.section.upload.js?v=<?php  echo $versions;?>"></script>

<?php  } else if($setting['savetype']==2) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/web/js/qcloud.section.upload.js?v=<?php  echo $versions;?>"></script>

<?php  } else if($setting['savetype']==3) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/web/js/aliyunVod.section.upload.js?v=<?php  echo $versions;?>"></script>

<?php  } else if($setting['savetype']==4) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/web/js/qcloudVod.section.upload.js?v=<?php  echo $versions;?>"></script>

<?php  } else if($setting['savetype']==5) { ?>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>library/aliyunOSS/oss-h5-upload-js/plupload-2.1.2/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="<?php echo MODULE_URL;?>static/web/js/aliyunOSS.upload.js?v=<?php  echo $versions;?>"></script>
<?php  } ?>