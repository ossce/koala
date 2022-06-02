(function($){
    $.fn.extend({
	    Scroll:function(opt,callback){
	    	if(!opt) var opt={};
	    	var oo;
	    	var _this=this.eq(0).find("ul:first");
	    	var lineH=_this.find("li:first").height(),//23
	    	line = opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10),
	    	speed=opt.speed?parseInt(opt.speed,10):7000, //卷动速度，数值越大，速度越慢（毫秒）
	    	timer=opt.timer?parseInt(opt.timer,10):7000; //滚动的时间间隔（毫秒）
	    	if(line==0) line=1;
	    	var upHeight = 0-line*lineH;//-总高度
		    scrollUp=function(){
			    _this.animate({
			    marginTop:upHeight // <li>的margin-top
			    },speed,function(){
				    for(i=1;i<=line;i++){
				    _this.find("li:first").appendTo(_this);
				    }
			    	_this.css({marginTop:0});
			    	}
			    );
		    };
		    var timerID = function(){
		    	oo =setInterval("scrollUp()",timer);
		    };
		   	timerID();
	    _this.hover(function(){
		    clearInterval(oo);
		    },function(){
				timerID();
		    })
	    }
    });
})(jQuery);