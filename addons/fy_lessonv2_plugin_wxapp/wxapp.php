<?php 
/**
 * 微课堂小程序入口文件
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
class Fy_lessonv2ModuleWxapp extends WeModuleWxapp {
	
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


/***************************** 私有方法1 ******************************** */

	/* 读取设置缓存
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
		return pdo_get($this->table_setting, array('uniacid'=>$_W['uniacid']));
	}

	/* 获取分销设置参数 */
	private function getComsetting(){
		global $_W;
		return pdo_get($this->table_commission_setting, array('uniacid'=>$_W['uniacid']));
	}

/***************************** 接口数据(实际使用) *********************************/

	/* 分销设置页面 */
	public function doPageShareInfo(){
		global $_W, $_GPC;

		$setting = $this->readCache(1);
		$comsetting = $this->readCache(2);
		$shareinfo = unserialize($comsetting['sharelink']);
		$shareinfo['images'] = $shareinfo['images'] ? $_W['attachurl'].$shareinfo['images'] : $shareinfo['images'];
		
		$wxapp_uniacid = trim($_GPC['wxapp_uniacid']); /* 小程序uniacid */
		$wxapp_version = trim($_GPC['wxapp_version']); /* 小程序版本号 */
		$navigationBar = cache_load('fy_lessonv2_wxapp_navigationBar'.$wxapp_uniacid);
		if(empty($navigationBar)){
			$wxapp_versions = pdo_get('wxapp_versions', array('uniacid'=>$wxapp_uniacid,'version'=>$wxapp_version));
			$appjson = unserialize($wxapp_versions['appjson']);
			$navigationBar = array(
				'navigationBarTextStyle' => $appjson['window']['navigationBarTextStyle'] ? $appjson['window']['navigationBarTextStyle'] : '#000000',
				'navigationBarBackgroundColor' => $appjson['window']['navigationBarBackgroundColor'] ? $appjson['window']['navigationBarBackgroundColor'] : '#ffffff',
			);
			cache_write('fy_lessonv2_wxapp_navigationBar'.$wxapp_uniacid, $navigationBar);
		}

		$data = array(
			'attachurl'	 => $_W['attachurl'],
			'avatar'	 => $_W['account']['avatar'],
			'setting'	 => $setting,
			'shareinfo'  => $shareinfo,
			'hidden_sale'=> $comsetting['hidden_sale'] ? true : false,
			'navigationBar' => $navigationBar,
		);

		return $this->result(0, '', $data);
	}
		
	/* 首页 */
	public function doPageIndex() {
		global $_W;

		$comsetting = $this->readCache(2);
		if($comsetting['hidden_sale']){
			$url = "";
		}else{
			$url = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('index'));
		}

		return $this->result(0, '', $url);
	}

	/* 我的课程 */
	public function doPageMylesson() {
		global $_W;
		$comsetting = $this->readCache(2);

		if($comsetting['hidden_sale']){
			$url = "";
		}else{
			$url = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('mylesson'));
		}
		
		return $this->result(0, '', $url);
	}

	/* vip页面 */
	public function doPageVip() {
		global $_W;
		$comsetting = $this->readCache(2);

		if($comsetting['hidden_sale']){
			$url = "";
		}else{
			$url = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('vip'));
		}

		return $this->result(0, '', $url);
	}

	/* 我的课程 */
	public function doPageMyteacher() {
		global $_W;
		$comsetting = $this->readCache(2);

		if($comsetting['hidden_sale']){
			$url = "";
		}else{
			$url = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('myteacher'));
		}
		
		return $this->result(0, '', $url);
	}

	/* 获取关注页面 */
	public function doPageFollowQrcode(){
		global $_W, $_GPC;

		/* 检查目录是否存在 */
		$dirpath = "../attachment/images/{$_W['uniacid']}/fy_lessonv2/";
		if (!file_exists($dirpath)) {
            mkdir($dirpath, 0777);
        }

		$imagepath = $dirpath."wxapp_follow.png";
		if(!file_exists($imagepath) || filectime($imagepath) > time()+86400){
			load()->classs('wxapp.account');
			$account_api = WeAccount::create();
			$response = $account_api->getCodeUnlimit('1047', 'fy_lessonv2/pages/follow/index', 450, array(
				'auto_color' => true,
				'line_color' => array(
					'r' => '#ABABAB',
					'g' => '#ABABAC',
					'b' => '#ABABAD',
				),
			));
			file_put_contents($imagepath, $response);

			return $this->result(0, '', $_W['uniacid']);
		}
	}

	public function doPayGetopenid() {
		global $_W,$_GPC;

		$ordersn = $_GPC['ordersn'];
		$order_type = substr($ordersn, 0, 1);

		if(is_numeric($_W['openid']) && $_W['openid']>0){
			load()->model('mc');
			$openid = mc_uid2openid($_W['openid']);
		}

		$data = array(
			'openid' => $openid ? $openid : $_W['openid'],
		);
		/* 购买VIP订单 */
		if($order_type=='V'){
			$data['real_url'] = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('mylesson'));
		}
		/* 购买VIP订单 */
		if($order_type=='L'){
			$data['real_url'] = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('vip'));
		}
		/* 购买讲师订单 */
		if($order_type=='T'){
			$teacherorder = pdo_get($this->table_teacher_order, array('ordersn'=>$ordersn));
			$data['real_url'] = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('teacher', array('teacherid'=>$teacherorder['teacherid'])));
		}
		/* 直播课程打赏订单 */
		if($order_type=='T'){
			$awardorder = pdo_get($this->table_live_award, array('ordersn'=>$ordersn));
			$data['real_url'] = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('lesson', array('id'=>$awardorder['lessonid'],'play'=>1)));
		}

		return $this->result(0, '', $data);
	}

	/* 支付参数 */
	public function doPagePay() {
        global $_GPC, $_W;

        $title = $_GPC['title'];
		$ordersn = $_GPC['ordersn'];
		$order_type = substr($ordersn, 0, 1);

		/* 购买VIP订单 */
		if($order_type=='V'){
			$viporder = pdo_get($this->table_member_order, array('ordersn'=>$ordersn));
			$fee = $viporder['vipmoney'];
		}
		/* 购买VIP订单 */	
		if($order_type=='L'){
			$lessonorder = pdo_get($this->table_order, array('ordersn'=>$ordersn));
			$fee = $lessonorder['price'];
		}
		/* 购买讲师订单 */
		if($order_type=='T'){
			$teacherorder = pdo_get($this->table_teacher_order, array('ordersn'=>$ordersn));
			$fee = $teacherorder['price'];
		}
		/* 购买讲师订单 */
		if($order_type=='A'){
			$awardorder = pdo_get($this->table_live_award, array('ordersn'=>$ordersn));
			$fee = $awardorder['price'];
			$real_url = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('lesson', array('id'=>$awardorder['lessonid'],'play'=>1)));
		}

		if(is_numeric($_W['openid'])){
			load()->model('mc');
			$openid = mc_uid2openid($_W['openid']);
		}

		$paylog = pdo_get($this->table_core_paylog, array('tid' => $ordersn, 'status'=>0));
		if(!empty($paylog)){
			pdo_delete($this->table_core_paylog, array('tid' => $ordersn));
		}

        $order = array(
            'tid'	=> $ordersn,
            'user'	=> $openid ? $openid : $_W['openid'],
            'fee'	=> floatval($fee),
            'title' => $title,
        );

        $pay_params = $this->pay($order);
        if (is_error($pay_params)) {
            return $this->result(1, $pay_params);
        }

		/* 订单类型 1.vip订单 2.课程订单 3.讲师订单 4.直播打赏订单 */
		if(!empty($viporder)){
			$pay_params['order_type'] = 1;
		}elseif(!empty($lessonorder)){
			$pay_params['order_type'] = 2;
		}elseif(!empty($teacherorder)){
			$pay_params['order_type'] = 3;
		}elseif(!empty($awardorder)){
			$pay_params['order_type'] = 4;
		}
		$pay_params['real_url'] = $real_url;
        return $this->result(0, '', $pay_params);
    }

