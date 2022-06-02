<?php
/**
 * 积分余额明细
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];

/* 个人中心自定义字体 */
$self_page = $common['self_page'];
$type = $_GPC['type'];

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid=:uniacid AND uid=:uid ";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;

if($type==1){
	$title = $self_page['credit1'] ? $self_page['credit1'].'明细' : '会员积分明细';
	$credittype = 'credit1';

}elseif($type==2){
	$title = $self_page['credit2'] ? $self_page['credit2'].'明细' : '会员余额明细';
	$credittype = 'credit2';
}

$condition .= " AND credittype=:credittype ";
$params[':credittype'] = $credittype;

if($_W['isajax']){
	$list = pdo_fetchall("SELECT num,module,createtime,remark FROM " . tablename('mc_credits_record') . " WHERE {$condition} ORDER BY createtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
		$v['award_name'] = $v['num']>0 ? '充值' : '消费';
		$list[$k] = $v;
	}

	$this->resultJson($list);
}else{
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_credits_record') . " WHERE {$condition} ", $params);
}


include $this->template("../mobile/{$template}/credit");


?>