<?php defined('IN_IA') or exit('Access Denied');?><!-- 选择用户 -->
<div class="modal fade modal-select-wrap" id="modal-select-member" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">选择用户</h4>
			</div>
			<div class="item-search">
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="select_type">
							<option value="0">用户uid</option>
							<option value="1">用户昵称</option>
							<option value="2">用户姓名</option>
							<option value="3">手机号码</option>
						</select>
					</div>
				</div>
				<div class="input-group">
					<input type="text" id="member_kwd" class="form-control" autocomplete="off" placeholder="请输入关键字" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-search-member">
							<span class="fa fa-search"></span>搜索
						</button>
					</span>
				</div>
			</div>
			<div class="modal-body pad-t-0 pad-b-10">
				<div class="item-content clearfix h-470" id="modal-member-list"></div>
			</div>
			<div class="modal-footer" style="padding-top:10px;">
				<ul class="pagination pagination-centered">
					<li>
						<a href="javascript:;" data-name="prev" class="member-pager-nav">«上一页</a>
					</li>
					<li>
						<a><span id="show_member_curr_page">0</span> / <span id="show_member_total_page">0</span></a>
					</li>
					<li>
						<a href="javascript:;" data-name="next" class="member-pager-nav">下一页»</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/* 搜索用户 START */
	var member_curr_page = 1;
	function searchMember() {
		var select_type = $('#select_type').val();
		var keyword = $.trim($('#member_kwd').val());

		$("#modal-member-list").html("正在搜索....");
		$.get("<?php  echo $this->createWebUrl('getmember')?>", { 
			select_type: select_type,
			keyword: keyword,
			page: member_curr_page,
		}, function (dat) {
			$('#modal-member-list').html(dat);
			$("#show_member_curr_page").text($(".member_curr_page").text());
			$("#show_member_total_page").text($(".member_total_page").text());
		});
	}

	$(".member-pager-nav").click(function(){
		var member_total_page	= $(".member_total_page").text();
		var button_name = $(this).data('name');

		if(button_name == 'prev'){
			if(member_curr_page <= 1){
				return;
			}else{
				member_curr_page--;
				searchMember();
			}
		}else if(button_name == 'next'){
			if(member_curr_page >= member_total_page){
				return;
			}else{
				member_curr_page++;
				searchMember();
			}
		}
	})

	$(".btn-search-member").click(function(){
		member_curr_page = 1;
		searchMember();
	})

	$("#member_kwd").bind("keypress", function(e) {
		if (e.keyCode == "13") {
			e.preventDefault();
			member_curr_page = 1;
			searchMember();
		}
	});
	searchMember();
	/* 搜索用户 END */
</script>