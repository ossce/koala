<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 我的团队
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/commission.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div class="team_top">
    <div class="title"> <?php  echo $sontitle;?></div>
</div>

<div class="team_tab">
	<?php  if($level==1 || empty($level)) { ?>
    <div class="team_nav team_navon" style='width:100%'>一级会员</div>
	<?php  } else if($level==2) { ?>
    <div class="team_nav team_navon" style='width:50%'>二级会员</div>
	<?php  } else if($level==3) { ?>
    <div class="team_nav team_navon" style='width:33.3%'>三级会员</div>
	<?php  } ?>
</div>
<div class="team_list_head">
        <div class="info">成员信息</div>
        <div class="num"><?php echo $font['his_commission'] ? $font['his_commission'] : 'Ta的佣金/成员';?></div>
</div>
<div id="teamlist">
	<?php  if(empty($teamlist)) { ?>
	<div class="team_no"><span style="line-height:100px; font-size:16px;">Ta的团队还没有任何成员~</span></div>
	<?php  } ?>
</div>
<div id="loading_div" class="loading_div">
	<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
</div>


<script type="text/javascript">
var ajaxurl = "<?php  echo $this->createMobileUrl('team', array('leval'=>$leval,'mid'=>$mid));?>";
var level = <?php  echo $level?>;
var fxlevel = <?php  echo $comsetting['level']?>;
var murl = "<?php  echo $this->createMobileUrl('team');?>";
var loadingToast = document.getElementById("loadingToast");
$(function () {
    var nowPage = 1; //设置当前页数，全局变量
    function getData(page) {  
        nowPage++; //页码自动增加，保证下次调用时为新的一页。  
        $.get(ajaxurl, {page: page}, function (data) {  
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
        var mainDiv =$("#teamlist");
        var chtml = '';  
        for (var j = 0; j < result.length; j++) {
		if(level==1){
			if(fxlevel>1){
				chtml += '<div class="team_list" onclick="location.href=\'' + murl + '&level=2&mid=' +result[j].uid+ '\'">';
			}else{
				chtml += '<div class="team_list">';
			}
            
		}else if(level==2){
			if(fxlevel>2){
				chtml += '<div class="team_list" onclick="location.href=\'' + murl + '&level=3&mid=' +result[j].uid+ '\'">';
			}else{
				chtml += '<div class="team_list">';
			}
		}else if(level==3){
			chtml += '<div class="team_list">';
		}
			chtml += '	<div class="img"><img src="' +result[j].avatar+ '"></div>';
			chtml += '	<div class="info"><div class="member-info"><span>[uid:' + +result[j].uid + ']</span>' +result[j].nickname+ '</div><span>' +result[j].addtime+ '</span></div>';
			chtml += '	<div class="num">+' +result[j].commission+ '<br><span>' +result[j].recnum+ ' 个成员</span></div>';
			chtml += '</div>'; 
        }
		mainDiv.append(chtml);
		if(result.length==0){
			document.getElementById("loading_div").innerHTML='<div class="loading_bd">没有了，已经到底了</div>';
		}
    }  
  
    //==============核心代码=============  
    var winH = $(window).height(); //页面可视区域高度   
  
    var scrollHandler = function () {  
        var pageH = $(document.body).height();  
        var scrollT = $(window).scrollTop(); //滚动条top   
        var aa = (pageH - winH - scrollT) / winH;  
        if (aa < 0.02) { 
            if (nowPage % 1 === 0) {
                getData(nowPage);  
                $(window).unbind('scroll');  
                $("#btn_Page").show();
            } else {  
                getData(nowPage);  
                $("#btn_Page").hide();
            }  
        }  
    }  
    //定义鼠标滚动事件
    $(window).scroll(scrollHandler);
    //继续加载按钮事件
    $("#btn_Page").click(function () {
		loadingToast.style.display = 'block';
        getData(nowPage);
        $(window).scroll(scrollHandler);
    });
  
});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>