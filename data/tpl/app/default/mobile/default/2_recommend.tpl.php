<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 推荐板块课程列表
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/search.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<?php  if($op=='display') { ?>
<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title">"<?php  echo $recommend['rec_name'];?>"课程列表</div>
</div>

<!-- 课程列表 -->
<?php  if(!empty($list)) { ?>
<div class="list_grid" id="lesson-list">
</div>

<div id="loading_div" class="loading_div" style="padding:15px 0 30px 0;">
	<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
</div>
<?php  } else { ?>
<div class="my_empty">
    <div class="empty_bd  my_course_empty">
        <h3>没有找到任何课程~</h3>
        <p><a href="<?php  echo $this->createMobileUrl('index', array('t'=>1));?>">到首页去看看</a></p>
    </div>
</div>
<?php  } ?>
<!-- /课程列表 -->


<script type="text/javascript">
var ajaxUrl   = "<?php  echo $_W['siteUrl'];?>";
var attachUrl = "<?php  echo $_W['attachurl'];?>";
var lessonUrl = "<?php  echo $this->createMobileUrl('lesson');?>";
var loadingToast = document.getElementById("loadingToast");
$(function () {
	var nowPage = 1; //设置当前页数，全局变量
    function getData(page) {
		nowPage++;
        $.get(ajaxUrl, {page: page}, function (data) {  
            if (data.length > 0) {
            	loadingToast.style.display = 'none';
                var jsonObj = JSON.parse(data);
                insertDiv(jsonObj);
				
            }
        });  
    } 
    //初始化加载第一页数据  
    getData(1);

    //生成数据html,append到div中  
    function insertDiv(result) {  
        var mainDiv =$("#lesson-list");
		var lesson_vip_status = <?php  echo $setting['lesson_vip_status'];?>;
        var chtml = '';
        for (var j = 0; j < result.length; j++) {
			chtml += '<a href="' + lessonUrl + '&id=' + result[j].id + '" class="normal_grid flex0_1">';
			chtml += '	<div class="normal_grid_a flex_g0">';
			chtml += '		<div class="img-box flex_g0">';
			chtml += '			<div class="img"><img src="' + attachUrl + result[j].images + '"></div>';
			chtml += '			<div class="icon-live ' + result[j].icon_live_status + '"></div>';
		<?php  if($setting['show_study_number']){ ?>
			chtml += '			<div class="learned ' + result[j].learned_hide + '">' + result[j].buyTotal + result[j].study_word + '</div>';
		<?php  } ?>
			chtml += '			<i class="ico_common '+ result[j].ico_name +'"></i>';
			chtml += '		</div>';
			chtml += '	</div>';
			chtml += '	<div class="flex-al1 flex10">';
			chtml += '		<div>';
			chtml += '			<div class="grid_title2">' + result[j].bookname + '</div>';
			chtml += '			<div class="grid_info flex0">';
			if(result[j].price==0){
				chtml += '		<span class="price flex_g0 index_price_lesson fs-14">免费</span>';
			}else if(result[j].show_vip==1){
				chtml += '		<span class="price flex_g0 index_price_lesson fs-14">VIP免费</span>';
			}else{
				chtml += '		<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥' + result[j].price + '</span>';
			}

			if(result[j].soncount>0){
					chtml += '		<span class="index_section_number mar-l-a">' + result[j].soncount + '节</span>';
			}
			chtml += '			</div>';
			chtml += '		</div>';
			chtml += '		<div class="grid_bottom2 flex1">';
			if(result[j].score>0){
					chtml += '	<span class="eva index_lesson_evaluation">' + result[j].score_rate + '%好评</span>';
			}
			chtml += '	<div class="text">';
			if(result[j].section_status==0){
				chtml += '	已完结';
			}
			chtml += '	</div>';
			chtml += '		</div>';
			chtml += '	</div>';
			chtml += '</a>';
        }
		mainDiv.append(chtml);
		if(result.length==0){
			document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
		}
    }  
  
    //==============核心代码=============
	var msg_list_loading = false;
	$('.section').on('scroll', function(){
		if (!msg_list_loading){
			load_more_msg();
		}
	})
	function load_more_msg(){     
		var msg_list = $('.section');
		if (msg_list.height() + msg_list[0].scrollTop >= msg_list[0].scrollHeight) {
			msg_list_loading = true;
			$("#btn_Page").hide();
			getData(nowPage);
			msg_list_loading = false;
		}
		$("#btn_Page").show();
	}

    //继续加载按钮事件
    $("#btn_Page").click(function () {
    	loadingToast.style.display = 'block';
        getData(nowPage);
    });
  
});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>