<?php
/**
 * 课堂同步模块微站定义
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
include_once dirname(__FILE__).'/../fy_lessonv2/inc/core/TypeStatus.php';

class Fy_lessonv2_plugin_sysncModuleSite extends WeModuleSite {

	public $table_article			 = 'fy_lesson_article';
    public $table_banner			 = 'fy_lesson_banner';
	public $table_setting			 = 'fy_lesson_setting';
	public $table_setting_pc		 = 'fy_lesson_setting_pc';
	public $table_commission_setting = 'fy_lesson_commission_setting';
	public $table_commission_level	 = 'fy_lesson_commission_level';
	public $table_category			 = 'fy_lesson_category';
	public $table_teacher_category	 = 'fy_lesson_teacher_category';
	public $table_teacher			 = 'fy_lesson_teacher';
	public $table_teacher_price		 = 'fy_lesson_teacher_price';
	public $table_recommend			 = 'fy_lesson_recommend';
	public $table_vip_level			 = 'fy_lesson_vip_level';
	public $table_lesson_parent		 = 'fy_lesson_parent';
	public $table_lesson_son		 = 'fy_lesson_son';
	public $table_lesson_spec		 = 'fy_lesson_spec';
	public $table_lesson_title		 = 'fy_lesson_title';

	function __construct() {
        global $_W, $_GPC;
		if(!$_W['setting']['copyright']['develop_status']){
			error_reporting(E_ERROR); 
			ini_set("display_errors","Off");
		}
    }

	public function getMenus(){
		return $menus = array(
			array(
				'title' => '一键同步',
				'url'	=> $this->createWebUrl('copy'),
				'icon'  => 'fa fa-cubes',
			),
			array(
				'title' => '增量同步',
				'url'	=> $this->createWebUrl('category'),
				'icon'  => 'fa fa-cloud-upload',
			),
		);
	}

	public function __web($f_name){
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$versions = "3.8.1";

		$wechat = pdo_get('account_wechats', array('uniacid'=>$uniacid));
		if(!$wechat){
			message('获取目标公众号失败', '', 'error');
		}

		include_once  'inc/web/'.substr($f_name,5).'.php';
	}

	public function doWebCopy() {
        $this->__web(__FUNCTION__);
    }

	public function doWebCategory() {
        $this->__web(__FUNCTION__);
    }

	public function doWebGetWechatList(){
		 $this->__web(__FUNCTION__);
	}

	public function doWebTeachercate() {
        $this->__web(__FUNCTION__);
    }

	public function doWebTeacher() {
        $this->__web(__FUNCTION__);
    }

	public function doWebRecommend() {
        $this->__web(__FUNCTION__);
    }

	public function doWebVip() {
        $this->__web(__FUNCTION__);
    }

	public function doWebLesson() {
        $this->__web(__FUNCTION__);
    }

	//检查当前用户是否为公众号的管理员
	private function checkUserAccount($uniacid){
		global $_W;

		if(!$_W['isfounder']){
			$uid = $_W['uid'];
			$account = pdo_get('uni_account_users', array('uniacid'=>$uniacid,'uid'=>$uid,'role'=>'manager'));
			if(empty($account)){
				message("您不是数据来源公众号的管理员，无权继续此操作，可使用创始人身份操作");
			}
		}
	}

	private function forbidHandle(){
		global $_W;

		if($_SERVER['SERVER_NAME']=='wx.haoshu888.com' && $_W['uniacid']==1){
			message('Prohibit operating');
		}
	}
}

?>