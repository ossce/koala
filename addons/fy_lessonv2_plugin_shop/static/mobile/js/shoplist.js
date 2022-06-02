var config = window.config;
var get_status  = true;

$(function(){
	if(config.active_pid > 0){
		$("#first_category_" + config.active_pid).click();
	}

	var category_height = (window.screen.height - 86) * 0.8;
	$(".category-list-wrap").css('height', category_height + 'px');

	var shop_list_type = localStorage.getItem('shop_list_type' + config.uniacid);
	if(shop_list_type == 'circle'){
		$(".btn-list-cut").data('type', 'circle');
		$(".btn-list-cut").children(':first').children(':first').removeClass('icon-list-rectangle').addClass('icon-list-circle');
		$(".shop-list-rectangle").hide();
		$(".shop-list-circle").show();
	}else{
		$(".btn-list-cut").data('type', 'rectangle');
		$(".btn-list-cut").children(':first').children(':first').removeClass('icon-list-circle').addClass('icon-list-rectangle');
		$(".shop-list-circle").hide();
		$(".shop-list-rectangle").show();
	}

	var nowPage = 1;
    function getData(page) {
		if(get_status){
			nowPage++;
			$.get(config.ajaxurl, {page: page}, function (data) {  
				$("#loadingToast").hide();
				
				var jsonObj = JSON.parse(data);
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
					empty_html	+=	'		<p>没有找到任何商品</p>';
					empty_html	+=	'	</div>';
					empty_html	+=	'</div>';

					$(".shop-list-rectangle,.shop-list-circle").html(empty_html);
				}
			});
		}
    }
	getData(1);
    function insertDiv(result) {  
        var mainDiv_1 = $(".shop-list-rectangle");
		var mainDiv_2 = $(".shop-list-circle .shop-recommend-two");
        var chtml_1 = chtml_2 = '';  
        for (var j = 0; j < result.length; j++) {
			/* 一行一个商品样式 */
			chtml_1 += '<div class="shop-recommend-one">';
			chtml_1 += '	<div class="shop-flex-2">';
			chtml_1 += '		<a href="' + config.goodsurl + '&id=' + result[j].id + '" class="shop-goods-img">';
			chtml_1 += '			<img src="' + config.attachurl + result[j].cover + '">';
			chtml_1 += '			<i class="icon-goods-image ' + result[j].icon_name + '"></i>';
			chtml_1 += '		</a>';
			chtml_1 += '		<div class="shop-flex-box">';
			chtml_1 += '			<a href="' + config.goodsurl + '&id=' + result[j].id + '" class="ds-block">';
			chtml_1 += '				<h2>' + result[j].title + '</h2>';
			chtml_1 += '				<h3>';
			chtml_1 += '					<em>' + result[j].show_price + '</em>';
			if(result[j].sell_type != 1 && result[j].market_price > 0){
				chtml_1 += '				<i>￥' + result[j].market_price + '</i>';
			}
			chtml_1 += '				</h3>';
			chtml_1 += '			</a>';
			chtml_1 += '			<h4>';
			if(result[j].total_sales > 0){
				chtml_1 += '			<em>已售' + result[j].total_sales + '</em>';
			}
			chtml_1 += '			</h4>';
			chtml_1 += '		</div>';
			chtml_1 += '	</div>';
			chtml_1 += '</div>';

			/* 一行两个商品样式 */
			chtml_2 += '<div class="shop-hot-list-img">';
			chtml_2 += '	<a href="' + config.goodsurl + '&id=' + result[j].id + '" class="position-re ds-block">';
			chtml_2 += '		<div class="goods-cover">';
			chtml_2 += '			<div class="img-cover">';
			chtml_2 += '				<img src="' + config.attachurl + result[j].cover + '">';
			chtml_2 += '			</div>';
			chtml_2 += '		</div>';
			if(result[j].total_sales > 0){
				chtml_2 += '	<div class="icon-sell-num">已售' + result[j].total_sales + '</div>';
			}
			chtml_2 += '		<i class="icon-goods-image ' + result[j].icon_name + '"></i>';
			chtml_2 += '	</a>';
			chtml_2 += '	<a href="' + config.goodsurl + '&id=' + result[j].id + '" class="goods-title">' + result[j].title + '</a>';
			chtml_2 += '	<h2>' + result[j].show_price + '</h2>';
			chtml_2 += '<h3 style="height:16px;"><em>';
			if(result[j].sell_type != 1 && result[j].market_price > 0){
				chtml_2 += '￥' + result[j].market_price;
			}
			chtml_2 += '</em></h3>';
			chtml_2 += '</div>';
        }

		mainDiv_1.append(chtml_1);
		mainDiv_2.append(chtml_2);
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

/* 点击菜单事件 */
$(".shopType ul .sort-menu-item").click(function(){
	$(".shopType ul .sort-menu-item").removeClass('on');
	$(this).addClass('on');
})
$(".btn-sort,.btn-category,.btn-filtrate").click(function(){
	$(".sort-list,.category-list-wrap,.filtrate-list").hide();
	var curr_class = $(this).data('class');
	$("." + curr_class).show();
	$(".weui-mask").show();
})
$(".weui-mask").click(function(){
	$(".sort-list,.category-list-wrap,.filtrate-list").hide();
	$(".weui-mask").hide();
})

/* 点击分类菜单事件 */
$(".btn-category").click(function(){
	if(config.active_pid > 0){
		var first_category_id = $(".first-category .first-category-item.on").data('id');
		var f_category_margin_top = $("#first_category_" + first_category_id).position().top-86;
		if(f_category_margin_top > 0){
			$(".first-category").animate({scrollTop: f_category_margin_top}, 100);
		}
	}

	if(config.active_cid > 0){
		var second_categoty_on = $("#first_category_" + config.active_pid).parent().next().find('.category-right .second-categoty.on');
		var s_category_margin_top = second_categoty_on.position().top-10;
		if(s_category_margin_top > 0){
			$(".category-inner.all-son-category").animate({scrollTop: s_category_margin_top}, 100);
		}
	}
})

/* 点击一级分类事件 */
$(".first-category-item").click(function(){
	$(".first-category-item").removeClass('on');
	$(this).addClass('on');

	var goods_type = $("#goods_type").val();
	var sell_type  = $("#sell_type").val();
	var searchurl = config.searchurl + "&goods_type=" + goods_type + "&sell_type=" + sell_type;

	var curr_id = $(this).data('id');
	//一级分类
	for(var j1=0; j1<config.category_list.length; j1++){
		if(config.category_list[j1].id == curr_id){
			var s_html = '';
			if(config.category_list[j1].adv_cover){
				s_html	+=	'<a href="' + config.category_list[j1].adv_link + '" class="category_right_banner">';
				s_html	+=	'	<img src="' + config.attachurl + config.category_list[j1].adv_cover + '" alt="' + config.category_list[j1].name + '">';
				s_html	+=	'</a>';
			}

			//二级分类
			if(config.category_list[j1].second.length){
				for(var j2=0; j2<config.category_list[j1].second.length; j2++){
					var second_on = '';
					if(config.category_list[j1].second[j2].id == config.active_cid){
						second_on = 'on';
					}

					s_html	+=	'<div class="category-right">';
					s_html	+=	'	<a href="' + config.searchurl + '&pid=' + curr_id + '&cid=' + config.category_list[j1].second[j2].id + '" class="second-categoty ' + second_on + '"><img src="' + config.attachurl + config.category_list[j1].second[j2].icon + '">' + config.category_list[j1].second[j2].name + '</a>';

					if(config.category_list[j1].second[j2].third.length){
						s_html	+=	'	<div class="third-category">';
						for(var j3=0; j3<config.category_list[j1].second[j2].third.length; j3++){
							var third_on = '';
							if(config.category_list[j1].second[j2].third[j3].id == config.active_ccid){
								third_on = 'on';
							}

							s_html	+=	'	<a href="' + config.searchurl + '&pid=' + curr_id + '&cid=' + config.category_list[j1].second[j2].id + '&ccid=' + config.category_list[j1].second[j2].third[j3].id + '" class="category-item">';
							s_html	+=	'		<div class="icon radius">';
							s_html	+=	'			<img src="' + config.attachurl + config.category_list[j1].second[j2].third[j3].icon + '">';
							s_html	+=	'		</div>';
							s_html	+=	'		<div class="text ' + third_on + '">' + config.category_list[j1].second[j2].third[j3].name + '</div>';
							s_html	+=	'	</a>';
						}
						s_html	+=	'	</div>';
					}
					s_html	+=	'</div>';
				}
			}
			$(".all-son-category").html(s_html);
			break;
		}
	}
	
})

/* 点击筛选和切换商品样式菜单事件 */
$("#filterCBtn,.btn-list-cut").click(function(){
	$(".shopType ul .sort-menu-item").removeClass('on');
	$(".sort-list,.category-list-wrap,.filtrate-list").hide();
	$(".weui-mask").hide();
})

/* 点击筛选选项事件 */
$(".tags_selection .option").click(function(){
	$(this).siblings().removeClass('on');
	$(this).addClass('on');

	var select_type = $(this).data('type');
	var select_id	= $(this).data('id');
	$("#" + select_type).val(select_id);
})

/* 清除筛选选项事件 */
$(".filtrate-s-btn").click(function(){
	var pid  = GetQueryString("pid");
	var cid  = GetQueryString("cid");
	var ccid = GetQueryString("ccid");
	var curr_url = config.searchurl;
	if(pid){
		curr_url += "&pid=" + pid;
	}
	if(cid){
		curr_url += "&cid=" + cid;
	}
	if(ccid){
		curr_url += "&ccid=" + ccid;
	}
	window.location.href = curr_url;
})

/* 确认筛选事件 */
$("#filterFinishBtn").click(function(){
	var pid  = GetQueryString("pid");
	var cid  = GetQueryString("cid");
	var ccid = GetQueryString("ccid");
	var goods_type = $("#goods_type").val();
	var sell_type  = $("#sell_type").val();
	var curr_url = config.searchurl + "&pid=" + pid + "&cid=" + cid + "&ccid=" + ccid + "&goods_type=" + goods_type + "&sell_type=" + sell_type;
	window.location.href = curr_url;
})

/* 切换商品列表展示方式 */
$(".btn-list-cut").click(function(){
	var curr_type = $(this).data('type');
	if(curr_type == 'rectangle'){
		$(this).children(':first').children(':first').removeClass('icon-list-rectangle').addClass('icon-list-circle');
		$(this).data('type','circle');
		$(".shop-list-rectangle").hide();
		$(".shop-list-circle").show();
		localStorage.setItem('shop_list_type' + config.uniacid, 'circle');
	}else if(curr_type == 'circle'){
		$(this).children(':first').children(':first').removeClass('icon-list-circle').addClass('icon-list-rectangle');
		$(this).data('type','rectangle');
		$(".shop-list-circle").hide();
		$(".shop-list-rectangle").show();
		localStorage.setItem('shop_list_type' + config.uniacid, 'rectangle');
	}
})

function GetQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r!=null){
		return unescape(r[2]);
	}else{
		return null;
	}
}