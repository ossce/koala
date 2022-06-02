var config = window.config;

var get_order_status = get_card_status = true; //允许获取订单状态
var nowOrderPage = nowCardPage = 1;

$(function () {
	//获取VIP订单数据
	getOrderData(1);
	$("#btn_order_page").click(function () {
		$("#loadingToast").show();
		nowOrderPage++;
		getOrderData(nowOrderPage);
	});
	function getOrderData(nowOrderPage) {
		if(get_order_status){
			$.get(config.vipurl + "&op=ajaxgetList", {page: nowOrderPage}, function (data) {
				$("#loadingToast").hide();
				var jsonObj = JSON.parse(data);

				if (jsonObj.length > 0) {
					insertDiv(jsonObj, 1);
				}else{
					get_order_status = false;  //没有数据后，禁止请求获取数据
					document.getElementById("loading_order").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
				}
			});
		}
	}

	//获取VIP服务卡数据
	getCardData();
	$('#loading_card').on('click','a',function(){
		$("#loadingToast").show();
		nowCardPage++;
		getCardData(nowCardPage);
	});
	function getCardData(nowCardPage) {
		var card_status = $("#card_status").val();
		if(get_card_status){
			$.get(config.vipurl + "&op=ajaxgetCard", {page: nowCardPage, card_status:card_status}, function (data) {
				$("#loadingToast").hide();
				var jsonObj = JSON.parse(data);
				$("#nouse_total").html(jsonObj.nouse_total);
				$("#used_total").html(jsonObj.used_total);
				$("#pass_total").html(jsonObj.pass_total);

				if (jsonObj.card_list.length > 0) {
					insertDiv(jsonObj.card_list, 2);
				}else{
					get_card_status = false;  //没有数据后，禁止请求获取数据
					document.getElementById("loading_card").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
				}
			});
		}
	}

	//生成数据html,append到div中  
	function insertDiv(result, type) {  
		var chtml = '';
		if(type==1){
			var mainDiv =$("#orderList");
			for (var j = 0; j < result.length; j++) { 
				chtml += '<div class="aui-order-box">';
				chtml += '	<a href="javascript:void(0);" class="aui-well-item">';
				chtml += '		<div class="aui-well-item-bd">';
				chtml += '			<h3>订单编号：' + result[j].ordersn + '</h3>';
				chtml += '		</div>';
				chtml += '	</a>';
				chtml += '	<p class="aui-order-fl aui-order-address">购买详情：购买[' + result[j].level_name + ']-'+ result[j].viptime +'天</p>';
				chtml += '	<p class="aui-order-fl aui-order-address">支付方式：' + result[j].paytype + '</p>';
				chtml += '	<p class="aui-order-fl aui-order-time">下单时间：' + result[j].addtime + '</p>';
				chtml += '	<p class="aui-order-fl aui-order-time">付款时间：' + result[j].paytime + '</p>';
				chtml += '	<p class="aui-order-fl aui-order-door">优惠金额：' + result[j].discount_money + ' 元</p>';
				chtml += '	<p class="aui-order-fl aui-order-door">实付金额：<em class="income_amount">' + result[j].vipmoney + '</em> 元</p>';
				chtml += '</div>';
			}
		}else if(type==2){
			var mainDiv =$("#cardList");
			for (var j = 0; j < result.length; j++) { 
				chtml += '<div class="aui-order-box">';
				chtml += '	<a class="aui-well-item">';
				chtml += '		<div class="aui-well-item-bd">';
				chtml += '			<h3>服务卡密钥：' + result[j].password + '</h3>';
				chtml += '		</div>';
				chtml += '	</a>';

				if(result[j].status==0){
					chtml += '	<p class="aui-order-fl aui-order-address">状态：'+ result[j].is_use_status +'</p>';
					chtml += '	<p class="aui-order-fl aui-order-address">有效期：需在'+ result[j].validity_date +'前使用</p>';
				}else if(result[j].status==1){
					chtml += '	<p class="aui-order-fl aui-order-door">状态：'+ result[j].is_use_status +'</p>';
					chtml += '	<p class="aui-order-fl aui-order-time">使用时间：' + result[j].use_time_date + '</p>';
					chtml += '	<p class="aui-order-fl aui-order-door">使用用户：uid: ' + result[j].uid + '</p>';
					chtml += '	<p class="aui-order-fl aui-order-door">使用订单号：' + result[j].ordersn + '</p>';
				}else if(result[j].status==2){
					chtml += '	<p class="aui-order-fl aui-order-door">状态：'+ result[j].is_use_status +'</p>';
					chtml += '	<p class="aui-order-fl aui-order-address">有效期：需在'+ result[j].validity_date +'前使用</p>';
				}
				chtml += '	<p class="aui-order-fl aui-order-address">VIP等级：' + result[j].level_name + '</p>';
				chtml += '	<p class="aui-order-fl aui-order-time">服务卡时长：' + result[j].viptime + ' 天</p>';
				chtml += '</div>';
			}
		}
		
		mainDiv.append(chtml);
	}

	$('.card-list-status>label').click(function() {
		var index = $(this).data('id');
		$(this).addClass('checked').siblings().removeClass('checked');
		$("#card_status").val(index);
		$("#cardList").html('');
		get_card_status = true;
		nowCardPage = 1;
		getCardData(nowCardPage);
		document.getElementById("loading_card").innerHTML = '<a href="javascript:void(0);" id="btn_card_page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>';
	});


	/* 推广收益 START */
	var scroll_height = "40px";
	var tsq;
	var showidx = 0;

	var new_scroll = function () {
	  var len = $(".article_div_list li").length;
	  var m = $(".article_div_list li");
	  clearInterval(tsq);
	  if (len > 1) {
		tsq = setInterval(function () {
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear', function () {
			$(this).css("top", scroll_height);
		  });
		  showidx++;
		  if (showidx == len) {
			showidx = 0;
		  }
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear');
		}, 3000);
	  }
	}();
	/* 推广收益 END */

})


