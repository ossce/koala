$(function(){
	//导航栏位置固定
	/*
	$(window).scroll(function(){
		if($(this).scrollTop()>=250){
			$(".ql_webnav").addClass("ps-f");
		}else{
			$(".ql_webnav").removeClass("ps-f");
		}
	})
	*/

	$(".pl_btn_down").click( function () {
		$(this).toggleClass("dsblock dsbnone");
		$(this).siblings(".pl_btn_up").toggleClass("dsbnone dsblock");
		$(this).parent("p").toggleClass("hg-44");
	});
	$(".pl_btn_up").click( function () {
		$(this).toggleClass("dsblock dsbnone");
		$(this).siblings(".pl_btn_down").toggleClass("dsbnone");
		$(this).parent("p").toggleClass("hg-44");
	});

	//登录
	login = function(){
		var uid = window.pageinfo.uid;
		var uniacid = window.pageinfo.uniacid;
		var refurl  = window.pageinfo.refurl;

		if(!uid){
			window.location.href = '/' + uniacid + '/login.html?refurl=' + refurl;
			return false;
		}

		return true;
	}

	//弹框样式
	swalalert = function(title, message, type){
		swal({
			title: title,
			text: message,
			type: type,
			showCancelButton: false,
			closeOnConfirm: false,
			confirmButtonColor: "#1d97d0"
		});
	}



})
