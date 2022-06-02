<?php
/**
 * 课程详情页
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
if((!$userAgent && in_array('lesson', $login_visit)) || ($userAgent && !$comsetting['hidden_login']) || $setting['mustinfo']==2){
	checkauth();
}

$uid = $_W['member']['uid'];
$site_common->check_black_list('visit', $uid);
$isfollow = json_decode($setting['isfollow'], true); /* 引导关注 */
$index_page  = $common['index_page'];  /* 首页字体 */
$lesson_page = $common['lesson_page']; /* 课程页面字体 */
$lesson_config = json_decode($setting['lesson_config'], true); /* 课程页面设置 */

if($uid && !$_GPC['uid']){
	header("Location:".$_W['siteurl'].'&uid='.$uid);
}

$id = intval($_GPC['id']);/* 课程id */
$sectionid = intval($_GPC['sectionid']);/* 点播章节id */
$section_keyword = trim($_GPC['section_keyword']);/* 搜索章节关键词 */

if($uid){
	$member = pdo_fetch("SELECT a.*,b.follow,c.avatar,c.nickname,c.realname,c.mobile,c.msn,c.idcard,c.occupation,c.company,c.graduateschool,c.grade,c.address,c.education,c.position FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_fans). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_mc_members). " c ON a.uid=c.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));
}
if(empty($member['avatar'])){
	$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
	$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}

$lesson = pdo_fetch("SELECT a.*,b.teacher,b.qq,b.qqgroup,b.qqgroupLink,b.weixin_qrcode,b.online_url,b.teacherphoto,b.teacherdes,b.uid AS teacher_uid FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
if(!$lesson || $lesson['status']==0 || $lesson['status']==2){
	message("该课程已下架，您可以看看其他课程", $this->createMobileUrl('index',array('t'=>1)), "warning");
}
$lesson['qq'] = $config['teacher_qq'] ? $config['teacher_qq'] : $lesson['qq'];
$lesson['qqgroup'] = $config['teacher_qqgroup'] ? $config['teacher_qqgroup'] : $lesson['qqgroup'];
$lesson['qqgroupLink'] = $config['teacher_qqlink'] ? $config['teacher_qqlink'] : $lesson['qqgroupLink'];
$lesson['weixin_qrcode'] = $config['teacher_qrcode'] ? $config['teacher_qrcode'] : $lesson['weixin_qrcode'];
$lessonNumber = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND teacherid=:teacherid AND status=:status", array(':uniacid'=>$uniacid,':teacherid'=>$lesson['teacherid'],':status'=>1));

/* 课程规格 */
$spec_condition = " uniacid=:uniacid AND lessonid=:lessonid ";
$spec_params = array(
	':uniacid' => $uniacid,
	':lessonid' => $id,
);
if($setting['stock_config']){
	$spec_condition .= " AND spec_stock>:spec_stock";
	$spec_params[':spec_stock'] = 0;
}

$spec_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_spec). " WHERE {$spec_condition} ORDER BY spec_sort DESC,spec_price ASC", $spec_params);

/* 显示折扣 */
$discount_lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_discount_lesson). " WHERE uniacid=:uniacid AND lesson_id=:lesson_id AND starttime<:time AND endtime>:time", array(':uniacid'=>$uniacid,':lesson_id'=>$id,':time'=>time()));
if(!empty($discount_lesson)){
	foreach($spec_list as $k=>$v){
		$spec_list[$k]['spec_price'] = round($v['spec_price']*$discount_lesson['discount']*0.01, 2);
	}
	$discount_endtime = date('Y/m/d H:i:s', $discount_lesson['endtime']);
	$diacount_price = explode('.', $spec_list[0]['spec_price']);
	$market_price = $lesson['price'];
	$lesson['price'] = round($lesson['price']*$discount_lesson['discount']*0.01, 2);
}

/* 购买讲师价格 */
$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$lesson['teacherid']));

