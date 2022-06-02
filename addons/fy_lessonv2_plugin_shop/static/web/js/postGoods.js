var GoodsSkuData	= window.GoodsSkuData;
var goods_config	= window.goods_config;
var getGoodsUrl		= window.getGoodsUrl;


/* 通用 START*/
$('.tab-group').tabify();

$(':radio[name="commission[commission_type]"]').click(function () {
   if ($(this).val()=='0') {
		$('.commission_type_name').html('%') ;
	} else {
		$('.commission_type_name').html('元') ;
	}
});

var express_ids = document.getElementsByName("express_ids[]");
var selAllExpress = false;
$("#selAllExpress").click(function(){
	selAllExpress = !selAllExpress;
	for(var i=0; i<express_ids.length; i++){
		express_ids[i].checked = selAllExpress;
	}
});

$(':radio[name="goods_type"]').click(function() {
	var index = $(this).val();
	if(index=='1'){
		$(".tab-group>.lesson-tab-nav>li:eq(3)").hide();
	}else{
		$(".tab-group>.lesson-tab-nav>li:eq(3)").show();
	}
});

if(!goods_config.goods || (goods_config.goods && goods_config.goods.goods_type != 2)){
	$(".tab-group>.lesson-tab-nav>li:eq(3)").hide();
}

function checkData(){
	if($("input[name=title]").val() == ''){
		util.message('请填写商品名称', '', 'error');
		return false;
	}
	if($("#pid").val() == '0'){
		util.message('请选择商品分类', '', 'error');
		return false;
	}
	if($("input[name=cover]").val() == ''){
		util.message('请上传商品封面图', '', 'error');
		return false;
	}

	var sell_type = $("input[name='sell_type']:checked").val();
	var price =$("input[name=price]").val();
	var integral = parseInt($("input[name=integral]").val());
	if(sell_type == 1 || sell_type == 3){
		if(!integral){
			util.message('请填写兑换积分', '', 'error');
			return false;
		}
	}
	if(sell_type == 2 || sell_type == 3){
		if(price == ''){
			util.message('销售价不能为空或0', '', 'error');
			return false;
		}
		price = parseFloat(price).toFixed(2);
		if(price <= '0.00'){
			util.message('销售价不能为空或0', '', 'error');
			return false;
		}
		if(price== 'NaN'){
			util.message('您填写的销售价有误', '', 'error');
			return false;
		}
	}

	return true;
}

$(document).on('click', '.check-all-spec', function(){
	var check_status = $(this).attr('data-status');
	if(check_status == 0){
		$(this).attr('data-status', 1);
		var checkFlag = true;
	}else{
		$(this).attr('data-status', 0);
		var checkFlag = false;
	}

	var spec_inputs = $(this).parent().children().find('input');
	spec_inputs.each(function(){
		$(this).prop('checked', checkFlag);
	});
})
/* 通用 END*/



/* 选择商品分类 START */
var category = goods_config.category;
if(goods_config.goods){
	var pid		 = goods_config.goods.pid ? goods_config.goods.pid : 0;
	var cid		 = goods_config.goods.cid ? goods_config.goods.cid : 0;
	var ccid	 = goods_config.goods.ccid ? goods_config.goods.ccid : 0;
}else{
	var pid		 = 0;
	var cid		 = 0;
	var ccid	 = 0;
}
var html = '<option value="0">请选择一级分类</option>';

$(function(){
	$("#pid").find("option[value='"+pid+"']").attr("selected",true);
	document.getElementById("pid").onchange();
});

//选择一级分类
function renderCategory(pid){
	var chtml2 = '<option value="0">请选择二级分类</option>';
	if(pid){
		for(var i in category){
			if(category[i].id==pid){
				var second = category[i].second;
				for(var j in second){
					if(second[j].id==cid){
						chtml2 += '<option value="' + second[j].id+'" selected>' + second[j].name + '</option>';
					}else{
						chtml2 += '<option value="' + second[j].id+'">' + second[j].name + '</option>';
					}
				}
				$("#cid").html(chtml2);
			}
		}
	}else{
		$("#cid").html(chtml2);
	}

	if(pid){
		renderSecondCategory();
	}
}

