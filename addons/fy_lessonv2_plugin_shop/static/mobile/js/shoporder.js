var config = window.config;
var nowPage = 1, get_status = true;
var order_status = '', order_kwd = '';

$(function(){
	/* 订单列表页 start */
	if(config.op == 'display'){
		function getData(page) {
			if(get_status){
				nowPage++;
				order_kwd = $("#order_kwd").val();
				$.get(config.orderurl + '&op=getList', {page:page,order_status:order_status,order_kwd:order_kwd}, function (data) {  
					$("#loadingToast").hide();

					var jsonObj = JSON.parse(data);
					/* 设置待付款、待发货、待收货、待评价和退款中订单数量 */
					if(jsonObj.nopay_total > 0){
						$("#waitPay").text(jsonObj.nopay_total).show();
					}
					if(jsonObj.nosend_total > 0){
						$("#waitSend").text(jsonObj.nosend_total).show();
					}
					if(jsonObj.waitrec_total > 0){
						$("#waitReceive").text(jsonObj.waitrec_total).show();
					}
					if(jsonObj.waitcomment_total > 0){
						$("#waitComment").text(jsonObj.waitcomment_total).show();
					}
					if(jsonObj.returning_total > 0){
						$("#returning").text(jsonObj.returning_total).show();
					}

					if (jsonObj.list.length > 0) {
						insertDiv(jsonObj.list);
					}
					if(page >= jsonObj.total_page){
						get_status = false;
						document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
					}
					if(jsonObj.total == 0){
						var empty_html = '';
						empty_html	+=	'<div class="my_empty">';
						empty_html	+=	'	<div class="empty_bd my_course_empty">';
						empty_html	+=	'		<p>没有找到任何订单</p>';
						empty_html	+=	'	</div>';
						empty_html	+=	'</div>';

						$(".order-wrapper").html(empty_html);
					}else{
						$(".my_empty").remove();
					}
				});
			}
		}
		getData(1);
		//正常商品
		function insertDiv(result) {  
			var mainDiv = $(".order-wrapper");
			var chtml = ''; 

			for (var j = 0; j < result.length; j++) {
				chtml += '<div class="order-item">';
				chtml += '	<div class="order-details">';
				chtml += '		<div class="order-title">';
				chtml += '			<div class="title-text">';
				chtml += '				<span class="span-ellipsis">订单编号:' + result[j].ordersn + '</span>';
				chtml += '			</div>';
				chtml += '			<div class="order-status">';
				chtml += '				<span class="span-ellipsis ' + result[j].status_color + '">' + result[j].status_name + '</span>';
				chtml += '			</div>';
				chtml += '		</div>';
				chtml += '	</div>';

				chtml += '	<div class="order-goods-list" data-id="' + result[j].id + '">';
				for(var k = 0; k < result[j].goods_list.length; k++){
					chtml += '	<div class="goods-item-wrapper">';
					chtml += '		<div class="goods-item">';
					chtml += '			<div class="goods-cover">';
					chtml += '				<img src="' + config.attachurl + result[j].goods_list[k].cover + '">';
					chtml += '			</div>';
					chtml += '			<div class="goods-title-info">';
					chtml += '				<div class="goods-title">';
					chtml += '					<span class="ellipsis-2">' + result[j].goods_list[k].title + '</span>';
					chtml += '				</div>';
					chtml += '				<div class="sku_name">';
					chtml += '					<span class="ellipsis-1">' + result[j].goods_list[k].sku_name + '</span>';
					chtml += '				</div>';
					chtml += '				<div class="price-number">';
					chtml += '					<span class="price">' + result[j].goods_list[k].show_price + '</span>';
					chtml += '					<span class="number">x' + result[j].goods_list[k].total + '</span>';
					chtml += '				</div>';
					chtml += '			</div>';
					chtml += '		</div>';
					chtml += '	</div>';
				}
				chtml += '	</div>';

				chtml += '	<div class="total-price">';
				chtml += '		实付款' + result[j].total_price;
				chtml += '	</div>';
				chtml += '	<div class="button-wrapper" data-id="' + result[j].id + '">';
				if(result[j].btn_delete){
					chtml += '	<a class="button-delete btn_delete"><i class="iconfont icon-delete"></i></a>';
				}
				if(result[j].btn_cancel){
					chtml += '	<a class="button-item default btn_cancel">取消订单</a>';
				}
				if(result[j].btn_pay){
					chtml += '	<a class="button-item active btn_pay">付款</a>';
				}
				if(result[j].btn_refund){
					chtml += '	<a class="button-item default btn_refund">' + result[j].refund_name + '</a>';
				}
				if(result[j].btn_shipping){
					chtml += '	<a class="button-item default btn_shipping">查看物流</a>';
				}
				if(result[j].btn_received){
					chtml += '	<a class="button-item active btn_received">确认收货</a>';
				}
				if(result[j].btn_comment){
					chtml += '	<a class="button-item active btn_comment">评价</a>';
				}
				chtml += '	</div>';
				chtml += '</div>';
			}

			mainDiv.append(chtml);
		}

		//定义鼠标滚动事件
		var scroll_loading = false;
		$(window).scroll(function(){
		　　var scrollTop = $(this).scrollTop();
		　　var scrollHeight = $(document).height();
		　　var windowHeight = $(this).height();
		　　if(scrollTop + windowHeight >= scrollHeight && !scroll_loading){
				scroll_loading = true;
				getData(nowPage);
				scroll_loading = false;
		　　}

			/* 固定和取消订单状态导航栏 */
			if(scrollTop >= 104){
				$(".my_order_nav").addClass('nav_fixed');
			}else{
				$(".my_order_nav").removeClass('nav_fixed');
			}
		});
		$(document).on('click','#btn_Page',function(){
			$("#loadingToast").show();
			getData(nowPage);
		})

		$(".my_order_nav_list > .my_order_nav_list_item").click(function(){
			var $currItem = $(this),
			index = $currItem.index();
			$currItem.addClass('cur').siblings().removeClass('cur');

			order_status = $(this).data('type');
			$("#order_kwd").val('');

			resetPageAndLoad();
		})

		$(".searchbar_wrap > .btn-search-shoporder").click(function(){
			resetPageAndLoad();
		})

		function resetPageAndLoad(){
			$('.order-wrapper').html('');
			get_status = true;
			nowPage = 1;
			getData(1);
			document.getElementById("loading_div").innerHTML = '<a href="javascript:;" id="btn_Page" style="display:inline;"><img src="' + config.moduleurl + 'static/mobile/images/icon-pull-down.png" class="pull-down"> 加载更多</a>';
		}
	}else if(config.op == 'details'){
		$(".footer-copyright").css('padding-bottom', '75px');
	}
	/* 订单列表页 end */
})

