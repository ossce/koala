<?php
/**
 * 个人中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();

$memberid = $_W['member']['uid'];
$site_common->check_black_list('visit', $memberid);

$title = "个人中心";
$self_item = $common['self_item'];
$font = json_decode($comsetting['font'], true);


$page_data = $site_common->getDiyTemplate('', $page_type=3);
if(empty($page_data)){
	/* 顶部背景图 */
	$ucenter_bg = cache_load('fy_lessonv2_'.$uniacid.'_ucenter_bg');
	if(!$ucenter_bg){
		$ucenter_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>7,'is_pc'=>0,'is_show'=>1), array('picture'));
		$ucenter_bg = $ucenter_bg_data ? $_W['attachurl'].$ucenter_bg_data['picture'] : MODULE_URL."static/mobile/{$template}/images/agency-top.jpg?v=4";
		cache_write('fy_lessonv2_'.$uniacid.'_ucenter_bg', $ucenter_bg);
	}

	/* 中间广告图 */
	$self_adv = cache_load('fy_lessonv2_'.$uniacid.'_self_adv');
	if(!$self_adv){
		$self_adv = pdo_get($this->table_banner, array('uniacid'=>$uniacid,'banner_type'=>13,'is_pc'=>0,'is_show'=>1), array('picture','link'));
		cache_write('fy_lessonv2_'.$uniacid.'_self_adv', $self_adv);
	}

	/* 自定义菜单 */
	$self_menu = $site_common->getSelfMenu();
}


$memberinfo = pdo_fetch("SELECT uid,mobile,credit1,credit2,nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid LIMIT 1", array(':uid'=>$memberid));
$memberinfo['credit1'] = intval($memberinfo['credit1']);

if(empty($memberinfo['avatar'])){
	$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
}else{
	$inc = strstr($memberinfo['avatar'], "http://") || strstr($memberinfo['avatar'], "https://");
	$avatar = $inc ? $memberinfo['avatar'] : $_W['attachurl'].$memberinfo['avatar'];
}

/* 签到判断 */
$today = date('Y-m-d', time());
$signin_log = pdo_get($this->table_signin, array('uniacid'=>$uniacid,'uid'=>$memberid,'sign_date'=>$today));

/* 订阅模板消息判断 */
$subscribe_msg = pdo_get($this->table_subscribe_msg, array('uid'=>$memberid));
$is_subscribe = empty($subscribe_msg) || $subscribe_msg['subscribe'] ? 1 : 0;

/* VIP等级数量 */
$memberListCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND is_show=:is_show", array(':uniacid'=>$uniacid,':is_show'=>1));

/* 已购VIP数量 */
$memberVipCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity", array(':uniacid'=>$uniacid,':uid'=>$memberid,':validity'=>time()));

/* 检查会员是否讲师身份 */
$teacher = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND uid=:uid AND status=:status", array(':uniacid'=>$uniacid,':uid'=>$memberid,':status'=>1));

/* 关注的课程数量 */
$collect_lesson = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uid=:uid AND ctype=:ctype", array(':uid'=>$memberid, ':ctype'=>1));

/* 关注的讲师数量 */
$collect_teacher = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uid=:uid AND ctype=:ctype", array(':uid'=>$memberid, ':ctype'=>2));

/* 机构名下讲师数量 */
$company_teachers = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher) . " WHERE uniacid=:uniacid AND company_uid=:company_uid", array('uniacid'=>$uniacid,':company_uid'=>$memberid));

/* 可用优惠券数量 */
$coupon_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND uid=:uid AND status=:status", array(':uniacid'=>$uniacid,':uid'=>$memberid,':status'=>0));


/* 手动更新会员昵称头像 */
if($_GPC['upInfo']){
	load()->classs('weixin.account');
	$accObj = WeixinAccount::create($_W['acid']);
	
	$code = $_GPC['code'];
	if(!$code){
		$callback = $_W['siteroot'] . "app/" . str_replace("./", "", $this->createMobileUrl('self', array('upInfo'=>1)));
		$res1 = $accObj->getOauthUserInfoUrl(urlencode($callback));
		header("Location:" . $res1);
	}else{
		$res2 = $accObj->getOauthInfo($code);
		if($res2['access_token']){
			$res3 = $accObj->getOauthUserInfo($res2['access_token'], $res2['openid']);
			if($res3['nickname'] || $res3['headimgurl']){
				$data = array(
					'nickname' => $res3['nickname'],
					'avatar'   => $res3['headimgurl']
				);
				pdo_update($this->table_mc_members, $data, array('uniacid'=>$uniacid,'uid'=>$memberid));
				message("更新成功", $this->createMobileUrl('self'), "success");
			}
		}else{
			message($res2['message'], $this->createMobileUrl('self'), "info");
		}
	}
}

include $this->template("../mobile/{$template}/self");


?>