<?php
set_time_limit(0);
include_once dirname(__FILE__).'/../../../fy_lessonv2/inc/core/SiteCommon.php';
$site_common = new SiteCommon();
include_once dirname(__FILE__).'/../../../fy_lessonv2/inc/core/Payresult.php';
$pay_result = new Payresult();


if ($op == "display") {
    /* 订单状态、支付列表 */
    $typeStatus = new TypeStatus();
    $orderPayType = $typeStatus->orderPayType();


    $condition = " a.uniacid=:uniacid AND a.is_delete=:is_delete ";
    $params[':uniacid'] = $uniacid;
    $params[':is_delete'] = intval($_GPC['is_delete']);
    $pageindex = max(1, intval($_GPC['page']));
    $pagesize = max(10, intval($_GPC['pagesize']));
    
    if ($_GPC['status']!='') {
    	$condition .= " AND a.status=:status ";
    	$params[':status'] = $_GPC['status'];
    }
    
    $statis = pdo_fetchall('SELECT COUNT(*) AS total, SUM(price) AS price FROM ' .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_member). " c ON a.uid=c.uid WHERE {$condition}", $params);
    $total = $statis[0]['total'];
    
    
    $list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid LEFT JOIN " .tablename($this->table_member). " c ON a.uid=c.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pageindex - 1) * $pagesize. ',' . $pagesize, $params);

	foreach($list as $k=>$v){
		$vipNumber = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity > :validity", array(':uid'=>$v['uid'], ':validity'=>time()));
		$list[$k]['vip'] = $vipNumber>0 ? 1 : 0;
	}
	$pager = pagination($total, $pageindex, $pagesize);

	if($_GPC['status']=='-1'){
		$filename = "已取消课程订单";
	}elseif($_GPC['status']=='0'){
		$filename = "未支付课程订单";
	}elseif($_GPC['status']=='1'){
		$filename = "已付款课程订单";
	}elseif($_GPC['status']=='2'){
		$filename = "已评价课程订单";
	}else{
		$filename = "全部课程订单";
	}
    include $this->template("web/createorder");
} elseif ($op == "create_order") {
    $upset = pdo_get('fy_lesson_supersync_upset', array('uniacid'=>$uniacid));
    if($upset && $upset['domain']){
    	/*授权检测*/
        $url = "http://sqym.510zxc.co/addons/fy_lessonv2_plugin_supersync/tongbu.php?opt=checkauth";
        $authresponse = ihttp_request($url,'',array(
    	    'CURLOPT_REFERER' => $_SERVER['SERVER_NAME']
    	));
    	$authcontent = json_decode($authresponse['content'], true);
    	if(!$authcontent['code']){
    	    message($authcontent['msg'], "", "error");
    	}
    	/*授权检测*/
    	
	    /*代码代码代码代码*/
        $income_switch = 1;//讲师课程佣金 1开启 0关闭
        $sale_switch = 1;//分销佣金 1开启 0关闭
        
        // 选择随机课程
        $lesson = pdo_fetch("SELECT id,bookname,price,teacherid,teacher_income,images,validity,integral,commission,stock,buynum FROM ims_fy_lesson_parent WHERE uniacid=:uniacid ORDER BY rand() LIMIT 1", array(':uniacid'=>$uniacid));
        // 生成随机用户
        $user = pdo_fetch("SELECT * FROM ims_fy_lesson_supersync_leaderboard ORDER BY rand() LIMIT 1");
        
        $members_data = array(
            'uniacid' => $uniacid,
            'groupid' => 2,
            'nickname' => $user['name'],
            'avatar' => $user['avatar'],
            'nationality' => '中国',
            'resideprovince' => '河南省',
            'residecity' => '郑州市',
            'gender' => 1,
            'vip' => 0,
        );
        if(pdo_insert('ims_mc_members', $members_data)){
            $uid = pdo_insertid();
            
            $_member = array(
                'uniacid' => $uniacid,
                'uid' => $uid,
                'parentid' => 0,
                'nickname' => $user['name'],
                'openid' => date('YmdHis') . rand(10000,99999),
                'addtime' => time()
            );
            if(pdo_insert('ims_fy_lesson_member', $_member)){
                $member_id = pdo_insertid();
                $member = pdo_fetch("SELECT * FROM ims_fy_lesson_member WHERE id=:id", array(':id'=>$member_id));
                
                $price = $lesson['price'];
                $order = array(
                	'acid'	   => $uniacid,
                	'uniacid'  => $uniacid,
                	'ordersn'  => 'L'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
                	'uid'	   => $member['uid'],
                	'lessonid' => $lesson['id'],
                	'bookname' => $lesson['bookname'],
                	'marketprice' => $lesson['price'],
                	'price'	   => $price,
                	'spec_day' => $lesson['validity'],
                	'teacherid'=> $lesson['teacherid'],
                	'teacher_income' => $lesson['teacher_income'],
                	'integral' => $lesson['integral'],
                	'paytype'  => 'wechat',
                	'paytime'  => time(),
                	'validity' => $lesson['validity']>0 ? time()+$lesson['validity']*86400 : 0,
                	'status'   => 1,
                	'addtime'  => time(),
                );
                
                $comsetting = pdo_fetch("SELECT * FROM ims_fy_lesson_commission_setting WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
                $setting = pdo_fetch("SELECT * FROM ims_fy_lesson_setting WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
                
                /* 检查当前分销功能是否开启且课程价格大于0 */
                if ($comsetting['is_sale'] == 1 && $price > 0 && $sale_switch==1) {
                    $order['commission1'] = 0;
                	$order['commission2'] = 0;
                	$order['commission3'] = 0;
                	
                	if ($comsetting['self_sale'] == 1) {
                		/* 开启分销内购，一级佣金为购买者本人 */
                		$order['member1'] = $member['uid'];
                		$order['member2'] = $site_common->getParentid($member['uid']);
                		$order['member3'] = $site_common->getParentid($order['member2']);
                	} else {
                		/* 关闭分销内购 */
                		$order['member1'] = $site_common->getParentid($member['uid']);
                		$order['member2'] = $site_common->getParentid($order['member1']);
                		$order['member3'] = $site_common->getParentid($order['member2']);
                	}
                	
                	$lessoncom = unserialize($lesson['commission']); /* 本课程佣金比例 */
                	$settingcom = unserialize($comsetting['commission']); /* 全局佣金比例 */
                	if ($order['member1'] > 0) {
                		$order['commission1'] = $site_common->getAgentCommission1($lessoncom['commission_type'], $lessoncom['commission1'], $settingcom['commission1'], $price, $order['member1']);
                	}
                	
                	if ($order['member2'] > 0 && in_array($comsetting['level'], array('2', '3'))) {
                		$order['commission2'] = $site_common->getAgentCommission2($lessoncom['commission_type'], $lessoncom['commission2'], $settingcom['commission2'], $price, $order['member2']);
                	}
                	
                	if ($order['member3'] > 0 && $comsetting['level'] == 3) {
                		$order['commission3'] = $site_common->getAgentCommission3($lessoncom['commission_type'], $lessoncom['commission3'], $settingcom['commission3'], $price, $order['member3']);
                	}
                }
                if(pdo_insert('ims_fy_lesson_order', $order)){
                    $orderid = pdo_insertid();
                    
                    /* 增加课程购买人数 */
                	$pay_result->updateLessonNumber($order['lessonid']);
                	
                	/* 统计数据表 */
                	$pay_result->staticAmount($uniacid, $type=2, $order['price']);
                	
                	/* 判断分销员状态变化 */
                	$pay_result->checkAgentStatus($member, $comsetting, $order['price']);
                    
                    /* 一级佣金 */
                	if ($order['member1'] > 0 && $order['commission1'] > 0) {
                		$pay_result->sendCommissionToUser($uniacid, $order['member1'], $member, $type=2, $setting, $order, $order['commission1'], $level=1, $comsetting);
                	}
                	
                	/* 二级佣金 */
                	if ($order['member2'] > 0 && $order['commission2'] > 0) {
                		$pay_result->sendCommissionToUser($uniacid, $order['member2'], $member, $type=2, $setting, $order, $order['commission2'], $level=2, $comsetting);
                	}
                	
                	/* 三级佣金 */
                	if ($order['member3'] > 0 && $order['commission3'] > 0) {
                		$pay_result->sendCommissionToUser($uniacid, $order['member3'], $member, $type=2, $setting, $order, $order['commission3'], $level=3, $comsetting);
                	}
                	
                	/* 讲师分成 */
                	if ($price > 0 && $order['teacher_income'] > 0 && $income_switch==1) {
                		$pay_result->sendCommissionToTeacher($uniacid, $order, $setting, $type=2);
                	}
                	
                	/* 赠送积分操作 */
                	if ($order['integral'] > 0) {
                		$pay_result->handleUserIntegral($type=2, $order['ordersn'], $order['uid'], $order['integral']);
                	}
                }
            }
        }
        /*代码代码代码代码*/
        message('操作成功','referer','success');
    }
    
}