/* 佣金记录和赚取佣金按钮 */
if(!$sectionid && !$_GPC['play'] && $uid && $comsetting['is_sale']){
	if($member['status']){
		/* 分销身份为VIP身份 */
		if($comsetting['sale_rank'] == 2){
			$member_vip = pdo_fetch("SELECT * FROM  " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 0,1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
			$commission_switch = $member_vip ? true : false;
		}else{
			$commission_switch = true;
		}
	}

	if($commission_switch){
		if($lesson['price'] > 0){
			$lesson_commission = unserialize($lesson['commission']);
			if($lesson_commission['commission_type']){
				//课程单独固定佣金
				$commisson1_amount = $commission1;
			}else{
				//课程单独佣金比例
				$commission1 = $lesson_commission['commission1'];
				if(empty($commission1)){
					if($member['agent_level']){
						$commission_level = pdo_get($this->table_commission_level, array('id'=>$member['agent_level']));
						$commission1 = $commission_level['commission1'];
					}else{
						$commission = unserialize($comsetting['commission']);
						$commission1 = $commission['commission1'];
					}
				}
				$commisson1_amount = round($commission1 * $spec_list[count($spec_list)-1]['spec_price'] * 0.01, 2);
			}
		}

		/* 所有课程最近佣金记录 */
		if($common['lately_commission']){
			$commission_log = $this->readCommonCache('fy_lesson_'.$uniacid.'_lesson_commission_log');
			if(empty($commission_log)){
				$commission_log = pdo_fetchall("SELECT a.change_num,b.nickname,b.realname FROM " .tablename($this->table_commission_log). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.change_num>:change_num AND a.source=:source ORDER BY a.id DESC LIMIT 0,40", array(':uniacid'=>$uniacid,':change_num'=>5,':source'=>1));
				
				foreach($commission_log as $k=>$v){
					$v['nickname'] = $v['nickname'] ? $v['nickname'] : $v['realname'];
					$v['nickname'] = $site_common->substrCut($v['nickname'], 1, 1);

					if($v['change_num']*100 == intval($v['change_num'])*100){
						$v['change_num'] = intval($v['change_num']);
					}elseif($v['change_num']*10 == round($v['change_num'],1)*10){
						$v['change_num'] = round($v['change_num'],1);
					}

					$commission_log[$k] = $v;
				}
				shuffle($commission_log);
				cache_write('fy_lesson_'.$uniacid.'_lesson_commission_log', $commission_log);
			}
		}
	}
}


/* 购买按钮名称 */
$buynow_info = json_decode($lesson['buynow_info'], true);
$buynow_name = $buynow_info['buynow_name'] ? $buynow_info['buynow_name'] : $config['buynow_name'];
$buynow_link = $buynow_info['buynow_link'] ? $buynow_info['buynow_link'] : $config['buynow_link'];
$study_name  = $buynow_info['study_name']  ? $buynow_info['study_name']  : $config['study_name'];
$study_link  = $buynow_info['study_link']  ? $buynow_info['study_link']  : $config['study_link'];

if($uid>0){
	/* 查询是否收藏该课程 */
	$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid=:uniacid AND uid=:uid AND outid=:outid AND ctype=:ctype LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':outid'=>$id,':ctype'=>1));

	/* 查询是否购买该课程 */
	$isbuy = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
}

/* 标题 */
$title = $lesson['bookname'];

/* 非直播课程 */
if($lesson['lesson_type']!=3){
	/* 章节列表 */
	$section_list = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status AND auto_show=:auto_show AND show_time<=:show_time", array(':parentid'=>$id, ':status'=>0, ':auto_show'=>1, ':show_time'=>time()));
	foreach($section_list as $item){
	   pdo_update($this->table_lesson_son, array('status'=>1,'auto_show'=>0,'show_time'=>''), array('id'=>$item['id']));
	}

	$first_section = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status ORDER BY displayorder DESC,id ASC LIMIT 1", array(':parentid'=>$id,':status'=>1));
	
	if(!$section_keyword){
		//已归纳课程目录的章节
		$title_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_title)." WHERE lesson_id=:lesson_id ORDER BY displayorder DESC,title_id ASC", array('lesson_id'=>$id));
		foreach($title_list as $k=>$v){
			$title_list[$k]['section'] = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>$v['title_id'],':status'=>1));
			
			if($sectionid){
				foreach($title_list[$k]['section'] as $item){
					if($sectionid == $item['id']){
						$title_list[$k]['play'] = true;
					}
				}
			}
			
			if(empty($title_list[$k]['section'])){
				unset($title_list[$k]);
			}
		}

		//未归纳课程目录的章节
		$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>0,':status'=>1));

		$section_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$id,':status'=>1));
	}else{
		//按章节标题关键词搜索
		$section_condition = " uniacid=:uniacid AND parentid=:parentid AND status=:status AND title LIKE :title ";
		$section_params = array(
			':uniacid'	=> $uniacid,
			':parentid' => $id,
			':status'	=> 1,
			':title'	=> '%'.$section_keyword.'%',
		);
		$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE {$section_condition} ORDER BY title_id ASC,displayorder DESC,id ASC", $section_params);

		$section_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE {$section_condition}", $section_params);
	}
}

/*课程VIP免费学习*/
$lesson_vip_list = array();
if(is_array(json_decode($lesson['vipview'])) && $lesson['price']>0){
	foreach(json_decode($lesson['vipview']) as $v){
		$level = $site_common->getLevelById($v);
		if(!empty($level['level_name']) && $level['is_show']==1){
			$level['discount_price'] = round($level['level_price']*$level['open_discount']*0.01,2);
			$lesson_vip_list[] = $level;
		}
	}
}

