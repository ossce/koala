<?php
/**
 * 微课堂V2模块订阅器
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

class Fy_lessonv2ModuleReceiver extends WeModuleReceiver {
	
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

	public function receive() {
		global $_W;

		$type = $this->message['type'];
		$event = $this->message['event'];
		$from = $this->message['from'];
		$to = $this->message['to'];
		$scene = $this->message['scene']; /*格式：lesson_48、uid_3*/
		$uniacid = $_W['uniacid'];

		/* 用户关注事件 */
		if($event=="subscribe"){
			if(strstr($scene, "lesson_")){
				$this->sendLessonNews($uniacid, $scene, $from);
			}

			if(strstr($scene, "uid_")){
				$this->recommendMember($uniacid, $scene, $from);
			}
		}
	}

	/**
	 * 用户关注公众号发送图文消息
	 * 场景：用户在课程详情页关注公众号
	 */
	private function sendLessonNews($uniacid, $scene, $from){
		global $_W;
		load()->func('logging');

		$lessonid = str_replace("lesson_", "", $scene);
		$lesson = pdo_fetch("SELECT id,bookname,images,share FROM " .tablename($this->table_lesson_parent). " WHERE id=:id", array(':id'=>$lessonid));
		if(!$lesson){
			logging_run('用户关注公众号(uniacid:'.$uniacid.')发送课程图文消息失败，原因：课程(id:'.$lessonid.')不存在', 'trace', 'fylessonv2');
			return;
		}
		$share = json_decode($lesson['share'], true);

		$fans = pdo_fetch("SELECT nickname FROM " .tablename($this->table_fans). " WHERE uniacid=:uniacid AND openid=:openid", array(':uniacid'=>$uniacid,':openid'=>$from));
		$nickname = isset($fans['nickname']) ? $fans['nickname']."，" : "";
		$title = !empty($share['title']) ? $share['title'] : "欢迎继续回来，点击继续学习《{$lesson['bookname']}》课程";
		$description = !empty($share['descript']) ? $share['descript'] : "点击继续学习《{$lesson['bookname']}》";

		$message = array(
			'touser' => $from,
			'msgtype' => 'news',
			'news' => array(
				'articles' => array(
					'0' => array(
						'title' => $nickname.$title,
						'description' => $description,
						'url' => $_W['siteroot'].'app/'.$this->createMobileUrl('lesson', array('id'=>$lessonid)),
						'picurl' => !empty($share['images']) ? $_W['attachurl'].$share['images'] : $_W['attachurl'].$lesson['images']
					)
				)
			)
		);
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if(is_error($token)){
			logging_run('用户关注公众号(uniacid:'.$uniacid.')发送课程图文消息失败，原因：获取access_token失败', 'trace', 'fylessonv2');
			return;
		}

		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
		$result = ihttp_request($url, json_encode($message, JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 用户识别海报关注公众号，关联上下级关系
	 * 场景：用户通过好友分享海报关注公众号
	 */
	private function recommendMember($uniacid, $scene, $from){
		global $_W;

		include_once dirname(__FILE__).'/inc/core/SiteCommon.php';
		$site_common = new SiteCommon();

		$setting = $this->readCache(1); /* 基本设置 */
		$comsetting = $this->readCache(2); /* 分销设置 */

		load()->model('mc');
		
		/* 当前用户 */		
		$fansinfo = mc_fansinfo($from);
		$uid = $fansinfo['uid'];
		if(empty($uid)){
        	return;
        }
		$member = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$uid));

		/* 推荐人 */
		$recid = str_replace("uid_", "", $scene);
		$recmember = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'], 'uid'=>$recid));
		if(!empty($member)){
			return;
		}

		if(!empty($member)){
			/* 更新用户Openid */
			$site_common->updateOpenid($member);

			if(!empty($recmember)){
				$site_common->checkSaleModel($member, $recmember, $comsetting);
			}
		}else{
			$mc_member = pdo_get($this->table_mc_members, array('uid'=>$uid));
			if(!empty($mc_member)){
				$insertarr = array(
					'uniacid' => $uniacid,
					'uid' => $uid,
					'openid' => $from,
					'nickname' => $fansinfo['nickname'] ? $fansinfo['nickname'] : $mc_member['nickname'],
					'parentid' => $recmember['status']==1 ? $recmember['uid'] : 0,
					'status' => $comsetting['agent_status'],
					'coupon_tip' => 0,
					'uptime' => 0,
					'addtime' => time(),
				);
				pdo_insert($this->table_member, $insertarr);
				$source_id = pdo_insertid();
				$member = pdo_get($this->table_member, array('uid'=>$uid));
			}
		}

		if($source_id>0){
			/* 新会员注册发放优惠券&&成功推荐下级，给直接推荐人发放优惠券 */
			$site_common->sendCouponByNewMember($member, $recmember, $setting);
			/* 新下级加入、通知一二三级推荐人 */
			$site_common->setMemberParentId($member, $recmember, $setting, $comsetting, $source_id);
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
				$setting = pdo_fetch("SELECT * FROM " .tablename($this->table_setting). " WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
				cache_write('fy_lesson_'.$_W['uniacid'].'_setting', $setting);
			}
			return $setting;

		}elseif($type==2){
			$comsetting = cache_load('fy_lesson_'.$_W['uniacid'].'_commission_setting');
			if(empty($comsetting)){
				$comsetting = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_setting). " WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
				cache_write('fy_lesson_'.$_W['uniacid'].'_commission_setting', $comsetting);
			}
			return $comsetting;
		}
	}

}