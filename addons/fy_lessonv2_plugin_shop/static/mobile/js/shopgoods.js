var config = window.config;
var goods  = window.goods;


$(function(){
	/* 商品为单规格，则设置库存为0的规格为灰色 */
	if(config.attr_number == 1){
		$(".sku_choose .item").each(function(){
			var valueid = $(this).data('valueid');
			for(var i=0; i < config.sku_list.length; i++){
				if(config.sku_list[i].value_ids == valueid && config.sku_list[i].total == 0){
					$(this).unbind('click').addClass('disabled');
				}
			}
		})
	}

	/* 使用微信预览图片接口 */
	if(config.userAgent){
		$(".goods-swiper img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('.goods-swiper img');
			let l=imgObj.length;
			for (let i = 0; i < l; i++) {
				imgs.push(imgObj[i].src);
			}
			WeixinJSBridge.invoke("imagePreview", {
				"urls": imgs,
				"current": this.src,
			})
		})
		$(".goods_content img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('.goods_content img');
			let l=imgObj.length;
			for (let i = 0; i < l; i++) {
				imgs.push(imgObj[i].src);
			}

			WeixinJSBridge.invoke("imagePreview", {
				"urls": imgs,
				"current": this.src,
			})
		})
		$(".comment_picture img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('.comment_picture img');
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
		$(document).on('click','.comment_picture img,.cmt-modal-mask',function(){
			$('.cmt-modal-mask').toggleClass('show');
			if($('.cmt-modal-mask').hasClass('show')){
				$('.cmt-picture-view.cmt-modal-main > img').attr('src', this.src);
			}
		})
	}

	/* 设置返回直播间参数 */
	if(config.live_lessonid > 0){
		sessionStorage.setItem(goods.uniacid + "_live_lessonid", config.live_lessonid);
	}
})


/* 下拉时锁定标题栏 */
$(window).scroll(function(event) {
	if($(this).scrollTop() > 48){
		$(".shop-navBar").addClass('goods-head-fixed');
		$(".goods-swiper").css('padding-top','48px');
	}else{
		$(".shop-navBar").removeClass('goods-head-fixed');
		$(".goods-swiper").css('padding-top','0px');
	}

	var item_topPin		= $('#goodsItem').offset().top - $(window).scrollTop() - 90;
	var detail_topPin	= $('#goodsDetail').offset().top - $(window).scrollTop() - 90;
	var comment_topPin	= $('#goodsComment').offset().top - $(window).scrollTop() - 90;
    if(comment_topPin <= 0){
		$(".detail_anchor .detail_anchor_item").removeClass('cur').eq(2).addClass('cur');
	}else if(detail_topPin <= 0){
		$(".detail_anchor .detail_anchor_item").removeClass('cur').eq(1).addClass('cur');
	}else if(item_topPin <= 0){
		$(".detail_anchor .detail_anchor_item").removeClass('cur').eq(0).addClass('cur');
	}
});

/* 商品、详情、评价选项卡 */
$(".detail_anchor .detail_anchor_item").click(function(index, e){
	var curr_index = $(this).index();
	$(".detail_anchor .detail_anchor_item").removeClass('cur').eq(curr_index).addClass('cur');
})

/* 商品图集 */
var goods_swiper = new Swiper(".goods-swiper", {
	pagination: {
		el: '.goods-swiper-pagination',
		type: 'fraction',
	},
	autoplay: false,
	centeredSlides: true,
});

/* 商品下方广告 */
var goods_adv_swiper = new Swiper(".goods-adv-swiper", {
	autoplay: {
		delay: 5000,
		stopOnLastSlide: false,
		disableOnInteraction: false,
	},
	centeredSlides: true,
});

/* 规格详情事件 */
$(".detail_sku,.goods_sku_close").click(function(){
	$(".goods_sku_main").toggleClass('show');
})