/* 点播章节 */
if($sectionid>0){
	$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND id=:id AND status=:status LIMIT 1", array(':parentid'=>$id,':id'=>$sectionid,':status'=>1));
}

/**
  $play  用户学习章节资格标识
  $plays 拥有学习完整课程标识
  $show_isbuy 显示开始学习按钮
 */
if($section['is_free']==1){
	$play = true;
	$plays = false;
}
if($lesson['price']==0){
	$play = true;
	$plays = true;
	$show_isbuy = true;
}
if($isbuy){
	if($isbuy['validity']==0){
		$play = true;
		$plays = true;
		$show_isbuy = true;
	}else{
		if($isbuy['validity']>time()){
			$play = true;
			$plays = true;
			$show_isbuy = true;
		}
	}
}
/* 讲师自己课程免费 */
$teacher = pdo_get($this->table_teacher, array('uid'=>$uid), array('id'));

if($lesson['teacherid'] == $teacher['id']){
	$play = true;
	$plays = true;
	$show_isbuy = true;
}

/* 已购买讲师服务 */
$buy_teacher = pdo_fetch("SELECT * FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND teacherid=:teacherid AND validity>:validity", array(':uid'=>$uid, ':teacherid'=>$lesson['teacherid'], ':validity'=>time()));
if(!empty($buy_teacher)){
	$play = true;
	$plays = true;
	$show_isbuy = true;
}

if($uid){
	/* vip免费学习课程对于普通课程生效 */
	if($lesson['lesson_type']==0 || $lesson['lesson_type']==3){
		$memberVip_list = pdo_fetchall("SELECT level_id FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
		if(!empty($memberVip_list)){
			foreach($memberVip_list as $v){
				if(in_array($v['level_id'], json_decode($lesson['vipview']))){
					$play = true;
					$plays = true;
					$show_isbuy = true;
					$freeEvaluate = true; //VIP免费评价标识
					$viplesson = true; //vip等级相应课程
					break;
				}
			}
		}
	}

	/* 报名课程核销后才显示购买按钮 */
	if($lesson['lesson_type']==1){
		$apply_order = pdo_fetch("SELECT id,verify_number FROM " .tablename($this->table_order). " WHERE uid=:uid AND lesson_type=:lesson_type AND lessonid=:lessonid AND status>=:status AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lesson_type'=>1,':lessonid'=>$id,':status'=>1,':is_delete'=>0));
		if($apply_order){
			$verify_log = $site_common->getOrderVerifyLog($apply_order['id']);
			if($verify_log['count']<$apply_order['verify_number']){
				$show_qrcode = true;
			}
		}
	}

	/* 增加会员课程足迹 */
	$history = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_history). " WHERE lessonid=:lessonid AND uid=:uid LIMIT 1", array(':lessonid'=>$id,':uid'=>$uid));
	$insertdata = array(
		'uniacid'  => $uniacid,
		'uid'	   => $uid,
		'lessonid' => $id,
		'addtime'  => time(),
	);

	$parent_data = array();
	if($viplesson){
		$insertdata['vipview'] = 1;
		$parent_data['vip_number +='] = 1;
	}
	if($buy_teacher){
		$insertdata['teacherview'] = 1;
		$parent_data['teacher_number +='] = 1;
	}

	
	if(!$history){
		pdo_insert($this->table_lesson_history, $insertdata);
		
		$parent_data['visit_number +='] = 1;
		pdo_update($this->table_lesson_parent, $parent_data, array('id'=>$lesson['id']));
	}else{
		if(($viplesson && !$history['vipview']) || ($buy_teacher && !$history['teacherview'])){
			pdo_update($this->table_lesson_parent, $parent_data, array('id'=>$lesson['id']));
		}
		if((!$history['vipview'] && $insertdata['vipview']) || !$history['teacherview'] && $insertdata['teacherview']){
			pdo_update($this->table_lesson_history, $insertdata, array('lessonid'=>$id,'uid'=>$uid));
		}else{
			pdo_update($this->table_lesson_history, array('addtime'=>time()), array('lessonid'=>$id,'uid'=>$uid));
		}
	}
}

if(!$isbuy && !$viplesson && $lesson['status']=='-1'){
	message("该课程已下架，您可以看看其他课程", $this->createMobileUrl('index',array('t'=>1)), "warning");
}


/* 课程开启自定义链接，则跳转到自定义链接 */
if(!empty($buynow_info['mobile_link'])){
	header("Location:".$buynow_info['mobile_link']);
}


/* 检查是否完善个人信息 */
if($setting['mustinfo'] && $uid){
	$smsConfig = json_decode($setting['sms'], true);
	if($smsConfig['type']==1){
		$sms = $smsConfig['aliyun'];
	}elseif($smsConfig['type']==2){
		$sms = $smsConfig['qcloud'];
	}
	$user_info = json_decode($setting['user_info'], true);

	if(!empty($common_member_fields)){
		foreach($common_member_fields as $v){
			if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
				 $writemsg = true;
			}
		}
	}
}