//选择二级分类
function renderSecondCategory(){
	pid = $("#pid").val() ? $("#pid").val() : pid;
	cid = $("#cid").val() ? $("#cid").val() : cid;

	var chtml3 = '<option value="0">请选择三级分类</option>';
	if(pid && cid){
		for(var i in category){
			if(category[i].id==pid){
				var second = category[i].second;
				for(var j in second){
					var third = category[i].second[j].third;
					for(var k in third){
						if(third[k].id==ccid){
							chtml3 += '<option value="' + third[k].id+'" selected>' + third[k].name + '</option>';
						}else{
							chtml3 += '<option value="' + third[k].id+'">' + third[k].name + '</option>';
						}
					}

				}
				$("#ccid").html(chtml3);
			}
		}
	}else{
		$("#ccid").html(chtml3);
	}
}
/* 选择商品分类 END */



/* 选择商品类型和规格 START */
$(':radio[name="goods_type"]').click(function () {
   if($(this).val()=='1'){
		$("#stock").removeAttr('readonly');
		$(".switchery").attr('onclick',"specSwitch();");
	}else if ($(this).val()=='2'){
		$("#stock").attr({readonly:'true'});
		$(".switchery").removeAttr("onclick");
		$("input[name=spec_switch]").val(0);
		$(".switchery").removeClass("checked");
		$(".goods_spec_wrap,.goods_sku_list").animate({opacity: 'hide'}, 'slow');

		$("input[name='market_price']").removeAttr('readonly');
		$("input[name='price']").removeAttr('readonly');
		$("input[name='integral']").removeAttr('readonly');
		$("input[name='stock']").removeAttr('readonly');
	}
});

function specSwitch(){
	if($("input[name=spec_switch]").val() == 0) {
		$("input[name=spec_switch]").val(1);
		$(".switchery").addClass("checked");
		$(".goods_spec_wrap,.goods_sku_list").animate({opacity: 'show'}, 'slow');
		
		$("input[name='market_price']").attr('readonly','readonly');
		$("input[name='price']").attr('readonly','readonly');
		$("input[name='integral']").attr('readonly','readonly');
		$("input[name='stock']").attr('readonly','readonly');

		$('.refresh_goods_attr').trigger('click');
	}else{
		$("input[name=spec_switch]").val(0);
		$(".switchery").removeClass("checked");
		$(".goods_spec_wrap,.goods_sku_list").animate({opacity: 'hide'}, 'slow');
		
		$("input[name='market_price']").removeAttr('readonly');
		$("input[name='price']").removeAttr('readonly');
		$("input[name='integral']").removeAttr('readonly');
		$("input[name='stock']").removeAttr('readonly');
	}
}

