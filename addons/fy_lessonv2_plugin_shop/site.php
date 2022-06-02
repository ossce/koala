<?php
/**
 * 课堂商城模块微站定义
 * ============================================================================
 * 版权所有 2015-2021 风影科技，并保留所有权利。
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
include_once dirname(__FILE__).'/inc/core/ShopCommon.php';

class Fy_lessonv2_plugin_shopModuleSite extends WeModuleSite {

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


	public $table_shop_address			= 'fy_lesson_plugin_shop_address';
	public $table_shop_attr				= 'fy_lesson_plugin_shop_attr';
	public $table_shop_banner			= 'fy_lesson_plugin_shop_banner';
	public $table_shop_cart				= 'fy_lesson_plugin_shop_cart';
	public $table_shop_category			= 'fy_lesson_plugin_shop_category';
	public $table_shop_commission_log	= 'fy_lesson_plugin_shop_commission_log';
	public $table_shop_diy_template		= 'fy_lesson_plugin_shop_diy_template';
	public $table_shop_express			= 'fy_lesson_plugin_shop_express';
	public $table_shop_goods			= 'fy_lesson_plugin_shop_goods';
	public $table_shop_goods_comment	= 'fy_lesson_plugin_shop_goods_comment';
	public $table_shop_navigation		= 'fy_lesson_plugin_shop_navigation';
	public $table_shop_notice			= 'fy_lesson_plugin_shop_notice';
	public $table_shop_notice_category	= 'fy_lesson_plugin_shop_notice_category';
	public $table_shop_order			= 'fy_lesson_plugin_shop_order';
	public $table_shop_order_goods		= 'fy_lesson_plugin_shop_order_goods';
	public $table_shop_refund			= 'fy_lesson_plugin_shop_refund';
	public $table_shop_refund_log		= 'fy_lesson_plugin_shop_refund_log';
	public $table_shop_refund_reason	= 'fy_lesson_plugin_shop_refund_reason';
	public $table_shop_setting			= 'fy_lesson_plugin_shop_setting';
	public $table_shop_sku				= 'fy_lesson_plugin_shop_sku';
	public $table_shop_syslog			= 'fy_lesson_plugin_shop_syslog';
	public $table_shop_value			= 'fy_lesson_plugin_shop_value';
	public $table_shop_virtual			= 'fy_lesson_plugin_shop_virtual';

	public function getMenus(){
		global $_W;

		$menus = array(
			array(
				'title' => '商品分类',
				'url'	=> $this->createWebUrl('category'),
				'icon'  => 'fa fa-sitemap',
			),
			array(
				'title' => '商品管理',
				'url'	=> $this->createWebUrl('goods',array('status'=>1)),
				'icon'  => 'fa fa-cubes',
			),
			array(
				'title' => '订单管理',
				'url'	=> $this->createWebUrl('order'),
				'icon'  => 'fa fa-list-ol',
			),
			array(
				'title' => '评价管理',
				'url'	=> $this->createWebUrl('comment'),
				'icon'  => 'fa fa-comment-o',
			),
			array(
				'title' => '佣金管理',
				'url'	=> $this->createWebUrl('commission'),
				'icon'  => 'fa fa-yen',
			),
			array(
				'title' => '快递管理',
				'url'	=> $this->createWebUrl('express'),
				'icon'  => 'fa fa-truck',
			),
			array(
				'title' => '商城公告',
				'url'	=> $this->createWebUrl('notice'),
				'icon'  => 'fa fa-volume-up',
			),
			array(
				'title' => '基本设置',
				'url'	=> $this->createWebUrl('setting'),
				'icon'  => 'fa fa-cog',
			),
			array(
				'title' => '数据统计',
				'url'	=> $this->createWebUrl('statis'),
				'icon'  => 'fa fa-bar-chart',
			),
			array(
				'title' => '清空缓存',
				'url'	=> $this->createWebUrl('clearcache'),
				'icon'  => 'fa fa-refresh',
			),
			array(
				'title' => '日志管理',
				'url'	=> $this->createWebUrl('syslog'),
				'icon'  => 'fa fa-floppy-o',
			),
			
		);

		return $menus;
	}

/************************************* WEB方法 ****************************************/
	public function doWebCategory() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebClearcache() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebComment() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebCommission() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebExpress() {
		$this->__web(__FUNCTION__);
	}

	public function doWebGetgoods() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebGoods() {
		$this->__web(__FUNCTION__);
	}

	public function doWebNotice() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebOrder() {
		$this->__web(__FUNCTION__);
	}

	public function doWebSetting() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebSyslog() {
		$this->__web(__FUNCTION__);
	}

	public function doWebStatis() {
		$this->__web(__FUNCTION__);
	}
