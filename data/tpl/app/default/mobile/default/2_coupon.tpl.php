<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 优惠券管理
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/coupon.css?v=<?php  echo $versions;?>" rel="stylesheet"/>
<style type="text/css">
.tabbar_wrap {
	-webkit-overflow-scrolling: unset;
}
</style>
<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>


<?php  if($op=='display') { ?>
	<!-- 顶部导航  -->
	<ul class="tab_wrap">
		<li class="tab_item <?php  if($_GPC['status']=='0' || $_GPC['status']=='') { ?>tab_item_on<?php  } ?>">
			<a href="<?php  echo $this->createMobileUrl('coupon', array('status'=>'0'));?>">未使用</a>
		</li>
		<li class="tab_item <?php  if($_GPC['status']=='1') { ?>tab_item_on<?php  } ?>">
			<a href="<?php  echo $this->createMobileUrl('coupon', array('status'=>1));?>">已使用</a>
		</li>
		<li class="tab_item <?php  if($_GPC['status']=='-1') { ?>tab_item_on<?php  } ?>">
			<a href="<?php  echo $this->createMobileUrl('coupon', array('status'=>-1));?>">已过期</a>
		</li>
	</ul>
	<!-- /顶部导航  -->

	<?php  if(!$status) { ?>
	<div class="aui-tab-search-box">
		<div class="aui-tab-search-bg">
			<input type="text" name="card_password" id="card_password" class="cart-input" placeholder="请输入课程优惠码">
			<input type="submit" name="submit" class="submit-btn" id="submit-btn" value="转换">
		</div>
	</div>
	<?php  } ?>

	<?php  if(!empty($list)) { ?>
	<div>
		<div class="pepper-con">
			<div class="pepper-w">
			</div>
		</div>
	</div>
	<?php  } else { ?>
	<div class="my_empty">
		<div class="empty_bd  my_course_empty">
			<h3>没有找到任何优惠券~</h3>
		</div>
	</div>
	<?php  } ?>
	<div class="more-pepper"><a href="<?php  echo $this->createMobileUrl('getcoupon');?>">更多好券，去兑换中心看看 <i class="fa fa-long-arrow-right"></i></a></div>

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
		var ajaxurl   = "<?php  echo $this->createMobileUrl('coupon');?>";
		var get_status = true; //允许获取状态

		$(function () {
			var nowPage = 1;
			function getData(page) {
				if(get_status){
					nowPage++;  
					$.get(ajaxurl, {page: page, status:status}, function (data) {  
						$("#loadingToast").hide();
						
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
				var give_switch = <?php  echo intval($common['give_coupon'])?>;
				var give_url = "<?php  echo $this->createMobileUrl('coupon',array('op'=>'give'));?>";
				var mainDiv =$(".pepper-w");
				var chtml = '';
				for (var j = 0; j < result.length; j++) {  
					chtml += '<div class="pepper '+ result[j].classname +'">';  
					chtml += '	 <div class="pepper-l">'; 
					chtml += '		<p class="pepper-l-num">';
					chtml += '			<span> ¥'+ result[j].amount +'</span>';
					chtml += '		</p>';
					chtml += '		<p class="pepper-l-con">使用条件：课程金额满'+ result[j].conditions +'元，' +result[j].category_name+ '可使用</p>';
				if(give_switch && result[j].status == 0){
					chtml += '		<p class="pepper-l-con text-center"><a href="' + give_url + '&member_coupon_id=' + result[j].id + '" class="btn-give"><i class="fa fa-share-square"></i> 转赠好友</a></p>';
				}
					chtml += '	</div>';
					chtml += '	<div class="pepper-r">';
					if(result[j].status==0){
						chtml += '		<span>课程券</span>';
						chtml += '		<a href=" ' + result[j].url + '" class="use-now">立即使用</a>';
						chtml += '		<div>有效期至<br/>'+ result[j].endDate +' '+ result[j].endTime +'</div>';
					}else if(result[j].status==1){
						chtml += '<div class="coupon-used"></div>';
					}else if(result[j].status==-1){
						chtml += '<div class="coupon-past"></div>';
					}
					chtml += '	</div>';
					chtml += '</div>';
					if(result[j].classname=='pepper-red'){
						chtml += '<div class="pepper-b">';
						chtml += '	<div class="pb-con">获取途径：' +result[j].source_name+ '</div>';
						chtml += '	<div class="pb-border"></div>';
						chtml += '</div>';
					}else{
						chtml += '<div class="pepper-b">';
						chtml += '	<div class="pb-con" style="background:#cccccc;">获取途径：' +result[j].source_name+ '</div>';
						chtml += '</div>';
					}
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
				$("#loadingToast").show();
				getData(nowPage);
			});
		  
		});

		//转换优惠码
		$("#submit-btn").click(function(){
			var card_password = $("#card_password").val();
			if(card_password==''){
				alert("请输入课程优惠码");
				return false;
			}
			if(card_password.length < 16){
				alert("课程优惠码长度有误");
				return false;
			}

			$.ajax({
				type:"POST",
				url:"<?php  echo $this->createMobileUrl('coupon', array('op'=>'addCoupon'));?>",
				data:{card_password:card_password},
				dataType: "json",
				beforeSend:function(){
					$("#loadingToast").show();
				},
				success:function(res){
					$("#loadingToast").hide();
					if(res.code == '-1'){
						alert(res.message);
						return false;
					}else if(res.code == '0'){
						alert("转换成功");
						window.location.reload();
					}
				},
				error: function(){
					$("#loadingToast").hide();
					alert("网络繁忙，请稍后重试");
				}
			 });
		})
	</script>

<?php  } else if($op=='give') { ?>
	<form method="post" action="" onsubmit="return checkSubmit();">
		<div class="weui-cells__group weui-cells__group_form">
			<div class="weui-cells weui-cells_form">
				<label for="password" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label">优惠券面值</span></div>
					<div class="weui-cell__bd">
						<?php  echo $member_coupon['amount'];?>元
					</div>
				</label>
				<label for="password" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label">使用范围</span></div>
					<div class="weui-cell__bd">
						<?php  echo $member_coupon['category_name'];?>可用
					</div>
				</label>
				<label for="password" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label">使用条件</span></div>
					<div class="weui-cell__bd">
						课程金额满<?php  echo $member_coupon['conditions'];?>可用
					</div>
				</label>
				<label for="password" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label">有效期</span></div>
					<div class="weui-cell__bd">
						<?php  echo date('Y-m-d H:i:s', $member_coupon['validity']);?>
					</div>
				</label>
				<label for="open_type" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label red-color">验证方式</span></div>
					<div class="weui-cell__bd">
						<label class="radio-inline mr10">
							<input type="radio" name="open_type" class="vertical-2m mr5" value="1" checked><?php  echo $studen_no;?>(uid)
						</label>
						<label class="radio-inline">
							<input type="radio" name="open_type" class="vertical-2m mr5" value="2">手机号码
						</label>
					</div>
				</label>
				<label for="give_user" class="weui-cell weui-cell_active">
					<div class="weui-cell__hd"><span class="weui-label red-color open-type-name"><?php  echo $studen_no;?>(uid)</span></div>
					<div class="weui-cell__bd">
						<input type="tel" name="give_user" id="give_user" class="weui-input" maxlength="11" placeholder="请输入对方的<?php  echo $studen_no;?>(即uid)" oninput="value=value.replace(/^\.+|[^\d]+/g,'')">
					</div>
				</label>
			</div>
		</div>
		<div class="h-40"></div>
		<div class="weui-btn-area" style="margin-top:16px;">
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
			<input type="submit" name="submit" class="weui-btn weui-btn_primary" value="立即转赠">
		</div>
	</form>

	<script type="text/javascript">
		$(function(){
			$(':radio[name="open_type"]').click(function() {
				if($(this).val() == 1){
					$(".open-type-name").text("<?php  echo $studen_no;?>(uid)");
					$("#give_user").attr("placeholder","请输入对方的<?php  echo $studen_no;?>(即uid)");
				}else if($(this).val() == 2){
					$(".open-type-name").text("手机号码");
					$("#give_user").attr("placeholder","请输入对方手机号码");
				}
			});
		})

		//提交
		function checkSubmit(){
			var open_type = $("input[name=open_type]:checked").val();
			var give_user = $("#give_user").val();

			if(open_type != 1 && open_type != 2){
				showSingleDialog("请选择验证方式");
				return false;
			}
			if(open_type == 1 && give_user == ''){
				showSingleDialog("请输入对方<?php  echo $studen_no;?>(uid)");
				return false;
			}
			if(open_type == 2 && give_user == ''){
				showSingleDialog("请输入对方手机号码");
				return false;
			}

			$("#loadingToast").show();
		}
	</script>
<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>