var ItemAttr = {
	level: -1,
	selectedAttr: [],
	init: function(){
		ItemAttr.binding();
		ItemAttr.refresh();
	},
	refresh: function(){
		ItemAttr.mutex();
		ItemAttr.check();
	},
	binding: function(){
		var obj;
		obj = $('.select_goods_attr');
		if (obj.length) {
			obj.unbind('click').click(function(){
				ItemAttr.add(this);
			});
		}
		obj = $('.del_goods_attr');
		if (obj.length) {
			obj.unbind('click').click(function () {
				var index = parseInt($(this).parent().parent().parent().parent().next().attr('data-index'))-1;
				$(this).parent().parent().parent().parent().next().attr('data-index', index);
				ItemAttr.delete($(this).parent().parent().parent());
			});
		}
		obj = $('.goods_attr');
		if (obj.length) {
			obj.unbind('change').change(function () {
				ItemAttr.change(this);
			});
		}
		obj = $('.refresh_goods_attr');
		if (obj.length && typeof(obj.attr('data-click')) == 'undefined') {
			obj.attr('data-click', 1).bind('click', function(){
				ItemAttr.refresh_table(this);
			});
		}
	},
	change: function(obj){
		ItemAttr.mutex();
		var attr_id = $('option:selected', obj).val();
		ItemValue.load(attr_id, $(obj).parent().parent());
	},
	mutex: function(){
		var goods_attr = $('.goods_attr');
		ItemAttr.selectedAttr = [];
		goods_attr.each(function() {
			var value = $('option:selected', this).val();
			if (value > 0) {
				if ($.inArray(value, ItemAttr.selectedAttr) == -1) {
					ItemAttr.selectedAttr.push(value);
				}
			}
		});
		if (ItemAttr.selectedAttr.length) {
			goods_attr.each(function(){
				$('option', this).each(function(){
					if ($.inArray($(this).val(), ItemAttr.selectedAttr) == -1) {
						$(this).show();
					} else {
						if (!$(this).prop('selected')) {
							$(this).hide();
						}
					}
				});
			});
		}
	},
	check: function(){
		if (ItemAttr.level > 0 && $('.goods_spec_inner .item_spec').length >= ItemAttr.level) {
			$('.select_goods_attr').hide();
		} else {
			$('.select_goods_attr').show();
		}
	},
	toggleAddBtn: function(obj){
		if (!$(obj).prop('disabled')) {
			$('img', obj).show();
			$(obj).prop('disabled', true)
		} else {
			$('img', obj).hide();
			$(obj).prop('disabled', false)
		}
	},
	add: function(obj){
		ItemAttr.toggleAddBtn(obj);
		var index = parseInt($(obj).attr('data-index'));
		var goods_id = $(obj).data('id');
		$.ajax({
			url: goods_config.loadAttrUrl,
			dataType: 'json',
			data: {goods_id:goods_id,number:index},
			success: function(res){
				ItemAttr.toggleAddBtn(obj);
				if (res.code == 0) {
					index++;
					$(obj).attr('data-index', index);

					var html = '', item;
					html += '<li class="item_spec clearfix mar-b-10">';
					html += '	<div class="col-md-6 clearfix mar-l-m-15">';
					html += '		<select class="goods_attr form-control pull-left w-280" name="goods_attr[]" data-index="'+index+'">';
					html += '		<option value="0">请选择</option>';
					for (var i=0; i<res.list.length; i++) {
						item = res.list[i];
						html += '<option value="'+item['id']+'">'+item['title']+'</option>';
					}
					html += '		</select>';
					html += '		<div class="btn-group">';
					html += '		<span class="btn btn-default btn-sm pad-t-b-7 pad-l-r-10 del_goods_attr" title="删除"><i class="fa fa-times"></i></span>';
					html += '		</div>';
					html += '	</div>';
					html += ' 	<div class="col-md-12 mar-t-10 mar-b-15"><ul class="goods_value_wrap"></ul></div>';
					html += '</li>';
					$('.goods_spec_inner').append(html);
					ItemAttr.binding();
					ItemAttr.refresh();
				} else {
					util.message(res.errmsg, '', 'error');
				}
			}
		});
	},
	delete: function(obj){
		var value = $('.goods_attr option:selected', obj).val();
		var index = $.inArray(value, ItemAttr.selectedAttr);
		if (index != -1) {
			ItemAttr.selectedAttr.splice(index, 1);
		}
		obj.remove();
		ItemAttr.refresh();
	},
	refresh_table: function(ele){
		var arr = new Array;
		$('.goods_attr option:selected').each(function(){
			arr.push($(this).text());
		});
		$('.goods_spec_inner :checkbox:checked').each(function(){
			arr.push($(this).attr('data-value'));
		});
		if (arr.length) {
			var md5 = $(ele).attr('data-md5');
			var str = arr.join(',');
			if (typeof(md5) == 'undefined') {
				md5 = $.md5(str);
				$(ele).attr('data-md5', md5);
			}
		}

		$(ele).attr('disabled', true);
		$('i', ele).addClass('fa-spin');

		setTimeout(function(){
			ItemSku.refresh();

			if (md5 != '') {
				var arr = new Array;
				$('.goods_attr option:selected').each(function(){
					arr.push($(this).text());
				});
				$('.goods_spec_inner :checkbox:checked').each(function(){
					arr.push($(this).attr('data-value'));
				});
				if (arr.length) {
					var str = arr.join(',');
					if (md5 != $.md5(str)) {
						$('input[name=spec_switch]').val('2');
					} else {
						$('input[name=spec_switch]').val('1');
					}
				}
			}

			$(ele).attr('disabled', false);
			$('i', ele).removeClass('fa-spin');
		}, 500);
	},
};
var ItemValue = {
	load: function(attr_id, parent_obj) {
		if (attr_id == 0) {
			$('.goods_value_wrap', parent_obj).html('');
			return;
		}
		$.ajax({
			url: goods_config.loadSpecUrl,
			type: 'post',
			data: {attr_id:attr_id},
			dataType: 'json',
			success: function(res){
				if (res.code == 0) {
					var html = '<span class="label label-default fl hand mar-t-2 check-all-spec" data-status="0">全选/反选</span>', item;
					for (var i=0; i<res.list.length; i++) {
						item = res.list[i];
						html += '<li class="item_value fl mar-l-10">';
						html += '	<label>';
						html += '		<input type="checkbox" value="'+item['id']+'" data-value="'+item['value']+'"> '+item['value'];
						html += '	</label>';
						html += '</li>';
					}
					$('.goods_value_wrap', parent_obj).html(html);
				} else {
					util.message(res.errmsg, '', 'error');
				}
			}
		});
	}
};
var ItemSku = {
	binding: function(){
		if ($('input[name="sku[total][]"]').length) {
			$('input[name="sku[total][]"]').on('input' , function(){
				ItemSku.calcTotal();
			});
		}
		if ($('input[name="sku[integral][]"]').length) {
			$('input[name="sku[integral][]"]').change(function(){
				var integral = parseInt($(this).val());
				if (!integral.isCurrency()) {
					$(this).parent().addClass('has-error');
				} else {
					$(this).parent().removeClass('has-error');
				}
				ItemSku.calcIntegral();
			});
		}
		if ($('input[name="sku[price][]"]').length) {
			$('input[name="sku[price][]"]').change(function(){
				var price = parseFloat($(this).val());
				if (!price.isCurrency()) {
					$(this).parent().addClass('has-error');
				} else {
					$(this).parent().removeClass('has-error');
				}
				ItemSku.calcPrice();
			});
		}
		if ($('input[name="sku[market_price][]"]').length) {
			$('input[name="sku[market_price][]"]').change(function(){
				var market_price = parseFloat($(this).val());
				if (!market_price.isCurrency()) {
					$(this).parent().addClass('has-error');
				} else {
					$(this).parent().removeClass('has-error');
				}
				ItemSku.calcMarket_price();
			});
		}
		if ($('input[name="sku[unit][]"]').length) {
			$('input[name="sku[unit][]"]').change(function(){
				var unit = $(this).val()
				ItemSku.calcUnit();
			});
		}
	},
	calcTotal: function(){
		var total = 0;
		$('input[name="sku[total][]"]').each(function(){
			total += parseInt($(this).val());
		});
		$('input[name=stock]').val(total);
	},
	calcIntegral: function(){
		var integral = 0, sku_integral = 0;
		$('input[name="sku[integral][]"]').each(function(){
			sku_integral = parseInt($(this).val());
			if (sku_integral > 0) {
				if (integral == 0) {
					integral = sku_integral;
				} else {
					if (sku_integral < integral) {
						integral = sku_integral;
					}
				}
			}
		});
		$('input[name=integral]').val(integral);
	},
	calcPrice: function(){
		var sku_price = 0.00, min_price = 999999999;
		$('input[name="sku[price][]"]').each(function(){
			sku_price = parseFloat($(this).val());
			if (sku_price <= min_price) {
				min_price = sku_price;
			}
		});
		
		$('input[name=price]').val(min_price);
	},
	calcMarket_price: function(){
		var price = 0.00, market_price = 0.00;
		$('input[name="sku[market_price][]"]').each(function(){
			market_price = parseFloat($(this).val());
			if (market_price > 0.00) {
				if (price == 0.00) {
					price = market_price;
				} else {
					if (market_price < price) {
						price = market_price;
					}
				}
			}
		});
		$('input[name=market_price]').val(price);
	},
	calcUnit: function(){
		var unit = '';
		$('input[name="sku[unit][]"]').each(function(){
			var val = $(this).val();
			if(val) {
				unit = val;
			}
		});
		$('input[name=unit]').val(unit);
	},
	tdSuffix: function(index){
		var skuid = 0, integral = 0, cover = '', total = 0, price = 0.00, market_price = 0.00, unit = '', sales = 0;
		if ($.isArray(GoodsSkuData) && GoodsSkuData.length && typeof(GoodsSkuData[index]) != 'undefined') {
			skuid = GoodsSkuData[index].id;
			integral = GoodsSkuData[index].integral;
			cover = GoodsSkuData[index].cover == '' ? '' : GoodsSkuData[index].cover;
			total = GoodsSkuData[index].total;
			price = GoodsSkuData[index].price;
			market_price = GoodsSkuData[index].market_price;
			unit = GoodsSkuData[index].unit;
			sales = GoodsSkuData[index].sales;
		}

		var html = '';
		html += '<td>';
		html += '<input class="sku_cover_input sku_cover_input_'+skuid+'" type="hidden" name="sku[cover][]" value="'+cover+'"/>';
		html += '<img src="' + tomedia(cover) + '" class="sku_cover sku_cover_flag_'+skuid+' hand" data-toggle="modal" data-target=".sku-cover-update-modal" data-skuid="'+skuid+'" onerror="this.src=\'' + goods_config.module_url + 'static/web/images/update-cover.png\'" width="60" height="60">';
		html += '</td>';
		html += '<td><input type="hidden" name="sku[id][]" value="'+skuid+'"/>';
		html += '<input type="text" class="form-control" name="sku[total][]" value="'+total+'"/></td>';
		html += '<td><input type="text" class="form-control" name="sku[integral][]" value="'+integral+'"/></td>';
		html += '<td><input type="text" class="form-control" name="sku[price][]" value="'+price+'"/></td>';
		html += '<td><input type="text" class="form-control" name="sku[market_price][]" value="'+market_price+'"/></td>';
		html += '<td><input type="text" class="form-control" name="sku[unit][]" value="'+unit+'"/></td>';
		html += '<input type="hidden" name="sku[sales][]" value="'+sales+'"/></td>';

		return html;
	},
	refresh: function(){
		var goods_attr = $('.goods_spec_inner select');
		if (!goods_attr.length) {
			$('#goods_sku_wrap').html('').parent().fadeOut();
			return;
		}
		var list = new Array, value_ids = new Array;
		goods_attr.each(function(){
			if ($(this).val() == '0') {
				return;
			}
			var values = $(':checkbox:checked', $(this).parent().parent());
			var arr = new Array, ids = new Array;
			values.each(function(){
				arr.push($(this).attr('data-value'));
				ids.push($(this).val());
			});
			$(this).attr('data-value-length', values.length);
			list.push(arr);
			value_ids.push(ids);
		});
		if (!list.length) {
			return;
		}
		list.sort(function(a, b){
			return a.length - b.length;
		});
		value_ids.sort(function(a, b){
			return a.length - b.length;
		});
		goods_attr.sort(function(a, b){
			return $(a).attr('data-value-length') - $(b).attr('data-value-length');
		});
		var rows = $(list).array2row(), ids = $(value_ids).array2row(), html = '';
		for (var i=0; i<ids.length; i++) {
			ids[i] = ids[i].split(',').sort(function(a, b){
				return a - b;
			}).join(',');
		}

		var th = '';
		th += '<th class="text-center" width="80">产品图</th>';
		th += '<th class="text-center">库存</th>';
		th += '<th class="text-center">兑换积分</th>';
		th += '<th class="text-center">销售价</th>';
		th += '<th class="text-center">市场价</th>';
		th += '<th class="text-center">单位</th>';

		var thead = th;
		html += '<table class="table table-bordered">';
		var tdPrefix = '';
		for (var i=0; i<goods_attr.length; i++) {
			tdPrefix += '<th>'+$(goods_attr[i]).find('option:selected').text()+'</th>';
		}
		html += tdPrefix + thead;
		var arr;
		for (var i=0; i<rows.length; i++) {
			html += '<tr>';
			html += '<input type="hidden" name="sku[value_ids][]" value="'+ids[i]+'"/>';
			arr = rows[i].split(',');
			for (var j=0; j<arr.length; j++) {
				html += '<td>'+arr[j]+'</td>';
			}
			html += ItemSku.tdSuffix(ids[i]);
			html += '</tr>';
		}
		html += '</table>';
		$('#goods_sku_wrap').html(html).parent().fadeIn('',function () {
			$('.sku-cover-update-modal').on('show.bs.modal', function (event) {
				var skuid = $(event.relatedTarget).data('skuid');
				var sku_cover = $(event.relatedTarget).prev().val();
				var modal = $(this);
				if (skuid == 0) {
					$(event.relatedTarget).addClass('current_element');
					$(event.relatedTarget).prev().addClass('current_element_input');
				} else {
					modal.find('.update_skucover_btn').attr('data-skuid',skuid);
				}
				if (sku_cover == 'undefined') {
					$('input[name="cur_sku_cover"]').val('');
					$('.sku-cover-update-modal').find('.img-thumbnail').attr('src',goods_config.manage_url + '/resource/images/nopic.jpg');
				} else {
					$('input[name="cur_sku_cover"]').val(sku_cover);
					$('.sku-cover-update-modal').find('.img-thumbnail').attr('src',tomedia(sku_cover));
				}
			});
			$('.update_skucover_btn').unbind('click').click(function () {
				var cur_skuid = $('.update_skucover_btn').attr('data-skuid');
				var cur_img_link = $('input[name="cur_sku_cover"]').val();
				if (cur_skuid == 0 || cur_skuid == undefined) {
					if ($('input[name="cur_sku_cover"]').val() == '') {
						$('.current_element_input').val('');
						$(".current_element").attr('src', goods_config.module_url + 'static/web/images/update-cover.png');
					} else {
						$(".current_element").attr('src',tomedia(cur_img_link));
						$('.current_element_input').val(cur_img_link);
					}
					$('.sku_cover').removeClass('current_element');
				} else {
					if ($('input[name="cur_sku_cover"]').val() == '') {
						$(".sku_cover_flag_"+cur_skuid).attr('src', goods_config.module_url + 'static/web/images/update-cover.png');
						$(".sku_cover_input_"+cur_skuid).val('');
					} else {
						$(".sku_cover_flag_"+cur_skuid).attr('src',tomedia(cur_img_link));
						$(".sku_cover_input_"+cur_skuid).val(cur_img_link);
					}
				}
				$('.update_skucover_btn').attr('data-skuid','');
				$('input[name="cur_sku_cover"]').val('');
				$('.sku-cover-update-modal').find('.img-thumbnail').attr('src', goods_config.manage_url + '/resource/images/nopic.jpg');
			});
			$('.sku-cover-update-modal').on('hide.bs.modal', function (event) {
				$('.sku_cover').removeClass('current_element');
				$('.sku_cover_input').removeClass('current_element_input');
			});
		});
		for (var i=0; i<goods_attr.length-1; i++) {
			$('#goods_sku_wrap table').rowspan(i);
		}
		ItemSku.binding();
	},
};
ItemAttr.init();	
if ($.isArray(GoodsSkuData) && GoodsSkuData.length) {
	$('.refresh_goods_attr').trigger('click');
}

