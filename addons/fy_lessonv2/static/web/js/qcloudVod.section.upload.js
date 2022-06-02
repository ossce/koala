var index = 0;
var cosBox = [];

/** 
 * 计算签名
**/
var getSignature = function(callback){
	$.ajax({
		url: window.uploadConfig.getTokenUrl,
		type: 'POST',
		data: {filename: $("#filename").val()},
		dataType: 'json',
		success: function(res){
			if(res && res.signature) {
				callback(res.signature);
			} else {
				return '获取签名失败';
			}
		}
	});
};

/**
 * 添加上传信息模块
 */
var addUploaderMsgBox = function(type){
	var html = '<div class="uploaderMsgBox" name="box'+index+'">';
	if(!type || type == 'hasVideo') {
		html += '视频名称：<span name="videoname'+index+'"></span><br/>' + 
			'计算sha进度：<span name="videosha'+index+'">0%</span><br/>' + 
			'上传进度：<span name="videocurr'+index+'">0%</span><br/>' + 
			'videoId：<span name="videofileId'+index+'">   </span><br/>' + 
			'上传结果：<span name="videoresult'+index+'">   </span><br/><br>';
	}
	
	if(!type || type == 'hasCover') {
		html += '封面名称：<span name="covername'+index+'"></span>；' + 
		'计算sha进度：<span name="coversha'+index+'">0%</span>；' + 
		'上传进度：<span name="covercurr'+index+'">0%</span>；' + 
		'上传结果：<span name="coverresult'+index+'">   </span>；<br>' + 
		'地址：<span name="coverurl'+index+'">   </span>；<br>' + 
		'</div>'
	}
	html += '</div>';
	
	$('#resultBox').append(html);
	return index++;
};

/** 
 * 直接上传视频
**/
$('#uploadVideoNow-file').on('change', function (e) {

	var file = e.target.files[0];
	var file_size = parseFloat((file.size/1024)/1024).toFixed(2);
	$("#file_size").val(file_size);
	$("#filename").val(file.name);

	var maxUploadSize = parseInt(window.uploadConfig.upload_max);
	if (maxUploadSize>0 && file.size > maxUploadSize * 1024 * 1024) {
		alert("上传错误：当前系统限制最大上传文件 " + maxUploadSize + "MB");
		return;
	}

	var num = addUploaderMsgBox('hasVideo');
	var videoFile = this.files[0];
	$('#result').append(videoFile.name +　'\n');
	var resultMsg = qcVideo.ugcUploader.start({
		videoFile: videoFile,
		getSignature: getSignature,
		allowAudio: 1,
		success: function(result){
			if(result.type == 'video') {
				$('[name=videoresult'+num+']').text('上传成功');
				$('[name=cancel'+num+']').remove();
				cosBox[num] = null;
			} else if (result.type == 'cover') {
				$('[name=coverresult'+num+']').text('上传成功');
			}
		},
		error: function(result){
			$('.uploaderMsgBox').append('<br>上传失败，原因：'+result.msg);
		},
		progress: function(result){
			if(result.type == 'video') {
				$('[name=videoname'+num+']').text(result.name);
				$('[name=videosha'+num+']').text(Math.floor(result.shacurr*100)+'%');
				$('[name=videocurr'+num+']').text(Math.floor(result.curr*100)+'%');
				$('[name=cancel'+num+']').attr('taskId', result.taskId);
				cosBox[num] = result.cos;
			} else if (result.type == 'cover') {
				$('[name=covername'+num+']').text(result.name);
				$('[name=coversha'+num+']').text(Math.floor(result.shacurr*100)+'%');
				$('[name=covercurr'+num+']').text(Math.floor(result.curr*100)+'%');
			}
			
		},
		finish: function(result){
			$('[name=videofileId'+num+']').text(result.fileId);
			$('[name=videourl'+num+']').text(result.videoUrl);
			if(result.message) {
				$('[name=videofileId'+num+']').text(result.message);
			}
			saveVideo(result);
			$("#videourl").val(result.fileId);
			setTimeout(function(){
				$("#checkMediaTime").click();
			},3000);
		}
	});
	if(resultMsg){
		$('[name=box'+num+']').text(resultMsg);
	}
});

$('#uploadVideoNow').on('click', function () {
	$('#uploadVideoNow-file').click();
});

function saveVideo(result){
	$.ajax({
		url: window.uploadConfig.saveVideoUrl,
		data:{
			filename: result.videoName,
			videourl: result.videoUrl,
			videoid: result.fileId,
			size: $("#file_size").val()
		},
		type:'POST',
		dataType:'json',
		success:function(msg){
		}
	});
}