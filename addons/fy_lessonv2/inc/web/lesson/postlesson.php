<?php

$module = pdo_get('modules', array('name'=>'fy_lessonv2'), array('webapp_support'));
$has_pc = $module['webapp_support']==2 ? true : false;

$id = intval($_GPC['id']);
if(!empty($id)){
	$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($lesson)){
		message("该课程不存在或已被删除", "", "error");
	}

	/*分类关联课程属性*/
	if($lesson['cid']){
		$nowcat = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$lesson['cid']));
	}elseif($lesson['pid']){
		$nowcat = pdo_get($this->table_category, array('uniacid'=>$uniacid,'id'=>$lesson['pid']));
	}
	$cat_attribute1 = json_decode($nowcat['attribute1']);
	$cat_attribute2 = json_decode($nowcat['attribute2']);
}


/* 课程规格 */
$spec_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_spec). " WHERE uniacid=:uniacid AND lessonid=:lessonid ORDER BY spec_sort DESC,spec_day ASC", array(':uniacid'=>$uniacid, ':lessonid'=>$id));

/* 推荐板块列表 */
$rec_list = pdo_fetchall("SELECT id,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

/* 讲师列表 */
$teacher_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND (status=1 OR status=3) ORDER BY first_letter ASC", array(':uniacid'=>$uniacid));

$commission     = unserialize($lesson['commission']);	      /* 佣金比例 */
$recidarr       = explode(",", $lesson['recommendid']);       /* 已推荐板块 */
$vipview        = json_decode($lesson['vipview'], true);      /* 免费学习的VIP等级 */
$share		    = json_decode($lesson['share'], true);	      /* 分享信息 */
$poster_setting = json_decode(str_replace("&quot;", "'", $lesson["poster_setting"]) , true);
$buynow_info    = json_decode($lesson['buynow_info'], true);  /* 立即购买信息 */
$appoint_info   = json_decode($lesson['appoint_info'], true); /* 预约报名课程信息 */
$saler_uids     = json_decode($lesson['saler_uids'], true);   /* 核销人员uid */
foreach($saler_uids as $k=>$v){
	$saler_info[$k] = pdo_get($this->table_mc_members, array('uid'=>$v), array('uid','nickname','avatar'));
}

/* 推荐指定课程类型 */
if($lesson['like_lesson_type']){
	$lesson['like_lesson_content'] = json_decode($lesson['like_lesson_content'], true);
	foreach($lesson['like_lesson_content'] as $k=>$v){
		$like_lesson_ids[$k] = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$v), array('id','bookname','images'));
	}
}

/* 推荐指定商品 */
if($lesson['like_goods_ids']){
	$lesson['like_goods_ids'] = json_decode($lesson['like_goods_ids'], true);
	foreach($lesson['like_goods_ids'] as $k=>$v){
		$like_goods_ids[$k] = pdo_get($this->table_shop_goods, array('uniacid'=>$uniacid,'id'=>$v), array('id','title','cover'));
	}
}


/* 加群客服 */
$service = json_decode($lesson['service'], true);

