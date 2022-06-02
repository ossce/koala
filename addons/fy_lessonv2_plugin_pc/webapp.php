<?php 
/**
 * 微课堂_PC版入口文件
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
include_once dirname(__FILE__).'/inc/common/AliyunVod.php';
include_once dirname(__FILE__).'/inc/common/QcloudVod.php';
include_once dirname(__FILE__).'/inc/common/QcloudCos.php';
include_once dirname(__FILE__).'/library/aodianyun/IM/tis.php';
include_once dirname(__FILE__).'/inc/core/SiteCommon.php';
include_once dirname(__FILE__).'/inc/core/WebAppCommon.php';
include_once dirname(__FILE__).'/inc/core/TypeStatus.php';

class Fy_lessonv2ModuleWebapp extends WeModuleWebapp {
	
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

/***************************** 初始化 ******************************** */
    function __construct() {
		$setting_pc = $this->readCache(3);
		$jump_setting = json_decode($setting_pc['jump_setting'], true);

        $webAppCommon = new WebAppCommon();
		$is_mobile = $webAppCommon->isMobile();

		if($is_mobile && $jump_setting['switch']){
			header("Location:".$jump_setting['url']);
		}
    }

/***************************** 私有方法 *********************************/
	
	/* 读取设置缓存
	 * $type 读取缓存类型 1.全局设置表 2.分销设置表 3.PC端单独设置表
	 */
	private function readCache($type){
		global $_W;

		if($type==1){
			$setting = cache_load('fy_lesson_'.$_W['uniacid'].'_setting');
			if(empty($setting)){
				$setting = pdo_get($this->table_setting, array('uniacid'=>$_W['uniacid']));
				cache_write('fy_lesson_'.$_W['uniacid'].'_setting', $setting);
			}
			return $setting;

		}elseif($type==2){
			$comsetting = cache_load('fy_lesson_'.$_W['uniacid'].'_commission_setting');
			if(empty($comsetting)){
				$comsetting = pdo_get($this->table_commission_setting, array('uniacid'=>$_W['uniacid']));
				cache_write('fy_lesson_'.$_W['uniacid'].'_commission_setting', $comsetting);
			}
			return $comsetting;

		}elseif($type==3){
			$setting_pc = cache_load('fy_lesson_'.$_W['uniacid'].'_setting_pc');
			if(empty($setting_pc)){
				$setting_pc = pdo_get($this->table_setting_pc, array('uniacid'=>$_W['uniacid']));
				cache_write('fy_lesson_'.$_W['uniacid'].'_setting_pc', $setting_pc);
			}
			return $setting_pc;
		}
	}

