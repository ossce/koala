<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 课程订单
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/mylesson.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="mine_head mine_head2" style="background-image:url(<?php  echo $mylesson_bg;?>);">
	<div class="titlebar">
		<a onclick="history.go(-1)" class="icon_back1"></a>
		<span><?php  echo $title;?></span>
	</div>
	<div class="mine_head_body">
		<div class="tx">
			<img src="<?php  echo $avatar;?>" id="btn-avatar" alt="会员头像" />
		</div>
		<div class="tip"><?php  echo $memberinfo['nickname'];?>，欢迎回来</div>
	</div>
</div>

<!-- 顶部导航  -->
<ul class="tab_wrap">
	<li class="tab_item <?php  if(!$_GPC['status']) { ?>tab_item_on<?php  } ?>">
		<a href="<?php  echo $this->createMobileUrl('mylesson');?>">全部订单</a>
	</li>
	<li class="tab_item <?php  if($_GPC['status']=='nopay') { ?>tab_item_on<?php  } ?>">
		<a href="<?php  echo $this->createMobileUrl('mylesson', array('status'=>'nopay'));?>">待付款</a>
	</li>
	<li class="tab_item <?php  if($_GPC['status']=='payed') { ?>tab_item_on<?php  } ?>">
		<a href="<?php  echo $this->createMobileUrl('mylesson', array('status'=>'payed'));?>">已付款</a>
	</li>
	<li class="tab_item <?php  if($_GPC['status']=='allow_verify') { ?>tab_item_on<?php  } ?>">
		<a href="<?php  echo $this->createMobileUrl('mylesson', array('status'=>'allow_verify'));?>">可核销</a>
	</li>
</ul>
<!-- /顶部导航  -->

<!-- 订单列表  -->
<?php  if(!empty($mylessonlist)) { ?>
<div class="list_grid" id="order-list">
</div>
<?php  } else { ?>
<div class="my_empty">
    <div class="empty_bd  my_course_empty">
        <h3>没有找到任何订单</h3>
    </div>
</div>
<?php  } ?>
<!-- 订单列表  -->
<div id="loading_div" class="loading_div">
	<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
</div>


