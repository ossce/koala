<?php defined('IN_IA') or exit('Access Denied');?><!-- 选择课程 -->
<div class="modal fade modal-select-wrap" id="modal-select-lesson" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal-select-lesson-close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">选择课程</h4>
			</div>
			<div class="item-search">
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="pid" onchange="renderCategory(this.value)">
							<option value="">一级分类</option>
							<?php  if(is_array($category_list)) { foreach($category_list as $item) { ?>
							<option value="<?php  echo $item['id'];?>"><?php  echo $item['name'];?></option>
							<?php  } } ?>
						</select>
					</div>
				</div>
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="cid">
							<option value="">二级分类</option>
						</select>
					</div>
				</div>
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="lessonType">
							<option value="">课程类型</option>
							<option value="0">普通课程</option>
							<option value="1">报名课程</option>
							<option value="3">直播课程</option>
						</select>
					</div>
				</div>
				<div class="select-form">
					<div class="select-list">
						<select class="form-control" id="lessonStatus">
							<option value="">状态</option>
							<?php  if(is_array($lessonStatusList)) { foreach($lessonStatusList as $key => $item) { ?>
							<option value="<?php  echo $key;?>" <?php  if($key==1) { ?>selected<?php  } ?>><?php  echo $item;?></option>
							<?php  } } ?>
						</select>
					</div>
				</div>
				<div class="input-group">
					<input type="text" id="bookname_kwd" class="form-control" autocomplete="off" placeholder="请输入课程标题" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-seach-lesson">
							<span class="fa fa-search"></span>搜索
						</button>
					</span>
				</div>
			</div>
			<div class="modal-body">
				<div class="item-content clearfix" id="modal-lesson-list"></div>
			</div>
			<div class="modal-footer" style="padding-top:10px;">
				<ul class="pagination pagination-centered">
					<li>
						<a href="javascript:;" data-name="prev" class="lesson-pager-nav">«上一页</a>
					</li>
					<li>
						<a><span id="show_lesson_curr_page">0</span> / <span id="show_lesson_total_page">0</span></a>
					</li>
					<li>
						<a href="javascript:;" data-name="next" class="lesson-pager-nav">下一页»</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/* 搜索课程 START */
	var lesson_curr_page = 1;

	//搜索课程
	function searchLesson() {
		var pid = $("#pid").val();
		var cid = $("#cid").val();
		var lesson_type = $("#lessonType").val();
		var status = $("#lessonStatus").val();
		var keyword = $("#bookname_kwd").val();

		$("#modal-lesson-list").html("正在搜索....");
		$.get("<?php  echo $this->createWebUrl('getlesson')?>", { 
			pid:pid,cid:cid,
			lesson_type:lesson_type,
			status:status,
			keyword:keyword,
			page: lesson_curr_page,
		}, function (dat) {
			$("#modal-lesson-list").html(dat);
			$("#show_lesson_curr_page").text($(".lesson_curr_page").text());
			$("#show_lesson_total_page").text($(".lesson_total_page").text());
		});
	}

	//翻页
	$(".lesson-pager-nav").click(function(){
		var lesson_total_page	= $(".lesson_total_page").text();
		var button_name = $(this).data('name');

		if(button_name == 'prev'){
			if(lesson_curr_page <= 1){
				return;
			}else{
				lesson_curr_page--;
				searchLesson();
			}
		}else if(button_name == 'next'){
			if(lesson_curr_page >= lesson_total_page){
				return;
			}else{
				lesson_curr_page++;
				searchLesson();
			}
		}
	})

	//点击搜索按钮事件
	$(".btn-seach-lesson").click(function(){
		lesson_curr_page = 1;
		searchLesson();
	})

	//回车按键事件
	$("#bookname_kwd").bind("keypress", function(e) {
		if (e.keyCode == "13") {
			e.preventDefault();
			lesson_curr_page = 1;
			searchLesson();
		}
	});

	//选择课程分类
	var category = <?php  echo json_encode($category_list)?>;
	function renderCategory(pid){
		var chtml = '<option value="0">二级分类</option>';

		if(pid){
			for(var i in category){
				if(category[i].id==pid){
					var child = category[i].child;
					for(var j in child){
						chtml += '<option value="' + child[j].id+'">' + child[j].name + '</option>';
					}
					$("#cid").html(chtml);
				}
			}
		}else{
			$("#cid").html(chtml);
		}
	}

	searchLesson();
	/* 搜索课程 END */
</script>