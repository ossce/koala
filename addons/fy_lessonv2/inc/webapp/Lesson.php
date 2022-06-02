<?php
/**
 * 课程详情页
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$login_visit = json_decode($setting_pc['login_visit'], true);
if(in_array('lesson', $login_visit) && empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
	header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
}

$mobile = $_SESSION['fy_lessonv2_'.$uniacid.'_mobile'];
$nickname = $_SESSION['fy_lessonv2_'.$uniacid.'_nickname'];

$site_common->check_black_list('visit', $uid); /* 访问权限 */

$index_page  = $common['index_page'];  /* 首页字体 */
$lesson_page = $common['lesson_page']; /* 课程页面字体 */
$lesson_config = json_decode($setting['lesson_config'], true); /* 课程页面设置 */

/* 课程id */
$id = intval($_GPC['id']);
/* 播放章节id */
$sectionid = $_GPC['sectionid'];

$lesson = pdo_fetch("SELECT a.*,b.teacher,b.qq,b.qqgroup,b.qqgroupLink,b.weixin_qrcode,b.online_url,b.teacherphoto,b.teacherdes,b.uid AS teacher_uid FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
if(!$lesson || $lesson['status']==0 || $lesson['status']==2){
	message("该课程已下架，您可以看看其他课程", $_W['siteroot'].$uniacid."/index.html", "error");
}
$title = $lesson['bookname'];

/* 试听章节数量 */
$free_test = pdo_get($this->table_lesson_son, array('uniacid'=>$uniacid,'parentid'=>$id,'is_free'=>1));

/* 用户信息 */
if($uid){
	$member = pdo_fetch("SELECT a.*,b.follow,c.avatar,c.nickname,c.realname,c.mobile,c.msn,c.idcard,c.occupation,c.company,c.graduateschool,c.grade,c.address,c.education,c.position FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_fans). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_mc_members). " c ON a.uid=c.uid WHERE a.uid=:uid", array(':uid'=>$uid));
}

/* 课程总数 */
$lessonNumber = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE teacherid=:teacherid", array(':teacherid'=>$lesson['teacherid']));

/* 点播章节 */
if($sectionid){
	$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND id=:id AND status=:status LIMIT 1", array(':parentid'=>$id,':id'=>$sectionid,':status'=>1));
	if(!$section){
		message("该章节不存在或已下架", $_W['siteroot'].$uniacid."/index.html", "error");
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


/* 分享课程信息 */
$mobile_site_root = $setting_pc['mobile_site_root'] ? $setting_pc['mobile_site_root'] : $_W['siteroot'];
$wap_url = $mobile_site_root."app/index.php?i={$uniacid}&c=entry&id={$id}&sectionid={$sectionid}&do=lesson&m=fy_lessonv2";

$share_alone = json_decode($lesson['share'], true);   /* 课程单独分享信息 */
$share_all = unserialize($comsetting['sharelesson']); /* 全局课程分享信息 */

if(!empty($share_alone['title'])){
	$share['title'] = $share_alone['title'];
}else{
	if(empty($section)){
		$share['title'] = $lesson['bookname'];
	}else{
		$share['title'] = $section['title'].' - '.$lesson['bookname'];
	}
}

$share['desc'] = $share_alone['descript'] ? $share_alone['descript'] : str_replace("【课程名称】","《".$title."》",$share_all['desc']);
if(!$share['desc']){
	$share['desc'] = mb_substr(strip_tags(html_entity_decode($lesson['descript'])), 0, 100);
}

$share['images'] = $share_alone['images'] ? $share_alone['images'] : $share_all['images'];
if(!$share['images']){
	$share['images'] = $lesson['images'];
}
$share['images'] = $_W['attachurl'].$share['images'];
$share['link'] = $_W['siteroot']."{$uniacid}/lesson.html?id={$id}&sectionid={$sectionid}&uid={$uid}";

$seo['keywords'] = $share_alone['keywords'];
$seo['description'] = $share_alone['description'];

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

/* 判断是否显示规格 */
$show_specprice = true;
if($setting['lesson_vip_status']==1){
	$vipview = json_decode($lesson['vipview'], true);
	if($vipview){
		$show_specprice = false;
	}
}

/* 显示折扣 */
$discount_lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_discount_lesson). " WHERE uniacid=:uniacid AND lesson_id=:lesson_id AND starttime<:time AND endtime>:time", array(':uniacid'=>$uniacid,':lesson_id'=>$id,':time'=>time()));
if($discount_lesson){
	foreach($spec_list as $k=>$v){
		$v['market_price'] = $v['spec_price'];
		$v['spec_price'] = round($v['spec_price']*0.01 * $discount_lesson['discount'], 2);
		$spec_list[$k] = $v;
	}
	$discount_endtime = date('Y/m/d H:i:s', $discount_lesson['endtime']);
	$diacount_price = explode('.', $spec_list[0]['spec_price']);
}

/* 课程详情页海报入口 */
$poster_show = false;
if($setting['lesson_poster_status']==1 && ($lesson['poster_bg'] || $lesson['images'])){
	$poster_show = true;
}elseif($setting['lesson_poster_status']==2 && $lesson['poster_bg']){
	$poster_show = true;
}

/* 分享赚取佣金 */
$lesson_commission = unserialize($lesson['commission']);
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
$lesson_qrcode_url = $mobile_site_root."app/index.php?i={$uniacid}&c=entry&lessonid={$id}&do=lessonqrcode&m=fy_lessonv2";

/* 购买按钮名称 */
$buynow_info = json_decode($lesson['buynow_info'], true);
$buynow_name = $buynow_info['buynow_name'] ? $buynow_info['buynow_name'] : $config['buynow_name'];
$study_name  = $buynow_info['study_name']  ? $buynow_info['study_name']  : $config['study_name'];

/* 非直播课程 */
if($lesson['lesson_type']!=3){
	/* 第一个章节 */
	$first_section = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status ORDER BY displayorder DESC,id ASC LIMIT 1", array(':parentid'=>$id,':status'=>1));
	/* 第一个试听章节 */
	$free_section = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status AND is_free=:is_free ORDER BY displayorder DESC,id ASC LIMIT 1", array(':parentid'=>$id,':status'=>1, ':is_free'=>1));
}


/* 免费学习的VIP等级 */
$lesson_vip_list = array();
if(is_array(json_decode($lesson['vipview'])) && $lesson['price']>0){
	foreach(json_decode($lesson['vipview']) as $v){
		$level = $site_common->getLevelById($v);
		if(!empty($level['level_name']) && $level['is_show']==1){
			$lesson_vip_list[] = $level;
		}
	}
}

/**
  $play  用户学习资格标识
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

if($uid){
	/* 查询是否购买该课程 */
	$isbuy = pdo_fetch("SELECT id,validity FROM " .tablename($this->table_order). " WHERE uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
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

	if($lesson['lesson_type']==0 || $lesson['lesson_type']==3){
		/* vip免费学习课程对于普通课程生效 */
		$memberVip_list = pdo_fetchall("SELECT level_id FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
		if(!empty($memberVip_list)){
			foreach($memberVip_list as $v){
				if(in_array($v['level_id'], json_decode($lesson['vipview']))){
					$play = true;
					$plays = true;
					$show_isbuy = true;
					$freeEvaluate = true; /* VIP免费评价标识 */
					$viplesson = true;    /* vip等级相应课程 */
					break;
				}
			}
		}
	}elseif($lesson['lesson_type']==1){
		/* 查询是否存在未核销的报名课程订单 */
		$apply_order = pdo_fetch("SELECT id,verify_number FROM " .tablename($this->table_order). " WHERE uid=:uid AND lesson_type=:lesson_type AND lessonid=:lessonid AND status>=:status AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lesson_type'=>1,':lessonid'=>$id,':status'=>1,':is_delete'=>0));
		if($apply_order){
			$verify_log = $site_common->getOrderVerifyLog($apply_order['id']);
			if($verify_log['count']<$apply_order['verify_number']){
				$show_qrcode = true;
			}
		}
	}

	/* 讲师自己课程免费 */
	$teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uid=:uid", array(':uid'=>$uid));
	if($lesson['teacherid'] == $teacher['id']){
		$play = true;
		$plays = true;
		$show_isbuy = true;
	}

	/* 已购买讲师服务 */
	$buy_teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND teacherid=:teacherid AND validity>:validity LIMIT 1", array(':uid'=>$uid, ':teacherid'=>$lesson['teacherid'], ':validity'=>time()));
	if($buy_teacher){
		$play = true;
		$plays = true;
		$show_isbuy = true;
	}

	/* 查询是否收藏课程 */
	$collect = pdo_get($this->table_lesson_collect, array('uniacid'=>$uniacid,'uid'=>$uid,'outid'=>$id,'ctype'=>1), array('id'));

	/* 增加会员访问足迹 */
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
	message("该课程已下架，您可以看看其他课程~", $_W['siteroot'].$uniacid."/index.html", "error");
}


/* 课程开启自定义链接，则跳转到自定义链接 */
if(!empty($buynow_info['pc_link'])){
	header("Location:".$buynow_info['pc_link']);
}


/* 学习和购买都需要完善信息 */
if($setting['mustinfo']){
	$user_info = json_decode($setting['user_info']);
	$jumpurl = $_W['siteroot']."{$uniacid}/memberInfo.html?lessonid={$id}&sectionid={$sectionid}&type=lesson";

	if(!empty($common_member_fields)){
		foreach($common_member_fields as $v){
			if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
				 $mustinfo = true;
			}
		}
	}
}

/* 购买讲师价格 */
$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$lesson['teacherid']));


/* 非直播课程 */
if($lesson['lesson_type']!=3){
	/* 已归纳课程目录的章节 */
	$title_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_title)." WHERE lesson_id=:lesson_id ORDER BY displayorder DESC,title_id ASC", array('lesson_id'=>$id));
	foreach($title_list as $k=>$v){
		$title_list[$k]['section'] = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>$v['title_id'],':status'=>1));

		if(empty($title_list[$k]['section'])){
			unset($title_list[$k]);
		}
	}

	/* 未归纳课程目录的章节 */
	$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND title_id=:title_id AND status=:status ORDER BY displayorder DESC,id ASC", array(':parentid'=>$id,':title_id'=>0,':status'=>1));

	/* 课程章节总数 */
	$section_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$id,':status'=>1));

	/* 评价总数 */
	$evaluate_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE lessonid=:lessonid AND status=:status", array(':lessonid'=>$id,':status'=>1));

	/* 好评率 */
	$evaluate_page = $common['evaluate_page'];
	$evaluate_score = pdo_get($this->table_evaluate_score, array('lessonid'=>$id));
	if(!$evaluate_score){
		$evaluate_score['score']			= $lesson['score']*100;
		$evaluate_score['global_score']		= '5.00';
		$evaluate_score['content_score']	= '5.00';
		$evaluate_score['understand_score'] = '5.00';
	}else{
		$evaluate_score['score'] = $evaluate_score['score']*100 > 100 ? 100 : $evaluate_score['score']*100;
	}
}


