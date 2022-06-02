<?php
/**
 * 课程海报
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();

$title = "课程海报";
$uid = $_W['member']['uid'];
$lessonid = intval($_GPC['lessonid']);/* 课程id */

if($_GPC['op']=='delete'){
	$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/lessonPoster/*");
	foreach($files as $file) {
		if(strstr($file, "lesson_{$lessonid}_")){
			unlink($file);
		}
	}
	header("Location:".$this->createMobileUrl('lessonqrcode', array('lessonid'=>$lessonid)));
}

$member = pdo_fetch("SELECT a.*,b.avatar,b.nickname AS mc_nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

if(empty($member['avatar'])){
	$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
	$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
}

$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id AND status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$lessonid, ':status'=>0));

if(empty($lesson)){
	message("该课程已下架，您可以看看其他课程", $this->createMobileUrl('index',array('t'=>1)), "warning");
}
if($setting['lesson_poster_status']==0){
	message("课程海报功能未开启", "", "warning");
}
if($setting['lesson_poster_status']==2 && !$lesson['poster_bg']){
	message("该课程未开启海报功能", "", "warning");
}

if($lesson['poster_bg']){
	$poster_list[0]['poster_bg'] = $lesson['poster_bg'];
	$poster_list[0]['poster_setting'] = $lesson['poster_setting'];
}else{
	$poster_list = cache_load('fy_lesson_'.$uniacid.'_lesson_poster_list');
	if(empty($poster_list)){
		$poster_list = pdo_getall($this->table_poster, array('uniacid'=>$uniacid,'poster_type'=>2));
		if(empty($poster_list)){
			$poster_list[0]['poster_default'] = true;
			$poster_list[0]['poster_bg'] = MODULE_URL."static/mobile/{$template}/images/lesson_default_poster.png";
			$tmp_poster_setting = array(
				'0' => array(
					'type'	 => 'cover',
					'width'  => 225,
					'height' => 137,
					'left'   => 48,
					'top'    => 79,
				),
				'1' => array(
					'type'	 => 'title',
					'left'   => 55,
					'top'    => 216,
					'size'	 => 20,
					'width'	 => 220,
					'rgb'    => $site_common->hexTorgb('#D24916'),
				),
				'2' => array(
					'type'	 => 'head',
					'width'  => 32,
					'height' => 32,
					'left'   => 38,
					'top'    => 29,
				),
				'3' => array(
					'type'	 => 'nickname',
					'left'   => 75,
					'top'    => 19,
					'size'	 => 12,
					'rgb'    => $site_common->hexTorgb('#D24916'),
				),
				'4' => array(
					'type'	 => 'qr',
					'width'  => 90,
					'height' => 90,
					'left'   => 120,
					'top'    => 283,
				),
			);
			$poster_list[0]['poster_setting'] = json_encode($tmp_poster_setting);
		}
		cache_write('fy_lesson_'.$uniacid.'_lesson_poster_list', $poster_list);
	}
}


/* 查询历史推广订单 */
$order = pdo_get($this->table_order, array('uid'=>$uid,'lessonid'=>$lessonid,'paytype'=>'recgive','is_delete'=>0));

/* 当前海报为第几张 */
$poster_no = intval($_GPC['poster_no']);
$poster_no = $poster_no <= count($poster_list)-1 ? $poster_no : 0;

$poster_setting = json_decode($poster_list[$poster_no]['poster_setting'], true);
foreach($poster_setting as $item){
	if($item['type']=='cover'){
		$poster_cover['left'] = $item['left'] * 2;
		$poster_cover['top'] = $item['top'] * 2;
		$poster_cover['width'] = $item['width'] * 2;
		$poster_cover['height'] = intval($poster_cover['width']*73/120);
	}
	if($item['type']=='title'){
		$poster_title['left'] = $item['left'] * 2;
		$poster_title['top'] = ($item['top'] * 2)+45;
		$poster_title['width'] = $item['width'] * 2;
		$poster_title['size'] = intval($item['size'])*1.5;
		$poster_title['rgb'] = $site_common->hexTorgb($item['color']);
	}
	if($item['type']=='head'){
		$poster_head['left'] = $item['left'] * 2;
		$poster_head['top'] = $item['top'] * 2;
		$poster_head['width'] = $item['width'] * 2;
		$poster_head['height'] = $item['height'] * 2;
	}
	if($item['type']=='nickname'){
		$poster_name['left'] = $item['left'] * 2;
		$poster_name['top'] = $item['top'] * 2;
		$poster_name['size'] = intval($item['size'])*1.5;
		$poster_name['rgb'] = $site_common->hexTorgb($item['color']);
	}
	if($item['type']=='qr'){
		$poster_qr['left'] = $item['left'] * 2;
		$poster_qr['top'] = $item['top'] * 2;
		$poster_qr['width'] = $item['width'] * 2;
		$poster_qr['height'] = $item['height'] * 2;
	}
}


/* 设置字体的路径 */
$font = MODULE_ROOT."/static/public/ttf/Alibaba-PuHuiTi-Regular.ttf";

/* 检查目录是否存在 */
$dirpath = "../attachment/images/{$uniacid}/";
$this->checkdir($dirpath);
$dirpath .="fy_lessonv2/";
$this->checkdir($dirpath);
$dirpath .="lessonPoster/";
$this->checkdir($dirpath);


$imagepath = $dirpath."lesson_{$lessonid}_uid_{$uid}_".$poster_no."_ok.png";
if(!file_exists($imagepath) || $comsetting['qrcode_cache']==0 || filectime($imagepath) < time()-86400){
	set_time_limit(60); 
	ignore_user_abort(true);

	$bgimg = $dirpath."lesson_{$lessonid}_posterbg_".$poster_no.".jpg";
	if($lesson['poster_bg']){
		if(!file_exists($bgimg)){
			$site_common->saveImage($_W['attachurl'].$lesson['poster_bg'], $dirpath."lesson_{$lessonid}_posterbg_".$poster_no.".", '');
		}
	}else{
		/* 背景图片 */
		if(!file_exists($bgimg)){
			$poster_bg_url = $poster_list[$poster_no]['poster_default'] ? $poster_list[$poster_no]['poster_bg'] : $_W['attachurl'].$poster_list[$poster_no]['poster_bg'];
			$site_common->saveImage($poster_bg_url, $dirpath."lesson_{$lessonid}_posterbg_".$poster_no.".", '');

			//加封面水印
			if($poster_cover){		
				$lesson_cover = $dirpath."lesson_{$lessonid}_cover.jpg";
				$site_common->saveImage($_W['attachurl'].$lesson['images'], $dirpath."lesson_{$lessonid}_cover.", '');
				$site_common->resize($lesson_cover, $lesson_cover, $poster_cover['width'], $poster_cover['height'], "100");

				$res = $site_common->img_water_mark($bgimg, $lesson_cover, $dirpath, "lesson_{$lessonid}_posterbg_".$poster_no.".jpg", $poster_cover['left'], $poster_cover['top']);
			}
		}

		//加课程标题
		if($poster_title){
			$title_arr = $site_common->autoLineSplit($lesson['bookname'], $font, $poster_title['size'], 'utf-8', $poster_title['width']);

			$info = getimagesize($bgimg);
			/* 通过编号获取图像类型 */
			$type = image_type_to_extension($info[2],false);
			/* 图片复制到内存 */
			if($type=='jpg' || $type=='jpeg'){
				$image = imagecreatefromjpeg($bgimg);
			}else{
				$image = imagecreatefrompng($bgimg);
			}
			/* 设置标题字体颜色和透明度 */
			$title_color = imagecolorallocatealpha($image, $poster_title['rgb']['r'], $poster_title['rgb']['g'], $poster_title['rgb']['b'], 0);

			/* 写入文字 */
			$fun = "image".$type;
			foreach($title_arr as $k=>$v){
				if($k>1) break;
				imagettftext($image, $poster_title['size'], 0, $poster_title['left'], $poster_title['top']+($k*45), $title_color, $font, $v);
				$fun($image, $dirpath."lesson_{$lessonid}_posterbg_".$poster_no.".jpg");
			}
		}
	}
	
	
	/* 二维码图片 */
	if($poster_qr){
		$errorCorrectionLevel = 'L';  /* 纠错级别：L、M、Q、H */
		$qrcode = $dirpath."lesson_{$lessonid}_uid_{$uid}.png"; /* 生成的文件名 */
		$lessonUrl = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$lessonid,'uid'=>$uid));

		include(IA_ROOT."/framework/library/qrcode/phpqrcode.php");
		QRcode::png($lessonUrl, $qrcode, $errorCorrectionLevel, $poster_qr['size']=5, 3);
		$site_common->resize($qrcode, $qrcode, $poster_qr['width'], $poster_qr['height'], "100");
		
		$savefield = $site_common->img_water_mark($bgimg, $qrcode, $dirpath, "lesson_{$lessonid}_uid_{$uid}_".$poster_no."_ok.png", $poster_qr['left'], $poster_qr['top']);
	}
	
	/* 合成头像 */
	if($poster_head){
		if(empty($member['avatar'])){
			$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
			$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
		}
		
		$suffix = $site_common->saveImage($avatar, $dirpath."avatar_{$uid}.", 'avatar');

		$avatar_size = filesize($dirpath."avatar_{$uid}.".$suffix);
		if($avatar_size==0){
			message("获取头像失败，请在个人中心点击头像更新", $this->createMobileUrl('self'), "warning");
		}

		if($suffix=='png'){
			$im = imagecreatefrompng($dirpath."avatar_{$uid}.".$suffix);
		}elseif($suffix=='jpeg' || $suffix=='jpg'){
			$im = imagecreatefromjpeg($dirpath."avatar_{$uid}.".$suffix);
		}else{
			$im = imagecreatefromjpeg(MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg");
		}
		imagejpeg($im, $dirpath."avatar_{$uid}.jpg");
		imagedestroy($im);
		
		$site_common->resize($dirpath."avatar_{$uid}.{$suffix}", $dirpath."avatar_{$uid}.{$suffix}", $poster_head['width'], $poster_head['height'], "100");
		$site_common->circularImg($dirpath."avatar_{$uid}.{$suffix}", $dirpath."avatar_{$uid}.{$suffix}");
		$savefield = $site_common->mergerImg($savefield, $dirpath."avatar_{$uid}.{$suffix}", $poster_head['left'], $poster_head['top'], $savefield);
	}

	$info = getimagesize($savefield);
	/* 通过编号获取图像类型 */
	$type = image_type_to_extension($info[2],false);
	/* 图片复制到内存 */
	if($type=='jpg' || $type=='jpeg'){
		$image = imagecreatefromjpeg($savefield);
	}else{
		$image = imagecreatefrompng($savefield);
	}

	/* 合成昵称 */
	if($poster_name){
		/* 设置字体颜色和透明度 */
		$color = imagecolorallocatealpha($image, $poster_name['rgb']['r'], $poster_name['rgb']['g'], $poster_name['rgb']['b'], 0);
		/* 写入文字 */
		$fun = $dirpath."lesson_{$lessonid}_uid_{$uid}_".$poster_no."_ok.png";
		imagettftext($image, $poster_name['size'], 0, $poster_name['left'], $poster_name['top']+45, $color, $font, $member['mc_nickname']);
	}

	/* 保存图片 */
	$fun = "image".$type;
	$okfield = $dirpath."lesson_{$lessonid}_uid_{$uid}_".$poster_no."_ok.png";
	$fun($image, $okfield);  
	/*销毁图片*/  
	imagedestroy($image);

	/* 删除多余文件 */
	unlink($dirpath."lesson_{$lessonid}_uid_{$uid}.png");
	unlink($dirpath."avatar_{$uid}.".$suffix);
	unlink($dirpath."lesson_{$lessonid}_cover.jpg");
}

$imagepath .= "?v=".time();

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

$sharelesson['link'] = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$lessonid, 'uid'=>$uid));
/* 构造分享信息结束 */


include $this->template("../mobile/{$template}/lessonqrcode");

?>