if(checksubmit('submit')){
	foreach ($_GPC['service'] as $k => $v) {
		$nickname = trim($v['nickname']);
		$avatar = trim($v['avatar']);
		$qrcode = trim($v['qrcode']);

		if (!$nickname || !$avatar || !$qrcode){
			continue;
		}
		$service_data[] = array(
			'nickname' => $nickname,
			'avatar' => $avatar,
			'qrcode' => $qrcode,
		);
	}

	$data = array();
	$data['uniacid']			= $uniacid;
	$data['bookname']			= trim($_GPC['bookname']);
	$data['pid']				= intval($_GPC['pid']);
	$data['cid']				= intval($_GPC['cid']);
	$data['attribute1']			= intval($_GPC['attribute1']);
	$data['attribute2']			= intval($_GPC['attribute2']);
	$data['lesson_type']		= intval($_GPC['lesson_type']);
	$data['verify_number']		= intval($_GPC['verify_number']);
	$data['appoint_info']		= json_encode(array_filter($_GPC['appoint_info']));
	$data['saler_uids']			= json_encode($_GPC['saler_uids']);
	$data['buynow_info']		= json_encode($_GPC['buynow_info']);
	$data['images']				= trim($_GPC['images']);
	$data['price']				= trim($_GPC['price'])?trim($_GPC['price']):0;
	$data['isdiscount']			= intval($_GPC['isdiscount']);
	$data['vipdiscount']		= intval($_GPC['vipdiscount']);
	$data['vipdiscount_money']  = round($_GPC['vipdiscount_money'], 2);
	$data['integral']			= intval($_GPC['integral']);
	$data['integral_rate']		= floatval($_GPC['integral_rate']);
	$data['deduct_integral']	= intval($_GPC['deduct_integral']);
	$data['validity']			= intval($_GPC['validity']);
	$data['virtual_buynum']		= intval($_GPC['virtual_buynum']);
	$data['ico_name']			= trim($_GPC['ico_name']);
	$data['teacherid']			= intval($_GPC['teacherid']);
	$data['descript']			= trim($_GPC['descript']);
	$data['displayorder']		= intval($_GPC['displayorder']);
	$data['lesson_show']		= intval($_GPC['lesson_show']);
	$data['drag_play']			= intval($_GPC['drag_play']);
	$data['status']				= intval($_GPC['status']);
	$data['show_time']			= strtotime($_GPC['show_time']);
	$data['section_status']		= intval($_GPC['section_status']);
	$data['vipview']			= json_encode($_GPC['vipview']);
	$data['share']				= json_encode($_GPC['share']);
	$data['support_coupon']		= intval($_GPC['support_coupon']);
	$data['poster_setting']		= htmlspecialchars_decode($_GPC['poster_setting']);
	$data['poster_bg']			= $_GPC['poster_bg'];
	$data['recommend_free_limit'] = intval($_GPC['recommend_free_limit']);
	$data['recommend_free_num']	  = intval($_GPC['recommend_free_num']);
	$data['recommend_free_day']	  = intval($_GPC['recommend_free_day']);
	$data['service']			  = json_encode($service_data);
	$data['like_lesson_type']	  = intval($_GPC['like_lesson_type']);
	$data['like_goods_ids']		  = json_encode($_GPC['like_goods_ids']);
	$data['addtime']			  = time();
	$data['commission']			  = serialize(array(
													'commission_type' => intval($_GPC['commission_type']),
													'commission1'     => floatval($_GPC['commission1']),
													'commission2'	  => floatval($_GPC['commission2']),
													'commission3'	  => floatval($_GPC['commission3']),
													)
											  );
	if($data['lesson_type']==1){
		$data['appoint_dir'] = intval($_GPC['appoint_dir']);
	}
	if($data['like_lesson_type'] == 0){
		$data['like_lesson_content'] = intval($_GPC['like_lesson_category']);
	}else{
		$data['like_lesson_content'] = json_encode($_GPC['lesson_ids']);
	}

	$checkTeacher = pdo_get($this->table_teacher, array('id'=>$data['teacherid']), array('teacher_income'));
	if($setting['show_teacher_income']){
		$data['teacher_income']	= intval($_GPC['teacher_income']);
	}else{
		$data['teacher_income']	= $lesson['teacher_income'] ? $lesson['teacher_income'] : $checkTeacher['teacher_income'];
	}
	if($setting['company_income']){
		$data['company_income']	= intval($_GPC['company_income']);
	}
	
	if(empty($data['bookname'])){
		message("请输入课程名称");
	}
	if ($data['teacher_income']<0 || $data['teacher_income']>100) {
		message("讲师分成必须介于0~100之间");
	}
	if(empty($data['pid'])){
		message("请选择课程分类");
	}
	if(empty($data['teacherid'])){
		message("请选择讲师");
	}
	if(!in_array($data['status'], array('0','1','2','3','-1'))){
		message("请选择课程状态");
	}
	if($data['isdiscount'] && $data['vipdiscount'] && $data['vipdiscount_money']){
		message("VIP会员课程折扣的百分比和固定金额只能二选一");
	}

	if($data['recommend_free_num'] && !$data['recommend_free_limit']){
		message("请在【分销分享】里填写免费推广学习推广期限");
	}
	if($data['recommend_free_num'] && !$data['recommend_free_day']){
		message("请在【分销分享】里填写免费推广学习时长");
	}

	if($data['lesson_type']==1 && !$data['verify_number']){
		message("请在【报名课程专用】里填写可核销总次数");
	}

	foreach($_GPC['recid'] as $recid){
		$tmprecid .= $recid.',';
	}
	$data['recommendid'] = trim($tmprecid, ",");
	
	if(empty($id)){
		pdo_insert($this->table_lesson_parent, $data);
		$id = pdo_insertid();
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "课程管理", "新增ID:{$id}的课程");
		}
	}else{
		unset($data['addtime']);
		$res = pdo_update($this->table_lesson_parent, $data, array('uniacid'=>$uniacid, 'id'=>$id));
		if($res){
			if($data['bookname'] != $lesson['bookname']){
				$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/poster/*");
				foreach($files as $file) {
					if(strstr($file, "lesson_{$id}_")){
						unlink($file);
					}
				}
			}
			cache_delete('fy_lesson_'.$uniacid.'_like_lesson_'.$id);
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "课程管理", "编辑ID:{$id}的课程");
		}
	}

	if($id){
		$lesson_stock = 0;
		/* 处理课程规格 */
		foreach ($_GPC['spec_time'] as $key => $row) {
			$spec_time = floatval($row);
			$spec_id = intval($_GPC['spec_id'][$key]);
			$spec_price = floatval($_GPC['spec_price'][$key]);
			$spec_stock = trim($_GPC['spec_stock'][$key]);
			$spec_name  = trim($_GPC['spec_name'][$key]);
			$spec_sort  = trim($_GPC['spec_sort'][$key]);

			if (!$spec_time){
				if($spec_id){
					pdo_delete($this->table_lesson_spec, array('uniacid'=>$uniacid,'lessonid'=>$id,'spec_id'=>$spec_id));
				}
				continue;
			}
			$spec_data = array(
				'uniacid'	 => $uniacid,
				'lessonid'	 => $id,
				'spec_day'	 => $spec_time,
				'spec_price' => $spec_price,
				'spec_name'  => $spec_name,
				'spec_stock' => $spec_stock,
				'spec_sort'  => $spec_sort,
				'addtime'	 => time(),
			);
			if($spec_id){
				pdo_update($this->table_lesson_spec, $spec_data, array('uniacid'=>$uniacid,'lessonid'=>$id,'spec_id'=>$spec_id));
			}else{
				pdo_insert($this->table_lesson_spec, $spec_data);
			}
			$lesson_stock += $spec_stock;
			$price_array[] = $spec_price;
		}

		$min_price = array_search(min($price_array), $price_array);
		pdo_update($this->table_lesson_parent, array('price'=>$price_array[$min_price], 'stock'=>$lesson_stock), array('uniacid'=>$uniacid,'id'=>$id));
	}

	if($lesson['bookname'] != $_GPC['bookname'] || $lesson['poster_bg'] != $_GPC['poster_bg']){
		$files = glob(ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/poster/*");
		foreach($files as $file) {
			if(strstr($file, "lesson_{$id}_")){
				unlink($file);
			}
		}
	}
	
	message("操作成功", $refurl, "success");
}