//查看VIP服务协议
$("#view-vip-agreement").click(function(){
	$('#vip-agreement-content').fadeIn(200).unbind('click').click(function(){
		$(this).fadeOut(100);
	})
});

//关闭加群客服
function closeTip(){
	$(".aui-dialog").hide();
	$(".aui-mask").hide();
}

//我的VIP、订单记录、VIP服务卡密切换
$(".vip-title-table").on("click", 'li', function() {
	var $currItem = $(this),
	index = $currItem.index();
	$("#nowPage").val(index);

	$currItem.addClass('active').siblings().removeClass('active');
	$(".content-tab").hide().eq(index).show();
});

//开通VIP
function buyvip(id){
	if(config.comsetting.vip_agreement != ''){
		if(!$("#vip_agreement").is(':checked')){
			showSingleDialog('请阅读并同意《VIP服务协议》');
			return false;
		}
	}

	if(config.setting.mustinfo && config.writemsg){
		$(".writemsg_shade").show();
		$(".writemsg_wrap").show();
	}else{
		var setTitle = '系统提示';
		var setContents = '确定提交订单?';
		var setButton = '["取消","确定"]';
		var setCancelUrl = 'javascript:;';
		var setConfirmUrl = config.vipurl + "&op=buyvip&level_id="+id;
		$(this).openWindow(setTitle,setContents,setButton,setCancelUrl,setConfirmUrl);
	}
}

//最近购买信息
if(config.v_buyinfo){
	var time = buyinfo_serial = 0;
	var timer = setInterval(function(){
		if(time == 0 || time%15 == 0){
			$(".newbuy_info_item").html(config.v_buyinfo[buyinfo_serial]);
			$(".newbuy_wrap").animate({opacity: 'show'}, 'slow');
			buyinfo_serial ++;

			setTimeout(function(){
				$(".newbuy_wrap").animate({opacity: 'hide'}, 'slow');
			}, 8000);
		}
		if(buyinfo_serial >= config.v_buyinfo.length){
			clearInterval(timer);
		}
		time++;
	},1000);
}

//异步开通VIP
$("#btn-card-password").click(function(){
	var card_password = $("#card_password").val();
	if(card_password==''){
		showSingleDialog('请输入' + config.vipcard_text);
		return false;
	}

	if(config.comsetting.vip_agreement != '' && config.op == 'vipcard'){
		if(!$("#vip_agreement").is(':checked')){
			showSingleDialog('请阅读并同意《VIP服务协议》');
			return false;
		}
	}

	$("#loadingToast").show();
	$.ajax({
		url:config.vipurl + "&op=vipcard",
		type:"post",
		data:{card_password:card_password},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code==0){
				showSuccessToast(res.msg);
				setTimeout(function(){
					window.location.href = config.vipurl;
				},3000)
			}else{
				showSingleDialog(res.msg);
				return false;
			}
		},
		error:function(e){
			$("#loadingToast").hide();
			showSingleDialog('网络错误，请稍后重试');
		}
	}); 
});