/* 运费详情事件 */
$(".detail_transfer,.goods_transfer_close,.goods_transfer_sure").click(function(){
	$(".goods_transfer_main").toggleClass('show');
})

/*
$(document).mouseup(function (e) {
	var goods_sku_main = $(".goods_sku_main.show .main");
	if (!goods_sku_main.is(e.target) && goods_sku_main.has(e.target).length === 0 && $(".goods_sku_main").hasClass('show')) {
		$(".goods_sku_main").removeClass('show');
	}

	var goods_transfer_main = $(".goods_transfer_main.show .main");
	if (!goods_transfer_main.is(e.target) && goods_transfer_main.has(e.target).length === 0 && $(".goods_transfer_main").hasClass('show')) {
		$(".goods_transfer_main").removeClass('show');
	}
});
*/

/* 选择规格 */
$(".sku_choose .item").click(function(){
	$(this).addClass('active').siblings().removeClass('active');

	var curr_serial		= $(this).data('serial');
	var curr_valueid	= $(this).data('valueid');
	var orig_valueids	= $("#orig_valueids").val();
	
	//已选规格值处理
	var value_arr = [];
	//如果存在已选的规格项，则转为数组形式
	if(orig_valueids){
		value_arr = orig_valueids.split(",");
	}
	//把当前选中的规格值写入数组
	value_arr[curr_serial] = curr_valueid;
	//把数组转为字符串后保存
	orig_valueids = value_arr.toString();
	$("#orig_valueids").val(orig_valueids);

	//已选规格名称处理
	var sku_choose_name = $("#sku_choose_name").val();
	var sku_choose_arr = [];
	var curr_sku_choose = $(this).parent().prev().text();
	curr_sku_choose += ':' + $(this).text();
	if(sku_choose_name){
		sku_choose_arr = sku_choose_name.split(",");
	}
	sku_choose_arr[curr_serial] = curr_sku_choose;
	sku_choose_name = sku_choose_arr.toString();
	$("#sku_choose_name").val(sku_choose_name);


	if(config.sku_list.length){
		value_arr = value_arr.sort(compareSize);
		var valueids = value_arr.toString();
		$("#valueids").val(valueids);

		for(var j=0; j<config.sku_list.length; j++){
			if(config.sku_list[j].value_ids == valueids){
				if(config.sku_list[j].cover){
					$("#popupImg").attr("src", config.attachurl + config.sku_list[j].cover);
				}
				
				if(goods.sell_type == 1){
					$("#integralSale1").text(config.sku_list[j].integral);
				}else if(goods.sell_type == 2){
					$("#priceSale1").text(config.sku_list[j].price);
					$("#priceMarket").text(config.sku_list[j].market_price);
				}else if(goods.sell_type == 3){
					$("#integralSale2").text(config.sku_list[j].integral);
					$("#priceSale2").text(config.sku_list[j].price);
					$("#priceMarket").text(config.sku_list[j].market_price);
				}
				$("#skuChoose1").html($("#sku_choose_name").val().replace(',','; '));
				$("#popupSkuChoose").html('<span>已选</span>' + $("#sku_choose_name").val().replace(',','; '));
				$("#popupTotalChoose").html('<span>库存</span>' + config.sku_list[j].total);
				$("#sku_id").val(config.sku_list[j].id);
			}
		}

	}	
})

function compareSize(a,b){
	return (a - b);
}

