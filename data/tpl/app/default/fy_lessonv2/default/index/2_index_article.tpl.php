<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($articlelist) && is_array($articlelist)) { ?>
<div class="article_div flex0_1  <?php  if($common['small_index']) { ?>mar10-15 b-radius-50<?php  } ?>">
	<i class="flex_g0 icon_new" <?php  if($common['article_ico']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $common['article_ico'];?>)"<?php  } ?>></i>
	<ul class="article_div_list flex_all">
		<?php  if(is_array($articlelist)) { foreach($articlelist as $article) { ?>
		<li>
			<a href="<?php  echo $this->createMobileUrl('article', array('op'=>'display','aid'=>$article['id']));?>">
				<?php  if($article['is_vip']) { ?>
					<img src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/images/article-title-vip.png?v=3" height="18" style="vertical-align:-4px;" >
				<?php  } ?>
				<?php  echo $article['title'];?>
			</a>
		</li>
		<?php  } } ?>
	</ul>
	<a class="flex_g0 more icon_right" href="<?php  echo $this->createMobileUrl('article', array('op'=>'list'));?>">更多</a>
</div>
<script type="text/javascript">
    var scroll_height = "40px"; //优惠活动滚动距离
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
        }, 5000);
      }
    }();
</script>
<?php  } ?>