var config = window.config;

$(function(){
	if(config.op == 'display'){
		selectRefundReason();
	}

	/* 使用微信预览图片接口 */
	if(config.userAgent){
		$(".consult-history img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('.consult-history img');
			let l=imgObj.length;
			for (let i = 0; i < l; i++) {
				imgs.push(imgObj[i].src);
			}

			WeixinJSBridge.invoke("imagePreview", {
				"urls": imgs,
				"current": this.src,
			})
		})
	}else{
		$(document).on('click','.consult-history img,.cmt-modal-mask',function(){
			$('.cmt-modal-mask').toggleClass('show');
			if($('.cmt-modal-mask').hasClass('show')){
				$('.cmt-picture-view.cmt-modal-main > img').attr('src', this.src);
			}
		})
	}
})



//选择退货原因
$(".reason_content").click(function(){
	selectRefundReason();
})

function selectRefundReason(){
	weui.picker(config.reason_label, {
		defaultValue: [config.reason_label[0].value],
		onChange: function (result) {
		},
		onConfirm: function (result) {
			$("#refund_reason").val(result[0].label);
			$(".reason_content").text(result[0].label);
		},
		title: '请选择退款原因',
	});
}

//上传图片凭证
function removeimgs(obj){
	if(!confirm('您确定要删除吗?')){
		return false;
	}else{
		$(obj).parent().next().show();
		$(obj).remove();
	}
}
function uploadimgs(obj, goods_id) {
	var files = obj.files;
	var len = files.length <= 6 ? files.length : 6;
	var file_key = $(obj).parent().prev().children().length;

	for (var i=0; i < len; i++) {
		lrz(files[i], {width: 900, fieldName: "file"}).then(function (data) {
			$.post(config.uploadurl, {imageData: data.base64}, function (rs) {
				$(obj).parent().prev().append('<li onclick="removeimgs(this)" class="weui-uploader__file " style="background-image:url(' + rs.url + ')"><input value="'+rs.src+'" type="hidden" name="images" /></li>');

				var number = $(obj).parent().prev().children('li').length;
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

//提交申请退款
$(".apply-refund").click(function(){
	var refund_reason = $("#refund_reason").val();
	var refund_amount = $("#refund_amount").val();
	var refund_desc   = $("#refund_desc").val();

	if(refund_reason == ''){
		showSingleDialog("请选择退款原因");
		return false;
	}
	if(refund_amount === "" || refund_amount ==null){
        showSingleDialog("请填写退款金额");
		return false;
　　}
	if(isNaN(refund_amount)){
		showSingleDialog("退款金额必须为数字");
		return false;
	}
	if(refund_amount > config.order.total_amount){
		showSingleDialog("退款金额不得超过" + config.order.total_amount + "元");
		return false;
	}

	var postData = {
		refund_reason: refund_reason,
		refund_amount: refund_amount,
		  refund_desc: refund_desc,
		refund_images: [],
	};

	var inputs = $(".weui-uploader__files").find("input");
	inputs.each(function(j){
		var key_name = $(this).attr("name");
		if(key_name == 'images'){
			postData['refund_images'][j] = $(this).val();
		}
	})
   
	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.refundurl + "&op=submitRefund",
		data:{orderid:config.order.id,postData:postData},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();

			if(res.code == 0){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.href = config.refundurl + "&op=details&orderid=" + config.order.id;
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


//提交补充说明
$(".append-refund").click(function(){
	var refund_desc   = $("#refund_desc").val();

	if(refund_desc == ''){
		showSingleDialog("请填写补充说明");
		return false;
	}

	var postData = {
		  refund_desc: refund_desc,
		refund_images: [],
	};

	var inputs = $(".weui-uploader__files").find("input");
	inputs.each(function(j){
		var key_name = $(this).attr("name");
		if(key_name == 'images'){
			postData['refund_images'][j] = $(this).val();
		}
	})
   
	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.refundurl + "&op=appendRefund",
		data:{orderid:config.order.id,postData:postData},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();

			if(res.code == 0){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.reload();
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
	

//选择退货物流公司
$(".express_content").click(function(){
	weui.picker(config.express_label, {
		defaultValue: [0],
		onChange: function (result) {
		},
		onConfirm: function (result) {
			$("#express_code").val(result[0].code);
			$(".express_content").text(result[0].label);
		},
		title: '请选择物流公司',
	});
})

//提交退货物流信息
$(".btn-submit-express").click(function(){
	var express_code = $("#express_code").val();
	var	express_no   = $("#express_no").val();

	if(!express_code){
		showSingleDialog("请选择物流公司");
		return false;
	}
	if(!express_no){
		showSingleDialog("请填写物流单号");
		return false;
	}

	var postData = {
		     express: 1,
		express_code: express_code,
		  express_no: express_no,
	};

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.refundurl + "&op=appendRefund",
		data:{orderid:config.order.id,postData:postData},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();

			if(res.code == 0){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.reload();
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