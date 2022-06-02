<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 分销佣金明细
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/credit.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>


<?php  if(!$total) { ?>
<div class="my_empty">
	<div class="empty_bd  my_course_empty">
		<h3>没有找到任何记录</h3>
	</div>
</div>
<?php  } else { ?>
<div id="container" class="mar10-top mar10-left-right"></div>
<?php  } ?>

<div id="loading_div" class="loading_div">
	<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
</div>


<script type="text/javascript">
var ajaxurl   = "<?php  echo $this->createMobileUrl('credit', array('type'=>$type));?>";
var loadingToast = document.getElementById("loadingToast");
var get_status = true; //允许获取状态

$(function () {
    var nowPage = 1; //设置当前页数，全局变量
    function getData(page) {  
        if(get_status){
			nowPage++; //页码自动增加，保证下次调用时为新的一页。  
			$.get(ajaxurl, {page: page }, function (data) {  
				loadingToast.style.display = 'none';
				
				var jsonObj = JSON.parse(data);
				if (jsonObj.length > 0) {
					insertDiv(jsonObj);
				}else{
					get_status = false;  //没有数据后，禁止请求获取数据
					document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
				}
			});
		}
       
    } 
    //初始化加载第一页数据  
    getData(1);

    //生成数据html,append到div中  
    function insertDiv(result) {  
        var mainDiv =$("#container");
        var chtml = '';
        for (var j = 0; j < result.length; j++) {
			chtml += '<div class="aui-order-box">';
			chtml += '	<a href="javascript:void(0);" class="aui-well-item">';
			chtml += '		<div class="aui-well-item-bd">';
			chtml += '		<h3>交易时间：' + result[j].createtime + '</h3>';
			chtml += '		</div>';
			chtml += '	</a>';
			chtml += '	<p class="aui-order-fl aui-order-address">类型：'+ result[j].award_name + '</p>';
			chtml += '	<p class="aui-order-fl aui-order-door">数额：<em class="income_amount">'+ result[j].num + '</em></p>';
			chtml += '	<p class="aui-order-fl aui-order-time">时间：' + result[j].createtime + '</p>';
			chtml += '	<p class="aui-order-fl aui-order-address">备注：'+ result[j].remark + '</p>';
			chtml += '</div>';
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
    $("#btn_Page").click(function () {
		loadingToast.style.display = 'block';
        getData(nowPage);
    });
  
});  
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>