$.fn.array2row = function() {
    var arr = this, len = arr.length;
    if (len>=2){
        var len1 = arr[0].length;
        var len2 = arr[1].length;
        var newlen = len1 * len2;
        var temp = new Array(newlen);
        var index = 0;
        for (var i=0; i<len1; i++) {
            for (var j=0; j<len2; j++) {
                temp[index] = arr[0][i] + ',' + arr[1][j];
                index++;
            }
        }
        var newarray = new Array(len-1);
        for (var i=2; i<len; i++) {
            newarray[i-1] = arr[i];
        }
        newarray[0] = temp;
        return $(newarray).array2row();
    } else {
        return arr[0];
    }
};

$.fn.rowspan = function(colIdx) {
    return this.each(function(){
        var that, rowspan;
        $('tr', this).each(function(row) {
            $('td:eq('+colIdx+')', this).filter(':visible').each(function(col) {
                if (that != null && $(this).html() == $(that).html()) {
                    rowspan = $(that).attr("rowSpan");
                    if (rowspan == undefined) {
                        $(that).attr("rowSpan",1);
                        rowspan = $(that).attr("rowSpan");
                    }
                    rowspan = Number(rowspan)+1;
                    $(that).attr("rowSpan",rowspan);
                    $(this).hide();
                } else {
                    that = this;
                }
            });
        });
    });
};