if($sectionid){
	if(!$play){
		$nobuyTip = $lesson_page['nobuyTip'] ? $lesson_page['nobuyTip'] : '请先购买课程再学习';
		message($nobuyTip, $_W['siteroot'].$uniacid."/lesson.html?id={$id}", "error");
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

		$systemType = $site_common->checkSystenType();
		if($section['savetype']==1){
			$qiniu = unserialize($setting['qiniu']);
			if($qiniu['https']==1){
				$section['videourl'] = str_replace("http://", "https://", $section['videourl']);
			}
			$section['videourl'] = $site_common->privateDownloadUrl($qiniu['access_key'],$qiniu['secret_key'],$section['videourl']);

		}elseif($section['savetype']==2){
			$section['videourl'] = str_replace("height=220px", "height=100%", $section['videourl']);

		}elseif($section['savetype']==3){
			$qcloud = unserialize($setting['qcloud']);
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
					message("播放失败，错误原因:".$e->getMessage(), "", "error");
				}

				if(empty($section['videourl'])){
					message("获取播放地址失败，请联系管理员", "", "error");
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
					message("播放失败，错误原因:".$e->getMessage(), "", "error");
				}
			}
		
		}elseif($section['savetype']==5){
			$qcloudvod = unserialize($setting['qcloudvod']);
			$newqcloudVod = new QcloudVod($qcloudvod['secret_id'], $qcloudvod['secret_key']);

			
			if($section['sectiontype']==3){/* 音频章节 */
				$section['videourl'] = $newqcloudVod->getUrlPlaySign($qcloudvod['safety_key'],$section,$exper='');
				if(empty($section['videourl'])){
					message("获取播放地址失败，请联系管理员", "", "error");
				}

			}else{/* 视频章节 */
				try {
					$qcloudVodRes = $newqcloudVod->getPlaySign($qcloudvod['safety_key'], $qcloudvod['appid'], $section['videourl'], $qcloudvod['player_name']);
				} catch (Exception $e) {
					message("播放失败，错误原因:".$e->getMessage(), "", "error");
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

		if($section['sectiontype']==3){
			$audio_bg_list = $webAppCommon->getLessonAudioBg();
			$audio_bg = $audio_bg_list[rand(0, count($audio_bg_list)-1)];
			$audio_bg_pic = $audio_bg['picture'] ? $_W['attachurl'].$audio_bg['picture'] : '';
		}

		/* 验证访问密码 */
		if($section['password'] && $_W['ispost']){
			$visit_password = trim($_GPC['visit_password']);
			if($section['password'] == $visit_password){
				session_start();
				$_SESSION[$uniacid.'_'.$id.'_'.$sectionid] = true;
			}else{
				message("密码错误，请重新输入");
			}
		}
	}
	
	/* 图文章节 */
	if($section['sectiontype']==2){
		$lastAndNext = $site_common->getArticleLastAndNext($section, $title_list);

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

		include $this->template("../webapp/{$template}/lessonArticle");
		exit();
	}

	/* 外链章节 */
	if($section['sectiontype']==4){
		header("Location:".$section['videourl']);
	}
}

/* 章节图标集 */
$section_icon = array(
	'1' => 'icon-video',
	'2' => 'icon-article',
	'3' => 'icon-audio',
	'4' => 'icon-urllink',
);

if($op=='display'){
	/* 详情、目录默认显示 */
	if($lesson['lesson_type']==1 && !$lesson['appoint_dir']){
		/* 报名课程 */
		$show_details = true;
	}else{
		if($lesson['lesson_show']==1 || ($lesson['lesson_show']!=1 && $section['content'])){
			$show_details = true;
		}elseif($lesson['lesson_show']==2 && !$section['content']){
			$show_dir = true;
		}else{
			if($section['content'] || $setting['lesson_show']==0){
				$show_details = true;
			}
			if(!$section['content'] && $setting['lesson_show']==1){
				$show_dir = true;
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
}

//允许https站点请求http资源
if(($lesson['lesson_type']==3 && $_W['sitescheme']=='https://') || ($lesson['lesson_type']!=3 && $section['savetype']==0 && $_W['sitescheme']=='https://')){
	header("Content-Security-Policy:upgrade-insecure-requests");
}

/* 关联商品 */
$lesson['like_goods_ids'] = json_decode($lesson['like_goods_ids'], true);
if($lesson['like_goods_ids']){
	/* 商品二维码保存路径 */
	$dirpath = "../attachment/images/{$uniacid}/";
	$this->checkdir($dirpath);
	$dirpath .="fy_lessonv2/";
	$this->checkdir($dirpath);
	$dirpath .="lessonGoods/";
	$this->checkdir($dirpath);
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

			$imagepath = $dirpath."goods_{$v}.jpg";
			if((!file_exists($imagepath) || time() > filectime($imagepath)+7*86400)){
				$goods_url = $setting_pc['mobile_site_root']."/app/index.php?i={$uniacid}&c=entry&id={$v}&do=shopgoods&m=fy_lessonv2_plugin_shop";

				include_once IA_ROOT."/framework/library/qrcode/phpqrcode.php";
				QRcode::png($goods_url, $imagepath, 'L', '8', 2);
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
			message('直播未开始，请稍等...');
		}
	}elseif(time() > $endtime){
		//已结束
		$icon_live_status = 'icon-live-ended';
		$live_status = -1;

		if($uid && $_GPC['play']){
			message('直播已结束，下次早点来哦');
		}
	}elseif(time() > $starttime && time() < $endtime){
		//直播中
		$icon_live_status = 'lesson-live-starting';
		$live_status = 1;
	}
	//获取直播地址
	if($_GPC['play']){
		$live_url = $site_common->getLiveUrl($setting, $live_info, $play_type='pc');
	}

	if($_GPC['req_login']){
		checkauth();
	}

	$login_url = $_W['siteroot']."{$uniacid}/login.html?refurl=".urlencode($_W['siteroot']."/{$uniacid}/lesson.html?id={$id}&play=".$_GPC['play']);
	if(!$play && $_GPC['play']){
		if(!$uid){
			 header("Location:". $login_url);
		}
		message("请先购买课程再学习");
	}

	/* 验证访问密码 */
	if($_GPC['play'] && $live_info['password'] && $_W['ispost']){
		$visit_password = trim($_GPC['visit_password']);
		if($live_info['password'] == $visit_password){
			session_start();
			$_SESSION[$uniacid.'_'.$id] = true;
		}else{
			message("密码错误，请重新输入");
		}
	}

	/* 聊天室配置 */
	$im_config = json_decode($setting['im_config'], true);

	if($uid && $live_status==1 && $live_info['chatroom']){
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
			}
		}
	}

	include $this->template("../webapp/{$template}/lesson_live");
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

			$like_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} ORDER BY RAND() LIMIT 0,6", $params);
		}
		if($like_list){
			foreach($like_list as $k=>$v){
				$v['vipview'] = json_decode($v['vipview'], true);
				if($v['price']*100 == intval($v['price'])*100){
					$v['price'] = intval($v['price']);
				}elseif($v['price']*10 == round($v['price'],1)*10){
					$v['price'] = round($v['price'],1);
				}

				$v['buynum_total'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
				if($v['price']==0){
					$v['buynum_total'] += $v['visit_number'];
				}

				if($v['lesson_type']==1){
					$buynow_info = json_decode($v['buynow_info'], true);
					if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
						$v['ico_name'] = 'ico-appointed';
					}

					/* 重新计算报名课程学习人数 */
					$v['buynum_total'] = $v['buynum']+$v['virtual_buynum'];
				}elseif($v['lesson_type'] == 3){
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

	include $this->template("../webapp/{$template}/lesson");
}


?>