<?php defined('IN_IA') or exit('Access Denied');?><!-- text toast -->
<div id="textToast" style="display: none;">
	<div class="weui-mask_transparent"></div>
	<div class="weui-toast weui-toast_text">
		<p class="weui-toast__content weui-toast_text_content"></p>
	</div>
</div>

<!-- loading toast -->
<div id="loadingToast" style="display:none;">
	<div class="weui-mask_transparent"></div>
	<div class="weui-toast">
		<i class="weui-loading weui-icon_toast"></i>
		<p class="weui-toast__content">加载数据中</p>
	</div>
</div>

<!-- top tips -->
<div class="weui-toptips weui-toptips_warn" id="topTips" style="display:none;"></div>

<!-- singleDialog -->
<div class="js_dialog" id="singleDialog" style="display: none;">
	<div class="weui-mask"></div>
	<div class="weui-dialog">
		<div class="weui-dialog__bd singleDialog_content"></div>
		<div class="weui-dialog__ft">
			<a href="javascript:" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
		</div>
	</div>
</div>

<!-- success toast -->
<div id="successToast" style="display: none;">
	<div class="weui-mask_transparent"></div>
	<div class="weui-toast">
		<i class="weui-icon-success-no-circle weui-icon_toast"></i>
		<p class="weui-toast__content success_toast_content"></p>
	</div>
</div>



<script type="text/javascript">
	//text toast
	function showTextToast(text){
		var $textToast = $('#textToast');
		$('#textToast .weui-toast_text_content').text(text);
		if ($textToast.css('display') != 'none') return;

		$textToast.fadeIn(100);
		setTimeout(function () {
			$textToast.fadeOut(100);
		}, 2000);
	}

	//top tips
	function showTopTips(text){
		var $topTips = $('#topTips');
		$('#topTips').text(text);
		if ($topTips.css('display') != 'none') return;

		$topTips.fadeIn(100);
		setTimeout(function () {
			$topTips.fadeOut(100);
		}, 2000);
	}

	//single dialog
	function showSingleDialog(text){
		$("#singleDialog .singleDialog_content").text(text);
		$("#singleDialog").fadeIn(200);
	}
	$('#singleDialog').on('click', '.weui-dialog__btn', function(){
		$(this).parents('#singleDialog').fadeOut(200);
	});

	//sucess toast
	function showSuccessToast(text){
		var $toast = $('#successToast');

		$("#successToast .success_toast_content").text(text);
		if ($toast.css('display') != 'none') return;

		$toast.fadeIn(100);
		setTimeout(function () {
			$toast.fadeOut(100);
		}, 2000);
	}

</script>