Number.prototype.isCurrency = function(){
    var reg = /^[0-9]*(\.[0-9]{1,2})?$/;
    return reg.test(this)?true:false;
};

function tomedia(t, e) {
	if (0 == t.indexOf("http://") || 0 == t.indexOf("https://") || 0 == t.indexOf("./resource")) return t;
	if (0 != t.indexOf("./addons")) return e ? window.sysinfo.attachurl_local + t: window.sysinfo.attachurl + t;
	var i = window.document.location.href,
	o = window.document.location.pathname,
	n = i.indexOf(o),
	a = i.substring(0, n);
	return "." == t.substr(0, 1) && (t = t.substr(1)),
	a + t
}

//批量设置
$('.btn_total_submit').click(function(){
	var value = $('input[name=batch_total]', $(this).parent().prev()).val();
	$('input[name="sku[total][]"]').val(value);
	ItemSku.calcTotal();
});
$('.btn_integral_submit').click(function(){
	var value = $('input[name=batch_integral]', $(this).parent().prev()).val();
	$('input[name="sku[integral][]"]').val(value);
	ItemSku.calcIntegral();
});
$('.btn_price_submit').click(function(){
	var value = $('input[name=batch_price]', $(this).parent().prev()).val();
	$('input[name="sku[price][]"]').val(value);
	ItemSku.calcPrice();
});
$('.btn_market_price_submit').click(function(){
	var value = $('input[name=batch_market_price]', $(this).parent().prev()).val();
	$('input[name="sku[market_price][]"]').val(value);
	ItemSku.calcMarket_price();
});
$('.btn_unit_submit').click(function(){
	var value = $('input[name=batch_unit]', $(this).parent().prev()).val();
	$('input[name="sku[unit][]"]').val(value);
	ItemSku.calcUnit();
});
/* 选择商品类型和规格 END */



