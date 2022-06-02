<?php


$time = time();
$keyword = trim($_GPC['keyword']);

if($_W['isfounder']){
	//创始人(含副创始人)

	$condition = " a.uniacid != :uniacid AND b.isdeleted=:isdeleted ";
	$params = array(
		':uniacid'	=> $uniacid,
		':isdeleted' => 0,
	);
	if(!empty($keyword)){
		$condition .= " AND a.name LIKE :name ";
		$params[':name'] = "%".$keyword."%";
	}
	$list = pdo_fetchall("SELECT a.uniacid,a.name FROM " .tablename('account_wechats'). " a INNER JOIN " .tablename('account'). " b ON a.uniacid=b.uniacid WHERE {$condition}", $params);

}else{
	//非创始人
	$uid = $_W['uid'];
	$list = pdo_fetchall("SELECT DISTINCT(uniacid) FROM " .tablename('uni_account_users'). " WHERE uid=:uid ", array(':uid'=>$uid));
	
	$list = array();
	foreach($uniacid_list as $v){
		$account = pdo_fetch("SELECT a.uniacid,a.name FROM " .tablename('account_wechats'). " a INNER JOIN " .tablename('account'). " b ON a.uniacid=b.uniacid WHERE a.uniacid=:uniacid1 AND a.uniacid!=:uniacid2 AND b.isdeleted=:isdeleted", array(':uniacid1'=>$v['uniacid'],':uniacid2'=>$uniacid,':isdeleted'=>0));
		if(!empty($account)){
			$list[] = $account;
			unset($account);
		}
	}
}

include $this->template('web/getWechatList');