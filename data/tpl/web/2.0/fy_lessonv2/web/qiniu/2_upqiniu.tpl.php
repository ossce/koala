<?php defined('IN_IA') or exit('Access Denied');?><link rel="stylesheet" href="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/main.css">
<link rel="stylesheet" href="<?php echo MODULE_URL;?>library/Qiniu/plupload-h5-v2/highlight.css">

<div class="main">
	<div class="alert alert-info">
	    音频请上传<span style="color:red;">mp3</span>格式文件，视频请上传<span style="color:red;">H.264编码的mp4</span>格式文件，否则部分ios系统手机将无法识别音视频格式。
	</div>
	<div class="panel-body">
		<form class="form-horizontal">
			<div class="form-group" style="margin-bottom:0;">
				<label class="col-lg-1 control-label" style="width:60px;">分类信息</label>
				<div class="col-xs-12 col-lg-2">
					<select class="form-control" id="pid" onchange="firstCategory(this.value)">
						<option value="0" selected="selected">请选择一级分类</option>
						<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
						<option value="<?php  echo $item['id'];?>"><?php  echo $item['name'];?></option>
						<?php  } } ?>
					</select>
				</div>
				<div class="col-xs-12 col-lg-2">
					<select class="form-control" id="cid" onchange="secordCategory(this.value)">
						<option value="0">请选择二级分类</option>
					</select>
				</div>
				<div class="col-xs-12 col-lg-2">
					<select class="form-control" id="ccid">
						<option value="0">请选择三级分类</option>
					</select>
				</div>
			</div>
		</form>
	</div>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="demo" aria-labelledby="demo-tab">
			<div class="row" style="margin-top: 20px;">
				<input type="hidden" id="domain" value="<?php  echo $qiniu['url'];?>">
				<input type="hidden" id="uptoken_url" value="uptoken">
				<div class="col-md-12">
					<div id="container" style="position: relative;">
						<input type="file" id="pickfiles" class="btn btn-default" name="video">
					</div>
				</div>
				<div style="display:none" id="success" class="col-md-12">
					<div class="alert-success">
						队列全部文件处理完毕
					</div>
				</div>
				<div class="col-md-12 ">
					<table class="table table-striped table-hover text-left" style="margin-top:40px;display:none">
						<thead>
							<tr>
								<th class="col-md-4">文件名称</th>
								<th class="col-md-2">文件大小</th>
								<th class="col-md-6">上传详情</th>
							</tr>
						</thead>
						<tbody id="fsUploadProgress">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="code" aria-labelledby="code-tab">
		</div>
	</div>
</div>

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
<script type="text/javascript">
	/* 选择分类信息 START */
	var category = <?php  echo json_encode($category_list)?>;
	function firstCategory(pid){
		var chtml2 = '<option value="0">请选择二级分类</option>';
		var chtml3 = '<option value="0">请选择三级分类</option>';

		if(pid>0){
			for(var i in category){
				if(category[i].id==pid){
					var second = category[i].second;
					for(var j in second){
						chtml2 += '<option value="' + second[j].id+'">' + second[j].name + '</option>';
					}
					$("#cid").html(chtml2);
					$("#ccid").html(chtml3);
				}
			}
		}else{
			$("#cid").html(chtml2);
			$("#ccid").html(chtml3);
		}
	}

	function secordCategory(cid){
		var pid = $("#pid").val();
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
					$("#ccid").html(chtml3);
				}
			}
		}else{
			$("#ccid").html(chtml3);
		}
	}
	/* 选择分类信息 END */


	$(function() {
		var uploader = Qiniu.uploader({
			runtimes: 'html5,flash,html4',
			browse_button: 'pickfiles',
			container: 'container',
			drop_element: 'container',
			flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
			dragdrop: true,
			chunk_size: '4mb',
			uptoken: "<?php  echo $token; ?>",
			multi_selection: !(mOxie.Env.OS.toLowerCase()==="ios"),
			filters : {
				max_file_size : "2048mb",
				prevent_duplicates: true,
				mime_types: [
					{title : "Audio files", extensions : "mp3"},
					{title : "Video files", extensions : "mp4,wmv,flv"},
				]
			},
			domain: $('#domain').val(),
			get_new_uptoken: false,
			auto_start: true,
			log_level: 1,
			init: {
				'FilesAdded': function(up, files) {
					$('table').show();
					$('#success').hide();
					plupload.each(files, function(file) {
						var progress = new FileProgress(file, 'fsUploadProgress');
						progress.setStatus("等待...");
						progress.bindUploadCancel(up);
					});
				},
				'BeforeUpload': function(up, file) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
					if (up.runtime === 'html5' && chunk_size) {
						progress.setChunkProgess(chunk_size);
					}
				},
				'UploadProgress': function(up, file) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
					progress.setProgress(file.percent + "%", file.speed, chunk_size);
				},
				'UploadComplete': function(file) {
					$('#success').show();
				},
				'FileUploaded': function(up, file, info) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					progress.setComplete(up, info);
					
				},
				'Key': function(up, file) {
					var key = "admin/"+file.name;
					$.ajax({
						url:"<?php  echo $this->createWebUrl('video', array('op'=>'saveQiniuUrl')); ?>",
						data:{
							name:file.name,
							com_name:key,
							size:file.size,
							pid: $("#pid").val(),
							cid: $("#cid").val(),
							ccid: $("#ccid").val(),
						},
						type:'post',
						dataType:'json',
						success:function(res){
							if(res.code == -1){
								alert('已存在文件名：' + file.name);
							}
						}
					});

					return key;
				},
				'Error': function(up, err, errTip) {
					$('table').show();
					var progress = new FileProgress(err.file, 'fsUploadProgress');
					progress.setError();
					progress.setStatus(errTip);
				}
			}
		});

		uploader.bind('FileUploaded', function() {
		});
		$('#container').on(
			'dragenter',
			function(e) {
				e.preventDefault();
				$('#container').addClass('draging');
				e.stopPropagation();
			}
		).on('drop', function(e) {
			e.preventDefault();
			$('#container').removeClass('draging');
			e.stopPropagation();
		}).on('dragleave', function(e) {
			e.preventDefault();
			$('#container').removeClass('draging');
			e.stopPropagation();
		}).on('dragover', function(e) {
			e.preventDefault();
			$('#container').addClass('draging');
			e.stopPropagation();
		});

		$('#show_code').on('click', function() {
			$('#myModal-code').modal();
			$('pre code').each(function(i, e) {
				hljs.highlightBlock(e);
			});
		});

		$('body').on('click', 'table button.btn', function() {
			$(this).parents('tr').next().toggle();
		});

	});
</script>