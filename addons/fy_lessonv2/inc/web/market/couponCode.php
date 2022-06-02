<?php

$time = time();

$condition = " uniacid = :uniacid ";
$params['uniacid'] = $uniacid;

if(intval($_GPC['card_id'])){
	$condition .= " AND card_id=:card_id ";
	$params[':card_id'] = intval($_GPC['card_id']);
}
if (trim($_GPC['prefix'])) {
	$condition .= " AND password LIKE :prefix ";
	$params[':prefix'] = trim($_GPC['prefix'])."%";
}
if (trim($_GPC['password'])) {
	$condition .= " AND password=:password ";
	$params[':password'] = trim($_GPC['password']);
}
if ($_GPC['is_use'] != '') {
	if($_GPC['is_use']==0){
		$condition .= " AND is_use = :is_use AND validity > :validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}elseif($_GPC['is_use']==1){
		$condition .= " AND is_use = :is_use ";
		$params[':is_use'] = $_GPC['is_use'];
	}elseif($_GPC['is_use']==-1){
		$condition .= " AND is_use = :is_use AND validity < :validity ";
		$params[':is_use'] = 0;
		$params[':validity'] = $time;
	}
}
if(intval($_GPC['uid'])){
	$condition .= " AND uid=:uid ";
	$params[':uid'] = intval($_GPC['uid']);
}
if (trim($_GPC['nickname'])) {
	$condition .= " AND nickname LIKE :nickname ";
	$params[':nickname'] = "%".trim($_GPC['nickname'])."%";
}
if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;

	$condition .= " AND a.addtime>=:starttime AND a.addtime<=:endtime";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_coupon). " WHERE {$condition}", $params);

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	foreach($list as $k=>$v){
		if($v['use_type']==1){
			$category = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$v['category_id']), array('name'));
			$v['category_name'] = $category['name'] ? "[".$category['name']."]分类课程" : "全部分类课程";
		}elseif($v['use_type']==2){
			$v['category_name'] = "指定部分课程";
			
			$v['lesson_list'] = "";
			$lesson_ids = json_decode($v['lesson_ids'],true);
			foreach($lesson_ids as $ids){
				$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$ids), array('bookname'));
				if(empty($lesson)){
					continue;
				}else{
					$v['lesson_list'] .= "<p>[ID:".$ids."] ".$lesson['bookname']."</p>";
				}
			}
		}
		unset($category);

		$list[$k] = $v;
	}
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	
	//课程分类
	$category_parents = pdo_getall($this->table_category, array('uniacid'=>$uniacid,'parentid'=>0), array('id','name'));
	$category_list = array();
	foreach($category_parents as $v){
		$category_list[$v['id']] = $v['name'];
	}
	
	$list  = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC", $params);
	foreach ($list as $key => $value) {
		$arr[$key]['card_id']		= $value['card_id'];
		$arr[$key]['password']		= $value['password'];
		$arr[$key]['amount']		= $value['amount'];
		if($value['use_type']==1){
			if($value['category_id']){
				$use_conditions = "[".$category_list[$value['category_id']]."]分类课程可用";
			}else{
				$use_conditions = "全部分类课程可用";
			}
		}elseif($value['use_type']==2){
			$use_conditions = "指定部分课程可用";
		}
		$arr[$key]['conditions']	= "订单满".$value['conditions']."元，".$use_conditions;
		$arr[$key]['validity']		= date('Y-m-d H:i:s',$value['validity']);
		if($value['is_use']==1){
			$status = "已使用";
		}elseif($value['is_use']==0 && $value['validity']>time()){
			$status = "未使用";
		}elseif($value['is_use']==0 && $value['validity']<time()){
			$status = "已过期";
		}
		$arr[$key]['is_use']		= $status;
		$arr[$key]['use_time']		= $value['use_time']?date('Y-m-d H:i:s', $value['use_time']):'';
		$arr[$key]['uid']			= $value['uid'];
		$arr[$key]['nickname']		= preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$value['nickname']);
		$arr[$key]['addtime']		= date('Y-m-d H:i:s', $value['addtime']);
	}

	$title = array('编号', '密钥', '面值(元)', '使用条件', '有效期', '状态', '使用时间', '使用者UID', '使用者昵称', '添加时间');
	$site_common->exportCSV($arr, $title, $fileName="课程优惠码");
}


?>