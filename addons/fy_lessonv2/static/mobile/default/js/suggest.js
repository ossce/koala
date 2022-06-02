var config = window.config;


//选择投诉类型
$(".suggest-category .item").click(function(){
	$("#category_id").val($(this).data('id'));
	$(this).addClass('active').siblings().removeClass('active');
})

//上传图片
function uploadimgs(obj) {
	var files = obj.files;
	var len = files.length <= 6 ? files.length : 6;
	var file_key = $(obj).parent().prev().children().length;

	for (var i=0; i < len; i++) {
		lrz(files[i], {width: 900, fieldName: "file"}).then(function (data) {
			$.post(config.uploadurl, {imageData: data.base64}, function (rs) {
				var number = $(obj).parent().prev().children('li').length;
				$(obj).parent().prev().append('<li onclick="removeimgs(this)" class="weui-uploader__file " style="background-image:url(' + config.attachurl + rs.path + ')"><input value="'+rs.path+'" type="hidden" name="picture" /></li>');

				if(number>=6){
					$(obj).parent().hide();
				}
			}, 'json');

		}).then(function (data) {

		}).catch(function (err) {
			console.log(err);
		});
	}
}

//移除图片
function removeimgs(obj){
	if(!confirm('您确定要删除吗?')){
		return false;
	}else{
		$(obj).parent().next().show();
		$(obj).remove();
	}
}

//提交
$("#btn-submit").click(function(){
	var category_id = $("#category_id").val();
	var content = $("#content").val();
	var mobile = $("#mobile").val();
	var myreg = /^((1)+\d{10})$/;

	if(!category_id){
		showTextToast('请选择' + config.suggest_type);
		return false;
	}
	if(content == ''){
		showTextToast('请填写' + config.suggest_content);
		return false;
	}
	if(mobile == ''){
		showTextToast('请填写联系手机号码');
		return false;
	}else if(!myreg.test(mobile)) {
		showTextToast('您输入的手机号码有误');
		return false;
	}

	var postData = {
		category_id: category_id,
			content: content,
			 mobile: mobile,
			picture: [],
	};

	var inputs = $(".suggest_picture").find("input");
	inputs.each(function(j){
		postData.picture[j] = $(this).val();
	})

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.suggesturl + "&op=submitSuggest",
		data:{postData:postData},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();

			if(res.code == 0){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.href = config.indexurl;
				},2000)
			}else if(res.code == '-1'){
				showSingleDialog(res.message);
				return false;
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})