<?php
set_time_limit(0);
include_once dirname(__FILE__).'/../../../fy_lessonv2/inc/core/SiteCommon.php';
$site_common = new SiteCommon();
include_once dirname(__FILE__).'/../../../fy_lessonv2/inc/core/Payresult.php';
$pay_result = new Payresult();


if ($op == "display") {
    $condition = " a.uniacid=:uniacid ";
    $params[':uniacid'] = $uniacid;
    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_cashlog) . "  a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
    
    $list = pdo_fetchall("SELECT a.*,b.mobile,b.nickname,b.avatar FROM " . tablename($this->table_cashlog) . " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    
	foreach($list as $k=>$v){
		if(empty($v['avatar'])){
			$list[$k]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
			$list[$k]['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
		}
	}
	$pager = pagination($total, $pindex, $psize);
    
    include $this->template("web/createtxorder");
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
                $cashlog = array(
    				'uniacid'	  => $uniacid,
    				'cash_way'	  => 2, //1.提现到余额  2.提现到微信钱包 3.支付宝
    				// 'pay_account' => '',
    				// 'pay_name'    => '',
    				'uid'		  => $uid,
    				'openid'	  => $_member['openid'],
    				'cash_num'    => rand(0, 200),
    				'lesson_type' => 1, /* 提现类型 1.分销佣金提现 2.课程收入提现 */
    				'addtime'	  => time(),
    				'status'	  => 1,
    			);
    			pdo_insert($this->table_cashlog, $cashlog);
    			
    			$tplmessage = pdo_fetch("SELECT newcash, newcash_format FROM " .tablename($this->table_tplmessage). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
				$newcash_format = json_decode($tplmessage['newcash_format'], true);

				$manage = explode(",", $setting['manageopenid']);
				foreach($manage as $manageopenid){
					$sendneworder = array(
						'touser'      => $manageopenid,
						'template_id' => $tplmessage['newcash'],
						'url'         => "",
						'topcolor'    => "",
						'data'        => array(
							'first'=> array(
								'value' => $newcash_format['first'] ? $newcash_format['first'] : "亲，您收到一条新的用户提现申请",
								'color' => "",
							),
							'keyword1'  => array(
								'value' => $user['name'],
								'color' => "",
							),
							'keyword2'  => array(
								'value' => date('Y-m-d H:i', time()),
								'color' => "",
							),
							'keyword3'  => array(
								'value' => $cashlog['cash_num']."元",
								'color' => "",
							),
							'keyword4'  => array(
								'value' => '微信钱包',
								'color' => "",
							),
							'remark'	=> array(
								'value' => $newcash_format['remark'] ? $newcash_format['remark'] : "详情请登录网站后台查看！",
								'color' => "",
							),
						)
					);
					$site_common->send_template_message($sendneworder,$_W['acid']);
				}
            }
        }
        /*代码代码代码代码*/
    	
    	message('操作成功','referer','success');
    }
    
}