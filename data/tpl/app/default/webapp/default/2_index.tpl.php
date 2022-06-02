<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>

<div class="w-all w-minw banner ps-r">
    <div id="full-screen-slider">
        <ul id="slides">
			<?php  if(is_array($banner_list)) { foreach($banner_list as $banner) { ?>
            <li style="background:url(<?php  echo $_W['attachurl'];?><?php  echo $banner['picture'];?>) center top no-repeat;"><a <?php  if($banner['link']) { ?>href="<?php  echo $banner['link'];?>"<?php  } ?> target="_blank"></a></li>
			<?php  } } ?>
        </ul>
    </div>
</div> 

<div class="w-all w-minw bg-c-fcfcfc ovhidden m-t-30"> 
	
	<?php  if($notice_list) { ?>
    <!-- 通知公告 -->
	<div class="w-main m-auto"> 
        <div class="w-main ovhidden fl m-b-6">
			<h3 class="fl gray3 fs-20 ft-w-b line-h50">
				<span class="dsblock w-25 fs-26 index_notice_icon ql_h_news tx-ind m-r-10 fl" <?php  if($new_notice_setting['icon']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $new_notice_setting['icon'];?>)"<?php  } ?>><?php echo $new_notice_setting['font'] ? $new_notice_setting['font'] : '最新通知';?></span><?php echo $new_notice_setting['font'] ? $new_notice_setting['font'] : '最新通知';?>
			</h3>
            <a href="/<?php  echo $uniacid;?>/article.html" class="fr gray7 fs-14 ftc-000000 line-h50 more">查看更多&gt;</a>
		</div>
        <div class="w-main"> 
            <div class="w-285 hot_news bg-c-ffffff fl">
				<?php  if($notice_adv) { ?>
				<a <?php  if($notice_adv['link']) { ?>href="<?php  echo $notice_adv['link'];?>"<?php  } ?> class="dsblock w-all hg-64 ovhidden fl">
					<img src="<?php  echo $_W['attachurl'];?><?php  echo $notice_adv['picture'];?>" />
				</a>
				<?php  } ?>
			</div>
            <div class="w-906 fr"> 
                <div class="w-895 m-auto">
                    <ul id="hotnewlist" class="bg-c-ffffff p-l-25 p-r-30 ovhidden">
                        <p class="hg-185 w-1 tx-ind">竖线</p>
                        <?php  if(is_array($notice_list)) { foreach($notice_list as $item) { ?>
						<li>
							<a href="/<?php  echo $uniacid;?>/article.html?op=details&aid=<?php  echo $item['id'];?>">
								<h3 class="w-270 fl fs-13 line-h32 ftc-000000 one_hidden">
									<?php  if($item['is_vip']) { ?>
										<img src="<?php echo MODULE_URL;?>static/webapp/default/images/article-title-vip.png?v=3" height="18" style="vertical-align:-4px;" >
									<?php  } ?>
									<?php  echo $item['title'];?>
								</h3>
								<span class="dsblock w-115 fs-13 line-h32 ftc-8c8c8c lspa-1 fr">【<?php  echo date('Y-m-d', $item['addtime']);?>】</span>
							</a>
						</li>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                    <script type="text/javascript">
						window.onload = function () {
							var ul = document.getElementById ("hotnewlist");
							var lis = ul.getElementsByTagName("li");
							for (var i = 0; i < lis.length; i++) {
								if(i>0 && i%2==1){
									lis[i].className="note";
								}
							};
						}
					</script> 
                </div>
            </div>
        </div>
		<div class="clear"></div>
    </div>
	<!-- 分割线 -->
    <div class="w-main m-auto">
        <div class="w-main fl hg-30"></div>
        <div class="w-main fl hg-1 bg-c-e5e5e5"></div>
        <div class="w-main fl hg-30"></div>
    </div>
	<?php  } ?>

	<?php  if($discount_adv) { ?>    
    <!-- 限时折扣广告 -->
    <div class="w-main m-auto">
		<?php  if(is_array($discount_adv)) { foreach($discount_adv as $key => $item) { ?>
		<a <?php  if($item['link']) { ?>href="<?php  echo $item['link'];?>"<?php  } ?> class="dsblock homead ovhidden w-380 hg-170 fl <?php  if($key) { ?>m-l-30<?php  } ?>" target="_blank">
			<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['picture'];?>" alt="<?php  echo $item['banner_name'];?>"/>
		</a>
		<?php  } } ?>
	</div>
	<!-- 分割线 -->
    <div class="w-main m-auto">
        <div class="w-main fl hg-40"></div>
        <div class="w-main fl hg-1 bg-c-e5e5e5"></div>
        <div class="w-main fl hg-8"></div>
    </div>
	<?php  } ?>

	<?php  if($rec_teacher) { ?>
	<!-- 名师风采 -->
    <div class="w-main m-auto"> 
        <div class="w-main ovhidden fl m-b-6">
			<h3 class="fl gray3 fs-20 ft-w-b line-h50">
				<span class="dsblock w-25 fs-26 index_rec_teacher_icon ql_h_news tx-ind m-r-10 fl" <?php  if($rec_teacher_setting['icon']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $rec_teacher_setting['icon'];?>)"<?php  } ?>><?php echo $rec_teacher_setting['font'] ? $rec_teacher_setting['font'] : '名师风采';?></span><?php echo $rec_teacher_setting['font'] ? $rec_teacher_setting['font'] : '名师风采';?>
			</h3>
            <a href="/<?php  echo $uniacid;?>/teacherlist.html" class="fr gray7 fs-14 ftc-000000 line-h50 more">查看更多&gt;</a>
		</div>
    </div>
    <div class="w-main m-auto">
        <ul class="home_tearch w-main ovhidden">
			<?php  if(is_array($rec_teacher)) { foreach($rec_teacher as $item) { ?>
            <li>
                <div class="h_tea_img w-120">
					<a href="/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $item['id'];?>" class="dsblock" target="_blank">
						<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['teacherphoto'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC">
					</a>
                    <p class="w-all fl fs-14 ft-w-b ftc-000000 text-c line-h30"><?php  echo $item['teacher'];?></p>
                </div>
                <div class="w-200 fl">
					<a href="/<?php  echo $uniacid;?>/teacher.html?teacherid=<?php  echo $item['id'];?>" target="_blank">
						<h3 class="fs-14 ftc-000000 line-h22 lesson-total">
							<?php  if($item['total']) { ?>
								总共<?php  echo $item['total'];?><?php echo $lesson_page['lessonNum'] ? $lesson_page['lessonNum'] : '个课程'?>
							<?php  } else { ?>
								暂无发布课程
							<?php  } ?>
						</h3>
						<h3 class="fs-14 ftc-000000 line-h22 all-lesson"><em></em><?php echo $lesson_page['allLesson'] ? $lesson_page['allLesson'] : '查看全部课程'?></h3>
					</a>
                </div>
                <div class="w-all fl fs-14 ftc-000000 line-h22 m-t-50 fu_hidden teacher-descript">
					<?php  if(is_array($item['teacherdes'])) { foreach($item['teacherdes'] as $digest) { ?>
						<p><?php  echo $digest;?></p>
					<?php  } } ?>
				</div>
            </li>
            <?php  } } ?>
            <div class="clear"></div>
        </ul>
    </div>
	<!-- 分割线 -->
    <div class="w-main m-auto">
        <div class="w-main fl hg-11"></div>
        <div class="w-main fl hg-1 bg-c-e5e5e5"></div>
        <div class="w-main fl hg-8"></div>
    </div>
	<?php  } ?>

	<?php  if($newlesson) { ?>
	<!-- 最新更新课程 -->
	<div class="w-main m-auto"> 
        <div class="w-main ovhidden fl m-b-6">
			<h3 class="fl gray3 fs-20 ft-w-b line-h50">
				<span class="dsblock w-25 fs-26 index_newlesson_icon ql_h_news tx-ind m-r-10 fl" <?php  if($newlesson_setting['icon']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $newlesson_setting['icon'];?>)"<?php  } ?>><?php echo $newlesson_setting['font'] ? $newlesson_setting['font'] : '最新更新';?></span><?php echo $newlesson_setting['font'] ? $newlesson_setting['font'] : '最新更新';?>
			</h3>
		</div>
		<div class="w-main w-1200 course-list">
			<ul>
				<?php  if(is_array($newlesson)) { foreach($newlesson as $item) { ?>
				<li>
					<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['id'];?>" title="<?php  echo $item['bookname'];?>" target="_blank">
						<div class="cover">
							<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" alt="<?php  echo $item['bookname'];?>">
							<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
							<?php  if(!empty($item['ico_name'])) { ?>
								<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
							<?php  } ?>
							<?php  if($item['section_status']=='0') { ?>
								<i class="section_ended">已完结</i>
							<?php  } ?>
						</div>
						<div class="info">
							<p class="title"><?php  echo $item['bookname'];?></p>
							<p class="item1">
								<?php  if($setting['show_study_number']) { ?>
								<span class="click"><i class="click-count-icon"></i><?php  echo $item['study_number'];?></span>
								<?php  } ?>
								<span class="type price">
								<?php  if($item['price']==0) { ?>
									免费
								<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
									VIP免费
								<?php  } else { ?>
									<?php  if($item['market_price']) { ?><i class="market-price">¥<?php  echo $item['market_price'];?></i><?php  } ?> <strong>¥ <?php  echo $item['price'];?></strong>
								<?php  } ?>
								</span>
							</p>
							<p class="item2">
								<span class="teacher">
									<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['teacher']['teacherphoto'];?>" width="32"
									 height="32" ><?php  echo $item['teacher']['teacher'];?>
								</span>
								<?php  if($item['count'] && $item['lesson_type']!=3) { ?>
								<span class="count"><i class="section-count-icon"></i>共<?php  echo $item['count'];?>课时</span>
								<?php  } ?>
							</p>
							<p class="update-info">
								<?php  if($item['lesson_type']!=3) { ?>
								<span class="section-title"><i class="section-title-icon"></i><?php  echo $item['section']['title'];?></span>
								<?php  } ?>
								<span class="update-time"><i class="section-update-icon"></i><?php  echo $item['tran_time'];?> 更新</span>
							</p>
						</div>
					</a>
				</li>
				<?php  } } ?>
			</ul>
        </div> 
    </div>
	<!-- 分割线 -->
    <div class="w-main m-auto">
        <div class="w-main fl hg-11"></div>
        <div class="w-main fl hg-1 bg-c-e5e5e5"></div>
        <div class="w-main fl hg-8"></div>
    </div>
	<?php  } ?>

<?php  if($rec_lesson) { ?>
	<?php  if(is_array($rec_lesson)) { foreach($rec_lesson as $rec) { ?>
	<div class="w-main m-auto"> 
        <div class="w-main ovhidden fl m-b-6">
			<h3 class="fl gray3 fs-20 ft-w-b line-h50">
				<span class="dsblock w-25 fs-26 index_rec_lesson_icon ql_h_news tx-ind m-r-10 fl" <?php  if($rec['icon']) { ?>style="background-image:url(<?php  echo $_W['attachurl'];?><?php  echo $rec['icon'];?>)"<?php  } ?>><?php  echo $rec['rec_name'];?></span><?php  echo $rec['rec_name'];?>
			</h3>
			<a href="/<?php  echo $uniacid;?>/recommend.html?recid=<?php  echo $rec['recid'];?>" class="fr gray7 fs-14 ftc-000000 line-h50 more">查看更多&gt;</a>
		</div>
		<div class="w-main w-1200 course-list">
			<ul>
				<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
				<li>
					<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['id'];?>" title="<?php  echo $item['bookname'];?>" target="_blank">
						<div class="cover">
							<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" alt="<?php  echo $item['bookname'];?>">
							<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
							<?php  if(!empty($item['ico_name'])) { ?>
								<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
							<?php  } ?>
							<?php  if($item['section_status']=='0') { ?>
								<i class="section_ended">已完结</i>
							<?php  } ?>
						</div>
						<div class="info">
							<p class="title"><?php  echo $item['bookname'];?></p>
							<p class="item1">
								<?php  if($setting['show_study_number']) { ?>
								<span class="click">
									<i class="click-count-icon"></i> <?php  echo $item['study_number'];?>
								</span>
								<?php  } ?>
								<span class="type price">
								<?php  if($item['price']==0) { ?>
									免费
								<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
									VIP免费
								<?php  } else { ?>
									<?php  if($item['market_price']) { ?><i class="market-price">¥<?php  echo $item['market_price'];?></i><?php  } ?> <strong>¥ <?php  echo $item['price'];?></strong>
								<?php  } ?>
								</span>
							</p>
							<p class="item2">
								<span class="teacher">
									<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['teacher']['teacherphoto'];?>" width="32" height="32" /><?php  echo $item['teacher']['teacher'];?>
								</span>
								<?php  if($item['lesson_type']!=3) { ?>
								<span class="count"><i class="section-count-icon"></i>共<?php  echo $item['count'];?>课时</span>
								<?php  } ?>
							</p>
							<p class="description"><?php  echo $item['descript'];?></p>
						</div>
					</a>
				</li>
				<?php  } } ?>
			</ul>
        </div> 
    </div>
	<!-- 分割线 -->
    <div class="w-main m-auto">
        <div class="w-main fl hg-11"></div>
        <div class="w-main fl hg-1 bg-c-e5e5e5"></div>
        <div class="w-main fl hg-8"></div>
    </div>
	<?php  } } ?>
<?php  } ?>
</div>

<?php  include $this->template($template.'/_footer')?>