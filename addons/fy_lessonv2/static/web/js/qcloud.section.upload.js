$(function(){
	var bucket = window.uploadConfig.bucket;
	var appid  = window.uploadConfig.appid;
	var region = window.uploadConfig.qcloud_area;
	var cdnUrl = window.uploadConfig.qcloud_url;
	var myFolder = "/" + window.uploadConfig.dirname +"/";

	var cos = new CosCloud({
		appid: appid,
		bucket: bucket,
		region: region,
		getAppSign: function (callback) {
			callback(window.uploadConfig.signature)
		},
		getAppSignOnce: function (callback) {
			callback("")
		}
	});

	var successCallBack = function (result) {
		var res = eval(result);
		var tmpUrl = res.data.source_url.split("myqcloud.com");
		var resTxt = "============恭喜您，上传成功============";
		resTxt += "\n文件链接：http://" + cdnUrl + tmpUrl[1];

		$.ajax({
			url:window.uploadConfig.saveVideoUrl,
			data:{com_name:tmpUrl[1], sys_link:res.data.source_url, size:$("#file_size").val()},
			type:'post',
			dataType:'json',
			success:function(msg){}
		});

		$("#result").val(resTxt);
		$("#videourl").val("http://" + cdnUrl + tmpUrl[1]);
	};

	var errorCallBack = function (result) {
		console.log(result);

		var res = eval(result);
		var errorHtml = "";
		if(result.responseJSON.code=='-70'){
			errorHtml = "【请求的appid和签名中的appid不匹配】";
		}else if(result.responseJSON.code=='-71'){
			errorHtml = "【操作太频繁】";
		}else if(result.responseJSON.code=='-96'){
			errorHtml = "【上传失败，原因：签名已过期，请刷新后重试】";
		}else if(result.responseJSON.code=='-97'){
			errorHtml = "【上传失败，原因：签名校验失败】";
		}else if(result.responseJSON.code=='-177'){
			errorHtml = "【上传失败，原因：同名文件已存在】";
		}else if(result.responseJSON.code=='-178'){
			errorHtml = "【路径冲突，要上传的文件或者目录已经存在】";
		}else if(result.responseJSON.code=='-285'){
			errorHtml = "【上传文件大小大于限制】";
		}else if(result.responseJSON.code=='-5957'){
			errorHtml = "【操作不存在的bucket】";
		}else{
			errorHtml = "【上传失败，请稍后重试】";
		}
		$("#result").val(errorHtml + "\n腾讯云存储返回信息：\n" + result.responseText + "\n 详细错误信息请查看：https://www.qcloud.com/document/product/436/8432");
	};

	var progressCallBack = function (curr) {
		$("#result").val('文件上传中...' + parseInt(curr*100) + '%');
	};

	//分片上传文件,当选择大于20M大小的文件的时候用分片上传
	$('#sliceUploadFile').on('click', function () {
		$('#file').off('change').on('change', function (e) {
			var file = e.target.files[0];
			var file_size = parseFloat((file.size/1024)/1024).toFixed(2);
			var max_img_size = window.uploadConfig.upload_max;
			if (file_size > max_img_size) {
				alert("当前系统上传文件不能超过" + max_img_size + "MB");
				return false;
			}

			$.ajax({
				url:window.uploadConfig.verifyVideoUrl,
				data:{filename:file.name},
				type:'post',
				dataType:'json',
				success:function(res){
					if(res.code==-1){
						alert("文件名不能为空");
						return false;
					}else if(res.code==0){
						$("#result").val("============恭喜您，上传成功============\n文件链接：http://" + cdnUrl + "/admin/" + encodeURIComponent(file.name));
						$("#videourl").val("http://" + cdnUrl + "/admin/" + encodeURIComponent(file.name));
						return false;
					}else{
						$("#file_size").val(file_size);
						cos.uploadFile(successCallBack, errorCallBack, progressCallBack, bucket, myFolder + file.name, file, 0);
						return false;
					}
				}
			});
		});

		setTimeout(function () {
			$('#file').click();
		}, 0);

		return false;
	});
});