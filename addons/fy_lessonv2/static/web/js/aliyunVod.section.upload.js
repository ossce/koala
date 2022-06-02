var uploader = new AliyunUpload.Vod({
	retryCount: 5,
    'onUploadFailed': function (uploadInfo, code, message) {
        log("上传文件失败：" + uploadInfo.file.name + ",错误代码:" + code + ", 错误信息:" + message);
    },
    'onUploadSucceed': function (uploadInfo) {
		var file_size = parseFloat((uploadInfo.file.size/1024)/1024).toFixed(2);
		$.ajax({
			url: window.uploadConfig.saveVideoUrl,
			data:{
				filename: uploadInfo.file.name,
				object: uploadInfo.object,
				videoId: uploadInfo.videoId,
				size: file_size
			},
			type:'post',
			dataType:'json',
			success:function(msg){
			}
		});
        log("上传文件成功：" + uploadInfo.file.name + ", VideoId： " + uploadInfo.videoId);
		$("#videourl").val(uploadInfo.videoId);
		setTimeout(function(){
			$("#checkMediaTime").click();
		},3000);
    },
    'onUploadProgress': function (uploadInfo, totalSize, loadedPercent) {
        log("正在上传:文件名：" + uploadInfo.file.name + ", 大小:" + ((totalSize/1024)/1024).toFixed(2) + "MB, 进度:" + (loadedPercent * 100.00).toFixed(2) + "%");
    },
	// 上传凭证超时
	'onUploadTokenExpired': function (uploadInfo) {
		$.ajax({
			type: "POST",
			url:  window.uploadConfig.refTokenUrl,
			data: {videoId:uploadInfo.videoId},
			dataType: "json",
			success: function(res){
				uploader.resumeUploadWithAuth(res.UploadAuth);
			},
			fail: function(res){
				log("获取上传信息失败，请刷新重试!");
			}
		});
		
	},
    onUploadCanceled:function(uploadInfo){
        log("用户取消上传文件：" + uploadInfo.file.name);
    },
    'onUploadstarted': function (uploadInfo) {
		$.ajax({
			type: "POST",
			url: window.uploadConfig.getTokenUrl,
			data: {filename:uploadInfo.file.name},
			dataType: "json",
			success: function(res){
				uploader.setUploadAuthAndAddress(uploadInfo, res.UploadAuth, res.UploadAddress,res.VideoId);
			},
			fail: function(res){
				log("获取上传信息失败，请刷新重试!");
			}
		});
        log("开始上传文件：" + uploadInfo.file.name);
    }
    ,
    'onUploadEnd':function(uploadInfo){
        log("上传结束：已成功上传文件");
    }
});

document.getElementById("files").addEventListener('change', function (event) {
	var userData;
	var max_size = parseInt(window.uploadConfig.upload_max)*1024*1024;
	for(var i=0; i<event.target.files.length; i++) {
		if(max_size>0 && event.target.files[i].size>max_size){
			alert("单个视频最大只能上传" + window.uploadConfig.upload_max + "MB，请重新选择");
			return;
		}
		userData = '{"Vod":{"StorageLocation":"","Title":"'+event.target.files[i].name+'","Description":"","CateId":"","Tags":"","UserData":""}}';
		log("添加待上传文件: " + event.target.files[i].name);
		uploader.addFile(event.target.files[i], null, null, null, userData);
	}
	uploader.startUpload();
});

var textarea=document.getElementById("textarea");
function log(value) {
    if (!value) {
        return;
    }

    $("#textarea").append(value+'\n');
	textarea.scrollTop  = '100000000';
}