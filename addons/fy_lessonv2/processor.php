<?php
/**
 * 微课堂V2模块回复规则
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

defined('IN_IA') or exit('Access Denied');

class Fy_lessonv2ModuleProcessor extends WeModuleProcessor {
	
	public $table_aliyun_upload		 = 'fy_lesson_aliyun_upload';
	public $table_aliyunoss_upload	 = 'fy_lesson_aliyunoss_upload';
	public $table_article			 = 'fy_lesson_article';
	public $table_article_category	 = 'fy_lesson_article_category';
	public $table_attribute			 = 'fy_lesson_attribute';
    public $table_banner			 = 'fy_lesson_banner';
    public $table_cashlog			 = 'fy_lesson_cashlog';
    public $table_category			 = 'fy_lesson_category';
	public $table_lesson_collect	 = 'fy_lesson_collect';
	public $table_commission_level	 = 'fy_lesson_commission_level';
	public $table_commission_log	 = 'fy_lesson_commission_log';
	public $table_commission_setting = 'fy_lesson_commission_setting';
	public $table_coupon			 = 'fy_lesson_coupon';
	public $table_discount			 = 'fy_lesson_discount';
	public $table_discount_lesson	 = 'fy_lesson_discount_lesson';
	public $table_diy_template		 = 'fy_lesson_diy_template';
	public $table_document			 = 'fy_lesson_document';
    public $table_evaluate			 = 'fy_lesson_evaluate';
	public $table_evaluate_score	 = 'fy_lesson_evaluate_score';
	public $table_footer_group		 = 'fy_lesson_footer_group';
    public $table_lesson_history	 = 'fy_lesson_history';
	public $table_index_module		 = 'fy_lesson_index_module';
	public $table_inform			 = 'fy_lesson_inform';
	public $table_inform_fans		 = 'fy_lesson_inform_fans';
	public $table_login_pc			 = 'fy_lesson_login_pc';
	public $table_recommend_junior	 = 'fy_lesson_recommend_junior';
	public $table_recommend_activity = 'fy_lesson_recommend_activity';
	public $table_market			 = 'fy_lesson_market';
	public $table_mcoupon			 = 'fy_lesson_mcoupon';
    public $table_member			 = 'fy_lesson_member';
	public $table_member_bind		 = 'fy_lesson_member_bind';
	public $table_member_buyteacher	 = 'fy_lesson_member_buyteacher';
	public $table_member_coupon		 = 'fy_lesson_member_coupon';
    public $table_member_order		 = 'fy_lesson_member_order';
	public $table_member_vip		 = 'fy_lesson_member_vip';
	public $table_navigation		 = 'fy_lesson_navigation';
    public $table_order				 = 'fy_lesson_order';
	public $table_order_verify		 = 'fy_lesson_order_verify';
    public $table_lesson_parent		 = 'fy_lesson_parent';
    public $table_playrecord		 = 'fy_lesson_playrecord';
	public $table_poster			 = 'fy_lesson_poster';
	public $table_qcloudvod_upload	 = 'fy_lesson_qcloudvod_upload';
	public $table_qcloud_upload		 = 'fy_lesson_qcloud_upload';
	public $table_qiniu_upload		 = 'fy_lesson_qiniu_upload';
    public $table_recommend			 = 'fy_lesson_recommend';
    public $table_setting			 = 'fy_lesson_setting';
	public $table_setting_pc		 = 'fy_lesson_setting_pc';
	public $table_signin			 = 'fy_lesson_signin';
    public $table_lesson_son		 = 'fy_lesson_son';
	public $table_lesson_title		 = 'fy_lesson_title';
	public $table_lesson_spec		 = 'fy_lesson_spec';
	public $table_static			 = 'fy_lesson_static';
	public $table_study_duration	 = 'fy_lesson_study_duration';
	public $table_subscribe_msg		 = 'fy_lesson_subscribe_msg';
    public $table_syslog			 = 'fy_lesson_syslog';
    public $table_teacher			 = 'fy_lesson_teacher';
	public $table_teacher_category	 = 'fy_lesson_teacher_category';
    public $table_teacher_income	 = 'fy_lesson_teacher_income';
	public $table_teacher_order		 = 'fy_lesson_teacher_order';
	public $table_teacher_price		 = 'fy_lesson_teacher_price';
	public $table_tplmessage		 = 'fy_lesson_tplmessage';
    public $table_vip_level			 = 'fy_lesson_vip_level';
    public $table_vipcard			 = 'fy_lesson_vipcard';
	public $table_mc_members		 = 'mc_members';
	public $table_fans				 = 'mc_mapping_fans';
	public $table_core_paylog		 = 'core_paylog';
    public $table_users				 = 'users';

	public $table_live_award		 = 'fy_lesson_plugin_live_award';
	public $table_live_chatrecord	 = 'fy_lesson_plugin_live_chatrecord';
	public $table_live_chatroom		 = 'fy_lesson_plugin_live_chatroom';
	public $table_live_stream		 = 'fy_lesson_plugin_live_stream';
	
	public $table_exam_course		 = 'fy_lesson_plugin_exam_course';
	public $table_exam_examine		 = 'fy_lesson_plugin_exam_examine';
	public $table_exam_examine_record = 'fy_lesson_plugin_exam_examine_record';
	public $table_exam_paper		 = 'fy_lesson_plugin_exam_paper';


    public function respond() {
		global $_W;
		include_once dirname(__FILE__).'/inc/core/SiteCommon.php';
		$site_common = new SiteCommon();

        $content = trim($this->message['content']);
		if(in_array($content, array('我的海报', '推广海报', '二维码海报', '专属海报'))){
			$setting = $this->readCache(1);
			$comsetting = $this->readCache(2);
			if($comsetting['is_sale']==0){
				return $this->respText('抱歉，系统未开启该功能.');
			}

			$uniacid = $_W['uniacid'];
			$template = $setting['template'] ? $setting['template'] : 'default';

			$uid = $_W['member']['uid'];
			$member = pdo_fetch("SELECT a.*,b.avatar,b.nickname AS mc_nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

			if($member['status']!=1){
				return $this->respText('抱歉，您的身份为冻结状态.');
			}

			$poster_list = cache_load('fy_lesson_'.$uniacid.'_poster_list');
			if(empty($poster_list)){
				$poster_list = pdo_getall($this->table_poster, array('uniacid'=>$uniacid));
				if(empty($poster_list)){
					$poster_list[0] = array(
						'poster_type' => true,
						'poster_bg' => MODULE_URL."template/mobile/{$template}/images/posterbg.jpg",
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

			$this->checkdir(IA_ROOT."/attachment/images/fy_lessonv2/");
			$this->checkdir(IA_ROOT."/attachment/images/fy_lessonv2/{$uniacid}/");
			$dirpath = IA_ROOT."/attachment/images/fy_lessonv2/{$uniacid}/";

			$imagepath = $dirpath.$uniacid."_".$uid."_ok.png";
			/* 缓存图片1分钟 */
			if(!file_exists($imagepath) || $comsetting['qrcode_cache']==0 || filectime($imagepath) < time()-60){
				set_time_limit(60); 
				ignore_user_abort(true);

				$poster_no = rand(0, count($poster_list)-1);
				if($poster_no){
					unlink($dirpath.$uniacid."_".$uid."_ok.png");
				}
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

				/* 背景图片 */
				$bgimg = IA_ROOT."/attachment/images/fy_lessonv2/{$uniacid}/posterbg_{$poster_no}.jpg";
				if(!file_exists($bgimg)){
					$poster_bg = $poster_list[$poster_no]['poster_type'] ? $poster_list[$poster_no]['poster_bg'] : $_W['attachurl'].$poster_list[$poster_no]['poster_bg'];
					$site_common->saveImage($poster_bg, $dirpath."posterbg_{$poster_no}.", '');
				}

				/* 二维码图片 */
				if($poster_qr){
					include(IA_ROOT."/framework/library/qrcode/phpqrcode.php");
					if($setting['poster_type']==2){
						$codeArray = array (
							'expire_seconds' => 2592000,
							'action_name' => 'QR_LIMIT_STR_SCENE',
							'action_info' => array (
								'scene' => array (
									'scene_str' => "uid_{$uid}",
								),
							),
						);
						$account_api = WeAccount::create();
						$res = $account_api->barCodeCreateFixed($codeArray);

						if(empty($res['ticket'])){
							return $this->respText('获取二维码失败，错误信息:'.$res['errcode'].'，'.$res['errmsg']);
						}
						$qrcodeurl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$res['ticket'];
						$qrcode_suffix = $site_common->saveImage($qrcodeurl, $dirpath.$uniacid."_".$uid."_qrcode.");
						$site_common->resize($dirpath.$uniacid."_".$uid."_qrcode.jpg", $dirpath.$uniacid."_".$uid."_qrcode.jpg", $poster_qr['width'], $poster_qr['height'], "100");
						$qrcode = $dirpath.$uniacid."_".$uid."_qrcode.".$qrcode_suffix;
						$savefield = $site_common->img_water_mark($bgimg, $dirpath.$uniacid."_".$uid."_qrcode.jpg", $dirpath, $uniacid."_".$uid.".png", $poster_qr['left'], $poster_qr['top']);
					}else{
						$errorCorrectionLevel = 'L';  /* 纠错级别：L、M、Q、H */
						
						$infourl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));
						$qrcode = $dirpath.$uniacid."_".$uid."_qrcode.png"; /* 生成的文件名 */
						QRcode::png($infourl, $qrcode, $errorCorrectionLevel, $poster_qr['size']=5, 3);
						$site_common->resize($qrcode, $qrcode, $poster_qr['width'], $poster_qr['height'], "100");
						$savefield = $site_common->img_water_mark($bgimg, $qrcode, $dirpath, $uniacid."_".$uid.".png", $poster_qr['left'], $poster_qr['top']);
					}
				}

				/* 合成头像 */
				if($poster_head){
					if(empty($member['avatar'])){
						$avatar = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
					}else{
						$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
						$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
					}
					
					$suffix = $site_common->saveImage($avatar, $dirpath.$uniacid."_".$uid."_avatar.", 'avatar');

					$avatar_size = filesize($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
					if($avatar_size==0){
						message("获取头像失败，请在个人中心点击头像更新", $this->createMobileUrl('self'), "error");
					}

					if($suffix=='png'){
						$im = imagecreatefrompng($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
					}elseif($suffix=='jpeg' || $suffix=='jpg'){
						$im = imagecreatefromjpeg($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
					}else{
						$im = imagecreatefromjpeg(MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg");
					}
					imagejpeg($im, $dirpath.$uniacid."_".$uid."_avatar.jpg");
					imagedestroy($im);
					
					$site_common->resize($dirpath.$uniacid."_".$uid."_avatar.jpg", $dirpath.$uniacid."_".$uid."_avatar.jpg", $poster_head['width'], $poster_head['height'], "100");
					$savefield = $site_common->img_water_mark($savefield, $dirpath.$uniacid."_".$uid."_avatar.jpg", $dirpath, $uniacid."_".$uid."_ok.png", $poster_head['left'], $poster_head['top']);
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
				$okfield = $dirpath.$uniacid."_".$uid."_ok.png";
				$fun($image, $okfield);  
				/*销毁图片*/  
				imagedestroy($image);
				
				/* 删除多余文件 */
				unlink($dirpath.$uniacid."_".$uid.".png");
				unlink($dirpath.$uniacid."_".$uid."_qrcode.png");
				unlink($dirpath.$uniacid."_".$uid."_qrcode.jpg");
				unlink($dirpath.$uniacid."_".$uid."_avatar.jpg");
				if($suffix!='jpg'){
					unlink($dirpath.$uniacid."_".$uid."_avatar.".$suffix);
				}
			}


			/* 发送海报给粉丝 */				
			$acc = WeAccount::create($_W['acid']);
			$imagepath = $dirpath.$uniacid."_".$uid."_ok.png";
			$data = $acc->uploadMedia($imagepath);

			$send = array();
			$send['touser']  = $_W['openid'];
			$send['fromuser']  = $_W['openid'];
			$send['createtime']  = time();
			$send['msgtype'] = 'image';
			$send['image'] = array('media_id' => $data['media_id']);
			if($_W['acid']) {
				$result = $acc->sendCustomNotice($send);
			}

			if(!is_error($result)){
				return $this->respText('您的专属海报已成功生成并下发，可转发给好友.');
			}else{
				return $this->respText('抱歉，生成海报失败，请稍候重试.');
			}
			exit();
		}
    }



	/* 读取缓存
	  * $type 读取缓存类型 1.全局设置表 2.分销设置表
	  */
	private function readCache($type){
		global $_W;

		if($type==1){
			$setting = cache_load('fy_lesson_'.$_W['uniacid'].'_setting');
			if(empty($setting)){
				$setting = $this->getSetting();
				cache_write('fy_lesson_'.$_W['uniacid'].'_setting', $setting);
			}
			return $setting;

		}elseif($type==2){
			$comsetting = cache_load('fy_lesson_'.$_W['uniacid'].'_commission_setting');
			if(empty($comsetting)){
				$comsetting = $this->getComsetting();
				cache_write('fy_lesson_'.$_W['uniacid'].'_commission_setting', $comsetting);
			}
			return $comsetting;
		}
	}
	
	/* 获取基本设置参数 */
	private function getSetting(){
		global $_W;
		return pdo_fetch("SELECT * FROM " .tablename($this->table_setting). " WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
	}

	/* 获取分销设置参数 */
	private function getComsetting(){
		global $_W;
		return pdo_fetch("SELECT * FROM " .tablename($this->table_commission_setting). " WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
	}

	/**
     *  检查目录是否存在
     */
    private function checkdir($path) {
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
    }

}