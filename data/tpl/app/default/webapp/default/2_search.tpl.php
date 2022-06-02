<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template($template.'/_header')?>
<div class="w-all w-minw bg-c-fcfcfc ovhidden">
    <div class="w-main m-auto"> 
        <div class="w-main fs-14 ftc-7a7a7a line-h45 m-t-10 m-b-10">
			当前位置：<a href="/<?php  echo $uniacid;?>/index.html" class="more ftc-414141">首页</a> &gt; 全部课程
		</div>

        <div class="w-1200 bg-c-ffffff fl">
            <div class="ql_ftclass p-l-10 p-r-10">
                <div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl">课程分类</div>
                    <ul class="w-1100 fl">
						<li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if(!$_GPC['pid']) { ?>class_cur<?php  } ?>">不限</a>
						</li>
						<?php  if(is_array($categorylist)) { foreach($categorylist as $category) { ?>
                        <li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $category['id'];?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['pid']==$category['id']) { ?>class_cur<?php  } ?>"><?php  echo $category['name'];?></a>
						</li>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="w-all fl hg-1 bg-c-e5e5e5"></div>

				<?php  if($_GPC['pid']) { ?>
                <div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl">下级分类</div>
                    <ul class="w-1100 fl">
                        <li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if(!$_GPC['cid']) { ?>class_cur<?php  } ?>">不限</a>
						</li>
						<?php  if(is_array($child)) { foreach($child as $item) { ?>
                        <li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $item['id'];?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['cid']==$item['id']) { ?>class_cur<?php  } ?>"><?php  echo $item['name'];?></a>
						</li>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="w-all fl hg-1 bg-c-e5e5e5"></div>
				<?php  } ?>

				<?php  if($lesson_attribute['attribute1']) { ?>
				<div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl"><?php  echo $lesson_attribute['attribute1'];?></div>
                    <ul class="w-1100 fl">
                        <li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if(!$_GPC['attr1']) { ?>class_cur<?php  } ?>">不限</a>
						</li>
						<?php  if(is_array($attribute1)) { foreach($attribute1 as $item) { ?>
							<?php  if(in_array($item['id'], $cat_attribute1)) { ?>
								<li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
									<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $item['id'];?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['attr1']==$item['id']) { ?>class_cur<?php  } ?>"><?php  echo $item['name'];?></a>
								</li>
							<?php  } ?>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="w-all fl hg-1 bg-c-e5e5e5"></div>
				<?php  } ?>

				<?php  if($lesson_attribute['attribute2']) { ?>
				<div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl"><?php  echo $lesson_attribute['attribute2'];?></div>
                    <ul class="w-1100 fl">
                        <li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if(!$_GPC['attr2']) { ?>class_cur<?php  } ?>">不限</a>
						</li>
						<?php  if(is_array($attribute2)) { foreach($attribute2 as $item) { ?>
							<?php  if(in_array($item['id'], $cat_attribute2)) { ?>
								<li class="w-min80 w-max120 ovhidden m-l-24 p-b-3 fl">
									<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $item['id'];?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['attr2']==$item['id']) { ?>class_cur<?php  } ?>"><?php  echo $item['name'];?></a>
								</li>
							<?php  } ?>
						<?php  } } ?>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="w-all fl hg-1 bg-c-e5e5e5"></div>
				<?php  } ?>

				<div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl">课程类型</div>
                    <ul class="w-1100 fl">
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_type']=='') { ?>class_cur<?php  } ?>">不限</a>
						</li>
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=0&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_type']=='0') { ?>class_cur<?php  } ?>">普通课程</a>
						</li>
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=1&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_type']==1) { ?>class_cur<?php  } ?>">报名课程</a>
						</li>
						<li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=3&lesson_nature=<?php  echo $lesson_nature;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_type']==3) { ?>class_cur<?php  } ?>">直播课程</a>
						</li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="w-all fl hg-1 bg-c-e5e5e5"></div>

                <div class="w-all p-t-10 p-b-10 ovhidden">
                    <div class="w-80 fs-14 line-h28 ftc-888888 fl">课程性质</div>
                    <ul class="w-1100 fl">
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if(!$_GPC['lesson_nature']) { ?>class_cur<?php  } ?>">不限</a>
						</li>
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=1&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_nature']==1) { ?>class_cur<?php  } ?>">免费课程</a>
						</li>
                        <li class="w-min80 w-max120 ovhidden m-l-24 fl">
							<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=2&sort=<?php  echo $sort;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="dsblock w-all fs-14 line-h28 ftc-525252 curpter text-c fl search-condition <?php  if($_GPC['lesson_nature']==2) { ?>class_cur<?php  } ?>">付费课程</a>
						</li>
                        <div class="clear"></div>
                    </ul>
                </div>
				<div class="w-all fl hg-1 bg-c-e5e5e5"></div>
            </div>
        </div>

		<?php  if($list) { ?>
		<div class="w-1200 bg-c-ffffff p-t-10 p-b-10 fl m-t-20">
            <dl class="ql_sort">
                <dd class="w-75 curpter fs-14 ftc-000000 line-h24 m-l-12 fl">
					<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="<?php  if(!$_GPC['sort']) { ?>cur<?php  } ?>">综合排序<i class="iconfont icon-default-sort"></i> </a>
				</dd>
                <dd class="w-75 p-l-10 curpter fs-14 ftc-000000 line-h24 m-l-12 fl">
					<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=hot&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="<?php  if($_GPC['sort']=='hot') { ?>cur<?php  } ?>">人气优先<i class="iconfont icon-sort"></i> </a>
				</dd>
                <dd class="w-75 p-l-10 curpter fs-14 ftc-000000 line-h24 m-l-12 fl">
					<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=price&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="<?php  if($_GPC['sort']=='price') { ?>cur<?php  } ?>">价格优先<i class="iconfont icon-sort"></i> </a>
				</dd>
                <dd class="w-75 p-l-10 curpter fs-14 ftc-000000 line-h24 m-l-12 fl">
					<a href="/<?php  echo $uniacid;?>/search.html?pid=<?php  echo $pid;?>&cid=<?php  echo $cid;?>&lesson_type=<?php  echo $lesson_type;?>&lesson_nature=<?php  echo $lesson_nature;?>&sort=score&keyword=<?php  echo $keyword;?>&attr1=<?php  echo $attr1;?>&attr2=<?php  echo $attr2;?>" class="<?php  if($_GPC['sort']=='score') { ?>cur<?php  } ?>">好评优先<i class="iconfont icon-sort"></i> </a>
				</dd>
                <div class="clear"></div>
            </dl>
        </div>
		<?php  } ?>

        <div class="w-main m-auto m-t-25 fl">
			<div class="course-list">
				<?php  if($list) { ?>
				<ul>
					<?php  if(is_array($list)) { foreach($list as $item) { ?>
					<li>
						<a href="/<?php  echo $uniacid;?>/lesson.html?id=<?php  echo $item['id'];?>" title="<?php  echo $item['bookname'];?>" target="_blank">
							<div class="cover">
								<img class="lazy" data-original="<?php  echo $_W['attachurl'];?><?php  echo $item['images'];?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" alt="<?php  echo $item['bookname'];?>" style="display: block;">
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
										<i class="click-count-icon"></i><?php  echo $item['buyTotal'];?>
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
									<?php  if($item['score_rate']) { ?>
									<span class="teacher">
										<img src="<?php echo MODULE_URL;?>static/webapp/<?php  echo $template;?>/images/icon-good.png" width="20"
										 height="19"><?php  echo $item['score_rate'];?>%好评
									</span>
									<?php  } ?>
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
				<?php  } else { ?>

				<div class="no-content">
					<div class="msg-inner">
						<div class="msg-text">
							<i class="iconfont i-info icon-information"></i> 未找到相关的课程，选择其他分类或者换个关键词试试吧：）
						</div>
					</div>
				</div>
				<?php  } ?>
			</div>

            <div class="w-amin hg-30"></div>
			<?php  echo $pager;?>
            <div class="w-amin hg-30"></div>
        </div>
        <!--精选课程 end--> 
    </div>
</div>

<?php  include $this->template($template.'/_footer')?>