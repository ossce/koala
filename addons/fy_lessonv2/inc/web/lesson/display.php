<?php

if (checksubmit('submit')) { /* 排序 */
	if (is_array($_GPC['lessonorder'])) {
		foreach ($_GPC['lessonorder'] as $pid => $val) {
			$data = array('displayorder' => intval($_GPC['lessonorder'][$pid]));
			pdo_update($this->table_lesson_parent, $data, array('id' => $pid));
		}
	}
	message('操作成功!', referer, 'success');
}

/* 推荐板块列表 */
$rec_list = pdo_getall($this->table_recommend, array('uniacid'=>$uniacid), array('id','rec_name'));

/* VIP列表 */
$vip_list = pdo_getall($this->table_vip_level, array('uniacid'=>$uniacid), array('id','level_name'));


$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$bookname = trim($_GPC['bookname']);
$teacher  = trim($_GPC['teacher']);
$teacherid= intval($_GPC['teacherid']);
$pid      = intval($_GPC['pid']);
$cid      = intval($_GPC['cid']);
$recid	  = intval($_GPC['recid']);
$lesson_type  = trim($_GPC['lesson_type']);
$status   = trim($_GPC['status']);
$vip_id   = trim($_GPC['vip_id']);
$attr1  = intval($_GPC['attribute1']);
$attr2  = intval($_GPC['attribute2']);

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;

if($teacherid){
	$condition .= " AND a.teacherid = :teacherid ";
	$params[':teacherid'] = $teacherid;
}
if($pid){
	$condition .= " AND a.pid=:pid ";
	$params[':pid'] = $pid;
}
if($cid){
	$condition .= " AND a.cid=:cid ";
	$params[':cid'] = $cid;
}
if($lesson_type != ''){
	$condition .= " AND a.lesson_type=:lesson_type ";
	$params[':lesson_type'] = $lesson_type;
}
if($status != ''){
	if($status == 999){
		$condition .= " AND a.stock < :stock ";
		$params[':stock'] = 10;
	}else{
		$condition .= " AND a.status=:status ";
		$params[':status'] = $status;
	}
}
if($vip_id){
	if($vip_id == '-1'){
		$condition .= " AND (a.vipview = '' OR a.vipview = 'null') ";
	}else{
		$condition .= " AND a.vipview LIKE :vipview ";
		$params[':vipview'] = '%"'.$vip_id.'"%';
	}
}
if($bookname!=''){
	$condition .= " AND a.bookname LIKE :bookname ";
	$params[':bookname'] = "%".$bookname."%";
}
if($teacher!=''){
	$condition .= " AND b.teacher LIKE :teacher ";
	$params[':teacher'] = "%".$teacher."%";
}
if($recid){
	$condition .= " AND ((a.recommendid='{$recid}') OR (a.recommendid LIKE '{$recid},%') OR (a.recommendid LIKE '%,{$recid}') OR (a.recommendid LIKE '%,{$recid},%')) ";
}
if($attr1){
	$condition .= " AND a.attribute1=:attribute1 ";
	$params[':attribute1'] = $attr1;
}
if($attr2){
	$condition .= " AND a.attribute2=:attribute2 ";
	$params[':attribute2'] = $attr2;
}