/* 非直播课程 */
if($sectionid && $lesson['lesson_type']!=3){
	if(empty($section)){
		message("该章节不存在或已被删除", $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
	}

	if(!$play){
		$nobuyTip = $lesson_page['nobuyTip'] ? $lesson_page['nobuyTip'] : '请先购买课程再学习';
		message($nobuyTip, $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
	}

	/**
	 * 视频课程格式
	 * @sectiontype 1.视频章节 2.图文章节 3.音频课程 4、外链章节
	 * @savetype	0.其他存储 1.七牛存储 2.内嵌播放代码模式 3.腾讯云存储 4.阿里云点播 5.腾讯云点播 6.阿里云OSS
	 */
	if(in_array($section['sectiontype'], array('1','3'))){
		/* 已购买用户获取下一个章节id */
		if($show_isbuy){
			$next_sectionid = $site_common->getNextSectionid($section, $title_list);
		}

		/* 该章节上次学习信息 */
		$prev_record = pdo_fetch("SELECT playtime FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid AND sectionid=:sectionid ORDER BY addtime DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$id,'sectionid'=>$sectionid));

		/* 课程禁止防拖拽播放，则检查用户是否完成学习该章节 */
		$drag_play = $lesson['drag_play'];
		if(!$drag_play){
			$complete_study = pdo_get($this->table_playrecord, array('uniacid'=>$uniacid,'uid'=>$uid,'lessonid'=>$id,'sectionid'=>$sectionid,'is_end'=>1));
			if($complete_study){
				$drag_play = 1;
			}
		}

		if($section['savetype']==1){
			$qiniu = unserialize($setting['qiniu']);
			if($qiniu['https']==1){
				$section['videourl'] = str_replace("http://", "https://", $section['videourl']);
			}
			$section['videourl'] = $site_common->privateDownloadUrl($qiniu['access_key'],$qiniu['secret_key'],$section['videourl']);

		}elseif($section['savetype']==3){
			$qcloud		 = unserialize($setting['qcloud']);
			if($qcloud['https']==1){
				$section['videourl'] = str_replace("http://", "https://", $section['videourl']);
			}
			$section['videourl'] = $site_common->tencentDownloadUrl($qcloud, $section['videourl']);

		}elseif($section['savetype']==4){
			$aliyun = unserialize($setting['aliyunvod']);
			$aliyunVod = new AliyunVod($aliyun);

			if($section['sectiontype']==3){/* 音频章节 */
				try {
					$section['videourl'] = $aliyunVod->get_play_info($section['videourl']);
				} catch (Exception $e) {
					message("播放失败，错误原因:".$e->getMessage(), "", "warning");
				}

				if(empty($section['videourl'])){
					message("获取播放地址失败，请联系管理员", "", "warning");
				}

			}else{/* 视频章节 */
				$file = pdo_get($this->table_aliyun_upload, array('uniacid'=>$uniacid,'videoid'=>$section['videourl']), array('name'));
				$suffix = substr(strrchr($file['name'], '.'), 1);
				$audio = strtolower($suffix)=='mp3' ? true : false;

				try {
					$response = $aliyunVod->getVideoPlayAuth($section['videourl']);
					$playAuth = $response->PlayAuth;
					if(!$audio){
						$m3u8_format = $aliyunVod->get_m3u8_format($section['videourl']);
					}
				} catch (Exception $e) {
					message("播放失败，错误原因:".$e->getMessage(), "", "warning");
				}
			}
			
		}elseif($section['savetype']==5){
			$qcloudvod = unserialize($setting['qcloudvod']);
			$newqcloudVod = new QcloudVod($qcloudvod['secret_id'], $qcloudvod['secret_key']);

			
			if($section['sectiontype']==3){/* 音频章节 */
				$section['videourl'] = $newqcloudVod->getUrlPlaySign($qcloudvod['safety_key'],$section,$exper='');
				if(empty($section['videourl'])){
					message("获取播放地址失败，请联系管理员", "", "warning");
				}

			}else{/* 视频章节 */
				try {
					$qcloudVodRes = $newqcloudVod->getPlaySign($qcloudvod['safety_key'], $qcloudvod['appid'], $section['videourl'], $qcloudvod['player_name']);
				} catch (Exception $e) {
					message("播放失败，错误原因:".$e->getMessage(), "", "warning");
				}
			}
		}elseif($section['savetype']==6){
			include_once dirname(__FILE__).'/../common/AliyunOSS.php';

			$aliyunoss = unserialize($setting['aliyunoss']);
			$params = parse_url($section['videourl']);
			$com_name = trim($params['path'], '/');

			$ossClient = new AliyunOSS($aliyunoss['access_key_id'], $aliyunoss['access_key_secret'], $aliyunoss['endpoint']);
			$default_url = $ossClient->getSignUrl($aliyunoss['bucket'], $com_name, $timeout=7200);
			$section['videourl'] = $site_common->aliyunOssPlayUrl($default_url, $aliyunoss);
		}

		/* 验证访问密码 */
		if($section['password'] && $_W['ispost']){
			$visit_password = trim($_GPC['visit_password']);
			if($section['password'] == $visit_password){
				session_start();
				$_SESSION[$uniacid.'_'.$id.'_'.$sectionid] = true;
			}else{
				message("密码错误，请重新输入", "", "warning");
			}
		}
	}
	
	if($section['sectiontype']==4){
		header("Location:".$section['videourl']);
	}
}elseif(!$sectionid && $lesson['lesson_type']!=3){
	/* 最近学习章节 */
	$record = pdo_fetch("SELECT sectionid FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid AND lessonid=:lessonid ORDER BY addtime DESC LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':lessonid'=>$id));
	$hissection = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'id'=>$record['sectionid']), array('title'));
	if(!empty($hissection)){
		$hisplayurl = $this->createMobileUrl("lesson",array('id'=>$id,'sectionid'=>$record['sectionid']));
	}
}

/* 非直播课程，脚部广告 */
if($lesson['lesson_type']!=3){
	$avd = $this->readCommonCache('fy_lesson_'.$uniacid.'_lesson_adv');
	if(empty($avd)){
		$avd = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND is_show=:is_show AND is_pc=:is_pc AND banner_type=:banner_type ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':is_show'=>1,':is_pc'=>0, 'banner_type'=>1));
		cache_write('fy_lesson_'.$uniacid.'_lesson_adv', $avd);
	}
	if(!empty($avd)){
		$advs = array_rand($avd,1);
		$advs = $avd[$advs];
	}
}

