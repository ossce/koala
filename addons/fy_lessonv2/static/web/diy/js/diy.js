// Extend the default Number object with a formatCurrency() method:
// usage: someVar.formatCurrency(decimalPlaces, symbol, thousandsSeparator, decimalSeparator)
// defaults: (2, '$', ',', '.')
Number.prototype.formatCurrency = function (places, symbol, thousand, decimal) {
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '';
    //symbol = symbol !== undefined ? symbol : '&#165;';
    thousand = thousand || ',';
    decimal = decimal || '.';
    var number = this,
        negative = number < 0 ? '-' : '',
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + '',
        j = (j = i.length) > 3 ? j % 3 : 0;
    return symbol + negative + (j ? i.substr(0, j) + thousand : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : '');
};
Number.prototype.isCurrency = function(){
    var reg = /^[0-9]*(\.[0-9]{1,2})?$/;
    return reg.test(this)?true:false;
};
Number.prototype.formatCurrency2 = function (places, symbol, thousand, decimal) {
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '';
    //symbol = symbol !== undefined ? symbol : '&#165;';
    thousand = thousand || ',';
    decimal = decimal || '.';
    var number = this,
        negative = number < 0 ? '-' : '',
        i = parseInt(number = Math.abs(+number || 0), 10) + '',
        j = (j = i.length) > 3 ? j % 3 : 0;
    return symbol + negative + (j ? i.substr(0, j) + thousand : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (places ? decimal + Math.abs(number - i).slice(2) : '');
};
//rgb(0,0,0)
String.prototype.rgb2Hex = function(prefix){
    var str = this.toLowerCase();
    var arr = str.replace('rgb(', '').replace(')', '').split(',');
    var r = arr[0], g = arr[1], b = arr[2];
    return (typeof(prefix)=='undefined'?'#':prefix)+((r << 16) | (g << 8) | b).toString(16);
};
fylesson.development = 0; //开发模式
fylesson.log = function (msg, type) {
    this.debug = window.fylesson.local_development || window.fylesson.online_development;
    switch (type) {
        case 'info':
            console.info(msg);break;
        case 'debug':
            if (this.debug) {
                console.debug(msg);
            }
            break;
        case 'warn':
            console.warn(msg);break;
        case 'error':
            console.error(msg);break;
        default:
            console.log(msg);break;
    }
};

//iosOverlay
var iosOverlay=function(params){var overlayDOM;var overlayBg;var noop=function(){};var defaults={onbeforeshow:noop,onshow:noop,onbeforehide:noop,onhide:noop,text:"",icon:null,spinner:null,duration:null,id:null,parentEl:null};var merge=function(obj1,obj2){var obj3={};for(var attrOne in obj1){obj3[attrOne]=obj1[attrOne]}for(var attrTwo in obj2){obj3[attrTwo]=obj2[attrTwo]}return obj3};var doesTransitions=(function(){var b=document.body||document.documentElement;var s=b.style;var p='transition';if(typeof s[p]==='string'){return true}var v=['Moz','Webkit','Khtml','O','ms'];p=p.charAt(0).toUpperCase()+p.substr(1);for(var i=0;i<v.length;i++){if(typeof s[v[i]+p]==='string'){return true}}return false}());var settings=merge(defaults,params);var handleAnim=function(anim){if(anim.animationName==="ios-overlay-show"){settings.onshow()}if(anim.animationName==="ios-overlay-hide"){destroy();settings.onhide()}};var create=(function(){overlayBg=document.createElement("div");overlayBg.className="ui-ios-bg";overlayDOM=document.createElement("div");overlayDOM.className="ui-ios-overlay";overlayDOM.innerHTML+='<span class="title">'+settings.text+'</span';if(params.icon){overlayDOM.innerHTML+='<img src="'+params.icon+'">'}else if(params.spinner){overlayDOM.appendChild(params.spinner.el)}if(doesTransitions){overlayBg.addEventListener("webkitAnimationEnd",handleAnim,false);overlayBg.addEventListener("msAnimationEnd",handleAnim,false);overlayBg.addEventListener("oAnimationEnd",handleAnim,false);overlayBg.addEventListener("animationend",handleAnim,false)}if(params.parentEl){document.getElementById(params.parentEl).appendChild(overlayDOM)}else{overlayBg.appendChild(overlayDOM);overlayBg.style.height=Math.max(document.body.scrollHeight,document.documentElement.scrollHeight)+'px';document.body.appendChild(overlayBg)}settings.onbeforeshow();if(doesTransitions){overlayBg.className+=" ios-overlay-show"}else if(typeof $==="function"){$(overlayBg).fadeIn({duration:200},function(){settings.onshow()})}if(settings.duration){window.setTimeout(function(){hide()},settings.duration)}}());var hide=function(){settings.onbeforehide();if(doesTransitions){overlayBg.className=overlayBg.className.replace("show","hide")}else if(typeof $==="function"){$(overlayDOM).fadeOut({duration:200},function(){destroy();settings.onhide()})}};var destroy=function(){if(params.parentEl){document.getElementById(params.parentEl).removeChild(overlayDOM)}else{document.body.removeChild(overlayBg)}};var update=function(params){if(params.text){overlayDOM.getElementsByTagName("span")[0].innerHTML=params.text}if(params.icon){if(settings.spinner){settings.spinner.el.parentNode.removeChild(settings.spinner.el);settings.spinner=null}overlayDOM.innerHTML+='<img src="'+params.icon+'">'}};return{hide:hide,destroy:destroy,update:update}};

//fgnass.github.com/spin.js#v1.2.7
!function(e,t,n){function o(e,n){var r=t.createElement(e||"div"),i;for(i in n)r[i]=n[i];return r}function u(e){for(var t=1,n=arguments.length;t<n;t++)e.appendChild(arguments[t]);return e}function f(e,t,n,r){var o=["opacity",t,~~(e*100),n,r].join("-"),u=.01+n/r*100,f=Math.max(1-(1-e)/t*(100-u),e),l=s.substring(0,s.indexOf("Animation")).toLowerCase(),c=l&&"-"+l+"-"||"";return i[o]||(a.insertRule("@"+c+"keyframes "+o+"{"+"0%{opacity:"+f+"}"+u+"%{opacity:"+e+"}"+(u+.01)+"%{opacity:1}"+(u+t)%100+"%{opacity:"+e+"}"+"100%{opacity:"+f+"}"+"}",a.cssRules.length),i[o]=1),o}function l(e,t){var i=e.style,s,o;if(i[t]!==n)return t;t=t.charAt(0).toUpperCase()+t.slice(1);for(o=0;o<r.length;o++){s=r[o]+t;if(i[s]!==n)return s}}function c(e,t){for(var n in t)e.style[l(e,n)||n]=t[n];return e}function h(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var i in r)e[i]===n&&(e[i]=r[i])}return e}function p(e){var t={x:e.offsetLeft,y:e.offsetTop};while(e=e.offsetParent)t.x+=e.offsetLeft,t.y+=e.offsetTop;return t}var r=["webkit","Moz","ms","O"],i={},s,a=function(){var e=o("style",{type:"text/css"});return u(t.getElementsByTagName("head")[0],e),e.sheet||e.styleSheet}(),d={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#fff",speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"auto",left:"auto",position:"relative"},v=function m(e){if(!this.spin)return new m(e);this.opts=h(e||{},m.defaults,d)};v.defaults={},h(v.prototype,{spin:function(e){this.stop();var t=this,n=t.opts,r=t.el=c(o(0,{className:n.className}),{position:n.position,width:0,zIndex:n.zIndex}),i=n.radius+n.length+n.width,u,a;e&&(e.insertBefore(r,e.firstChild||null),a=p(e),u=p(r),c(r,{left:(n.left=="auto"?a.x-u.x+(e.offsetWidth>>1):parseInt(n.left,10)+i)+"px",top:(n.top=="auto"?a.y-u.y+(e.offsetHeight>>1):parseInt(n.top,10)+i)+"px"})),r.setAttribute("aria-role","progressbar"),t.lines(r,t.opts);if(!s){var f=0,l=n.fps,h=l/n.speed,d=(1-n.opacity)/(h*n.trail/100),v=h/n.lines;(function m(){f++;for(var e=n.lines;e;e--){var i=Math.max(1-(f+e*v)%h*d,n.opacity);t.opacity(r,n.lines-e,i,n)}t.timeout=t.el&&setTimeout(m,~~(1e3/l))})()}return t},stop:function(){var e=this.el;return e&&(clearTimeout(this.timeout),e.parentNode&&e.parentNode.removeChild(e),this.el=n),this},lines:function(e,t){function i(e,r){return c(o(),{position:"absolute",width:t.length+t.width+"px",height:t.width+"px",background:e,boxShadow:r,transformOrigin:"left",transform:"rotate("+~~(360/t.lines*n+t.rotate)+"deg) translate("+t.radius+"px"+",0)",borderRadius:(t.corners*t.width>>1)+"px"})}var n=0,r;for(;n<t.lines;n++)r=c(o(),{position:"absolute",top:1+~(t.width/2)+"px",transform:t.hwaccel?"translate3d(0,0,0)":"",opacity:t.opacity,animation:s&&f(t.opacity,t.trail,n,t.lines)+" "+1/t.speed+"s linear infinite"}),t.shadow&&u(r,c(i("#000","0 0 4px #000"),{top:"2px"})),u(e,u(r,i(t.color,"0 0 1px rgba(0,0,0,.1)")));return e},opacity:function(e,t,n){t<e.childNodes.length&&(e.childNodes[t].style.opacity=n)}}),function(){function e(e,t){return o("<"+e+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',t)}var t=c(o("group"),{behavior:"url(#default#VML)"});!l(t,"transform")&&t.adj?(a.addRule(".spin-vml","behavior:url(#default#VML)"),v.prototype.lines=function(t,n){function s(){return c(e("group",{coordsize:i+" "+i,coordorigin:-r+" "+ -r}),{width:i,height:i})}function l(t,i,o){u(a,u(c(s(),{rotation:360/n.lines*t+"deg",left:~~i}),u(c(e("roundrect",{arcsize:n.corners}),{width:r,height:n.width,left:n.radius,top:-n.width>>1,filter:o}),e("fill",{color:n.color,opacity:n.opacity}),e("stroke",{opacity:0}))))}var r=n.length+n.width,i=2*r,o=-(n.width+n.length)*2+"px",a=c(s(),{position:"absolute",top:o,left:o}),f;if(n.shadow)for(f=1;f<=n.lines;f++)l(f,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(f=1;f<=n.lines;f++)l(f);return u(t,a)},v.prototype.opacity=function(e,t,n,r){var i=e.firstChild;r=r.shadow&&r.lines||0,i&&t+r<i.childNodes.length&&(i=i.childNodes[t+r],i=i&&i.firstChild,i=i&&i.firstChild,i&&(i.opacity=n))}):s=l(t,"animation")}(),e.Spinner=v}(window,document);

$.extend({
    toast: function (text = '', icon = '', duration = 2000, extraClass = 'ui-toast') {
        var opt = {
            text: text,
            duration: duration,
        };
        if (icon == 'success') {
            opt.icon = window.sysinfo.module.url + 'static/web/images/icon-success.png';
        } else if (icon == 'fail' || icon == 'error') {
            opt.icon = window.sysinfo.module.url + 'static/web/images/icon-error.png';
        }
        setTimeout(function () {
            iosOverlay(opt);
            $('.ui-ios-bg').addClass(extraClass);
            $('.ui-ios-overlay').css({
                'margin-left': -$('.ui-ios-overlay').width()/2,
                'margin-top': -$('.ui-ios-overlay').height()/2,
            });
        }, 10);
    },
    showLoading: function (text = '加载中...') {
        if ($('.ios-overlay-show')[0]) return;
        var opt = {
            text: text,
            spinner: new Spinner().spin(document.createElement('div'))
        };
        iosOverlay(opt);
    },
    hideLoading: function () {
        $('.ios-overlay-show').remove();
    },
    initContentHeight: function () {
        //初始化界面元素高度
        var h = $(window).height() - 85 - 39;
        var left = $('.left_wrap'), right = $('.right_wrap');
        var uh = left.height(), nh = right.height(), dh = $(document).height();
        h = Math.max(h,uh);
        h = Math.max(h,nh);
        h = Math.max(h,dh);
        var p = $(window).height() * 0.85;
		if(fylesson.development){
			fylesson.log('window height: '+$(window).height(), 'debug');
			fylesson.log('document height: '+$(document).height(), 'debug');
			fylesson.log('content height: '+h, 'debug');
			fylesson.log('p height: '+p, 'debug');
		}
        if (left.height() > p || right.height() > p) {
            if (left.length) {
                left.css({
                    'min-height': h+'px',
                });
            }
            if (right.length) {
                right.css({
                    'min-height': h+'px',
                });
            }
        } else {
            left.css({
                'min-height': p+'px',
            });
            right.css({
                'min-height': p+'px',
            });
        }
    },
    autoScrolling: function (wrapper, margin_top) {
        if($('.topline_wrap').length > 0) {
            $(wrapper).find('ul').animate({
                marginTop : margin_top
            }, 500, function(){
                $(this).css({marginTop : '0px'}).find('li').slice(0, 1).appendTo($(this));
            });
        }
    },
});

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

$(document).ready(function(){
	if(fylesson.development){
		fylesson.log('Diy System loading', 'info');
		fylesson.log(window.fylesson, 'debug');
	}
		
    $.extend($, {
        returnTop: function(){
            var obj = $('#return_top');
            if (!obj.length) {
                var html = '<a id="return_top" href="javascript:;"></a>';
                $(document.body).append(html);
                obj = $('#return_top');
            }
            obj.click(function () {
				if(fylesson.development){
					fylesson.log('return top click', 'debug');
				}
                $('body,html').animate({
                    scrollTop:0
                },300);
                return false;
            });
            $(window).scroll(function(){
                if ($(this).scrollTop() > 10) {
                    obj.show().animate({
                        bottom: 70
                    }, 50);
                } else if ($(this).scrollTop() == 0) {
                    obj.stop().animate({
                        bottom: -65
                    }, 200);
                }
            });
        }
    });
    $.returnTop();
});
require(['jquery', 'bootstrap'],function($){
    (function () {
        $('[data-toggle="tooltip"]').tooltip();
    })();
});
function base64_encode(str) {
    var c1, c2, c3;
    var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    var i = 0, len = str.length, string = '';
    while (i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if (i == len) {
            string += base64EncodeChars.charAt(c1 >> 2);
            string += base64EncodeChars.charAt((c1 & 0x3) << 4);
            string += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if (i == len) {
            string += base64EncodeChars.charAt(c1 >> 2);
            string += base64EncodeChars.charAt(((c1 & 0x3) << 4)
                | ((c2 & 0xF0) >> 4));
            string += base64EncodeChars.charAt((c2 & 0xF) << 2);
            string += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        string += base64EncodeChars.charAt(c1 >> 2);
        string += base64EncodeChars.charAt(((c1 & 0x3) << 4)
            | ((c2 & 0xF0) >> 4));
        string += base64EncodeChars.charAt(((c2 & 0xF) << 2)
            | ((c3 & 0xC0) >> 6));
        string += base64EncodeChars.charAt(c3 & 0x3F)
    }
    return string
}
function base64_decode(str) {
    var c1, c2, c3, c4;
    var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
        -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
        -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62,
        -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1,
        -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
        15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1,
        26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42,
        43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
    var i = 0, len = str.length, string = '';
    while (i < len) {
        do {
            c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff]
        } while (i < len && c1 == -1);
        if (c1 == -1)
            break;

        do {
            c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff]
        } while (i < len && c2 == -1);

        if (c2 == -1)
            break;
        string += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
        do {
            c3 = str.charCodeAt(i++) & 0xff;
            if (c3 == 61)
                return string;
            c3 = base64DecodeChars[c3]
        } while (i < len && c3 == -1);
        if (c3 == -1)
            break;
        string += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
        do {
            c4 = str.charCodeAt(i++) & 0xff;
            if (c4 == 61)
                return string;
            c4 = base64DecodeChars[c4]
        } while (i < len && c4 == -1);
        if (c4 == -1)
            break;
        string += String.fromCharCode(((c3 & 0x03) << 6) | c4)
    }
    return string;
}