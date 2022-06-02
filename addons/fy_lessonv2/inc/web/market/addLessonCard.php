<?php


if(checksubmit()){
	$prefix		= trim($_GPC['prefix']);
	$lesson_id	= intval($_GPC['lesson_id']);
	$number		= intval($_GPC['number']);
	$cardtime	= round($_GPC['cardtime'],2);
	$validity	= strtotime($_GPC['validity']);

	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lesson_id), array('lesson_type'));

	if(strlen($prefix) != 4){
		message("请输入卡密的4位前缀", "", "error");
	}
	if(!$lesson_id){
		message("请选择对应的课程", "", "error");
	}
	if(!$lesson){
		message("课程不存在", "", "error");
	}
	if($lesson['lesson_type'] == 1){
		message("报名课程不支持生成卡密", "", "error");
	}
	if(!$number){
		message("生成数量有误，请重新输入", "", "error");
	}
	if($number > 10000){
		message("单次生成优惠码不要超过10000张", "", "error");
	}
	if(!$cardtime){
		message("请输入卡密时长", "", "error");
	}
	if($validity < time()){
		message("有效期必须大于当前时间", "", "error");
	}

	set_time_limit(180);
	ob_end_clean();
	ob_implicit_flush(true);
	str_pad(" ", 256);

	$total = 0;
	for($i=1;$i<=$number;$i++){
		$seek=mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999);
		$start = mt_rand(0,14);
		$str=strtoupper(substr(md5($seek),$start,14));
		$str=str_replace("O",chr(mt_rand(65,78)),$str);
		$str=str_replace("0",chr(mt_rand(65,78)),$str);

		$cardData = array(
			'uniacid'		=> $uniacid,
			'password'		=> $prefix.$str,
			'cardtime'		=> $cardtime,
			'lesson_id'		=> $lesson_id,
			'validity'		=> $validity,
			'addtime'		=> time()
		);
		if(pdo_insert($this->table_lessoncard, $cardData)){
			$total++;
		}
	}

	if($total){
		$site_common->addSysLog($_W['uid'], $_W['username'], 1, "营销管理->课程卡密", "成功生成{$total}张课程卡密，卡密前缀：{$prefix}，对应课程ID：{$lesson_id}");
	}
	message("成功生成{$total}张卡密", $this->createWebUrl('market', array('op'=>'lessonCard')), "success");
}

?>