var config = window.config;
var get_status  = true;

$(function () {
	if(config.op == 'display'){
		var nowPage = 1;
		function getData(page) {  
			if(get_status){
				nowPage++;
				$.get(config.listurl, {page: page}, function (data) {  
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
						empty_html	+=	'		<p>没有找到任何记录</p>';
						empty_html	+=	'	</div>';
						empty_html	+=	'</div>';

						$("#commission_log").html(empty_html);
					}
				});
			}
		}

		getData(1);
		function insertDiv(result) {  
			var mainDiv =$("#commission_log");
			var chtml = '';
			for (var j = 0; j < result.length; j++) {
				chtml += '<div class="shop-commission-box">';
				chtml += '	<a href="javascript:void(0);" class="shop-well-item">';
				chtml += '		<div class="shop-well-item-bd">';
				chtml += '			<h3>编号：' + result[j].id + '</h3>';
				chtml += '		</div>';
				chtml += '		<span class="shop-well-item-fr"><em class="' + result[j].status_color + '">' + result[j].status_name + '</em></span>';
				chtml += '	</a>';
				chtml += '	<p class="shop-commission-fl shop-commission-address">';
				chtml += '		奖励明细：'+ result[j].remark;
				if(result[j].buyer_uid>0){
					chtml +=	'，用户信息:[uid:' + result[j].buyer_uid + ']';	
				}
				if(result[j].buyer_name){
					chtml +=	'，昵称：' + result[j].buyer_name;	
				}
				chtml += '	</p>';
				chtml += '	<p class="shop-commission-fl shop-commission-door">佣金金额：<em class="income_amount">'+ result[j].commission + '</em> 元</p>';
				if(result[j].status_code == 0){
					chtml += '	<p class="shop-commission-fl shop-commission-time">预计发放时间：' + result[j].predict_sendtime + '</p>';
				}else if(result[j].status_code == 1){
					chtml += '	<p class="shop-commission-fl shop-commission-time">发放时间：' + result[j].predict_sendtime + '</p>';
				}
				chtml += '</div>';
			}
			mainDiv.append(chtml);
		}  
	  
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
		})
		$("#btn_Page").click(function(){
			$("#loadingToast").show();
			getData(nowPage);
		})
	}
  
}) 

