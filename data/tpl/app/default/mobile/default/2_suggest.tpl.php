<?php defined('IN_IA') or exit('Access Denied');?><!-- 
 * 投诉建议
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
-->

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_headerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_headerv2', TEMPLATE_INCLUDEPATH));?>
<link href="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/css/suggest.css?v=<?php  echo $versions;?>" rel="stylesheet" />

<div class="header-2 cbox">
	<a href="javascript:history.go(-1);" class="ico go-back"></a>
	<div class="flex title"><?php  echo $title;?></div>
</div>

<div class="suggest-wrap">
	<div class="suggest-item">
		<p class="suggest-title"><strong class="require">*</strong> <?php echo $suggest_page['type'] ? $suggest_page['type'] : '投诉类型';?></p>
		<ul class="suggest-category">
			<?php  if(is_array($suggest_category)) { foreach($suggest_category as $key => $item) { ?>
			<li class="item <?php  if($key==0) { ?>active<?php  } ?>" data-id="<?php  echo $item['id'];?>"><?php  echo $item['title'];?></li>
			<?php  } } ?>
		</ul>
	</div>
	<div class="suggest-item">
		<p class="suggest-title"><strong class="require">*</strong> <?php echo $suggest_page['content'] ? $suggest_page['content'] : '投诉内容';?></p>
		<textarea class="suggest-content" id="content" placeholder="<?php echo $suggest_page['content_tips'] ? $suggest_page['content_tips'] : '请填写您的投诉内容，最多1000个字符';?>" maxlength="1000"></textarea>
	</div>
	<div class="suggest-item">
		<p class="suggest-title"><strong class="require">*</strong> 联系方式</p>
		<input type="number" id="mobile" class="suggest-input" placeholder="请输入手机号码">
	</div>
	<div class="suggest-item">
		<p class="suggest-title">上传图片</p>
		<div class="weui-uploader__bd">
			<ul class="weui-uploader__files suggest_picture"></ul>
			<div class="weui-uploader__input-box">
				<input class="weui-uploader__input" name="file" accept="image/*" type="file" onchange="uploadimgs(this)">
			</div>
		</div>
	</div>
	<div class="suggest-buttom">
		<input type="hidden" id="category_id" value="<?php  echo $suggest_category[0]['id'];?>" />
		<a href="javascript:;" id="btn-submit" class="btn-submit">提交</a>
	</div>
</div>

<script type="text/javascript">
	window.config = {
		attachurl: "<?php  echo $_W['attachurl'];?>",
	 suggest_type: "<?php echo $suggest_page['type'] ? $suggest_page['type'] : '投诉类型';?>",
  suggest_content: "<?php echo $suggest_page['content'] ? $suggest_page['content'] : '投诉内容';?>",
		uploadurl: "<?php  echo $this->createMobileUrl('ajaxuploadimage',array('type'=>'base64'))?>",
	   suggesturl: "<?php  echo $this->createMobileUrl('suggest')?>",
	     indexurl: "<?php  echo $this->createMobileUrl('index',array('t'=>1))?>",
	};
</script>
<script src="<?php echo MODULE_URL;?>static/mobile/<?php  echo $template;?>/js/suggest.js?v=<?php  echo $versions;?>"></script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($template.'/_footerv2', TEMPLATE_INCLUDEPATH)) : (include template($template.'/_footerv2', TEMPLATE_INCLUDEPATH));?>