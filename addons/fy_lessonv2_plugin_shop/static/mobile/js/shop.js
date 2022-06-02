
$(function(){
	$(".shop-recommend-two.static").each(function(){
		var number = $(this).children('.shop-hot-list-img').length;
		if(number % 2 !=0){
			$(this).children('.shop-hot-list-img').each(function(index){
				if(index == (number - 2)){
					$(this).css("border-bottom-width","1px");
					$(this).css("border-bottom-style","solid");
					$(this).css("border-bottom-color","#efefef");
				}
			})
		}
	})
})