/* 自定义海报 START */
$('form').submit(function() {
	var data = [];
	$('.drag').each(function() {
		var obj = $(this);
		var type = obj.attr('type');
		var left = obj.css('left'),
		top = obj.css('top');
		var d = {
			left: left,
			top: top,
			type: obj.attr('type'),
			width: obj.css('width'),
			height: obj.css('height')
		};
		if (type == 'nickname') {
			d.size = obj.attr('size');
			d.color = obj.attr('color');
		} else if (type == 'qr') {
			d.size = obj.attr('size');
		} else if (type == 'img') {
			d.src = obj.attr('src');
		}
		data.push(d);
	});
	$(':input[name=poster_setting]').val(JSON.stringify(data));

	return true;
});

function bindEvents(obj) {
    var index = obj.attr('index');
    var rs = new Resize(obj, {
		Max: true,
		mxContainer: "#poster"
    });
    rs.Set($(".rRightDown", obj), "right-down");
    rs.Set($(".rLeftDown", obj), "left-down");
    rs.Set($(".rRightUp", obj), "right-up");
    rs.Set($(".rLeftUp", obj), "left-up");
    rs.Set($(".rRight", obj), "right");
    rs.Set($(".rLeft", obj), "left");
    rs.Set($(".rUp", obj), "up");
    rs.Set($(".rDown", obj), "down");
    rs.Scale = true;
    var type = obj.attr('type');
    if (type == 'nickname') {
		rs.Scale = false;
    }
    new Drag(obj, {
		Limit: true,
		mxContainer: "#poster"
    });
    $('.drag .remove').unbind('click').click(function() {
		$(this).parent().remove();
    })

	$.contextMenu({
		selector: '.drag[index=' + index + ']',
		callback: function(key, options) {
			var index = parseInt($(this).attr('zindex'));

			if (key == 'next') {
				var nextdiv = $(this).next('.drag');
				if (nextdiv.length > 0) {
					nextdiv.insertBefore($(this));
				}
			} else if (key == 'prev') {
				var prevdiv = $(this).prev('.drag');
				if (prevdiv.length > 0) {
					$(this).insertBefore(prevdiv);
				}
			} else if (key == 'last') {
				var len = $('.drag').length;
				if (index >= len - 1) {
					return;
				}
				var last = $('#poster .drag:last');
					if (last.length > 0) {
					$(this).insertAfter(last);
				}
			} else if (key == 'first') {
				var index = $(this).index();
				if (index <= 1) {
					return;
				}
				var first = $('#poster .drag:first');
				if (first.length > 0) {
					$(this).insertBefore(first);
				}
			} else if (key == 'delete') {
				$(this).remove();
			}
			var n = 1;
			$('.drag').each(function() {
				$(this).css("z-index", n);
				n++;
			})
		},
		items: {
			"next": {
				name: "调整到上层"
			},
			"prev": {
				name: "调整到下层"
			},
			"last": {
				name: "调整到最顶层"
			},
			"first": {
				name: "调整到最低层"
			},
			"delete": {
				name: "删除元素"
			}
		}
	});
	obj.unbind('click').click(function() {
		bind($(this));
	})
}