/* 学习人数 */
$lesson['buyTotal'] = $lesson['buynum'] + $lesson['virtual_buynum'] + $lesson['vip_number'] + $lesson['teacher_number'];
if($lesson['price']==0){
	$lesson['buyTotal'] += $lesson['visit_number'];
}
if($lesson['lesson_type'] == 1){
	$lesson['buyTotal'] = $lesson['buynum'] + $lesson['virtual_buynum'];
}

/* 构造分享信息开始 */
$share_info = json_decode($lesson['share'], true);    /* 课程单独分享信息 */
$sharelesson = unserialize($comsetting['sharelesson']);  /* 全局课程分享信息 */

if(!empty($share_info['title'])){
	$sharelesson['title'] = $share_info['title'];
}else{
	$sharelesson['desc'] = $sharelesson['title'];
	if(empty($section)){
		$sharelesson['title'] = $lesson['bookname'];
	}else{
		$sharelesson['title'] = $section['title'].' - '.$lesson['bookname'];
	}
}

$sharelesson['desc'] = $share_info['descript'] ? $share_info['descript'] : str_replace("【课程名称】","《".$title."》",$sharelesson['desc']);
$sharelesson['images'] = $share_info['images'] ? $share_info['images'] : $sharelesson['images'];
if(empty($sharelesson['images'])){
	$sharelesson['images'] = $lesson['images'];
}
$sharelesson['images'] = $_W['attachurl'].$sharelesson['images'];

$sharelesson['link'] = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$id,'sectionid'=>$sectionid,'uid'=>$uid));
/* 构造分享信息结束 */


