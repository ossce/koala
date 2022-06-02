<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 我的讲师服务
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/myteacher.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div>
	<div class="myteacher-bg cbox disabled">
		<div class="teacher-bg"><img src="<?php  echo $myteacher_bg;?>"></div>
	</div>
	<div class="teacher-menu mb_10">
		<ul class="am-avg-sm-5">
			<li class="w-col-5 show-teacher active"><i class="my-teacher-icon"> </i>我的讲师服务</li>
			<li class="w-col-5 show-teacherorder"><i class="my-viporder-icon"> </i>订单记录</li>
			<div class="clear"></div>
		</ul>
	</div>

	<div class="myteacher-info">
		<!-- 讲师服务状态 -->
		<div class="teacher-prompt">
			<?php  if(!empty($list)) { ?>
			<span class="green">我的讲师服务:已开通</span>
			<?php  } else { ?>
			<span class="red">我的讲师服务:未开通</span>
			<?php  } ?>
		</div>
		<!-- 已开通的讲师 -->
		<?php  if(!empty($list)) { ?>
		<div class="teacher-list buy-teacher-list">
			<ul class="myteacher-list">
				<li class="align" style="width:32%;">讲师名称</li>
				<li class="align" style="width:40%;">有效期</li>
				<li class="align" style="width:28%;">操作</li>
				<div class="clear"></div>
			</ul>
			<?php  if(is_array($list)) { foreach($list as $item) { ?>
			<ul class="myteacher-list">
				<li class="align" style="width:32%;"><?php  echo $item['teacher']['teacher'];?></li>
				<li class="align" style="width:40%;"><?php  echo date('Y-m-d H:i',$item['validity']);?></li>
				<li class="align" style="width:28%;"><a href="<?php  echo $this->createMobileUrl('teacher', array('teacherid'=>$v['teacherid']));?>" class="buyteacher-btn">查看课程</a></li>
				<div class="clear"></div>
			</ul>
			<?php  } } ?>
		</div>
		<?php  } ?>
	</div>
	
	<div class="teacher-order-list" style="display:none;">
		<div id="list">
		</div>
		<div id="loading_div" class="loading_div">
			<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
		</div>
	</div>
</div>


<script type="text/javascript">
var ajaxurl   = "<?php  echo $this->createMobileUrl('myteacher', array('op'=>'ajaxgetlist'));?>";
var get_status = true; //允许获取状态
var loadingToast = document.getElementById("loadingToast");
$(function () {
    var nowPage = 1;
    function getData(page) {
		if(get_status){
			nowPage++;
			$.get(ajaxurl, {page: page}, function (data) {
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
    
    getData(1);//初始化加载第一页数据

    //生成数据html,append到div中  
    function insertDiv(result) {  
        var mainDiv =$("#list");
        var chtml = '';  
        for (var j = 0; j < result.length; j++) {
			chtml += '<div class="aui-order-box">';
			chtml += '	<a href="javascript:void(0);" class="aui-well-item">';
			chtml += '		<div class="aui-well-item-bd">';
			chtml += '			<h3>订单编号：' + result[j].ordersn + '</h3>';
			chtml += '		</div>';
			chtml += '	</a>';
			chtml += '	<p class="aui-order-fl aui-order-address">购买[' + result[j].teacher_name + ']-'+ result[j].ordertime +'天</p>';
			chtml += '	<p class="aui-order-fl aui-order-address">支付方式：' + result[j].paytype + '</p>';
			chtml += '	<p class="aui-order-fl aui-order-time">下单时间：' + result[j].addtime + '</p>';
			chtml += '	<p class="aui-order-fl aui-order-time">付款时间：' + result[j].paytime + '</p>';
			chtml += '	<p class="aui-order-fl aui-order-door">';
			chtml += '		订单金额：<em class="income_amount">' + result[j].price + '</em> 元';
			chtml += '	</p>';
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

$(".show-teacher").click(function(){
	$(".show-teacher").addClass('active');
	$(".show-teacherorder").removeClass('active');
	$(".myteacher-info").show();
	$(".teacher-order-list").hide();
});
$(".show-teacherorder").click(function(){
	$(".show-teacher").removeClass('active');
	$(".show-teacherorder").addClass('active');
	$(".myteacher-info").hide();
	$(".teacher-order-list").show();
});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>