<script type="text/javascript">
	function GetQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r != null) return unescape(r[2]);
		return null;
	}

	var status = GetQueryString('status');
	var is_verify = GetQueryString('is_verify');
	var ajaxUrl = "<?php  echo $this->createMobileUrl('mylesson', array('op'=>'ajaxgetlist'));?>";
	var attachUrl = "<?php  echo $_W['attachurl'];?>";
	var payUrl = "<?php  echo $this->createMobileUrl('pay');?>";
	var cancleUrl = "<?php  echo $this->createMobileUrl('mylesson', array('op'=>'cancle'));?>";
	var eUrl = "<?php  echo $this->createMobileUrl('evaluate');?>";
	var orderUrl = "<?php  echo $this->createMobileUrl('orderdetail');?>";
	var get_status = true; //允许获取状态
	var loadingToast = document.getElementById("loadingToast");
	$(function() {
		var nowPage = 1; //设置当前页数，全局变量
		function getData(page) {
			if(get_status){
				nowPage++; //页码自动增加，保证下次调用时为新的一页。  
				$.get(ajaxUrl, {
					page: page,
					status: status,
					is_verify: is_verify,
				}, function(data) {
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
			var mainDiv = $("#order-list");
			var chtml = '';
			for(var j = 0; j < result.length; j++) {
				chtml += '<div class="order_content">';
				chtml += '	<div class="order_sn">';
				chtml += '		<div class="fl text-left">';
				chtml += '			<p>订单编号：' + result[j].ordersn + '</p>';
				chtml += '		</div>';
				chtml += '		<div class="fr text-right">';
				chtml += '			<p class="order_status '+ result[j].status_class +'">' + result[j].statusname + '</p>';
				chtml += '		</div>';
				chtml += '		<div class="clear"></div>';
				chtml += '	</div>';
				chtml += '	<a href="'+ orderUrl +'&orderid='+ result[j].id +'" class="normal_grid flex0_1">';
				chtml += '		<div class="normal_grid_a flex_g0">';
				chtml += '			<div class="img-box flex_g0">';
				chtml += '				<div class="img">';
				chtml += '					<img src="' + attachUrl+result[j].images + '">';
				chtml += '				</div>';
				chtml += '			</div>';
				chtml += '		</div>';
				chtml += '		<div class="flex-al1 flex10">';
				chtml += '			<div>';
				chtml += '				<div class="grid_title2">';
				chtml += '					<span class="grid_info">课程名称：</span>' + result[j].bookname;
				chtml += '				</div>';
				if(result[j].lesson_type==1){
					chtml += '				<div class="flex0 font_12">';
					chtml += '					<span class="grid_info">课程规格：</span>'+result[j].spec_name;
					chtml += '				</div>';
					chtml += '				<div class="flex0 font_12">';
					if(result[j].is_verify==0){
						chtml += '				<span class="grid_info">核销状态：</span><i class="green-color">未核销</i>';
					}else if(result[j].is_verify==1){
						chtml += '				<span class="grid_info">核销状态：</span><i class="red-color">已核销' + result[j].verify_num + '次</i>';
					}else if(result[j].is_verify==2){
						chtml += '				<span class="grid_info">核销状态：</span><i class="red-color">核销完成(' + result[j].verify_num + '次)</i>';
					}
					chtml += '				</div>';
				}else{
					chtml += '				<div class="flex0 font_12">';
					if(result[j].spec_day>0){
						chtml += '			<span class="grid_info">课程规格：</span>'+result[j].spec_day+'天';
					}else{
						chtml += '			<span class="grid_info">课程规格：</span>长期有效';
					}
					chtml += '				</div>';
					if(result[j].validity!=0 && result[j].status>0){
						chtml += '		<div class="flex0 font_12">';
						chtml += '			<span class="grid_info">有效期限：</span>'+result[j].validity;
						chtml += '		</div>';
					}
				}
				chtml += '				<div class="flex0 font_12">';
				chtml += '					<span class="grid_info">下单时间：</span>' + result[j].addtime;
				chtml += '				</div>';
				chtml += '				<div class="flex0 font_12">';
				chtml += '				</div>';
				chtml += '			</div>';
				chtml += '		</div>';
				chtml += '	</a>';
				chtml += '	<div class="order_btn">';
				chtml += '		<div class="text-right">';
				if(result[j].show_qrcode){
					chtml += '		<a href="'+orderUrl+'&orderid='+result[j].id+'" class="mylesson-btn verify-btn">核销二维码</a>';
				}
				if(result[j].status=='0'){
					chtml += '		<a href="'+cancleUrl+'&orderid='+result[j].id+'" class="mylesson-btn cancle-btn">取消订单</a>';
					chtml += '		<a href="'+payUrl+'&orderid='+result[j].id+'&ordertype=buylesson" class="mylesson-btn pay-btn ios-system">立即支付</a>';
				}else if(result[j].status=='1'){
					chtml += '		<a href="'+eUrl+'&orderid='+result[j].id+'" class="mylesson-btn evaluate-btn">评价课程</a>';
				}else if(result[j].status=='-1'){
					chtml += '		<a href="'+cancleUrl+'&orderid='+result[j].id+'&is_delete=1" class="mylesson-btn cancle-btn">删除订单</a>';
				}
				chtml += '		</div>';
				chtml += '	</div>';
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
	
	$(".mylesson-btn").click(function(){
		loadingToast.style.display = 'block';
	})

	$("#btn-avatar").click(function(){
		var agent = <?php  echo intval($agent); ?>;
		if(!agent){
			return;
		}
		var sureUrl = "<?php  echo $this->createMobileUrl('self', array('updateInfo'=>1, 'back_do'=>'mylesson'));?>";
		$(this).openWindow('系统提示','更新头像信息?','["取消","确定"]','javascript:;', sureUrl);
	});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>