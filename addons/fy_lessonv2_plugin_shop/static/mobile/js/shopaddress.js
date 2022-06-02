var config = window.config;

$(function(){
	if(config.operation == 'display'){
		var x_distance;
		$('.address > ul')
			.on('touchstart', function(e) {
				$('.address > ul').css('left', '0px');
				$(e.currentTarget).addClass('open');
				x_distance = e.originalEvent.targetTouches[0].pageX;
			})
			.on('touchmove', function(e) {
				var change = e.originalEvent.targetTouches[0].pageX - x_distance;
				change = Math.min(Math.max(-140, change), 0);
				e.currentTarget.style.left = change + 'px';
			})
			.on('touchend', function(e) {
				var left = parseInt(e.currentTarget.style.left);
				var new_left;
				if (left < -70) {
					new_left = '-140px';
				} else if (left > 70) {
					new_left = '140px';
				} else {
					new_left = '0px';
				}
				$(e.currentTarget).animate({left: new_left}, 200);
			});
	}

	if(config.operation == 'editAddress'){
		var district = $('#Region');
		district.addressSelect();
		district.on('click', function(event) {
			event.stopPropagation();
			district.addressSelect('open');
		});
		district.on('address-select', function(data) {
			$(this).val(data.provance + ' ' + data.city + ' ' + data.area);
		});
	}

	$("#isdefault").click(function(){
		var isdefault = $(this).is(':checked') ? 1 : 0;
		$("#isdefault").val(isdefault);
	})
})

if(config.returnurl){
	localStorage.setItem(config.uniacid + '_' + config.uid + 'returnurl', config.returnurl);
}else{
	config.returnurl = localStorage.getItem(config.uniacid + '_' + config.uid + 'returnurl');
}

$("#btn-submit").click(function(){
	var id			= $("#address_id").val();
	var realname	= $("input[name=realname]").val().trim();
	var mobile		= $("input[name=mobile]").val().trim();
	var provinces	= $("#Region").val();
	var address		= $("input[name=address]").val().trim();
	var isdefault	= $("input[name=isdefault]").val();

	if(realname == ''){
		showTopTips('请填写收货人姓名');
		return false;
	}

	if(mobile == ''){
		showTopTips('请填写收货人手机号码');
		return false;
	}
	var mobileReg = /^((1)+\d{10})$/;
	if(!mobileReg.test(mobile)) {
		showTopTips('收货人手机号码格式有误');
		return false;
	}

	if(provinces == ''){
		showTopTips('请选择所在地区');
		return false;
	}

	if(address == ''){
		showTopTips('请填写详细地址');
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.editUrl,
		data:{id:id,realname:realname,mobile:mobile,provinces:provinces,address:address,isdefault:isdefault,returnurl:config.returnurl},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code == '0'){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.href = res.jumpurl;
				},2000)
			}else if(res.code == '-1'){
				showTopTips(res.message);
				return false;
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络繁忙，请稍后再试");
		}
	 });
})
	
$(".btn-edit").click(function(){
	var id = $(this).parent().parent().data('id');
	window.location.href = config.editUrl + '&id=' + id;
})

$(".btn-setting").click(function(){
	var id = $(this).parent().parent().data('id');
	var type = $(this).data('type');
	
	if(type=='delete' && !confirm('确认删除收货地址?')){
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.operUrl,
		data:{id:id,type:type},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code == '0'){
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
			showSingleDialog("网络繁忙，请稍后再试");
		}
	 });
})

//选择收货地址
$(".select-area").click(function(){
	if(!config.returnurl){
		return false;
	}

	var id = $(this).parent().parent().data('id');
	var returnurl = Base64.decode(config.returnurl);
	
	if(returnurl){
		window.location.href = returnurl + '&address_id=' + id;
	}
})