if($op=='display'){
	/* 详情、目录默认显示 */
	if($lesson['lesson_type']==1 && !$lesson['appoint_dir']){
		/* 报名课程 */
		$show_details = true;
	}else{
		if($section_keyword){
			/* 搜索章节时显示目录 */
			$show_dir = true;
		}else{
			if($lesson['lesson_show']==1 || $section['content']){
				$show_details = true;
			}elseif($lesson['lesson_type']==0 && !$section['content'] && $sectionid){
				$show_dir = true;
			}elseif($lesson['lesson_show']==2 && !$section['content']){
				$show_dir = true;
			}else{
				if($setting['lesson_show']==0){
					$show_details = true;
				}
				if(!$section['content'] && $setting['lesson_show']==1){
					$show_dir = true;
				}
			}
		}
	}

	/* 关联的练习或考试 */
	if(pdo_tableexists($this->table_exam_course) && pdo_tableexists($this->table_exam_examine)){
		$condition = " uniacid=:uniacid AND lesson_ids LIKE :lesson_ids ";
		$params[':uniacid'] = $uniacid;
		$params[':lesson_ids'] = '%"'.$id.'"%';
		$course_list = pdo_fetchall("SELECT id,title,images FROM " .tablename($this->table_exam_course). " WHERE {$condition} ORDER BY displayorder DESC,id DESC", $params);
		$examine_list = pdo_fetchall("SELECT id,title,images FROM " .tablename($this->table_exam_examine). " WHERE {$condition} ORDER BY displayorder DESC,id DESC", $params);
	}

	/* 课件资料 */
	$document_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_document). " WHERE uniacid=:uniacid AND lessonid=:lessonid ORDER BY displayorder DESC, id ASC ", array(':uniacid'=>$uniacid,'lessonid'=>$id));

	/* 评价开关 */
	if($isbuy['status']==1){
		$already_evaluate = pdo_fetch("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uid=:uid AND lessonid=:lessonid AND orderid>:orderid ", array(':uid'=>$uid,':lessonid'=>$id,':orderid'=>0));
		if(empty($already_evaluate)){
			$allow_evaluate = true;
			$evaluate_url   = $this->createMobileUrl("evaluate",array('op'=>'display',"orderid"=>$isbuy['id']));
		}
	}else{
		/* 课程价格为免费 或 会员为VIP身份且课程权限为VIP会员免费观看 */
		if($lesson['price']==0 || $freeEvaluate){
			$already_evaluate = pdo_fetch("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uid=:uid AND lessonid=:lessonid AND orderid=:orderid ", array(':uid'=>$uid,':lessonid'=>$id,':orderid'=>0));
			if(empty($already_evaluate)){
				$allow_evaluate = true;
				$evaluate_url   = $this->createMobileUrl("evaluate",array('op'=>'freeorder',"lessonid"=>$id));
			}
		}
	}
	/* 评价总数 */
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE lessonid=:lessonid AND status=:status", array(':lessonid'=>$id,':status'=>1));
	 
	/*生成课程参数二维码*/
	$dirpath = "../attachment/images/{$uniacid}/";
	$this->checkdir($dirpath);
	$dirpath .="fy_lessonv2/";
	$this->checkdir($dirpath);
	$dirpath .="followLesson/";
	$this->checkdir($dirpath);

	$imagepath = $dirpath."lesson_{$id}.jpg";
	if((!file_exists($imagepath) || time() > filectime($imagepath)+7*86400) && $isfollow['follow_lesson'] && $userAgent){
		$codeArray = array (
			'expire_seconds' => 2592000,
			'action_name' => 'QR_LIMIT_STR_SCENE',
			'action_info' => array (
				'scene' => array (
					'scene_str' => "lesson_{$id}",
				),
			),
		);
		$account_api = WeAccount::create();
		$res = $account_api->barCodeCreateFixed($codeArray);
		if(!empty($res['ticket'])){
			$qrcodeurl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$res['ticket'];

			$site_common->saveImage($qrcodeurl, $dirpath."qrcode_{$id}.", 'lesson_qrcode');
			$site_common->resize($dirpath."qrcode_{$id}.jpg", $dirpath."qrcode_{$id}.jpg", "170", "170", "100");
			$site_common->img_water_mark("../addons/fy_lessonv2/static/mobile/{$template}/images/lesson-qrcode-bg.jpg", $dirpath."qrcode_{$id}.jpg", $dirpath, "lesson_{$id}.jpg", "16", "24");
			unlink($dirpath."qrcode_{$id}.jpg");
		}
	}

	/* 随机获取客服列表 */
	$service = json_decode($lesson['service'], true);
	if(empty($service)){
		$service = json_decode($setting['qun_service'], true);
	}
	if(!empty($service)){
		$rand = rand(0, count($service)-1);
		$now_service = $service[$rand];
	}
	if(!empty($now_service) && $_GPC['ispay']==1){
		$show_service = true;
	}


	/* 课程详情页海报入口 */
	$poster_show = false;
	if($setting['lesson_poster_status']==1 && ($lesson['poster_bg'] || $lesson['images'])){
		$poster_show = true;
	}elseif($setting['lesson_poster_status']==2 && $lesson['poster_bg']){
		$poster_show = true;
	}

	/* 好评率 */
	$evaluate_page = $common['evaluate_page'];
	$evaluate_score = pdo_get($this->table_evaluate_score, array('lessonid'=>$id));
	$evaluate_score['score'] = $evaluate_score['score']*100;
	if(!$evaluate_score){
		$evaluate_score['score']			= $lesson['score']*100;
		$evaluate_score['global_score']		= '5.00';
		$evaluate_score['content_score']	= '5.00';
		$evaluate_score['understand_score'] = '5.00';
	}
	$evaluate_score['score'] = $evaluate_score['score']>100 ? 100 : $evaluate_score['score'];
}

