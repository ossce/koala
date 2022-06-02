<?php

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

/* 会员昵称 */
if (!empty($_GPC['nickname'])) {
	$condition .= " AND ( b.realname LIKE :nickname OR b.nickname LIKE :nickname OR b.mobile LIKE :nickname)";
	$params[':nickname'] = "%".trim($_GPC['nickname'])."%";
}
/* 会员ID */
if (!empty($_GPC['uid'])) {
	$condition .= " AND b.uid=:uid ";
	$params[':uid'] = intval($_GPC['uid']);
}
/* 推荐人ID */
if ($_GPC['parentid']!='') {        
	$condition .= " AND a.parentid =:parentid ";
	$params[':parentid'] = $_GPC['parentid'];
}
/* 分销状态 */
if ($_GPC['status'] != '') {
	$condition .= " AND a.status=:status";
	$params[':status'] = intval($_GPC['status']);
}
/* 分销级别 */
if ($_GPC['agent_level'] != '') {
	$condition .= " AND a.agent_level=:agent_level";
	$params[':agent_level'] = $_GPC['agent_level'];
}
/* VIP身份 */
if ($_GPC['vip'] != '') {
	$condition .= " AND a.vip=:vip";
	$params[':vip'] = intval($_GPC['vip']);
}
/* 加入时间 */
if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}
if($_GPC['orderby']=='addtime'){
	$orderby = " ORDER BY a.addtime DESC";
}else{
	$orderby = " ORDER BY a.uid DESC";
}

$total = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list  = pdo_fetchall("SELECT a.uid,a.parentid,a.nopay_commission,a.pay_commission,a.agent_level,a.status,a.addtime, b.mobile,b.realname,b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} {$orderby} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $key=>$value){
		if(empty($value['avatar'])){
			$list[$key]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$list[$key]['avatar'] = (strstr($value['avatar'], "http://") || strstr($value['avatar'], "https://")) ? $value['avatar'] : $_W['attachurl'].$value['avatar'];
		}

		$list[$key]['parent'] = pdo_fetch("SELECT nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$value['parentid']));
		if(empty($list[$key]['parent']['avatar'])){
			$list[$key]['parent']['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$list[$key]['parent']['avatar'] = (strstr($list[$key]['parent']['avatar'], "http://") || strstr($list[$key]['parent']['avatar'], "https://")) ? $list[$key]['parent']['avatar'] : $_W['attachurl'].$list[$key]['parent']['avatar'];
		}
		$list[$key]['agent'] = $levelList[$value['agent_level']] ? $levelList[$value['agent_level']] : '默认等级';
		$list[$key]['teachers'] = pdo_fetchcolumn("SELECT count(*) FROM " .tablename($this->table_teacher). " WHERE company_uid=:company_uid", array(':company_uid'=>$value['uid']));
	}
	
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	
	$list  = pdo_fetchall("SELECT a.uid,a.parentid,a.nopay_commission,a.pay_commission,a.payment_amount,a.payment_order,a.agent_level,a.status, a.addtime, b.mobile,b.realname,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} {$orderby} ", $params);
	
	foreach ($list as $key => $value) {
		$arr[$key]['uid']				= $value['uid'];
		$arr[$key]['nickname']			= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['realname']			= $value['realname'];
		$arr[$key]['mobile']			= $value['mobile'];
		$arr[$key]['status']			= $agentStatusList[$value['status']];
		$arr[$key]['levelname']			= $levelList[$value['agent_level']] ? $levelList[$value['agent_level']] : '默认等级';
		$arr[$key]['pay_commission']	= $value['pay_commission'];
		$arr[$key]['nopay_commission']	= $value['nopay_commission'];
		$arr[$key]['payment_amount']	= $value['payment_amount'];
		$arr[$key]['payment_order']		= $value['payment_order'];
		$arr[$key]['fans_count']		= $site_common->getFansCount($value['uid']);

		if($value['parentid']){
			$parent = pdo_get($this->table_mc_members,array('uniacid'=>$uniacid,'uid'=>$value['parentid']), array('uid','mobile','nickname','realname'));
		}
		$arr[$key]['parent_uid']		= $parent['uid'];
		$arr[$key]['parent_nickname']	= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$parent['nickname']);
		$arr[$key]['parent_realname']	= $parent['realname'];
		$arr[$key]['parent_mobile']		= $parent['mobile'];

		$arr[$key]['addtime']		    = date('Y-m-d H:i:s', $value['addtime']);
		unset($parent);
	}

	$title = array('用户ID', '昵称', '姓名', '手机号码', '分销商状态', '分销商级别', '已结算佣金(元)', '未结算佣金(元)','订单金额(元)','订单笔数','下级成员数量','推荐人ID','推荐人昵称','推荐人姓名','推荐人手机','加入时间');
	$site_common->exportCSV($arr, $title, $fileName="分销商列表");

}