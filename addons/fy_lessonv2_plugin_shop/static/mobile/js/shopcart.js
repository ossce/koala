var config = window.config;
var checkedLen = 0;
var nowPage = 1;
var get_status = lose_status = 1;

$(function(){
	var checkbox = document.getElementsByClassName('mycheckbox')

    function getData(page) {
		if(get_status){
			nowPage++;
			$.get(config.getlisturl, {page:page,lose_status:lose_status}, function (data) {  
				$("#loadingToast").hide();
				
				var jsonObj = JSON.parse(data);
				if (jsonObj.list1.length > 0) {
					insertValid(jsonObj.list1);
				}
				if (jsonObj.list2.length > 0) {
					lose_status = 0;
					insertLose(jsonObj.list2);
					$(".lose_wrap").show();
				}else{
					$(".lose_wrap").hide();
					if(jsonObj.list1_total == 0){
						var empty_html = '';
						empty_html += '<div class="my_empty">';
						empty_html += '	<div class="empty_bd my_course_empty">';
						empty_html += '			<p>您的购物车竟然是空的</p>';
						empty_html += '		</div>';
						empty_html += '	</div>';
						
						$(".main").html(empty_html);
					}
				}
				if(jsonObj.list1_total == 0){
					$("#loading_div").hide();
				}
				if(!jsonObj.list1.length || jsonObj.list1.length <= jsonObj.psize){
					get_status = 0;
					if(document.getElementById("loading_div")){
						document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
					}
				}
			});
		}
    }
	getData(1);
	//正常商品
    function insertValid(result) {  
        var mainValid = $(".valid_wrap");
        var chtml_1 = ''; 

		for (var j = 0; j < result.length; j++) {
			chtml_1 += '<div class="cart-block">';
			chtml_1 += '	<div class="checkbox-wrap">';
			chtml_1 += '		<img src="' + config.moduleurl + 'static/mobile/images/icon-tick.png" class="tick">';
			chtml_1 += '		<input type="checkbox" name="cart_ids[]" value="' + result[j].cart_id + '" class="checkbox mycheckbox"/>';
			chtml_1 += '	</div>';
			chtml_1 += '	<img src="' + config.attachurl + result[j].cover + '" class="cart-goods-img">';
			chtml_1 += '	<div class="cart-goods-info">';
			chtml_1 += '		<a href="' + config.goodsurl + '&id=' + result[j].goods_id + '" class="cart-goods-name">' + result[j].title + '</a>';
			chtml_1 += '		<p class="cart-goods-sku">' + result[j].sku_name + '</p>';
			chtml_1 += '		<div class="price-wrap">';
			chtml_1 += '			<span>' + result[j].show_price + '</span>';
			chtml_1 += '			<div class="num-wrap">';
			chtml_1 += '				<a href="javascript:;" data-id="' + result[j].cart_id + '" class="minus">-</a>';
			chtml_1 += '				<input type="tel" data-id="' + result[j].cart_id + '" class="num-input" value="' + result[j].total + '">';
			chtml_1 += '				<a href="javascript:;" data-id="' + result[j].cart_id + '" class="plus">+</a>';
			chtml_1 += '			</div>';
			chtml_1 += '		</div>';
			chtml_1 += '	</div>';
			chtml_1 += '</div>';
        }
		mainValid.append(chtml_1);
    }

	//失效商品
	function insertLose(result) {  
		var mainLose = $(".lose_wrap");
        var chtml_2 = ''; 

		for (var j = 0; j < result.length; j++) {
			chtml_2 += '<div class="cart-block">';
			chtml_2 += '	<div class="checkbox-wrap">';
			chtml_2 += '		<span class="lose">失效</span>';
			chtml_2 += '	</div>';
			chtml_2 += '	<img src="' + config.attachurl + result[j].goods_cover + '" class="cart-goods-img">';
			chtml_2 += '	<div class="cart-goods-info">';
			chtml_2 += '		<p class="cart-goods-name">' + result[j].title + '</p>';
			chtml_2 += '		<p class="cart-goods-sku"></p>';
			chtml_2 += '		<div class="price-wrap">';
			chtml_2 += '			<span>商品已失效不能购买</span>';
			chtml_2 += '		</div>';
			chtml_2 += '	</div>';
			chtml_2 += '</div>';
        }
		mainLose.append(chtml_2);
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
	});
    $("#btn_Page").click(function () {
		$("#loadingToast").show();
        getData(nowPage);
    });
})

//多选框-统一
$(document).on('click','.checkbox',function(){
	if($(this)[0].checked){
		// 需变选中
		$(this).prev().attr('src',config.moduleurl + 'static/mobile/images/icon-tick-select.png');
	}else{
		// 需变不选中
		$(this).prev().attr('src',config.moduleurl + 'static/mobile/images/icon-tick.png');
	}
})


//多选框-上面
$(document).on('click','.mycheckbox',function(){
	checkedLen = 0;
	$(".mycheckbox").each(function(index,item){
		if($(this)[0].checked){
			checkedLen++;
		}
	});
	total();
})