/* 图文章节获取上一节和下一节 */
if($section['sectiontype']==2){
	if($section['title_id']){
		$section_sort = pdo_fetchall("SELECT id,parentid,title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>$section['title_id'],':status'=>1));
		foreach($section_sort as $key=>$value){
			if($value['id']==$section['id']){
				$prev_article = $section_sort[$key-1];
				$next_article = $section_sort[$key+1];
			}
		}
		foreach($title_list as $key=>$value){
			if($value['title_id']==$section['title_id']){
				$prev_title = $title_list[$key-1];
				$next_title = $title_list[$key+1];
			}
		}
		if(!$prev_article){
			$prev_article = pdo_fetch("SELECT id,parentid,title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC LIMIT 1", array(':parentid'=>$id,':title_id'=>$prev_title['title_id'],':status'=>1));
		}
		if(!$next_article){
			$next_article = pdo_fetch("SELECT id,parentid,title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC LIMIT 1", array(':parentid'=>$id,':title_id'=>$next_title['title_id'],':status'=>1));
		}
	}else{
		$section_sort = pdo_fetchall("SELECT id,parentid,title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>0,':status'=>1));
		foreach($section_sort as $key=>$value){
			if($value['id']==$section['id']){
				$prev_article = $section_sort[$key-1];
				$next_article = $section_sort[$key+1];
			}
		}
	}

	/* 图文章节且试听时间大于0，表示仅可以观看部分内容 */	
	if($section['is_free'] && $section['sectiontype']==2 && $section['test_time']){
		$play = false;
	}

	if(!$play && !$plays && $section['is_free']){
		$free_article = 1; /* 试听图文部分内容 */
		$content = htmlspecialchars_decode($section['content']);
		$CP = new cutpage($content, 1000, 1);
		$pageContent = $CP->cut_str();
	}

	/* 图文章节 */
	include $this->template("../mobile/{$template}/lesson_article");
	exit();
}

/* 付费课程且允许展示虚拟购买信息 */
if(!$sectionid && !$_GPC['play'] && $common['virtual_buy'] && $lesson['price']>0){
	$rand_member_list = $site_common->getRandMember();
	$rand_total = count($rand_member_list);

	if($rand_total >=20){
		$rand_arr = array_rand($rand_member_list, 20);
	}else{
		$rand_arr = array_rand($rand_member_list, $rand_total);
	}

	$virtual_buyinfo = array();
	foreach($rand_arr as $v){
		$bug_arr = array('已购买','下单成功','已购买','已购买');
		$virtual_buyinfo[] = $rand_member_list[$v]. $bug_arr[array_rand($bug_arr, 1)];
	}
}

/* 关联商品 */
$lesson['like_goods_ids'] = json_decode($lesson['like_goods_ids'], true);
if($lesson['like_goods_ids']){
	foreach($lesson['like_goods_ids'] as $v){
		$goods = pdo_get($this->table_shop_goods, array('uniacid'=>$uniacid,'id'=>$v,'status'=>1), array('id','title','cover','goods_type','sell_type','market_price','price','integral','sales','virtual_sales'));
		if($goods){
			$goods['show_sales'] = $goods['sales'] + $goods['virtual_sales'];

			if($goods['price']*100 == intval($goods['price'])*100){
				$goods['price'] = intval($goods['price']);
			}elseif($goods['price']*10 == round($goods['price'],1)*10){
				$goods['price'] = round($goods['price'],1);
			}

			if($goods['sell_type'] == 1){
				$goods['show_price'] = $goods['integral'].'积分';
			}elseif($goods['sell_type'] == 2){
				$goods['show_price'] = '￥'.$goods['price'];
			}elseif($goods['sell_type'] == 3){
				$goods['show_price'] = '￥'.$goods['price'].'+'.$goods['integral'].'积分';
			}

			$like_goods_list[] = $goods;
			unset($goods);
		}
	}
}

/* 直播课程 */
if($lesson['lesson_type']==3){
	$like_goods_list = array_reverse($like_goods_list);
	$video_live = json_decode($setting['video_live'], true);
	$live_info = json_decode($lesson['live_info'], true);
	$starttime = strtotime($live_info['starttime']);
	$endtime = strtotime($live_info['endtime']);
	if(time() < $starttime){
		//未开始
		$count_down  = $starttime - time();
		$live_status = 0;

		if($uid && $_GPC['play']){
			message("直播未开始，请稍等...", "", "warning");
		}
	}elseif(time() > $endtime){
		//已结束
		$icon_live_status = 'icon-live-ended';
		$live_status = -1;

		if($uid && $_GPC['play']){
			message("直播已结束，下次早点来哦", "", "warning");
		}
	}elseif(time() > $starttime && time() < $endtime){
		//直播中
		$icon_live_status = 'lesson-live-starting';
		$live_status = 1;
	}
	//获取直播地址
	if($_GPC['play']){
		$live_url = $site_common->getLiveUrl($setting, $live_info, $play_type='mobile');
	}

	if($_GPC['req_login']){
		checkauth();
	}
	if(!$play && $_GPC['play']){
		if(!$uid){
			checkauth();
		}
		message("请先购买课程再学习", "", "warning");
	}

	/* 验证访问密码 */
	if($_GPC['play'] && $live_info['password'] && $_W['ispost']){
		$visit_password = trim($_GPC['visit_password']);
		if($live_info['password'] == $visit_password){
			session_start();
			$_SESSION[$uniacid.'_'.$id] = true;
		}else{
			message("密码错误，请重新输入", "", "warning");
		}
	}

	/* 聊天室配置 */
	$im_config = json_decode($setting['im_config'], true);

	if($uid && $live_info['chatroom']){
		/* 当前用户 */
		$nickname = $member['nickname'] ? $member['nickname'].'('.$uid.')' : '编号'.$uid.'的用户';
		
		if($im_config['type']==2){
			/* 奥点云IM */
			$room_status = true;
			$api = new TisApi($im_config['aodianyun']['accessId'], $im_config['aodianyun']['accessKey']);

			/* 聊天室状态 */
			$chatroom = pdo_fetch("SELECT * FROM " .tablename($this->table_live_chatroom). " WHERE uniacid=:uniacid AND type=:type AND lessonid=:lessonid ORDER BY id ASC", array(':uniacid'=>$uniacid,':type'=>2,':lessonid'=>$id));
			$tisId = $chatroom['roomid'];
			if(empty($tisId)){
				$aodianyun = array(
					's_key'		  => $im_config['aodianyun']['s_key'],
					'filterKeys'  => $im_config['aodianyun']['filterKeys'],
					'description' => $uniacid.'_'.$id.'_'.random(8),
				);
				$res = $api->createTisRoom($aodianyun);
				if($res['Flag']==100){
					$room_data = array(
						'uniacid'	=> $uniacid,
						'type'		=> 2,
						'lessonid'	=> $id,
						'roomname'	=> $aodianyun['description'],
						'roomid'	=> $res['id'],
						'addtime'	=> time(),
						'endtime'	=> strtotime($live_info['endtime']),
					);
					pdo_insert($this->table_live_chatroom, $room_data);
					$tisId = $res['id'];
				}else{
					$create_room_status = -1; //创建聊天室失败
				}
			}
			/* 黑白名单处理 */
			$allow_chat = true;
			$chatroom_uids = $live_info['chatroom_uids'];			
			if($live_info['chatroom_type']==0 && !in_array($uid,$chatroom_uids)){
				$allow_chat = false; /* 不在白名单之内 */
			}
			if($live_info['chatroom_type']==1 && in_array($uid,$chatroom_uids)){
				$allow_chat = false; /* 在黑名单之内 */
			}

			/* 聊天记录 */
			$chat_history = $site_common->getLiveHistoryChar($id, $tisId);

			/* 聊天轮播图 */
			if($live_info['banner']){
				foreach($live_info['banner'] as $k=>$v){
					$live_info['banner'][$k] = $_W['attachurl'].$v;
				}
			}else{
				$live_info['banner'] = array(MODULE_URL.'static/mobile/default/images/live-default-banner.png');
			}
		}
	}

	include $this->template("../mobile/{$template}/lesson_live");
}else{
	/* 关联课程 */
	if($lesson_config['like_lesson_switch']){
		if($lesson['like_lesson_type']){
			$like_list = $site_common->getLessonsLikeById($id);
		}else{
			$condition = " uniacid=:uniacid AND status=:status ";
			$params = array(
				':uniacid' => $uniacid,
				':status'  => 1,
			);
			if($lesson['like_lesson_content']){
				$condition .= " AND pid=:pid ";
				$params[':pid'] = $lesson['like_lesson_content'];
			}

			$like_list = pdo_fetchall("SELECT id,bookname,lesson_type,images,price,live_info,vipview FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} ORDER BY RAND() LIMIT 0,4", $params);
		}
		if($like_list){
			foreach($like_list as $k=>$v){
				$v['vipview'] = json_decode($v['vipview'], true);
				if($v['price']*100 == intval($v['price'])*100){
					$v['price'] = intval($v['price']);
				}elseif($v['price']*10 == round($v['price'],1)*10){
					$v['price'] = round($v['price'],1);
				}

				if($v['lesson_type'] == 3){
					$tmp_live_info = json_decode($v['live_info'], true);
					$tmp_starttime = strtotime($tmp_live_info['starttime']);
					$tmp_endtime = strtotime($tmp_live_info['endtime']);
					if(time() < $tmp_starttime){
						$v['icon_live_status'] = 'icon-live-nostart';
					}elseif(time() > $tmp_endtime){
						$v['icon_live_status'] = 'icon-live-ended';
					}elseif(time() > $tmp_starttime && time() < $tmp_endtime){
						$v['icon_live_status'] = 'icon-live-starting';
					}
				}

				$like_list[$k] = $v;
				unset($v);
			}
		}
	}

	include $this->template("../mobile/{$template}/lesson");
}
