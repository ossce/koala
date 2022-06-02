<?php
/**
 * 我的讲师服务
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

$uid = $_W['member']['uid'];
$site_common->check_black_list('visit', $uid);

if($op=='display'){
	$title = '我的讲师服务';

	$myteacher_bg = cache_load('fy_lessonv2_'.$uniacid.'_myteacher_bg');
	if(!$myteacher_bg){
		$myteacher_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>9,'is_pc'=>0,'is_show'=>1), array('picture'));
		$myteacher_bg = $myteacher_bg_data ? $_W['attachurl'].$myteacher_bg_data['picture'] : MODULE_URL."static/mobile/{$template}/images/myteacher-bg.png";
		cache_write('fy_lessonv2_'.$uniacid.'_myteacher_bg', $myteacher_bg);
	}
	
	/*我的讲师服务列表*/	
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
	foreach($list as $k=>$v){
		$list[$k]['teacher'] = pdo_get($this->table_teacher, array('id'=>$v['teacherid']));
	}
	
}elseif($op=='ajaxgetlist'){
	/* 购买讲师服务订单 */
	$pindex =max(1,$_GPC['page']);
	$psize = 5;

	$order_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_order). " WHERE uid=:uid AND status=:status ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uid'=>$uid,':status'=>1));
	foreach($order_list as $key=>$value){
		$order_list[$key]['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
		$order_list[$key]['paytime'] = $value['paytime']>0?date('Y-m-d H:i:s', $value['paytime']):'';
		$order_list[$key]['status']  = $value['status']==0?'未支付':'购买成功';
		if($value['paytype']=='credit'){
			$order_list[$key]['paytype'] = '余额支付';
		}elseif($value['paytype']=='wechat'){
			$order_list[$key]['paytype'] = '微信支付';
		}elseif($value['paytype']=='alipay'){
			$order_list[$key]['paytype'] = '支付宝支付';
		}elseif($value['paytype']=='vipcard'){
			$order_list[$key]['paytype'] = '服务卡支付';
		}elseif($value['paytype']=='admin'){
			$order_list[$key]['paytype'] = '后台支付';
		}elseif($value['paytype']=='wxapp'){
			$order_list[$key]['paytype'] = '微信小程序';
		}
		$teacher =  pdo_get($this->table_teacher, array('id'=>$value['teacherid']));
		$order_list[$key]['teacher_name'] = $teacher['teacher'];
	}

	$this->resultJson($order_list);
}


include $this->template("../mobile/{$template}/myteacher");