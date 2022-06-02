accessid = ''
accesskey = ''
host = ''
policyBase64 = ''
signature = ''
callbackbody = ''
filename = ''
key = ''
expire = 0
g_object_name = ''
g_object_name_type = ''
now = timestamp = Date.parse(new Date()) / 1000;

function send_request() {
	var xmlhttp = null;
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (xmlhttp != null) {
		serverUrl = window.uploadConfig.serverUrl
		xmlhttp.open("GET", serverUrl, false);
		xmlhttp.send(null);
		return xmlhttp.responseText
	} else {
		alert("Your browser does not support XMLHTTP.");
	}
};

function check_object_radio() {
	var tt = document.getElementsByName('myradio');
	for (var i = 0; i < tt.length; i++) {
		if (tt[i].checked) {
			g_object_name_type = tt[i].value;
			break;
		}
	}
}

function get_signature() {
	// 可以判断当前expire是否超过了当前时间， 如果超过了当前时间， 就重新取一下，3s 作为缓冲。
	now = timestamp = Date.parse(new Date()) / 1000;
	if (expire < now + 3) {
		body = send_request()
		var obj = eval("(" + body + ")");
		host = obj['host']
		policyBase64 = obj['policy']
		accessid = obj['accessid']
		signature = obj['signature']
		expire = parseInt(obj['expire'])
		callbackbody = obj['callback']
		key = obj['dir']
		return true;
	}
	return false;
};

function random_string(len) {
	len = len || 32;
	var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
	var maxPos = chars.length;
	var pwd = '';
	for (i = 0; i < len; i++) {
		pwd += chars.charAt(Math.floor(Math.random() * maxPos));
	}
	return pwd;
}

function get_suffix(filename) {
	pos = filename.lastIndexOf('.')
	suffix = ''
	if (pos != -1) {
		suffix = filename.substring(pos)
	}
	return suffix;
}

function calculate_object_name(filename) {
	if (g_object_name_type == 'local_name') {
		g_object_name += "${filename}"
	} else if (g_object_name_type == 'random_name') {
		suffix = get_suffix(filename)
		g_object_name = key + random_string(10) + suffix
	}
	return ''
}


function set_upload_param(up, filename, ret) {
	if (ret == false) {
		ret = get_signature()
	}
	g_object_name = key;
	if (filename != '') {
		suffix = get_suffix(filename)
		calculate_object_name(filename)
	}
	new_multipart_params = {
		'key': g_object_name,
		'policy': policyBase64,
		'OSSAccessKeyId': accessid,
		'success_action_status': '200', //让服务端返回200,不然，默认会返回204
		'callback': callbackbody,
		'signature': signature,
		'x-oss-forbid-overwrite': 'true',
	};

	up.setOption({
		'url': host,
		'multipart_params': new_multipart_params
	});

	up.start();
}

var uploader = new plupload.Uploader({
	runtimes: 'html5,flash,silverlight,html4',
	browse_button: 'selectfiles',
	//multi_selection: false,
	container: document.getElementById('container'),
	flash_swf_url: 'lib/plupload-2.1.2/js/Moxie.swf',
	silverlight_xap_url: 'lib/plupload-2.1.2/js/Moxie.xap',
	url: 'http://oss.aliyuncs.com',

	filters: {
		mime_types: [ //只允许上传图片和zip文件
			{
				title: "Video files",
				extensions: "mp4,flv,wmv"
			},
			{
				title: "Audio files",
				extensions: "mp3"
			}
		],
		max_file_size: window.uploadConfig.upload_max + 'mb', //上传文件最大限制
		prevent_duplicates: true //不允许选取重复文件
	},

	init: {
		PostInit: function() {
			document.getElementById('ossfile').innerHTML = '';
			document.getElementById('postfiles').onclick = function() {
				set_upload_param(uploader, '', false);
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(
						file.size) + ')<b></b>' +
					'<div class="progress" style="width:300px;"><div class="progress-bar" style="width: 0%"></div></div>' +
					'</div>';
			});
		},

		BeforeUpload: function(up, file) {
			check_object_radio();
			set_upload_param(up, file.name, true);
		},

		UploadProgress: function(up, file) {
			var d = document.getElementById(file.id);
			d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			var prog = d.getElementsByTagName('div')[0];
			var progBar = prog.getElementsByTagName('div')[0]
			progBar.style.width = 3 * file.percent + 'px';
			progBar.setAttribute('aria-valuenow', file.percent);
		},

		FileUploaded: function(up, file, info) {
			if (info.status == 200) {
				$.ajax({
					url: window.uploadConfig.saveVideoUrl,
					data: {
						file_name: file.name,
						size: file.size,
						pid: window.pid ? window.pid : 0,
						cid: window.cid ? window.cid : 0,
						ccid: window.ccid ? window.ccid : 0,
					},
					type: 'post',
					dataType: 'json',
					success: function(msg) {}
				});
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = "上传成功, 视频链接：http://" + window.uploadConfig.bucket_url +"/" + window.uploadConfig.dirname + "/" + file.name;
				if(window.uploadConfig.sectionUpload == true){
					document.getElementById("videourl").value = "http://" + window.uploadConfig.bucket_url +"/" + window.uploadConfig.dirname + "/" + file.name;
				}
			} else if (info.status == 203) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML =
					'上传到OSS成功，但是oss访问用户设置的上传回调服务器失败，失败原因是:' + info.response;
			} else {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
			}
		},

		Error: function(up, err) {
			console.log(err);
			document.getElementById('console').style.display = 'block';
			if (err.code == -600) {
				document.getElementById('console').appendChild(document.createTextNode("\n选择的文件太大，请重新选择"));
			} else if (err.code == -601) {
				document.getElementById('console').appendChild(document.createTextNode("\n选择的文件类型不支持，请重新选择"));
			} else if (err.code == -602) {
				document.getElementById('console').appendChild(document.createTextNode("\n该文件已选择，请勿重复添加"));
			} else if (err.status == 409) {
				document.getElementById('console').appendChild(document.createTextNode("\n文件：" + err.file.name + " 已存在，请勿重复上传"));
				if(window.uploadConfig.sectionUpload == true){
					document.getElementById("videourl").value = "http://" + window.uploadConfig.bucket_url + "/" + window.uploadConfig.dirname + "/" + err.file.name;
				}
			} else if (err.status == -200) {
				document.getElementById('console').appendChild(document.createTextNode("\n文件：" + err.file.name + " 上传失败，请检查网络或文件是否已存在"));
			} else {
				document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
			}
		}
	}
});
uploader.init();