var config = window.config;


$(function(){
	if(config.op == 'list'){
		var nowPage=1, get_status=true, type='';
		function getData(page) {
			if(get_status){
				nowPage++;
				$.get(config.commenturl + "&op=getList", {page:page,type:type,goods_id:config.goods_id}, function (data) {  
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
						empty_html	+=	'		<p>没有找到任何评价</p>';
						empty_html	+=	'	</div>';
						empty_html	+=	'</div>';

						$(".comment_wrap .comment-list").html(empty_html);
					}
				});
			}
		}
		getData(1);
		function insertDiv(result) {  
			var mainDiv = $(".comment_wrap .comment-list");
			var chtml = '';  
			for (var j = 0; j < result.length; j++) {
				chtml += '<li>';
				chtml += '	<div class="user_info">';
				chtml += '		<img class="avatar" src="' + result[j].avatar + '">';
				chtml += '		<span class="user_name">' + result[j].nickname + '</span>';
				chtml += '		<span class="star">';
				for(var k=0; k<5; k++){
					if(k<result[j].score){
						chtml += '	<img src="' + config.staticurl + 'icon-star-active.png">';
					}else{
						chtml += '	<img src="' + config.staticurl + 'icon-star-empty.png">';
					}
				}
				chtml += '		</span>';
				chtml += '		<span class="date">' + result[j].addtime + '</span>';
				chtml += '	</div>';
				chtml += '	<div class="content">' + result[j].content + '</div>';
				if(result[j].picture){
					chtml += '<div class="picture">';
					for(var m=0; m<result[j].picture.length; m++){
						chtml += '<span class="img">';
						chtml += '	<img src="' + config.attachurl + result[j].picture[m] + '">';
						chtml += '</span>';
					}
					chtml += '</div>';
				}
				chtml += '	<div class="sku_name">';
				chtml += '		<span>' + result[j].sku_name + '</span>';
				chtml += '	</div>';
				chtml += '</li>';
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
		});
		$(document).on('click','#btn_Page',function(){
			$("#loadingToast").show();
			getData(nowPage);
		});

		//顶部评论筛选按钮		
		$(".comment_tag_wrap .tag_new span").click(function () {
			$("#loadingToast").show();
			$(this).addClass('selected').siblings().removeClass('selected');

			$('.comment_wrap .comment-list').html('');
			type = $(this).data('type');
			nowPage = 1;
			get_status = true;
			getData(1);
			document.getElementById("loading_div").innerHTML = '<a href="javascript:;" id="btn_Page" style="display:inline;"><img src="' + config.staticurl + 'icon-pull-down.png" class="pull-down"> 加载更多</a>';
		});
	}

	/* 微信环境预览图片接口 */
	if(config.userAgent){
		$(document).on('click','.picture .img img',function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('.picture .img img');
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
		$(document).on('click','.picture img,.cmt-modal-mask',function(){
			$('.cmt-modal-mask').toggleClass('show');
			if($('.cmt-modal-mask').hasClass('show')){
				$('.cmt-picture-view.cmt-modal-main > img').attr('src', this.src);
			}
		})
	}
})


//评价星星
$(".star-score > a").click(function(){
	var score = $(this).data('score');
	$(this).parent().next(".goods_score").val(score);
	if(score == 1){
		$(this).parent().next(".goods_score").next(".attitude").text("非常差");
	}else if(score == 2){
		$(this).parent().next(".goods_score").next(".attitude").text("差");
	}else if(score == 3){
		$(this).parent().next(".goods_score").next(".attitude").text("一般");
	}else if(score == 4){
		$(this).parent().next(".goods_score").next(".attitude").text("好");
	}else if(score == 5){
		$(this).parent().next(".goods_score").next(".attitude").text("非常好");
	}

	$(this).parent().children().each(function(i){
		if(i+1 <= score){
			$(this).children(":first").attr("src", config.staticurl + "icon-star-active.png");
		}else{
			$(this).children(":first").attr("src", config.staticurl + "icon-star-empty.png");
		}
    });
})

//评价晒图
function removeimgs(obj){
	if(!confirm('您确定要删除吗?')){
		return false;
	}else{
		$(obj).parent().next().show();
		$(obj).remove();
	}
}
function uploadimgs(obj, goods_id) {
	var files = obj.files;
	var len = files.length <= 6 ? files.length : 6;
	var file_key = $(obj).parent().prev().children().length;

	for (var i=0; i < len; i++) {
		lrz(files[i], {width: 900, fieldName: "file"}).then(function (data) {
			$.post(config.uploadurl, {imageData: data.base64}, function (rs) {
				$(obj).parent().prev().append('<li onclick="removeimgs(this)" class="weui-uploader__file " style="background-image:url(' + rs.url + ')"><input value="'+rs.src+'" type="hidden" name="images" /></li>');

				var number = $(obj).parent().prev().children('li').length;
				if(number>=6){
					$(obj).parent().hide();
				}
			}, 'json');

		}).then(function (data) {

		}).catch(function (err) {
			console.log(err);
		});
	}
}

//提交评价
$(".btn-submit").click(function(){
	var score_arr = document.getElementsByClassName("goods_score");
	for(var i=0; i<score_arr.length; i++){
		if(score_arr[i].value == 0){
			showSingleDialog("请对商品进行评分");
			return false;
		}
	}

	var content_arr = document.getElementsByClassName("cmt-content");
	for(var i=0; i<content_arr.length; i++){
		if(content_arr[i].value == 0){
			showSingleDialog("请填写评价内容");
			return false;
		}
	}

	var postData = config.postData;
	$(".goods-form").each(function(i){
		var goods_id = $(this).data('goods-id');

		var inputs = $(this).find("input");
		inputs.each(function(j){
			var key_name = $(this).attr("name");
			if(key_name != 'file'){
				if(key_name == 'images'){
					postData[goods_id][key_name][j] = $(this).val();
				}else{
					postData[goods_id][key_name] = $(this).val();
				}
			}
		})

		var textareas = $(this).find("textarea");
		textareas.each(function(j){
			var key_name = $(this).attr("name");
			postData[goods_id][key_name] = $(this).val();
		})
	})
   
	$("#loadingToast").show();
	$.ajax({
		type: "POST",
		url: config.commenturl + "&op=submitComment",
		data:{orderid:config.orderid,postData:postData},
		dataType: "json",
		success:function(res){
			$("#loadingToast").hide();

			if(res.code == 0){
				showSuccessToast(res.message);
				setTimeout(function(){
					window.location.href = config.orderurl;
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
