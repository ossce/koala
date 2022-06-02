var config = window.config;

$(function(){
	$(".first-category.on").click();
})

$(".first-category").click(function(){
	$(".first-category").removeClass('on');
	$(this).addClass('on');

	var curr_id = $(this).data('id');
	//一级分类
	for(var j1=0; j1<config.list.length; j1++){
		if(config.list[j1].id == curr_id){
			var s_html = '';
			if(config.list[j1].adv_cover){
				s_html	+=	'<a href="' + config.list[j1].adv_link + '" class="category_right_banner">';
				s_html	+=	'	<img src="' + config.attachurl + config.list[j1].adv_cover + '" alt="' + config.list[j1].name + '">';
				s_html	+=	'</a>';
			}

			//二级分类
			if(config.list[j1].second.length){
				for(var j2=0; j2<config.list[j1].second.length; j2++){
					s_html	+=	'<div class="category-right">';
					s_html	+=	'	<a href="' + config.searchurl + '&pid=' + curr_id + '&cid=' + config.list[j1].second[j2].id + '" class="second-categoty"><img src="' + config.attachurl + config.list[j1].second[j2].icon + '">' + config.list[j1].second[j2].name + '</a>';

					if(config.list[j1].second[j2].third.length){
						s_html	+=	'	<div class="third-category">';
						for(var j3=0; j3<config.list[j1].second[j2].third.length; j3++){
							s_html	+=	'	<a href="' + config.searchurl + '&pid=' + curr_id + '&cid=' + config.list[j1].second[j2].id + '&ccid=' + config.list[j1].second[j2].third[j3].id + '" class="category-item">';
							s_html	+=	'		<div class="icon radius">';
							s_html	+=	'			<img src="' + config.attachurl + config.list[j1].second[j2].third[j3].icon + '">';
							s_html	+=	'		</div>';
							s_html	+=	'		<div class="text">' + config.list[j1].second[j2].third[j3].name + '</div>';
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