var imgsettimer = 0;
var nametimer = 0;
var bgtimer = 0;
function clearTimers() {
	clearInterval(imgsettimer);
	clearInterval(nametimer);
	clearInterval(bgtimer);

}
function bind(obj) {
	var imgset = $('#imgset'),
	nameset = $("#nameset"),
	qrset = $('#qrset');
	imgset.hide(),
	nameset.hide(),
	qrset.hide();
	clearTimers();
	var type = obj.attr('type');
	if (type == 'img') {
		imgset.show();
		var src = obj.attr('src');
		var input = imgset.find('input');
		var img = imgset.find('img');
		if (typeof(src) != 'undefined' && src != '') {
			input.val(src);
			img.attr('src', src);
		}

		imgsettimer = setInterval(function() {
			if (input.val() != src && input.val() != '') {
				var url = input.val();

				obj.attr('src', input.val()).find('img').attr('src', url);
			}
		}, 10);

	} else if (type == 'nickname') {
		nameset.show();
		var color = obj.attr('color') || "#000000";
		var size = obj.attr('size') || "16";
		var input = nameset.find('input:first');
		var namesize = nameset.find('#namesize');
		var picker = nameset.find('.sp-preview-inner');
		input.val(color);
		namesize.val(size.replace("px", ""));
		picker.css({
			'background-color': color,
			'font-size': size,
			'text-align': 'center',
		});

		nametimer = setInterval(function() {
		obj.attr('color', input.val()).find('.text').css('color', input.val());
		obj.attr('size', namesize.val() + "px").find('.text').css('font-size', namesize.val() + "px");
		obj.attr('text-align', input.val()).find('.text').css('text-align', 'center');
		}, 10);

	} else if (type == 'qr') {
		qrset.show();
		var size = obj.attr('size') || "3";
		var sel = qrset.find('#qrsize');
		sel.val(size);
		sel.unbind('change').change(function() {
			obj.attr('size', sel.val())
		});

	}
}

