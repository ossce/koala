$(function() {
	var uploader = Qiniu.uploader({
		runtimes: 'html5,flash,html4',
		browse_button: 'pickfiles',
		container: 'container',
		drop_element: 'container',
		flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
		dragdrop: true,
		chunk_size: '4mb',
		max_file_size: window.uploadConfig.upload_max + "mb",
		uptoken: window.uploadConfig.token,
		multi_selection: !(mOxie.Env.OS.toLowerCase()==="ios"),
		filters : {
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
				document.getElementById("videourl").value = window.uploadConfig.qiniu_url + window.uploadConfig.dirname + "/" + file.name;				
			},
			'Key': function(up, file) {
				var key = window.uploadConfig.dirname + "/" + file.name;
				$.ajax({
					url: window.uploadConfig.saveVideoUrl,
					data:{name:file.name, com_name:key, size:file.size},
					type:'post',
					dataType:'json',
					success:function(res){
						if(res.code == -1){
							console.log('已存在文件名：' + file.name);
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