/****************************** PC端方法 **********************************/
		
	public function doPageAbout() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageAccount() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageAddOrder() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageAjaxUploadImage() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageAodianyunIM() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageApplyTeacher() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageArticle() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageCashLog() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageChatRecord() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageCollect() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageCommissionLog() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageConfirm() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageCoupon() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageCouponLesson() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageCredit() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageDiscount() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageDownloadFile() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageEvaluate() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageGetcoupon() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageGetevaluate() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageGetLiveInfo() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageGetOrderStatus() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageHistory() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageIndex() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageLesson() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageLessonCashLog() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageLessonCoupon() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageLiveAward() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageLogin() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageMemberInfo() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageMylesson() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageMyteacher() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageMyTeam() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageMyvip() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageOrderDetails() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPagePayment() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageQrcode() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageQrcodeRec() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageReclesson() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageRecommend() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageRecord() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageRegister() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageSearch() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageSelf() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageSendCode() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageSignin() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageStudyduration() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageSectionStudyStatus() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageTeacher() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageTeacherAccount() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageTeacherIncome() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageTeacherlist() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageUpdateCollect() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageVip() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageVipcard() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageViplesson() {
		$this->__webapp(__FUNCTION__);
	}
	
	public function doPageWriteMsg() {
		$this->__webapp(__FUNCTION__);
	}

	public function doPageLogout() {
		global $_W;
        $uniacid = $_W['uniacid'];

		session_start();
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_uid']);
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_mobile']);
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_nickname']);
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_vip']);
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_teacher']);
		unset($_SESSION['fy_lessonv2_'.$uniacid.'_avatar']);

		header('Location:/'.$uniacid.'/login.html');
	}

	public function doPageVerifycode(){
		error_reporting(0);
		load()->classs('captcha');
		session_start();

		$captcha = new Captcha();
		$captcha->build(140, 43);
		$hash = md5(strtolower($captcha->phrase));
		isetcookie('__code', $hash);
		$_SESSION['__code'] = $hash;

		$captcha->output();
	}

	public function __webapp($f_name) {
        global $_W, $_GPC;
		$versions = "3.8.4";
        $uniacid = $_W['uniacid'];
		$module_name = $_W['current_module']['name'] ? $_W['current_module']['name'] : 'fy_lessonv2';
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$uid = $_SESSION['fy_lessonv2_'.$uniacid.'_uid'];

		$this->setParentId($_GPC['rec_uid']);
        $setting = $this->readCache(1);    /* 基本设置 */
		$comsetting = $this->readCache(2); /* 分销设置 */
		$setting_pc = $this->readCache(3); /* PC端设置 */
		$template = $setting_pc['template'] ? $setting_pc['template'] : 'default'; 
		$config = $this->module['config'];
		$common = json_decode($setting['common'], true);
		$module_title = $_W['current_module']['title'] ? $_W['current_module']['title'] : '微课堂V2';
		$http_url = $_W['ishttps'] ? 'https://' : 'http://';
		$current_url = $http_url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; /* 当前url */

		$site_common = new SiteCommon();
		$webAppCommon = new WebAppCommon();
		$top_navigation = $webAppCommon->getTopNavigation();
		$menu_navigation = $webAppCommon->getMenuNavigation();
		$categorylist = $webAppCommon->getNavCategory();
		$hot_search = $webAppCommon->getHotSearch($setting_pc);

		$friendly_link = json_decode($setting_pc['friendly_link'], true); /* 友情链接 */
		$company_info = json_decode($setting_pc['company_info'], true);   /* 公司信息 */
		$self_item = $common['self_item']; /* 个人中心菜单显示集合 */
		$self_diy = unserialize($setting['self_diy']); /* 个人中心自定义菜单 */
		$salefont = json_decode($comsetting['font'], true); /* 分销中心自定义字体 */
		$bottom_navigation = $webAppCommon->getBottomNavigation($friendly_link['number']);
		$footer_group = $webAppCommon->getBottomArticle();
		$self_navigation = $webAppCommon->getSelfNavigation();
		$common_member_fields = $this->disposeMemberFields();

		$no_login = array('index','search','vip','teacherlist','teacher','lesson','article','articlelist','recommend','discount','getcoupon','couponLesson','getevaluate','getliveinfo','sectionStudyStatus','login','logout','register','about');

		/* 个人中心菜单集合 */
		$often_menu = array('self','coupon','mylesson','orderDetails','myvip','myteacher','reclesson','studyduration','history','collect','signin');
		$teacher_menu = array('teacher','teacherIncome','lessonCashLog','teacherAccount','applyTeacher');
		$commission_menu = array('cashLog','myTeam','commissionLog','qrcode','qrcodeRec');
		$account_menu = array('memberInfo','account','credit');
		$self_do = array_merge_recursive($often_menu, $teacher_menu, $commission_menu, $account_menu);
		$is_vip = $_SESSION['fy_lessonv2_'.$uniacid.'_vip'];
		$global_teacher = $_SESSION['fy_lessonv2_'.$uniacid.'_teacher'];
		$teacher_platform = json_decode($setting_pc['teacher_platform'], true); /* 讲师平台自定义菜单 */
		$teacher_page = $common['teacher_page']; /* 讲师中心文字 */

		if(!in_array($_GPC['do'], $no_login)){
			if(empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
				header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
			}
		}

		$this->updateLessonMember();
        include_once 'inc/webapp/' . substr($f_name, 6) . '.php';
    }

	public function payResult($params) {
        global $_W, $_GPC;

		$setting = $this->readCache(1);   /* 基本设置 */
		$comsetting = $this->readCache(2);/* 分销设置 */

		include_once dirname(__FILE__).'/inc/core/Payresult.php';
		$pay_result = new Payresult();
		$pay_result->dealResult($params, $setting, $comsetting, $wechat_type='wechat');
    }

	/* 验证登录验证码 */
	private function codeVerify($code) {
		global $_W, $_GPC;
		session_start();
		$codehash = md5(strtolower($code));
		if (!empty($_GPC['__code']) && $codehash == $_SESSION['__code']) {
			$return = true;
			$_SESSION['__code'] = '';
			isetcookie('__code', '');
		} else {
			$return = false;
		}
		
		return $return;
	}

	private function updateLessonMember(){
		global $_W, $_GPC;
		$site_common = new SiteCommon();
		$comsetting = $this->readCache(2); /* 分销设置 */

		/* 当前登录用户信息 */
		$uid = $_SESSION['fy_lessonv2_'.$_W['uniacid'].'_uid'];
        if(empty($uid)){
        	return;
        }
		$member = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$uid));
        
		/* 推荐人信息 */
		$recid = intval($_GPC['rec_uid']) ? intval($_GPC['rec_uid']) : intval($_COOKIE['rec_uid']);
		$recmember = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$recid));
		setcookie('rec_uid', '', time()-3600);

		if(!empty($member) && !empty($recmember)){
			$site_common->checkSaleModel($member, $recmember, $comsetting);
		}
	}

	private function setParentId($parentid){
		if($parentid){
			setcookie("rec_uid", $parentid);
		}
	}

	private function checkdir($path) {
		if (!file_exists($path)) {
			mkdir($path, 0777);
		}
    }

	public function resultJson($data){
		echo json_encode($data);
		exit();
	}

	/* 检查黑名单 $type visit访问 order下单 */
    public function check_black_list($type) {
        global $_W;
		$uid = $_SESSION['fy_lessonv2_'.$_W['uniacid'].'_uid'];

		if(!$uid){
			return;
		}

		$member = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$uid), array('blacklist'));
		if(!$member['blacklist']){
			return;
		}
		if($type=='visit' && $member['blacklist']==2){
			message("当前用户已被停止访问，请联系管理员", "", "error");
		}
		if($type=='order' && $member['blacklist']==1){
			message("当前用户已被停止下单，请联系管理员", "", "error");
		}
    }

	/* 处理用户完善信息字段 */
	public function disposeMemberFields(){
		global $_W;

		$list = cache_load('fy_lesson_'.$_W['uniacid'].'_common_member_fields');
		if(!empty($list)){
			return $list;
		}

		$type_status = new TypeStatus();
		$fields = $type_status->memberFields();

		$list = array();
		foreach($fields as $k=>$v){
			$field = pdo_get('mc_member_fields', array('uniacid'=>$_W['uniacid'], 'fieldid'=>$k), array('title'));
			$list[] = array(
				'field_short' => $v,
				'field_name'  => $field['title'],
			);
		}

		cache_write('fy_lesson_'.$_W['uniacid'].'_common_member_fields', $list);
		return $list;
	}

	/* 检查讲师访问权限 */
	private function checkTeacherStatus($self_item){
		global $_W;
		$uid = $_SESSION['fy_lessonv2_'.$_W['uniacid'].'_uid'];

		$teacher = pdo_get($this->table_teacher, array('uniacid'=>$_W['uniacid'], 'uid'=>$uid));
		if(empty($teacher)){
			if(!in_array('teachercenter', $self_item)){
				message("系统没有开启讲师入驻！", "", "warning");
			}else{
				header("Location:".$_W['siteroot']."{$_W['uniacid']}/applyTeacher.html");
			}

		}elseif($teacher['status']==-1){
			header("Location:".$_W['siteroot']."{$_W['uniacid']}/applyTeacher.html");

		}elseif($teacher['status']==2){
			message("您的申请还在审核中，请耐心等待", "", "error");
		}
	}

	/* 检查分销中心访问权限 */
	private function checkDistributorStatus($comsetting){
		global $_W;
		$uid = $_SESSION['fy_lessonv2_'.$_W['uniacid'].'_uid'];

		$member = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'], 'uid'=>$uid));
		$member_vip = pdo_fetch("SELECT * FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity", array(':uniacid'=>$_W['uniacid'],':uid'=>$uid,':validity'=>time()));
			
		/* 分销文字 */
		$font = json_decode($comsetting['font'], true);

		if(!$comsetting['is_sale']){
			message('系统未开启该功能');
		}
		if($member['status']!=1){
			message("您的分销状态为{$font['agent_status_off']}，无法访问该功能");
		}
		if($comsetting['sale_rank']==2 && empty($member_vip)){
			message('您不是VIP会员，无法访问该功能', $_W['siteroot']."/".$_W['uniacid']."/vip.html", "error");
		}
	}


}


?>