//多选框-全选
$(document).on('click','.allselect',function(){
	if($(this)[0].checked){
		//需变选中
		$('.tick').attr('src',config.moduleurl + 'static/mobile/images/icon-tick-select.png');
		$('.checkbox').prop('checked', true);
	}else{
		//需变不选中
		$('.tick').attr('src',config.moduleurl + 'static/mobile/images/icon-tick.png');
		$('.checkbox').prop('checked', false);
	}

	checkedLen = 0;
	$(".mycheckbox").each(function(index,item){
		if($(this)[0].checked){
			checkedLen++;
		}
	});
	total();
})


//统计金额
function total(){
	var total_html = '合计: ';
	var totalprice = totalintegral = 0;
	$(".mycheckbox").each(function(index,item){
		if($(this)[0].checked){
			var price = Number($(this).parents('.cart-block').find('.price').html());
			var integral = Number($(this).parents('.cart-block').find('.integral').html());
			var num = $(this).parents('.cart-block').find('.num-input').val();
			totalprice += price * num;
			totalintegral += integral * num;
		}
	});

	totalprice = totalprice.toFixed(2);
	if(totalprice > 0 && totalintegral == 0){
		total_html += '<span class="symbol">￥</span><span class="price">' + totalprice + '</span>';
	}else if(totalprice == 0 && totalintegral > 0){
		total_html += '<span class="integral">' + totalintegral + '</span><span class="symbol">积分</span>';
	}else if(totalprice > 0 && totalintegral > 0){
		total_html += '<span class="symbol">￥</span><span class="price">' + totalprice + '</span><span class="symbol">+</span><span class="integral">' + totalintegral + '</span><span class="symbol">积分</span>';
	}else if(totalprice == 0 && totalintegral == 0){
		total_html += '<span class="symbol">￥</span><span class="price">0</span>';
	}
	$('#totalprice').html(total_html);
	
	//显示数量
	$(".checkedLen").html(checkedLen);
}


/* 数量增减 start */
$(document).on('click','.minus',function(){
	var val = $(this).parent().find('.num-input').val();
	if(val==1){
		showTextToast('购买数量最少为1');
		return;
	}

	var num = Number(val)-1;
	var cart_id = $(this).data('id');

	updateCartNumber(cart_id, num, this);
})

$(document).on('click','.plus',function(){
	var val = $(this).parent().find('.num-input').val();
	var num = Number(val)+1;
	var cart_id = $(this).data('id');

	updateCartNumber(cart_id, num, this);
})

$(document).on('blur','.num-input',function(){
	var val = $(this).parent().find('.num-input').val();
	var num = Number(val);
	var cart_id = $(this).data('id');

	updateCartNumber(cart_id, num, this);
})

function updateCartNumber(cart_id, number, obj){
	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.updateurl,
		data:{cart_id:cart_id,number:number},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.code == '0'){
				$(obj).parent().find('.num-input').val(number);
				total();
			}else if(res.code == '-1'){
				showSingleDialog(res.message);
				if(res.reload){
					setTimeout(function(){
						window.location.reload();
					},2000)
				}
			}else if(res.code == '-2'){
				showTextToast(res.message);
				$(obj).parent().find('.num-input').val(res.total);
				total();
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
}
/* 数量增减 end */


//编辑购物车
$(document).on('click','.cart-operation',function(){
	if($(this).hasClass('edit')){
		$(this).removeClass('edit');
		$('.settlement').hide();
		$('.delete-btn').show();
		$(this).html('完成');
	}else{
		$('.settlement').css('display','flex');
		$('.delete-btn').hide();
		$(this).addClass('edit');
		$(this).html('编辑');
	}
})

//清空失效商品
$(".clear_info_item").click(function(){
	if(!confirm('确认清空失效商品?')){
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.clearurl,
		data:{},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			showSuccessToast(res.message);
			setTimeout(function(){
				window.location.reload();
			},2000)
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})

//结算商品
$(".btn-settle").click(function(){
	var cart_ids = checkedIds();
	if(!cart_ids){
		return false;
	}
	
	window.location.href = config.confirmurl + '&cart_ids=' + cart_ids;

})

//删除商品
$(".delete-btn").click(function(){
	var cart_ids = checkedIds();
	if(!cart_ids){
		return false;
	}

	if(!confirm('确认删除选中商品?')){
		return false;
	}

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.deleteurl,
		data:{cart_ids:cart_ids},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			showSuccessToast(res.message);
			setTimeout(function(){
				window.location.reload();
			},2000)
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络错误，请稍后重试");
		}
	 });
})

function checkedIds(){
	var cart_ids = '';
	checkedLen = 0;
	$(".mycheckbox").each(function(index,item){
		if($(this)[0].checked){
			checkedLen++;
			cart_ids += $(this)[0].value + ',';
		}
	});
	cart_ids = cart_ids.substring(0, cart_ids.lastIndexOf(','));

	if(checkedLen == 0){
		showTextToast("未选中任何商品");
		return false;
	}else{
		return cart_ids;
	}
}