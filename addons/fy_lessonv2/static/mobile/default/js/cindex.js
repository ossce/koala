var config = window.config;


$(function(){
	//最近提现滚动
	var scroll_height = "40px";
	var tsq;
	var showidx = 0;

	var new_scroll = function () {
	  var len = $(".article_div_list li").length;
	  var m = $(".article_div_list li");
	  clearInterval(tsq);
	  if (len > 1) {
		tsq = setInterval(function () {
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear', function () {
			$(this).css("top", scroll_height);
		  });
		  showidx++;
		  if (showidx == len) {
			showidx = 0;
		  }
		  m.eq(showidx).animate({
			top: "-=" + scroll_height
		  }, 500, 'linear');
		}, 3000);
	  }
	}();
})


//等级说明
if(config.level_desc){
	$(".btn-level-desc").click(function(){
		$(".privacy_agreement_notice-mask").animate({opacity: 'show'}, 'slow');
	})
	$(".close-agreement").click(function(){
		$(".privacy_agreement_notice-mask").animate({opacity: 'hide'}, 'slow');
	});
}
