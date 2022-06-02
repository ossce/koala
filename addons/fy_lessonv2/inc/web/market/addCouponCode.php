<?php


if(checksubmit()){
	$prefix		= trim($_GPC['prefix']);
	$number		= intval($_GPC['number']);
	$amount		= intval($_GPC['amount']);
	$conditions = floatval($_GPC['conditions']);
	$use_type	= intval($_GPC['use_type']);
	$category_id= intval($_GPC['category_id']);
	$lesson_ids = $_GPC['lesson_ids'];
	$validity	= strtotime($_GPC['validity']);

	if(strlen($prefix) != 4){
		message("请输入优惠码的两位前缀", "", "error");
	}
	if($number < 1){
		message("生成数量有误，请重新输入", "", "error");
	}
	if($number > 10000){
		message("单次生成优惠码不要超过10000张", "", "error");
	}
	if($amount < 1){
		message("请输入正确的优惠码面值", "", "error");
	}
	if($validity < time()){
		message("有效期必须大于当前时间", "", "error");
	}
	if(empty($use_type)){
		message("请选择优惠码使用条件", "", "error");
	}
	if($use_type==2 && empty($lesson_ids)){
		message("请选择优惠码指定课程", "", "error");
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

		$couponData = array(
			'uniacid'		=> $uniacid,
			'password'		=> $prefix.$str,
			'amount'		=> $amount,
			'validity'		=> $validity,
			'conditions'	=> $conditions,
			'use_type'		=> $use_type,
			'category_id'	=> $category_id,
			'lesson_ids'	=> json_encode($lesson_ids),
			'addtime'		=> time()
		);
		if(pdo_insert($this->table_coupon, $couponData)){
			$total++;
		}
	}

	if($total){
		$site_common->addSysLog($_W['uid'], $_W['username'], 1, "营销管理->课程优惠码", "成功生成{$total}个面值为{$amount}元的优惠码");
	}
	message("成功生成{$total}张优惠码", $this->createWebUrl('market', array('op'=>'couponCode')), "success");
}

?>