/************************************ Mobile方法 **************************************/
	public function doMobileShop() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopaddcart() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopaddorder() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopaddress() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopcart() {
		$this->__mobile(__FUNCTION__);
	}

	public function doMobileShopcategory() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopcomment() {
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileShopcommission() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopconfirm() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopcrontab() {
		$this->__mobile(__FUNCTION__);
	}

	public function doMobileShopdiy() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopgoods() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShoplist() {
		$this->__mobile(__FUNCTION__);
	}

	public function doMobileShopnotice() {
		$this->__mobile(__FUNCTION__);
	}

	public function doMobileShoporder() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopqrcode() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShoprefund() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileShopupdateorder() {
		$this->__mobile(__FUNCTION__);
	}
	
	public function doMobileUploadimage() {
		$this->__mobile(__FUNCTION__);
	}

	public function __web($f_name) {
        global $_W, $_GPC;
		$versions = '4.1.4';
        $uniacid = $_W['uniacid'];
        $op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';

		$shop_setting = $this->readCache(0);
		$setting = $this->readCache(1);
		$comsetting = $this->readCache(2);
		$site_common = new SiteCommon();
		$shop_common = new ShopCommon();
		$module_title = $_W['current_module']['title'] ? $_W['current_module']['title'] : '课堂商城';

        include_once 'inc/web/' . strtolower(substr($f_name, 5)) . '.php';
    }

    public function __mobile($f_name) {
        global $_W, $_GPC;
		$versions = '4.1.4';
        $uniacid = $_W['uniacid'];
		$uid = $_W['member']['uid'];
        $op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';

		$site_common = new SiteCommon();
		$shop_common = new ShopCommon();

		$shop_setting = $this->readCache(0);  /* 商城设置 */
		$setting = $this->readCache(1);  /* 微课堂基本设置 */
		$comsetting = $this->readCache(2);  /* 微课堂分销设置 */
		$userAgent = $this->checkUserAgent(); /* 是否微信内访问 */
		$diy_data = $shop_common->getDiyTemplate('', $page_type=1); /* 手机端DIY信息 */
		$rightTopMenu = $shop_common->getNavigationMenu($location='rightTop'); /* 右上角快捷菜单 */
		$right_menu = $site_common->getRightBarMenu(); /* 右侧悬浮菜单 */
		$share_shop = json_decode($shop_setting['share_shop'], true); /* 分享信息 */
		$module_title = $_W['current_module']['title'] ? $_W['current_module']['title'] : '课堂商城';

		if($_GPC['visit']){
			setcookie($uniacid.'_visit', $_GPC['visit'], time()+3600);
		}
		if(!$shop_setting['visit_limit'] && $_GPC['do']!='shopcrontab'){
			$pro_visite = $_COOKIE[$uniacid.'_visit'];
			if(!$userAgent && !$pro_visite){
				header("Location:".str_replace('fy_lessonv2_plugin_shop','fy_lessonv2',$this->createMobileUrl('error')));
			}
		}

		$no_login = array('shop','shopcategory','shoplist','shopgoods','shopcrontab','shopdiy');
		if($userAgent){
			checkauth();
		}else{
			if(!in_array($_GPC['do'], $no_login)){
				checkauth();
			}
		}

		$this->setParentId($_GPC['uid']);
		$this->updatelessonmember();

        include_once 'inc/mobile/' . strtolower(substr($f_name, 8)) . '.php';
    }

	/* 更新课程用户信息 */
    public function updateLessonMember() {
        global $_W, $_GPC;
		$site_common = new SiteCommon();
		$setting = $this->readCache(1); /* 基本设置 */
		$comsetting = $this->readCache(2); /* 分销设置 */

		$uid = intval($_W['member']['uid']); /*当前用户id*/
        if(empty($uid)){
        	return;
        }
		$member = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$uid));
        
		/* 推荐人id */
		$recid = intval($_GPC['uid']) ? intval($_GPC['uid']) : intval($_COOKIE[$_W['uniacid'].'_parentId']);
		$recmember = pdo_get($this->table_member, array('uniacid'=>$_W['uniacid'],'uid'=>$recid));
		setcookie($_W['uniacid'].'_parentId', '', time()-3600);

		if(!empty($member)){
			/* 更新用户Openid */
			$this->updateOpenid($member);

			if(!empty($recmember)){
				$site_common->checkSaleModel($member, $recmember, $comsetting);
			}
		}else{
			$mc_member = pdo_fetch("SELECT * FROM " . tablename($this->table_mc_members) . " WHERE uid=:uid", array(':uid'=>$uid));
			if(!empty($mc_member)){
				$insertarr = array(
					'uniacid'	=> $_W['uniacid'],
					'uid'		=> $uid,
					'openid'	=> !is_numeric($_W['openid']) && !empty($_W['openid']) ? $_W['openid'] : "",
					'nickname'  => $_W['nickname'] ? $_W['nickname'] : $mc_member['nickname'],
					'parentid'  => $recmember['status']==1 ? $recmember['uid'] : 0,
					'status'	=> $comsetting['agent_status'],
					'coupon_tip'=> 0,
					'uptime'	=> 0,
					'addtime'	=> time(),
				);
				pdo_insert($this->table_member, $insertarr);
				$source_id = pdo_insertid();
				$member = pdo_fetch("SELECT * FROM " . tablename($this->table_member) . " WHERE uid=:uid", array(':uid'=>$uid));
			}
		}
		if($source_id>0){
			/* 新会员注册发放优惠券&&成功推荐下级，给直接推荐人发放优惠券 */
			$site_common->sendCouponByNewMember($member, $recmember, $setting);
			/* 新下级加入、通知一二三级推荐人 */
			$site_common->setMemberParentId($member, $recmember, $setting, $comsetting, $source_id);
			/* 插入推荐关系记录 */
			$site_common->recordRelation($uid, $insertarr['parentid']);
		}
    }

	/* 
	 * 更新课程会员表openid 
	 * $member 课程会员表会员信息
	 */
	private function updateOpenid($member){
		global $_W;
		
		$openid = $_W['openid'];
		/*课程会员表存在会员openid且openid不是数字(uid)*/
		if(!empty($member['openid']) && !is_numeric($member['openid'])){
			return;
		}
		/*当前获取到的全局openid为空或者为数字(uid)*/
		if(empty($openid) || is_numeric($openid)){
			return;
		}

		pdo_update($this->table_member, array('openid'=>$openid), array('uid'=>$member['uid']));
	}

	/* 把推荐人ID写入cookie */	
	public function setParentId($uid){
		global $_W;

		if($uid && $uid != $_W['member']['uid']){
			setcookie($_W['uniacid']."_parentId", $uid);
		}
	}

	public function recordShopCommission($order){
		$buyer = pdo_get($this->mc_members, array('uid'=>$order['buyer_uid']), array('nickname'));
		if($order['member1'] && $order['commission1']>0){
			$item = array(
				'uid'		 => $order['member1'],
				'commission' => $order['commission1'],
				'grade'		 => 1,
				'remark'	 => '1级佣金:订单号'.$order['ordersn'].'，用户信息:[uid:'.$order['uid'].$buyer['nickname'].']',
			);
			$this->insertToShopCommission($order, $item);
		}
		if($order['member2'] && $order['commission2']>0){
			$item = array(
				'uid'		 => $order['member2'],
				'commission' => $order['commission2'],
				'grade'		 => 2,
				'remark'	 => '2级佣金:订单号'.$order['ordersn'].'，用户信息:[uid:'.$order['uid'].$buyer['nickname'].']',
			);
			$this->insertToShopCommission($order, $item);
		}
		if($order['member3'] && $order['commission3']>0){
			$item = array(
				'uid'		 => $order['member3'],
				'commission' => $order['commission3'],
				'grade'		 => 3,
				'remark'	 => '3级佣金:订单号'.$order['ordersn'].'，用户信息:[uid:'.$order['uid'].$buyer['nickname'].']',
			);
			$this->insertToShopCommission($order, $item);
		}
	}
	private function insertToShopCommission($order, $item){
		$data = array(
			'uniacid'		=> $order['uniacid'],
			'orderid'		=> $order['id'],
			'uid'			=> $item['uid'],
			'goods_name'	=> $order['title'],
			'order_amount'	=> $order['price'],
			'commission'	=> $item['commission'],
			'grade'			=> $item['grade'],
			'remark'		=> $item['remark'],
			'buyer_uid'		=> $order['uid'],
			'addtime'		=> time(),
		);
		pdo_insert($this->table_shop_commission_log, $data);
	}

	public function resultJson($data){
		echo json_encode($data);
		exit();
	}

	/* 读取缓存
	  * $type 读取缓存类型 0.商城全局设置 1.微课堂全局设置 2.分销设置  3.PC端全局设置
	  * $uniacid 公众号id
	  */
	 private function readCache($type, $uniacid=''){
		 global $_W;

		 if(!$uniacid){
			 $uniacid = $_W['uniacid'];
		 }

		 if($type==0){
			 $shop_setting = cache_load('fy_lesson_'.$uniacid.'_shop_setting');
			 if(empty($shop_setting)){
				$shop_setting = pdo_get($this->table_shop_setting, array('uniacid'=>$uniacid));
				cache_write('fy_lesson_'.$uniacid.'_shop_setting', $shop_setting);
			}
			return $shop_setting;

		 }elseif($type==1){
			 $setting = cache_load('fy_lesson_'.$uniacid.'_setting');
			 if(empty($setting)){
				$setting = pdo_get($this->table_setting, array('uniacid'=>$uniacid));
				cache_write('fy_lesson_'.$uniacid.'_setting', $setting);
			}
			return $setting;

		 }elseif($type==2){
			 $comsetting = cache_load('fy_lesson_'.$uniacid.'_commission_setting');
			 if(empty($comsetting)){
				$comsetting = pdo_get($this->table_commission_setting, array('uniacid'=>$uniacid));
				cache_write('fy_lesson_'.$uniacid.'_commission_setting', $comsetting);
			}
			return $comsetting;

		 }elseif($type==3){
			 $setting_pc = cache_load('fy_lesson_'.$uniacid.'_setting_pc');
			 if(empty($setting_pc)){
				$setting_pc = pdo_get($this->table_setting_pc, array('uniacid'=>$uniacid));
				$setting_pc['site_root'] = $setting_pc['site_root'] ? $setting_pc['site_root'] : $_W['siteroot'];
				cache_write('fy_lesson_'.$uniacid.'_setting_pc', $setting_pc);
			}
			return $setting_pc;
		 }
	 }

	 /* 检查是否在微信中打开 */
	public function checkUserAgent(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			return false;
		}else{
			return true;
		}
	}

	private function checkdir($path) {
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
    }
	
}