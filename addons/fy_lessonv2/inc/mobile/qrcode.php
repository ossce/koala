<?php
/**
 * 二维码推广
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];
load()->model('mc');

$font = json_decode($comsetting['font'], true);
$title = $font['poster'] ? $font['poster'] : "我的海报";


if($comsetting['is_sale']==0){
	message("系统未开启该功能", "", "warning");
}

$member = pdo_fetch("SELECT a.*,b.avatar,b.nickname AS mc_nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

if(!empty($member)){
	$infourl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));
}

if($member['status']!=1){
	message("您的身份为冻结状态", $this->createMobileUrl('index'), "warning");
}
$sale_desc = $comsetting['sale_desc'] ? explode("\n", $comsetting['sale_desc']) : "";


/* 海报配置参数 */
$poster_list = cache_load('fy_lesson_'.$uniacid.'_poster_list');
if(empty($poster_list)){
	$poster_list = pdo_getall($this->table_poster, array('uniacid'=>$uniacid,'poster_type'=>1));
	if(empty($poster_list)){
		$poster_list[0] = array(
			'poster_default' => true,
			'poster_bg'		 => MODULE_URL."static/mobile/{$template}/images/posterbg.jpg",
			'poster_setting' => array(
				array(
					'left' => '11px',
					'top' => '349px',
					'type' => 'head',
					'width' => '50px',
					'height' => '50px',
				),
				array(
					'left' => '105px',
					'top' => '341px',
					'type' => 'nickname',
					'width' => '40px',
					'height' => '20px',
					'size' => '24px',
					'color' => '#ffffff',
				),
				array(
					'left' => '237px',
					'top' => '367px',
					'type' => 'qr',
					'width' => '80px',
					'height' => '80px',
				),
			),
		);
	}else{
		foreach($poster_list as $k=>$v){
			$poster_list[$k]['poster_setting'] = json_decode($v['poster_setting'], true);
		}
	}
	cache_write('fy_lesson_'.$uniacid.'_poster_list', $poster_list);
}

/* 检查目录是否存在 */
$dirpath = "../attachment/images/{$uniacid}/";
$this->checkdir($dirpath);
$dirpath .="fy_lessonv2/";
$this->checkdir($dirpath);
$dirpath .="salePoster/";
$this->checkdir($dirpath);

/* 当前海报为第几张 */
$poster_no = intval($_GPC['poster_no']);
$poster_no = $poster_no <= count($poster_list)-1 ? $poster_no : 0;

foreach($poster_list[$poster_no]['poster_setting'] as $item){
	if($item['type']=='qr'){
		$poster_qr['left'] = $item['left'] * 2;
		$poster_qr['top'] = $item['top'] * 2;
		$poster_qr['width'] = $item['width'] * 2;
		$poster_qr['height'] = $item['height'] * 2;
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
		$poster_name['size'] = intval($item['size']);
		$poster_name['rgb'] = $site_common->hexTorgb($item['color']);
	}
}

$imagepath = $dirpath.$uniacid."_".$uid."_".$poster_no."_ok.png";
if(!file_exists($imagepath) || $comsetting['qrcode_cache']==0 || filectime($imagepath) < time()-86400){
	set_time_limit(60); 
	ignore_user_abort(true);
	
	/* 背景图片 */
	$bgimg = $dirpath."posterbg_{$poster_no}.jpg";
	if(!file_exists($bgimg)){
		$poster_bg = $poster_list[$poster_no]['poster_default'] ? $poster_list[$poster_no]['poster_bg'] : $_W['attachurl'].$poster_list[$poster_no]['poster_bg'];
		$site_common->saveImage($poster_bg, $dirpath."posterbg_{$poster_no}.", '');
	}

	/* 二维码图片 */
	if($poster_qr){
		$errorCorrectionLevel = 'L';  /* 纠错级别：L、M、Q、H */
		$qrcode = $dirpath.$uniacid."_".$uid."_qrcode".'.png'; /* 生成的文件名 */

		include(IA_ROOT."/framework/library/qrcode/phpqrcode.php");
		QRcode::png($infourl, $qrcode, $errorCorrectionLevel, $poster_qr['size']=5, 3);
		$site_common->resize($qrcode, $qrcode, $poster_qr['width'], $poster_qr['height'], "100");
		$savefield = $site_common->img_water_mark($bgimg, $qrcode, $dirpath, $uniacid."_".$uid.".png", $poster_qr['left'], $poster_qr['top']);
	}

	/* 合成头像 */
	if($poster_head){
		if(empty($member['avatar'])){
			$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
			$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
		}
		
		$suffix = $site_common->saveImage($avatar, $dirpath.$uniacid."_".$uid."_avatar.", 'avatar');

		$avatar_size = filesize($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
		if($avatar_size==0){
			message("获取头像失败，请在个人中心点击头像更新", $this->createMobileUrl('self'), "warning");
		}

		if($suffix=='png'){
			$im = imagecreatefrompng($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
		}elseif($suffix=='jpeg' || $suffix=='jpg'){
			$im = imagecreatefromjpeg($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
		}else{
			$im = imagecreatefromjpeg(MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg");
		}
		imagejpeg($im, $dirpath.$uniacid."_".$uid."_avatar.jpg");
		imagedestroy($im);
		
		$site_common->resize($dirpath.$uniacid."_".$uid."_avatar.jpg", $dirpath.$uniacid."_".$uid."_avatar.jpg", $poster_head['width'], $poster_head['height'], "100");
		$site_common->circularImg($dirpath.$uniacid."_{$uid}_avatar.{$suffix}", $dirpath.$uniacid."_{$uid}_avatar.{$suffix}");
		$savefield = $site_common->mergerImg($savefield, $dirpath.$uniacid."_{$uid}_avatar.{$suffix}", $poster_head['left'], $poster_head['top'], $savefield);
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
		/* 设置字体的路径 */
		$font = MODULE_ROOT."/static/public/ttf/Alibaba-PuHuiTi-Regular.ttf";
		/* 设置字体颜色和透明度 */
		$color = imagecolorallocatealpha($image, $poster_name['rgb']['r'], $poster_name['rgb']['g'], $poster_name['rgb']['b'], 0);
		/* 写入文字 */
		$fun = $dirpath.$uniacid."_".$uid.".png";
		imagettftext($image, $poster_name['size'], 0, $poster_name['left'], $poster_name['top']+45, $color, $font, $member['mc_nickname']);
	}

	/* 保存图片 */
	$fun = "image".$type;
	$okfield = $dirpath.$uniacid."_".$uid."_".$poster_no."_ok.png";
	$fun($image, $okfield);  
	/*销毁图片*/  
	imagedestroy($image);
	
	/* 删除多余文件 */
	unlink($dirpath.$uniacid."_".$uid.".png");
	unlink($dirpath.$uniacid."_".$uid."_qrcode.png");
	unlink($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
}

$imagepath .= "?v=".time();


include $this->template("../mobile/{$template}/qrcode");