//查看订单详情
$(document).on('click','.order-goods-list',function(){
	var orderid = $(this).data('id');
	window.location.href = config.orderurl + "&op=details&orderid=" + orderid;
})

//订单详情进入商品详情页
$(document).on('click','.order-details-goods-item',function(){
	var goods_id = $(this).data('goodsid');
	window.location.href = config.goodsurl + "&id=" + goods_id;
})

//自动确认收货时间
if(config.op=='details' && config.order.show_process == 1){
	setTimeout("auto_receiving ()",1000);	 
	var auto_finish_time = new Date(config.order.auto_finish_time);
	auto_finish_time = auto_finish_time.getTime();
	 
	function auto_receiving (){
		var time_now = new Date();
		time_now = time_now.getTime();
		var time_distance = auto_finish_time - time_now;
		var int_day, int_hour, int_minute, int_second;
		if(time_distance >= 0){
			// 天时分秒换算
			int_day = Math.floor(time_distance/86400000)
			time_distance -= int_day * 86400000;
			int_hour = Math.floor(time_distance/3600000)
			time_distance -= int_hour * 3600000;
			int_minute = Math.floor(time_distance/60000)
			time_distance -= int_minute * 60000;
			int_second = Math.floor(time_distance/1000)

			// 时分秒为单数时、前面加零站位
			if(int_hour < 10)
			int_hour = "0" + int_hour;
			if(int_minute < 10)
			int_minute = "0" + int_minute;
			if(int_second < 10)
			int_second = "0" + int_second;

			// 显示时间
			var showTime = int_day + '天' + int_hour + '时' + int_minute + '分' + int_second + '秒';
			$("#delivery-time").text(showTime);
			setTimeout("auto_receiving ()",1000);
		}
	}
}


//取消订单
$(document).on('click','.btn_cancel',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	if(!confirm('确认取消订单?')){
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.updateorderurl,
		data:{orderid:orderid,type:'cancel'},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code == '0'){
				showSuccessToast(res.message);
			}else if(res.code == '-1'){
				showSingleDialog(res.message);
			}
			if(res.reload){
				setTimeout(function(){
					window.location.reload();
				},2000)
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})

//删除订单
$(document).on('click','.btn_delete',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	if(!confirm('确认删除订单?')){
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.updateorderurl,
		data:{orderid:orderid,type:'delete'},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code == '0'){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.href = config.orderurl;
				},2000)
			}else if(res.code == '-1'){
				showSingleDialog(res.message);
				if(res.reload){
					setTimeout(function(){
						window.location.reload();
					},2000)
				}
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})

//支付订单
$(document).on('click','.btn_pay',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}
	window.location.href = config.payorderurl + '&orderid=' + orderid;
})

//确认订单
$(document).on('click','.btn_received',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	if(!confirm('确定确认收货?')){
		return false;
	}
	
	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.updateorderurl,
		data:{orderid:orderid,type:'received'},
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
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})

//查看物流
$(document).on('click','.btn_shipping',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	window.location.href = config.orderurl + '&op=shipping&orderid=' + orderid;
})

//评价订单
$(document).on('click','.btn_comment',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	window.location.href = config.commenturl + '&orderid=' + orderid;
})

//申请退款
$(document).on('click','.btn_refund',function(){
	var orderid = $(this).parent().data('id');
	if(!orderid){
		showSingleDialog('订单参数错误，请稍后重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}

	window.location.href = config.refundurl + '&orderid=' + orderid;
})

//多个包裹切换
$('.my_express_nav_list_item').click(function(){
	var $currItem = $(this),
	index = $currItem.index();

	$currItem.addClass('cur').siblings().removeClass('cur');
	$(".shipping-details .express-list").hide().eq(index).show();
})