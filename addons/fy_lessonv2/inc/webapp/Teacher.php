<?php
/**
 * 讲师主页
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


$teacher_home = $common['teacher_home']; /* 页面文字 */
$teacherid = intval($_GPC['teacherid']); /* 讲师id */

/* 讲师信息 */
$teacher = pdo_fetch("SELECT * FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$teacherid));
if(!$teacher){
	message("该讲师不存在或已被删除！", "", "error");
}

$title = $teacher['teacher'].'的主页';

if($op=='display'){
	
	/* 讲师主页背景图 */
	$teacher_bg = cache_load('fy_lessonv2_'.$uniacid.'_teacher_bg_pc_'.$teacherid);
	if(!$teacher_bg){
		if($teacher['teacher_bg_pc']){
			$teacher_bg = $_W['attachurl'].$teacher['teacher_bg_pc'];
		
		}else{
			$teacher_bg_data = pdo_get($this->table_banner, array('uniacid'=>$uniacid, 'banner_type'=>10, 'is_pc'=>1), array('picture'));
			$teacher_bg = $teacher_bg_data ? $_W['attachurl'].$teacher_bg_data['picture'] : MODULE_URL."static/webapp/{$template}/images/teacher_bg_pc.jpg";
		}
		cache_write('fy_lessonv2_'.$uniacid.'_teacher_bg_pc_'.$teacherid, $teacher_bg);
	}


	/* 购买讲师信息 */
	$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$teacherid));

	if($uid){
		/* 判断当前用户是否为讲师 */
		if($uid==$teacher['uid']){
			$teacherself = true;
		}

		/* 当前用户是否拥有讲师服务 */
		$buy_teacher = pdo_fetch("SELECT * FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND teacherid=:teacherid AND validity>:validity", array(':uid'=>$uid, ':teacherid'=>$teacherid, ':validity'=>time()));

		/* 查询是否收藏该课程 */
		$collect = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_collect). " WHERE uniacid=:uniacid AND uid=:uid AND outid=:outid AND ctype=:ctype LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':outid'=>$teacherid,':ctype'=>2));
	}

	/* 默认显示页面 */
	$teacher_home_show = $teacher['teacher_home_show'] ? $teacher['teacher_home_show'] : $common['teacher_home_show'];
	$teacher_home_show = $teacher_home_show ? $teacher_home_show : 1;


	/* 讲师课程列表 */
	$pindex =max(1,$_GPC['page']);
	$psize = 16;

	$condition = " uniacid=:uniacid AND teacherid=:teacherid AND status=:status ";
	$params[':uniacid'] = $uniacid;
	$params[':teacherid'] = $teacherid;
	$params[':status'] = 1;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} ORDER BY displayorder DESC,addtime DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);

	$student_num = 0;
	foreach($list as $k=>$v){
		if($v['price']>0){
			$v['buyTotal'] = $v['virtual_buynum'] + $v['buynum'];
		}else{
			$v['buyTotal'] = $v['virtual_buynum'] + $v['buynum'] + $v['visit_number'];
		}
		$v['vipview'] = json_decode($v['vipview'], true);
		if($v['score']>0){
			$v['score_rate'] = $v['score']*100;
		}else{
			$v['score_rate'] = "";
		}
		if($v['ico_name'] == 'ico-vip' && (!empty($v['vipview']) && $v['vipview'] != 'null')){
			$v['ico_name'] = 'ico-vip';
		}
		$v['descript'] = strip_tags(html_entity_decode($v['descript']));
		if($v['lesson_type']==1){
			$buynow_info = json_decode($v['buynow_info'], true);
			if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
				$v['ico_name'] = 'ico-appointed';
			}

			/* 重新计算报名课程学习人数 */
			$v['buyTotal'] = $v['buynum']+$v['virtual_buynum'];
		}elseif($v['lesson_type']==3){
			$live_info = json_decode($v['live_info'], true);
			$starttime = strtotime($live_info['starttime']);
			$endtime = strtotime($live_info['endtime']);
			if(time() < $starttime){
				$v['icon_live_status'] = 'icon-live-nostart';
			}elseif(time() > $endtime){
				$v['icon_live_status'] = 'icon-live-ended';
			}elseif(time() > $starttime && time() < $endtime){
				$v['icon_live_status'] = 'icon-live-starting';
			}
		}

		$v['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$v['id'],':status'=>1));
		
		$student_num += $v['buyTotal'];
		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='buyTeacher'){
	$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$teacherid));
	if(!$teacher_price){
		message("该讲师未开启购买", "", "error");
	}

	if ($setting['mustinfo']) {
		$user_info = json_decode($setting['user_info']);
		$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$uid));
		$jumpurl = $_W['siteroot']."{$uniacid}/memberInfo.html?teacherid={$teacherid}&type=buyteacher";

		if(!empty($common_member_fields)){
			foreach($common_member_fields as $v){
				if(in_array($v['field_short'],$user_info) && empty($member[$v['field_short']])){
					 message("请完善您的".$v['field_name'], $jumpurl, "warning");
				}
			}
		}
	}

	/* 构造订单信息 */
	$orderdata = array(
		'uniacid'		 => $uniacid,
		'ordersn'		 => 'T'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
		'uid'			 => $uid,
		'ordertime'		 => $teacher_price['validity_time'],
		'price'			 => $teacher_price['price'],
		'integral'		 => $teacher_price['integral'],
		'teacherid'		 => $teacherid,
		'teacher_name'	 => $teacher['teacher'],
		'teacher_income' => $teacher_price['teacher_income'],
		'addtime'		 => time(),
		'update_time'    => time(),
	);

	/* 分销功能和购买讲师分销同时开启 */
	if($comsetting['is_sale']==1 && $teacher['is_distribution']==1){
		$orderdata['commission1'] = 0;
		$orderdata['commission2'] = 0;
		$orderdata['commission3'] = 0;

		if($comsetting['self_sale']==1){
			/* 开启分销内购，一级佣金为购买者本人 */
			$orderdata['member1'] = $uid;
			$orderdata['member2'] = $site_common->getParentid($uid);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}else{
			/* 关闭分销内购 */
			$orderdata['member1'] = $site_common->getParentid($uid);;
			$orderdata['member2'] = $site_common->getParentid($orderdata['member1']);
			$orderdata['member3'] = $site_common->getParentid($orderdata['member2']);
		}

		$teacher_com = json_decode($teacher['commission'], true); /* 购买讲师单独佣金比例 */
		$settingcom = unserialize($comsetting['commission']);	  /* 全局佣金比例 */
		if($orderdata['member1']>0){
			$orderdata['commission1'] = $site_common->getAgentCommission1($commission_type=0, $teacher_com['commission1'], $settingcom['commission1'], $orderdata['price'], $orderdata['member1']);
		}
		if($orderdata['member2']>0 && in_array($comsetting['level'], array('2','3'))){
			$orderdata['commission2'] = $site_common->getAgentCommission2($commission_type=0, $teacher_com['commission2'], $settingcom['commission2'], $orderdata['price'], $orderdata['member2']);
		}
		if($orderdata['member3']>0 && $comsetting['level']==3){
			$orderdata['commission3'] = $site_common->getAgentCommission3($commission_type=0, $teacher_com['commission3'], $settingcom['commission3'], $orderdata['price'], $orderdata['member3']);
		}
	}

	if($uid>0){
		$result = pdo_insert($this->table_teacher_order, $orderdata);
		$orderid = pdo_insertid();
	}

	if($result){
		header("Location:".$_W['siteroot']."{$uniacid}/payment.html?orderid={$orderid}&ordertype=buyteacher");
	}else{
		message("写入订单信息失败，请重试！", $_W['siteroot']."{$uniacid}/teacher.html?teacherid={$teacherid}", "error");
	}

}



include $this->template("../webapp/{$template}/teacher");


?>