<?php defined('IN_IA') or exit('Access Denied');?> <!-- 
 * 一键复制
 * ============================================================================
 * 版权所有 2015-2018 微课堂团队，并保留所有权利。
 * 网站地址: https://wx.haoshu888.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 * ============================================================================
-->

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>


<div class="main">
	<div class="alert alert-info">
	    请认真填写课程采集接口和采集方式，否则可能导致采集失败。采集接口失效，请联系<strong>QQ：3120792251</strong>获取。<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3120792251&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:3120792251:52" alt="点击这里给我发消息" title="点击这里给开发者发消息"/></a>
	</div>
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">课程采集设置（采集接口需包含http://或https://，定时任务仅在选择自动采集时用到，手动采集请忽略）</div>
            
            <div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">课程采集接口</label>
					<div class="col-sm-6 col-xs-9">
						<input name="domain" value="<?php  echo $upset['domain'];?>" class="form-control" placeholder="例如：http://api.weike.xyz">
					</div>
					<div class="col-sm-3 col-xs-3">
						<input name="tongbu_uniacid" value="<?php  echo $upset['tongbu_uniacid'];?>" class="form-control" placeholder="同步参数">
					</div>
				</div>
            </div>
            <div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">采集方式</label>
				<div class="col-sm-9">
				    <label class="radio-inline"><input type="radio" name="auto_tongbu" value="1" <?php  if($upset['auto_tongbu']) { ?>checked<?php  } ?>>自动</label>&nbsp;&nbsp;
					<label class="radio-inline"><input type="radio" name="auto_tongbu" value="0" <?php  if(!$upset['auto_tongbu']) { ?>checked<?php  } ?>>手动</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">定时任务</label>
				<div class="col-sm-9">
				    <a href="javascript:;" id="copy-btn"><?php  echo $dingshi;?></a>
					<span class="help-block">点击链接即可完成复制</span>
				   <!--<label class="radio-inline"><?php  echo $dingshi;?></label>-->
				</div>
			</div>
        </div>

        <div class="form-group col-sm-12">
            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" onclick="showOverlay()"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </div>
    </form>
</div>
<div class="mloading-bar" style="margin-top: -31px; margin-left: -140px;display:none;">
    <img src="<?php echo MODULE_URL;?>static/images/download.gif">
    <span class="mloading-text">同步数据中，请勿关闭或刷新...</span>
</div>
<div id="overlay"></div>
<script type="text/javascript">
/* 显示遮罩层 */
function showOverlay() {
//     $("#overlay").height("100%");
//     $("#overlay").width("100%");
//     $("#overlay").fadeTo(200, 0);
// 	$(".mloading-bar").show();
}
</script>
<script type="text/javascript">
require(['jquery', 'util'], function($, util){
	$(function(){
		util.clip($("#copy-btn")[0], $("#copy-btn").text());
	});
});
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>