/***************************** 支付返回结果处理开始 *******************************/
	
	/* 支付返回确认 */
    public function payResult($params) {
        global $_W, $_GPC;

		$setting = $this->readCache(1);   /* 基本设置 */
		$comsetting = $this->readCache(2);/* 分销设置 */

		include_once dirname(__FILE__).'/inc/core/Payresult.php';
		$pay_result = new Payresult();
		$pay_result->dealResult($params, $setting, $comsetting, $wechat_type='wxapp');
    }


/***************************** 接口数据(审核使用) *********************************/
	/* 基本设置参数 */
	public function doPageSetting() {
		global $_W;
		$setting = $this->readCache(1);

		$data = array(
			'setting' => $setting
		);

		return $this->result(0, '', $data);
	}
	
	/* 轮播图 */
	public function doPageBanner() {
		global $_W;

		$banner = array(
			'0' => array(
				'img' => $_W['siteroot'].'addons/fy_lessonv2/static/public/DemoPic/m_banner3.jpg',
			),
			'1' => array(
				'img' => $_W['siteroot'].'addons/fy_lessonv2/static/public/DemoPic/m_banner2.jpg',
			),
		);
		
		$this->result(0, '', $banner);
	}

	/* 首页展示课程 */
	public function doPageFreelesson(){
		global $_W;
		$setting = $this->readCache(1);

		$lesson = pdo_fetchall("SELECT id,bookname,price,images,buynum,virtual_buynum,update_time,visit_number FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND lesson_type=:lesson_type", array(':uniacid'=>$_W['uniacid'], ':lesson_type'=>2));
		foreach($lesson as $k=>$v){
			$lesson[$k]['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid=:parentid ", array(':parentid'=>$v['id']));
			$lesson[$k]['images'] = $_W['attachurl'].$v['images'];
			$lesson[$k]['buynumber'] = $v['buynum']+$v['virtual_buynum']+$v['visit_number'];
		}
		
		$this->result(0, '', array('lesson'=>$lesson));
	}

	/* 课程详情 */
	public function doPageLesson(){
		global $_W, $_GPC;

		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$sectionid = $_GPC['sectionid'];
		$setting = $this->readCache(1);

		/* 课程信息 */
		$lesson = pdo_fetch("SELECT a.*,b.teacher,b.qq,b.qqgroup,b.qqgroupLink,b.weixin_qrcode,b.teacherphoto,b.teacherdes FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
		if(empty($lesson)){
			$data = array(
				'code'  => -1,
				'msg' => '该课程已下架，您可以看看其他课程',
			);
			$this->result(0, '', $data);
		}
		$lesson['images'] = $_W['attachurl'].$lesson['images'];
		
		/* 章节列表 */
		$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status ORDER BY displayorder DESC, id ASC", array(':parentid'=>$id,':status'=>1));

		$data = array(
			'code'		   => 0,
			'lesson'	   => $lesson,
			'section'	   => $section,
			'section_list' => $section_list,
			'section_count'=> count($section_list),
		);
		$this->result(0, '', $data);
	}

	/* 图文章节课程 */
	public function doPageLessonsArticle(){
		global $_W, $_GPC;

		$uniacid = $_W['uniacid'];
		$id = $_GPC['lessonid'];
		$sectionid = $_GPC['sectionid'];

		$lesson = pdo_fetch("SELECT a.*,b.teacher,b.qq,b.qqgroup,b.qqgroupLink,b.weixin_qrcode,b.teacherphoto,b.teacherdes FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
		if(empty($lesson)){
			$data = array(
				'code' => -1,
				'msg'  => '该课程已下架，您可以看看其他课程',
			);
			$this->result(0, '', $data);
		}

		$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND id=:id AND status=:status LIMIT 1", array(':parentid'=>$id,':id'=>$sectionid,':status'=>1));
		if(empty($section)){
			$data = array(
				'code' => -1,
				'msg'  => '该章节不存在或已被删除',
			);
			$this->result(0, '', $data);
		}

		$section['content'] = htmlspecialchars_decode($section['content']);
		$section['addtime'] = date('Y-m-d', $section['addtime']);

		$data = array(
			'code'	   => 0,
			'advs'	   => $_W['attachurl'].$advs['img'],
			'lesson'   => $lesson,
			'section'  => $section,
		);
		$this->result(0, '', $data);
	}

}