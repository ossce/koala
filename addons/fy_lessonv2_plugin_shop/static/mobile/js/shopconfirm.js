var config = window.config;

$(function(){
	//默认选中第一个配送方式
	var province = $("#province").val(); //收货地址省份
	//存在收货地址省份，且购物车商品为实物
	if(province && config.goods_type == 1){
		var default_express = config.express_list[0]; //默认配送方式
		var original_totalprice = Number($("#original_totalprice").val()); //商品总额

		if(default_express.content.length > 0){
			//循环配送区域
			var area_status = false;
			for(var j=0; j<default_express.content.length; j++){
				var curr_freight = 0; //当前运费
				var curr_express = default_express.content[j]; //已选配送方式的配送区域信息
				var areas = curr_express.area.split(","); //已选配送方式的配送区域数组
				var index = $.inArray(province, areas);

				//如果收货地址省份在配送区域里
				if(index > -1){
					curr_freight = curr_express.postage; //初始化运费为首件费用
					//购买商品数量大于首件数量，则计算续件费用
					if(config.totalnumber > curr_express.start){
						var step_number = Math.ceil((config.totalnumber - curr_express.start)/curr_express.step);
						curr_freight += step_number * curr_express.renew;
					}

					area_status = true;
					original_totalprice += curr_freight; //运费为首件费用加上续件费用
					$(".total-price").text(original_totalprice);
					$(".express-price").text('￥' + curr_freight);

					break;
				}
			}

			//如果收货地址省份不在所有的配送区域里，则用默认运费
			if(!area_status){
				original_totalprice += Number(default_express.price);
				$(".total-price").text(original_totalprice);
				$(".express-price").text('￥' + default_express.price);
			}
		}else{
			//不存在配送区域，则用默认运费
			original_totalprice += Number(default_express.price);
			$(".total-price").text(original_totalprice);
			$(".express-price").text('￥' + default_express.price);
		}

		$(".shipping_content").text(default_express.title);
		$("input[name=express_id]").val(default_express.id);
	}
})



//选择地址
$(".address_defalut").click(function(){
	if(!config.confirmurl){
		showTextToast('系统繁忙，请重试');
		setTimeout(function(){
			window.location.reload();
		},2000)
		return false;
	}else{
		window.location.href = config.addressurl + '&returnurl=' + config.confirmurl;
	}
})

//配送方式
$(".shipping_content").click(function(){
	weui.picker(config.express_label, {
		defaultValue: [config.express_label[0].value],
		onChange: function (result) {
		},
		onConfirm: function (result) {
			var province = $("#province").val(); //收货地址省份
			var original_totalprice = Number($("#original_totalprice").val()); //商品总额

			for(var i=0; i<config.express_list.length; i++){
				if(config.express_list[i].id != result[0].value){
					continue; //循环的配送方式不是当前选中的配送方式，则跳过
				}

				if(config.express_list[i].content.length > 0){
					//存在配送区域
					if(!province){
						showSingleDialog('请选择收货地址');
						return false;
					}

					//循环配送区域
					var area_status = false;
					for(var j=0; j<config.express_list[i].content.length; j++){
						var curr_freight = 0; //当前运费
						var curr_express = config.express_list[i].content[j]; //已选配送方式的配送区域信息
						var areas = curr_express.area.split(","); //已选配送方式的配送区域数组
						var index = $.inArray(province, areas);

						//如果收货地址省份在配送区域里
						if(index > -1){
							curr_freight = curr_express.postage; //初始化运费为首件费用
							//购买商品数量大于首件数量，则计算续件费用
							if(config.totalnumber > curr_express.start){
								var step_number = Math.ceil((config.totalnumber - curr_express.start)/curr_express.step);
								curr_freight += step_number * curr_express.renew;
							}

							area_status = true;
							original_totalprice += curr_freight; //运费为首件费用加上续件费用
							$(".total-price").text(original_totalprice);
							$(".express-price").text('￥' + curr_freight);

							break;
						}
					}

					//如果收货地址省份不在所有的配送区域里，则用默认运费
					if(!area_status){
						original_totalprice += Number(result[0].price);
						$(".total-price").text(original_totalprice);
						$(".express-price").text('￥' + result[0].price);
					}
				}else{
					//不存在配送区域，则用默认运费
					original_totalprice += Number(result[0].price);
					$(".total-price").text(original_totalprice);
					$(".express-price").text('￥' + result[0].price);
				}
				break;
			}

			$(".shipping_content").text(result[0].label);
			$("input[name=express_id]").val(result[0].value);
		},
		title: '请选择配送方式'
	});
})


//提交订单
$(".btn-submit-order").click(function(){
	var address_id = $("input[name=address_id]").val();
	var express_id = $("input[name=express_id]").val();

	if(!address_id){
		showSingleDialog('请选择收货地址');
		return false;
	}

	if(config.goods_type == 1 && !express_id){
		showSingleDialog('请选择配送方式');
		return false;
	}

	$("#orderForm").submit();
})
