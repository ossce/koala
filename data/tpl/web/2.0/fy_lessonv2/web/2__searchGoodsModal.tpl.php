<?php defined('IN_IA') or exit('Access Denied');?><!-- 选择商品 -->
<div class="modal fade modal-select-wrap" id="modal-select-goods" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal-select-goods-close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">选择商品</h4>
			</div>
			<div class="item-search">
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="goods_type">
							<option value="">商品类型</option>
							<option value="1">实物商品</option>
							<option value="2">虚拟商品</option>
						</select>
					</div>
				</div>
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="goods_status">
							<option value="">状态</option>
							<option value="1">上架</option>
							<option value="0">下架</option>
						</select>
					</div>
				</div>
				<div class="input-group">
					<input type="text" id="goods_kwd" class="form-control" autocomplete="off" placeholder="请输入商品名称" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-seach-lesson">
							<span class="fa fa-search"></span>搜索
						</button>
					</span>
				</div>
			</div>
			<div class="modal-body">
				<div class="item-content clearfix" id="modal-goods-list"></div>
			</div>
			<div class="modal-footer" style="padding-top:10px;">
				<ul class="pagination pagination-centered">
					<li>
						<a href="javascript:;" data-name="prev" class="goods-pager-nav">«上一页</a>
					</li>
					<li>
						<a><span id="show_goods_curr_page">0</span> / <span id="show_goods_total_page">0</span></a>
					</li>
					<li>
						<a href="javascript:;" data-name="next" class="goods-pager-nav">下一页»</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/* 搜索商品 START */
	var goods_curr_page = 1;

	//搜索商品
	function searchGoods() {
		var goods_type = $("#goods_type").val();
		var status = $("#goods_status").val();
		var keyword = $("#goods_kwd").val();

		$("#modal-goods-list").html("正在搜索....");
		$.get("<?php  echo $this->createWebUrl('getGoods')?>", { 
			goods_type:goods_type,
			status:status,
			keyword:keyword,
			page: goods_curr_page,
		}, function (dat) {
			$("#modal-goods-list").html(dat);
			$("#show_goods_curr_page").text($(".goods_curr_page").text());
			$("#show_goods_total_page").text($(".goods_total_page").text());
		});
	}

	//翻页
	$(".goods-pager-nav").click(function(){
		var goods_total_page	= $(".goods_total_page").text();
		var button_name = $(this).data('name');

		if(button_name == 'prev'){
			if(goods_curr_page <= 1){
				return;
			}else{
				goods_curr_page--;
				searchGoods();
			}
		}else if(button_name == 'next'){
			if(goods_curr_page >= goods_total_page){
				return;
			}else{
				goods_curr_page++;
				searchGoods();
			}
		}
	})

	//点击搜索按钮事件
	$(".btn-seach-lesson").click(function(){
		goods_curr_page = 1;
		searchGoods();
	})

	//回车按键事件
	$("#goods_kwd").bind("keypress", function(e) {
		if (e.keyCode == "13") {
			e.preventDefault();
			goods_curr_page = 1;
			searchGoods();
		}
	});

	searchGoods();
	/* 搜索商品 END */
</script>