<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 通知公告
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/article.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<?php  if($op=='display') { ?>
<div class="rich_primary">
	<div class="rich_title">
		<?php  if($article['is_vip']) { ?>
			<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/article-title-vip.png?v=3" class="title-img" >
		<?php  } ?>
		<?php  echo $article['title'];?>
	</div>
	<div class="rich_mate">
		<div class="rich_mate_text"><?php  echo date('Y-m-d', $article['addtime']);?></div>
		<div class="rich_mate_text"></div>
		<a href="<?php  echo $this->createMobileUrl('follow');?>"><div class="rich_mate_text href"><?php  echo $article['author'];?></div></a>
	</div>
	<div class="rich_content">
		<?php  echo htmlspecialchars_decode($article['content']);?>
	</div>
	<div class="rich_tool">
	<?php  if(!empty($article['linkurl'])) { ?>
		<a href="<?php  echo $article['linkurl'];?>"><div class="rich_tool_text link">阅读原文</div></a>
	<?php  } ?>
		<div class="rich_tool_text">阅读 <?php  echo $article['view'] + $article['virtual_view'];?></div>
	</div>
</div>

<script type="text/javascript">
<?php  if($userAgent){ ?>
	$(function(){
		$("div.rich_content img").click(function(){
			let imgs = [];
			let imgObj = document.querySelectorAll('div.rich_content img');
			let l=imgObj.length;
			for (let i = 0; i < l; i++) {
				imgs.push(imgObj[i].src);
			}

			WeixinJSBridge.invoke("imagePreview", {
				"urls": imgs,
				"current": this.src
			})
		})
	})
<?php  } ?>

wx.ready(function(){
	var shareData = {
		title: "<?php  echo $article['title'];?>",
		desc: "<?php  echo $article['describes'];?>",
		link: "<?php  echo $shareurl;?>",
		imgUrl: "<?php  echo $_W['attachurl'];?><?php  echo $article['images'];?>",
		trigger: function (res) {},
		complete: function (res) {},
		success: function (res) {},
		cancel: function (res) {},
		fail: function (res) {}
	};
	wx.onMenuShareTimeline(shareData);
	wx.onMenuShareAppMessage(shareData);
	wx.onMenuShareQQ(shareData);
	wx.onMenuShareWeibo(shareData);
	wx.onMenuShareQZone(shareData);
});

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	var miniprogram_environment = false;
	wx.miniProgram.getEnv(function(res) {
		if(res.miniprogram) {
			miniprogram_environment = true;
		}
	})
	if((window.__wxjs_environment === 'miniprogram' || miniprogram_environment)) {
		wx.miniProgram.getEnv(function(res) {
			wx.miniProgram.postMessage({ 
				data: {
					'title': "<?php  echo $article['title'];?>",
					'images': "<?php  echo $_W['attachurl'];?><?php  echo $article['images'];?>",
				}
			})
		});
	}
});
</script>

<?php  } else if($op=='list') { ?>
<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div class="container">
	<div class="category-nav">
		<div class="category-nav-scroll">
			<a href="<?php  echo $this->createMobileUrl('article', array('op'=>'list'))?>" class="category-nav-menu <?php  if(!$_GPC['cate_id']) { ?>actived<?php  } ?>">全部<i class="i-cover"></i></a>
			<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
			<a href="<?php  echo $this->createMobileUrl('article', array('op'=>'list','cate_id'=>$item['id']))?>" class="category-nav-menu <?php  if($_GPC['cate_id']==$item['id']) { ?>actived<?php  } ?>"><?php  echo $item['name'];?><i class="i-cover"></i></a>
			<?php  } } ?>
		</div>
	</div>

	<ul class="article_list">
	</ul>
</div>

<div id="loading_div" class="loading_div">
	<a href="javascript:void(0);" id="btn_Page"><i class="fa fa-arrow-circle-down"></i> 加载更多</a>
</div>

<script type="text/javascript">
var ajaxurl = "<?php  echo $this->createMobileUrl('article', array('op'=>'list',cate_id=>$_GPC['cate_id'],'method'=>'ajaxgetlist'));?>";
var articleurl = "<?php  echo $this->createMobileUrl('article');?>";
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
        var mainDiv =$(".article_list");
        var chtml = '';  
        for (var j = 0; j < result.length; j++) {
			chtml += '<li>';
			chtml += '	<div class="thumb fl">';
			chtml += '		<img src="' + result[j].images + '" />';
			chtml += '	</div>';
            chtml += '	<a href="' + articleurl + '&aid=' + result[j].id + '" class="fr">';  
			chtml += '		<div class="title">';
			if(result[j].is_vip==1){
				chtml +=		'<img src="<?php echo MODULE_URL;?>static/mobile/default/images/article-title-vip.png?v=3" class="title-img">';
			}
			chtml +=			result[j].title;
			chtml += '		</div>';
			chtml += '		<div class="createtime">' + result[j].addtime + '</div>';
			chtml += '	</a>';
			chtml += '	<div class="clear"></div>';
			chtml += '</li>';
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
<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>
