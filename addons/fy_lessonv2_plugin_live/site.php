<?php
/**
 * 课堂直播模块定义
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
include_once dirname(__FILE__).'/../fy_lessonv2/inc/core/SiteCommon.php';
include_once dirname(__FILE__).'/../fy_lessonv2/inc/core/TypeStatus.php';
include_once dirname(__FILE__).'/../fy_lessonv2/library/aodianyun/IM/tis.php';

class Fy_lessonv2_plugin_liveModuleSite extends WeModuleSite {

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
	public $table_document			 = 'fy_lesson_document';
    public $table_evaluate			 = 'fy_lesson_evaluate';
	public $table_evaluate_score	 = 'fy_lesson_evaluate_score';
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

	/***************************** 初始化 *********************************/
    function __construct() {
        global $_W;
		if(!$_W['setting']['copyright']['develop_status']){
			error_reporting(E_ERROR); 
			ini_set("display_errors","Off");
		}
    }

	public function getMenus(){
		global $_W;

		$menus = array(
			array(
				'title' => '课程管理',
				'url'	=> $this->createWebUrl('lesson'),
				'icon'  => 'fa fa-mortar-board',
			),
			array(
				'title' => '直播管理',
				'url'	=> $this->createWebUrl('live'),
				'icon'  => 'fa fa-video-camera',
			),
			array(
				'title' => '直播设置',
				'url'	=> $this->createWebUrl('setting'),
				'icon'  => 'fa fa-cog',
			),
		);

		return $menus;
	}

/*****************************  WEB方法  *********************************/
	public function doWebAward() {
		$this->__web(__FUNCTION__);
	}
	public function doWebGetmember() {
		$this->__web(__FUNCTION__);
	}
	public function doWebGetlesson() {
		$this->__web(__FUNCTION__);
	}
	public function doWebLesson() {
		$this->__web(__FUNCTION__);
	}
	public function doWebLive() {
		$this->__web(__FUNCTION__);
	}
	public function doWebSetting() {
		$this->__web(__FUNCTION__);
	}
	public function doMobileIndex() {
		$this->resultJson('return');
	}

/***************************** 其他方法 *********************************/

	public function __web($f_name) {
        global $_W, $_GPC;
		$versions = "3.8.4";
        $uniacid = $_W['uniacid'];
        $op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$setting = $this->readCache(1);  /* 基本设置 */
		$setting_pc = $this->readCache(3); /* PC基本设置 */
		$common = json_decode($setting['common'], true);
		$site_common = new SiteCommon();

        include_once 'inc/web/' . strtolower(substr($f_name, 5)) . '.php';
    }

	 /* 读取缓存
	  * $type 读取缓存类型 1.手机端论坛设置表
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
		 }elseif($type==3){
			 $setting_pc = cache_load('fy_lesson_'.$_W['uniacid'].'_setting_pc');
			 if(empty($setting_pc)){
				$setting_pc = pdo_get($this->table_setting_pc, array('uniacid'=>$_W['uniacid']));
				$setting_pc['site_root'] = $setting_pc['site_root'] ? $setting_pc['site_root'] : $_W['siteroot'];
				cache_write('fy_lesson_'.$_W['uniacid'].'_setting_pc', $setting_pc);
			}
			return $setting_pc;
		 }
	 }

	/* json输出 */
	public function resultJson($data){
		echo json_encode($data);
		exit();
	}
	
}