$(function() {
	if(goods_config.poster_setting == 1){
		$('.drag').each(function(){
			bindEvents($(this));
		})
	}
	$(':radio[name=type]').click(function(){
		var type = $(this).val();
		bindType(type);
	})
	//改变背景
	$('#bgset').find('button:first').click(function() {
		var oldbg = $(':input[name=poster_bg]').val();
		bgtimer = setInterval(function() {
		var bg = $(':input[name=poster_bg]').val();
		if (oldbg != bg) {
			$('#poster .bg').remove();
			var bgh = $("<img src='" + goods_config.attachurl + bg + "' class='bg' />");
			var first = $('#poster .drag:first');
			if (first.length > 0) {
				bgh.insertBefore(first);
			} else {
				$('#poster').append(bgh);
			}
				oldbg = bg;
			}
		},10);
	})

	$('.btn-com').click(function() {
		var imgset = $('#imgset'),
		nameset = $("#nameset"),
		qrset = $('#qrset');
		imgset.hide(),
		nameset.hide(),
		qrset.hide();
		clearTimers();

		var type = $(this).data('type');
		var img = "";
		if (type == 'qr') {
			img = '<img src="' + goods_config.module_url + 'static/web/poster/images/qr.jpg?v=1" />';
		} else if (type == 'head') {
			img = '<img src="' + goods_config.module_url + 'static/web/poster/images/head.png" />';
		} else if (type == 'nickname') {
			img = '<div class=text>昵称</div>';
		}
		var index = $('#poster .drag').length + 1;
		var obj = $('<div class="drag" type="' + type + '" index="' + index + '" style="z-index:' + index + '">' + img + '<div class="rRightDown"> </div><div class="rLeftDown"> </div><div class="rRightUp"> </div><div class="rLeftUp"> </div><div class="rRight"> </div><div class="rLeft"> </div><div class="rUp"> </div><div class="rDown"></div></div>');

		$('#poster').append(obj);
		bindEvents(obj);
	});

    $('.drag').click(function() {
		bind($(this));
    })
})
/* 自定义海报 END */



/* 关联商品 START */
$(':radio[name="goods_like_type"]').click(function() {
	if($(this).val()==1){
		$(".like-type-goods").show();
	}else{
		$(".like-type-goods").hide();
	}
});

var curr_page = 1;
function searchGoods() {
	$('.modals-select-goods').modal();
	$("#modal-goods-list").html("正在搜索....");
	$.get(getGoodsUrl, {
		goods_type: $("#goods_type").val(),
		goods_status: $("#goods_status").val(),
		keyword: $.trim($("#search-kwd").val()),
		page: curr_page,
	}, function (dat) {
		$("#modal-goods-list").html(dat);
		curr_page = ($(".curr_page").text());
		$("#curr_page").text(($(".curr_page").text()));
		$("#total_page").text(($(".total_page").text()));
	});
}

function select_goods(obj) {
	var id = $(obj).data('id');
	var title = $(obj).data('title');
	var cover = $(obj).data('cover');

	if ($('.select-item[data-id="' + id + '"]').length > 0) {
		return;
	}

	var html = '<div class="select-item bg-size" data-id="' + id + '" data-title="' + title + '" style="background:#eee url(' + cover + ');" title="' + title + '">';
	html += '		<a href="javascript:;" class="remove_goods" onclick="remove_goods(this);" title="移除商品"></a>';
	html += '		<div class="text">' + title + '</div>';
	html += '		<input type="hidden" name="like_goods_ids[]" value="' + id + '">';
	html += '	</div>';
	$(".select-goods-choices").append(html);

	util.message('添加成功', '', 'success');
}

$(".goods-pager-nav").click(function(){
	var total_page	= $(".total_page").text();
	var button_name = $(this).data('name');

	if(button_name == 'prev'){
		if(curr_page <= 1){
			return;
		}else{
			curr_page--;
			searchGoods();
		}
	}else if(button_name == 'next'){
		if(curr_page >= total_page){
			return;
		}else{
			curr_page++;
			searchGoods();
		}
	}
})

function remove_goods(obj) {
	$(obj).parent().remove();
}

$("#search-kwd").bind("keypress", function(e) {
	if (e.keyCode == "13") {
		e.preventDefault();
		searchGoods();
	}
});

/* 关联商品 END */