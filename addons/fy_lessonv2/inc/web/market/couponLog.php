<?php

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = "a.uniacid=:uniacid";
$params[':uniacid'] = $uniacid;

if (!empty($_GPC['ordersn'])) {
	$condition .= " AND a.ordersn LIKE :ordersn ";
	$params[':ordersn'] = "%".$_GPC['ordersn']."%";
}
if ($_GPC['uid']) {
	$condition .= " AND a.uid=:uid ";
	$params[':uid'] = $_GPC['uid'];
}
if ($_GPC['status']!='') {
	$condition .= " AND a.status=:status ";
	$params[':status'] = $_GPC['status'];
}
if ($_GPC['source']) {
	$condition .= " AND a.source=:source ";
	$params[':source'] = $_GPC['source'];
}
if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}
$condition .= " AND a.uid>0";

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);


if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.mobile,b.realname FROM " .tablename($this->table_member_coupon). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT ".($pindex - 1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "".$category['name']."分类课程" : "全部分类课程";
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程";
		}
		unset($category);

		if(time()>$v['validity'] && $v['status']==0){
			pdo_update($this->table_member_coupon, array('status'=>-1), array('id'=>$v['id']));
			$v['status'] = -1;
		}

		$list[$k] = $v;
	}

	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.mobile,b.realname FROM " .tablename($this->table_member_coupon). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC", $params);
	foreach ($list as $key => $value) {
		$arr[$key]['uid']				= $value['uid'];
		$arr[$key]['nickname']			= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['mobile']			= $value['mobile'];
		$arr[$key]['realname']			= $value['realname'];
		$arr[$key]['amount']			= $value['amount'];
		$arr[$key]['conditions']		= $value['conditions'];
		if($value['use_type']==1){
			$arr[$key]['use_scope']		= $value['category_id'] ? '部分课程分类可用' : '全部课程分类可用';
		}elseif($value['use_type']==2){
			$arr[$key]['use_scope']		= '指定课程可用';
		}
		$arr[$key]['validity']		    = date('Y-m-d H:i:s', $value['validity']);
		if($value['status'] == 1){
			$arr[$key]['status']		= '已使用';
		}elseif($value['status'] == '-1'){
			$arr[$key]['status']		= '已过期';
		}elseif($value['status'] == '0'){
			$arr[$key]['status']		= time() > $value['validity'] ? '已过期' : '未使用';
		}
		$arr[$key]['addtime']		    = date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('用户ID', '昵称', '姓名', '手机号码', '优惠券面值(元)', '使用门槛(订单满x元用)', '使用范围', '有效期', '状态','领取时间');
	$site_common->exportCSV($arr, $title, $fileName="优惠券记录");
}