/* 规格详情下数量 */
$("#minus1").click(function(){
	var buyNum1 = parseInt($("#buyNum1").val());
	if(buyNum1 <= 1){
		$("#buyNum1").val(1);
		return;
	}else{
		buyNum1--;
		$("#buyNum1").val(buyNum1);
	}
})
$("#plus1").click(function(){
	var buyNum1 = parseInt($("#buyNum1").val());
	if(goods.order_buy_num > 0 && buyNum1 >= goods.order_buy_num){
		$("#buyNum1").val(goods.order_buy_num);
		showTextToast('单次最多购买数量为' + goods.order_buy_num);
		return;
	}else{
		buyNum1++;
		$("#buyNum1").val(buyNum1);
	}	
})
function checkNumber(){
	var buyNum1 = parseInt($("#buyNum1").val());
	
	if(goods.order_buy_num > 0 && buyNum1 > goods.order_buy_num){
		$("#buyNum1").val(goods.order_buy_num);
		showTextToast('单次最多购买数量为' + goods.order_buy_num);
		return;
	}else if(buyNum1 <= 1){
		$("#buyNum1").val(1);
		return;
	}
}

/* 加入购物车 start */
$("#addCart1").click(function(){
	//详情页
	if(goods.goods_type == 2){
		showSingleDialog("虚拟商品不支持加入购物车，请点击立即购买");
		return false;
	}else{
		$(".goods_sku_main").toggleClass('show');
	}
})

$("#addCart2").click(function(){
	if(goods.goods_type == 2){
		showSingleDialog("虚拟商品不支持加入购物车，请点击立即购买");
		return false;
	}else{
		//选择规格
		var sku_id = $("#sku_id").val();
		if(goods.spec_switch==1 && !sku_id){
			showTextToast("请选择规格");
		}else{
			addToCard();
		}
	}
})

function addToCard(){
	var number = $("#buyNum1").val();
	var sku_id = $("#sku_id").val();

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.addCarturl,
		data:{goods_id:goods.id,number:number,sku_id:sku_id},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.act == 'redirect'){
				window.location.href = config.loginurl + Base64.encode(encodeURI(config.redir_goodsurl));
			}
			if(res.code == 0){
				showSuccessToast(res.message);
				$("#cardNum").text(res.total).show();
				$(".goods_sku_main").removeClass('show');
			}else if(res.code == -1){
				showSingleDialog(res.message);
				if(res.reload){
					setTimeout(function(){
						window.location.reload();
					},2000)
				}
				return false;
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络请求超时，请稍后重试");
		}
	 });
}
/* 加入购物车 end */


/* 立即购买 start */
$("#buyBtn1").click(function(){
	//详情页
	$(".goods_sku_main").toggleClass('show');
})
$("#buyBtn2").click(function(){
	//选择规格
	var sku_id = $("#sku_id").val();
	if(goods.spec_switch==1 && !sku_id){
		showTextToast("请选择规格");
	}else{
		buynow(goods.id, sku_id);
	}
})

function buynow(goods_id, sku_id){
	var number = $("#buyNum1").val();

	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.addCarturl,
		data:{goods_id:goods.id,buynow:1,number:number,sku_id:sku_id},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();
			if(res.act == 'redirect'){
				window.location.href = config.loginurl + Base64.encode(encodeURI(config.redir_goodsurl));
			}
			if(res.code == 0){
				window.location.href = config.confirmurl + "&cart_ids=" + res.cart_id;
			}else if(res.code == -1){
				showSingleDialog(res.message);
				if(res.reload){
					setTimeout(function(){
						window.location.reload();
					},2000)
				}
				return false;
			}
		},
		error: function(e){
			$("#loadingToast").hide();
			showSingleDialog("网络请求超时，请稍后重试");
		}
	 });
}
/* 立即购买 end */


/* 分享信息 */
wx.ready(function(){
	var shareData = {
		title: config.share_goods.title,
		desc: config.share_goods.desc,
		link: config.share_goods.link,
		imgUrl: config.attachurl + config.share_goods.cover,
		trigger: function (res) {},
		complete: function (res) {},
		success: function (res) {},
		cancel: function (res) {},
		fail: function (res) {}
	};
	wx.onMenuShareTimeline(shareData);
	wx.onMenuShareAppMessage(shareData);
	wx.onMenuShareQQ(shareData);
	wx.onMenuShareWeibo(shareData);
	wx.onMenuShareQZone(shareData);
})