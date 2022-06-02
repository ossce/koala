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
    
    /* VIP等级列表 */
    $level_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid ORDER BY sort", array(':uniacid'=>$uniacid));
    $level_name_list = array();
    foreach($level_list as $item){
    	$level_name_list[$item['id']] = $item['level_name'];
    }
    
    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    
    $condition = " a.uniacid = :uniacid";
    $params[':uniacid'] = $uniacid;
    
    
    $statis = pdo_fetchall('SELECT COUNT(*) AS total, SUM(vipmoney) AS vipmoney FROM ' .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
    $total = $statis[0]['total'];
    
    $list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_member_order). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		$list[$k]['level'] = pdo_fetch("SELECT * FROM " .tablename($this->table_vip_level). " WHERE id=:id", array(':id'=>$v['level_id']));
	}
	
	$pager = pagination($total, $pindex, $psize);

    include $this->template("web/createviporder");
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
	    // 选择随机等级
        $level = pdo_fetch("SELECT * FROM ims_fy_lesson_vip_level WHERE uniacid=:uniacid ORDER BY rand() LIMIT 1", array(':uniacid'=>$uniacid));
        
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
                $data = array(
                	'uniacid'	=> $uniacid,
                	'uid'		=> $uid,
                	'level_id'	=> $level['id'],
                	'validity'	=> strtotime("+30 day"),
                );
                $data['discount'] = $level['discount'];
                
                /*检查会员是否开通过该等级*/
                $member_vip = pdo_fetch("SELECT * FROM ims_fy_lesson_member_vip WHERE uniacid=:uniacid AND uid=:uid AND level_id=:level_id", array(':uniacid'=>$uniacid, ':uid'=>$data['uid'], ':level_id'=>$data['level_id']));
                if(empty($member_vip)){
                	$data['addtime'] = time();
                	$res = pdo_insert('ims_fy_lesson_member_vip', $data);
                }else{
                	$data['update_time'] = time();
                	$res = pdo_update('ims_fy_lesson_member_vip', $data, array('id'=>$member_vip['id']));
                }
                
                if($res){
                    /* 添加VIP服务订单 */
                	$days = ceil(($data['validity']-time())/86400);
                	$vipOrder = array(
                		'acid' => $_W['acid'],
                		'uniacid' => $uniacid,
                		'ordersn' => 'V'.date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999)),
                		'uid' => $data['uid'],
                		'viptime' => $days,
                		'vipmoney' => $level['level_price'],
                		'paytype' => 'wechat',
                		'status' => 1,
                		'paytime' => time(),
                		'addtime' => time(),
                		'level_id' => $data['level_id'],
                		'level_name' => $level['level_name'],
                	);
                	
                	$res = pdo_insert('ims_fy_lesson_member_order', $vipOrder);
                	
                	/* 更新会员vip字段 */
                	pdo_update('ims_fy_lesson_member', array("vip" => 1), array("uid" => $data['uid']));
                	
                	/* 订单金额加入今日销售额汇总表 */
        	        $pay_result->staticAmount($uniacid, 1, $vipOrder['vipmoney']);
                }
            }
        }
        /*代码代码代码代码*/
    	
    	message('操作成功','referer','success');
    }
} elseif ($op == "delAll") {
    $ids = explode(",", $_GPC['ids']);
    foreach($ids as $id){
        $vipOrder = pdo_fetch("SELECT * FROM " .tablename($this->table_member_order). " WHERE id=:id", array(':id'=>$id));
        /* 订单金额减去今日销售额汇总表 */
        $pay_result->staticAmount($uniacid, 11, $vipOrder['vipmoney']);
        
    	pdo_delete($this->table_member_order, array('id'=>$id));
    }
    
    if(!empty($_GPC['ids'])){
    	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "VIP订单", "批量删除VIP订单ID:{$_GPC['ids']}");
    }
    
    exit(json_encode([
        'code' => 0,
        'msg'=> '批量删除成功！',
        'category' => ''
    ]));
}