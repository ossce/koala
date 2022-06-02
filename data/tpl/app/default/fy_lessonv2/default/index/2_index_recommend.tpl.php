<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($list)) { ?>
	<?php  if(is_array($list)) { foreach($list as $rec) { ?>
	<div class="index_unit <?php  if($common['small_index']) { ?>mar10-15 b-radius-10<?php  } ?>">
		<div class="index_title flex1">
			<div class="img flex0">
				<img class="flex_g0" src="<?php echo $rec['icon'] ? $_W['attachurl'].$rec['icon'] : MODULE_URL.'static/mobile/'.$template.'/images/index_default_rec_icon.png'?>" style="width: 19px;" />
			</div>
			<div class="flex_all index_recommend_title"><?php  echo $rec['rec_name'];?></div>
			<a href="<?php  echo $this->createMobileUrl('recommend', array('recid'=>$rec['recid']));?>" class="more icon_right index_recommend_more">查看更多</a>
		</div>
		
		<?php  if($rec['show_style']==1) { ?>
		<!-- 单图文 -->
		<div class="small_grid">
			<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
			<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']))?>" class="small_grid_a">
				<div class="img-box">
					<div class="img">
						<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
						<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
					</div>
					<?php  if($setting['show_study_number']) { ?>
					<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
						<?php  echo $item['study_number'];?>
						<?php  if($item['lesson_type'] == 0) { ?>
							<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
						<?php  } else if($item['lesson_type'] == 1) { ?>
							<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
						<?php  } ?>
					</div>
					<?php  } ?>
					<?php  if(!empty($item['ico_name'])) { ?>
						<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
					<?php  } ?>
				</div>
				<div class="grid_title flex1">
					<?php  if(!$item['section_status']) { ?><i class="section-status-btn">已完结</i><?php  } ?> <?php  echo $item['bookname'];?>
				</div>
				<div class="grid_info flex0">
				<?php  if($item['price']==0) { ?>
					<span class="price flex_g0 index_price_lesson fs-14">免费</span>
				<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
					<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
				<?php  } else { ?>
					<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
				<?php  } ?>
				<?php  if($item['count']) { ?>
					<span class="index_section_number mar-l-a"><?php  echo $item['count'];?>节</span>
				<?php  } ?>
				</div>
			</a>
			<?php  } } ?>
		</div>

		<?php  } else if($rec['show_style']==2) { ?>
		<!-- 专题+图文 -->
			<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$rec['lesson'][0]['id']));?>" class="big_grid">
				<div class="img-box big-img-box">
					<div class="img">
						<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $rec['lesson'][0]['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
						<div class="icon-live <?php  echo $rec['lesson'][0]['icon_live_status'];?>"></div>
					</div>
					<?php  if($setting['show_study_number']) { ?>
					<div class="learned <?php  if($rec['lesson']['0']==3) { ?>hide<?php  } ?>">
						<?php  echo $rec['lesson'][0]['study_number'];?>
						<?php  if($rec['lesson'][0]['lesson_type'] == 0) { ?>
							<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
						<?php  } else if($rec['lesson'][0]['lesson_type'] == 1) { ?>
							<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
						<?php  } ?>
					</div>
					<?php  } ?>
					<?php  if(!empty($rec['lesson'][0]['ico_name'])) { ?>
						<i class="ico_common <?php  echo $rec['lesson'][0]['ico_name'];?>"></i>
					<?php  } ?>
				</div>
				<div class="grid_title flex1">
					<span class="overhidden flex_all">
						<?php  if(!$rec['lesson'][0]['section_status']) { ?><i class="section-status-btn">已完结</i><?php  } ?> <?php  echo $rec['lesson'][0]['bookname'];?>
					</span>
					<span class="teacher"><?php  echo $rec['lesson'][0]['teacher']['teacher'];?></span>
				</div>
				<div class="grid_info flex0">
				<?php  if($rec['lesson'][0]['price']==0) { ?>
					<span class="price flex_g0 index_price_lesson fs-14">免费</span>
				<?php  } else if($setting['lesson_vip_status']==1 && $rec['lesson'][0]['vipview']) { ?>
					<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
				<?php  } else { ?>
					<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $rec['lesson'][0]['price'];?></span>
				<?php  } ?>
				<?php  if($rec['lesson'][0]['count']) { ?>
					<span class="index_section_number mar-l-a"><?php  echo $rec['lesson'][0]['count'];?>节</span>
				<?php  } ?>
				</div>
			</a>

			<div class="small_grid">
				<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $key => $item) { ?>
				  <?php  if($key>0) { ?>
					<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']))?>" class="small_grid_a">
						<div class="img-box">
							<div class="img">
								<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
								<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
							</div>
							<?php  if($setting['show_study_number']) { ?>
							<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
								<?php  echo $item['study_number'];?>
								<?php  if($item['lesson_type'] == 0) { ?>
									<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
								<?php  } else if($item['lesson_type'] == 1) { ?>
									<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
								<?php  } ?>
							</div>
							<?php  } ?>
							<?php  if(!empty($item['ico_name'])) { ?>
								<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
							<?php  } ?>
						</div>
						<div class="grid_title flex1">
							<?php  if(!$item['section_status']) { ?><i class="section-status-btn">已完结</i><?php  } ?> <?php  echo $item['bookname'];?>
						</div>
						<div class="grid_info flex0">
						<?php  if($item['price']==0) { ?>
							<span class="price flex_g0 index_price_lesson fs-14">免费</span>
						<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
							<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
						<?php  } else { ?>
							<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
						<?php  } ?>
						<?php  if($item['count']) { ?>
							<span class="index_section_number mar-l-a"><?php  echo $item['count'];?>节</span>
						<?php  } ?>
						</div>
					</a>
				  <?php  } ?>
				<?php  } } ?>
			</div>

		<?php  } else if($rec['show_style']==3) { ?>
		<!-- 单专题 -->
			<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
			<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']))?>" class="big_grid">
				<div class="img-box big-img-box">
					<div class="img">
						<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
						<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
					</div>
					<?php  if($setting['show_study_number']) { ?>
					<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
						<?php  echo $item['study_number'];?>
						<?php  if($item['lesson_type'] == 0) { ?>
							<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
						<?php  } else if($item['lesson_type'] == 1) { ?>
							<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
						<?php  } ?>
					</div>
					<?php  } ?>
					<?php  if(!empty($item['ico_name'])) { ?>
						<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
					<?php  } ?>
				</div>
				<div class="grid_title flex1">
					<span class="overhidden flex_all">
						<?php  if(!$item['section_status']) { ?><i class="section-status-btn">已完结</i><?php  } ?> <?php  echo $item['bookname'];?>
					</span>
					<span class="teacher"><?php  echo $item['teacher']['teacher'];?></span>
				</div>
				<div class="grid_info flex0">
				<?php  if($item['price']==0) { ?>
					<span class="price flex_g0 index_price_lesson fs-14">免费</span>
				<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
					<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
				<?php  } else { ?>
					<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
				<?php  } ?>
				<?php  if($item['count']) { ?>
					<span class="index_section_number mar-l-a"><?php  echo $item['count'];?>节</span>
				<?php  } ?>
				</div>
			</a>
			<?php  } } ?>

		<?php  } else if($rec['show_style']==4) { ?>
		<!-- 单标题 -->
		<div>
			<ul class="article_title_grid">
				<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
				<li>
					<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']));?>">
						<i class="index-book-ico"></i> <?php  echo $item['bookname'];?>
					</a>
				</li>
				<?php  } } ?>
			</ul>
		</div>

		<?php  } else if($rec['show_style']==5) { ?>
		<!-- 最新课程样式 -->
		<div>
			<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
			<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']));?>" class="new_lesson flex0_1">
				<div class="new_lesson_a flex_g0">
					<div class="img-box flex_g0">
						<div class="img">
							<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" />
							<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
						</div>
						<?php  if($setting['show_study_number']) { ?>
						<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
							<?php  echo $item['study_number'];?>
							<?php  if($item['lesson_type'] == 0) { ?>
								<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
							<?php  } else if($item['lesson_type'] == 1) { ?>
								<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
							<?php  } ?>
						</div>
						<?php  } ?>
						<?php  if(!empty($item['ico_name'])) { ?>
							<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
						<?php  } ?>
					</div>
				</div>

				<div class="flex_all flex10">
					<div>
						<div class="new_lesson_title"><?php  echo $item['bookname'];?></div>
						<div class="new_lesson_info"><?php  echo $item['section']['title'];?></div>
					</div>
					<div class="new_lesson_bottom flex1">
						<?php  if($item['price']==0) { ?>
							<span class="price flex_g0 index_price_lesson fs-14">免费</span>
						<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
							<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
						<?php  } else { ?>
							<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
						<?php  } ?>
					</div>
				</div>
			</a>
			<?php  } } ?>
		</div>

		<?php  } else if($rec['show_style']==6) { ?>
		<!-- 左右滚动样式 -->
		<div class="lesson_swiper_<?php  echo $rec['recid'];?>" style="padding:15px 0;overflow: hidden;">
			<div class="swiper-wrapper">
				<?php  if(is_array($rec['lesson'])) { foreach($rec['lesson'] as $item) { ?>
				<div class="swiper-slide">
					<a href="<?php  echo $this->createMobileUrl('lesson', array('op'=>'display', 'id'=>$item['id']))?>" class="small_grid_a">
						<div class="img-box" style="margin-left:4px;margin-right:4px;padding-bottom:56%;">
							<div class="img">
								<img src="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" />
								<div class="icon-live <?php  echo $item['icon_live_status'];?>"></div>
							</div>
							<?php  if($setting['show_study_number']) { ?>
							<div class="learned <?php  if($item['lesson_type']==3) { ?>hide<?php  } ?>">
								<?php  echo $item['study_number'];?>
								<?php  if($item['lesson_type'] == 0) { ?>
									<?php echo $index_page['videoLessonNum'] ? $index_page['videoLessonNum'] : '人已学习';?>
								<?php  } else if($item['lesson_type'] == 1) { ?>
									<?php echo $index_page['appointLessonNum'] ? $index_page['appointLessonNum'] : '人已报名';?>
								<?php  } ?>
							</div>
							<?php  } ?>
							<?php  if(!empty($item['ico_name'])) { ?>
								<i class="ico_common <?php  echo $item['ico_name'];?>"></i>
							<?php  } ?>
						</div>
						<div class="grid_title flex1 h-40" style="margin-left:4px;margin-right:4px;">
							<?php  if(!$item['section_status']) { ?><i class="section-status-btn">已完结</i><?php  } ?> <?php  echo $item['bookname'];?>
						</div>
						<div class="grid_info flex0">
						<?php  if($item['price']==0) { ?>
							<span class="price flex_g0 index_price_lesson fs-14">免费</span>
						<?php  } else if($setting['lesson_vip_status']==1 && $item['vipview']) { ?>
							<span class="price flex_g0 index_price_lesson fs-14 ios-system">VIP免费</span>
						<?php  } else { ?>
							<span class="price flex_g0 index_price_lesson fs-15 ios-system">¥ <?php  echo $item['price'];?></span>
						<?php  } ?>
						<?php  if($item['count']) { ?>
							<span class="index_section_number mar-l-a"><?php  echo $item['count'];?>节</span>
						<?php  } ?>
						</div>
					</a>
				</div>
				<?php  } } ?>
			</div>
		</div>
		<script type="text/javascript">
			var lesson_swiper_<?php  echo $rec['recid'];?> = new Swiper(".lesson_swiper_<?php  echo $rec['recid'];?>", {
				slidesPerView:2, 
				spaceBetween:5, 
				autoplay: 4000, 
				loop : true,
				autoplayDisableOnInteraction: false,
			});
		</script>
		<?php  } ?>
	</div>
	<?php  } } ?>
<?php  } ?>