if(!$_GPC['export']){
	$list = pdo_fetchall("SELECT a.id,a.lesson_type,a.pid,a.cid,a.bookname,a.price,a.buynum,a.stock,a.displayorder,a.status,a.section_status,a.vip_number,a.teacher_number,a.visit_number,b.teacher FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ORDER BY a.displayorder DESC,a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

	foreach($list as $k=>$v){
		$cat_id = $v['cid'] ? $v['cid'] : $v['pid'];
		if($cat_id){
			$v['category'] = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$cat_id), array('name'));
			$list[$k] = $v;
		}
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " . tablename($this->table_teacher) . " b ON a.teacherid=b.id WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

}else{
	set_time_limit(0);
	$list = pdo_fetchall("SELECT a.* FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ORDER BY a.displayorder DESC,a.id DESC", $params);
	
	$i = 0;
	foreach ($list as $k => $v) {
		if($k==0){
			$arr[$i]['id'] = "";
			$arr[$i]['lesson_type'] = "导出后的csv文件，如需再次导入，请用Microsoft Excel转为xls格式文件。课程类型、课程名称、一级分类ID、讲师ID、课程封面、课程状态为必填项，其他的为选填项，下方第三第四行为示例，可删除。添加数据请从第三行开始添加，输入多个免费学习等级ID、推荐板块ID或线下核销人员UID时，请使用英文半角逗号隔开，如：3,4";
			$i++;
		}

		$vipview = json_decode($v['vipview'], true);
		if($vipview){
			foreach($vipview as $item){
				$tmp_vipview .= $item.",";
			}
			$tmp_vipview = trim($tmp_vipview, ",");
		}

		if($v['price'] > 0){
			$spec_price = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_spec). " WHERE uniacid=:uniacid AND lessonid=:lessonid ORDER BY spec_sort DESC,spec_id ASC LIMIT 0,1 ",array(':uniacid'=>$uniacid,':lessonid'=>$v['id']));
			$tmp_price = $spec_price['spec_day'].",".$spec_price['spec_price'].",".$spec_price['spec_stock'].",".$spec_price['spec_name'];
		}

		$tmp_commission = unserialize($v['commission']);

		$appoint_info = json_decode($v['appoint_info'], true);
		if($appoint_info){
			foreach($appoint_info as $item){
				$tmp_appoint .= $item.",";
			}
			$tmp_appoint = trim($tmp_appoint, ",");
		}

		$buynow_info = json_decode($v['buynow_info'], true);
		
		$saler_uids = json_decode($v['saler_uids'], true);
		if($saler_uids){
			foreach($saler_uids as $item){
				$tmp_saler_uids .= $item.",";
			}
			$tmp_saler_uids = trim($tmp_saler_uids, ",");
		}

		$arr[$i]['id']				= $v['id'];
		$arr[$i]['lesson_type']		= $v['lesson_type'];
		$arr[$i]['bookname']		= $v['bookname'];
		$arr[$i]['pid']				= $v['pid'];
		$arr[$i]['cid']				= $v['cid'];
		$arr[$i]['teacherid']		= $v['teacherid'];
		$arr[$i]['images']			= $_W['attachurl'].$v['images'];
		$arr[$i]['status']			= $v['status'];
		$arr[$i]['teacher_income']	= $v['teacher_income'];
		$arr[$i]['vipview']			= $tmp_vipview;
		$arr[$i]['lesson_show']     = $v['lesson_show'];
		$arr[$i]['drag_play']		= $v['drag_play'];
		$arr[$i]['section_status']	= $v['section_status'];
		$arr[$i]['displayorder']    = $v['displayorder'];
		$arr[$i]['price']			= $tmp_price;
		$arr[$i]['descript']		= $v['descript'];
		$arr[$i]['integral']		= $v['integral'];
		$arr[$i]['integral_rate']	= $v['integral_rate'];
		$arr[$i]['deduct_integral']	= $v['deduct_integral'];
		$arr[$i]['virtual_buynum']	= $v['virtual_buynum'];
		$arr[$i]['recommendid']     = $v['recommendid'];
		$arr[$i]['support_coupon']	= $v['support_coupon'];
		$arr[$i]['ico_name']		= $v['ico_name'];
		$arr[$i]['commission_type']	= $tmp_commission['commission_type'];
		$arr[$i]['commission1']		= $tmp_commission['commission1'];
		$arr[$i]['commission2']		= $tmp_commission['commission2'];
		$arr[$i]['commission3']		= $tmp_commission['commission3'];
		$arr[$i]['appoint_dir']     = $v['appoint_dir'];
		$arr[$i]['verify_number']	= $v['verify_number'];
		$arr[$i]['appoint_info']	= $tmp_appoint;
		$arr[$i]['appoint_addres']	= $buynow_info['appoint_addres'];
		$arr[$i]['appoint_validity']= $buynow_info['appoint_validity'] ? "'".$buynow_info['appoint_validity'] : '';
		$arr[$i]['saler_uids']		= $tmp_saler_uids;

		unset($vipview);
		unset($tmp_vipview);
		unset($spec_price);
		unset($tmp_price);
		unset($tmp_commission);
		unset($tmp_appoint);
		unset($tmp_saler_uids);

		$i++;
	}

	$title = array('课程ID', '课程类型','课程名称', '一级分类ID','二级分类ID', '讲师ID', '课程封面', '课程状态', '讲师分成', '免费学习等级ID', '默认显示(0系统默认，1课程详情，2课程目录)','首次拖拽播放','连载状态','排序','价格信息','课程介绍','赠送固定积分','赠送比例积分','购买最多抵扣积分','初始学习人数','推荐到板块ID','优惠券抵扣','课程小标识','佣金类型','一级佣金','二级佣金','三级佣金','报名课程目录','可核销总次数','报名课程填写信息','线下活动地址','报名截止时间','线下核销人员UID');
	$site_common->exportCSV($arr, $title, $fileName="课程列表");
}