<?php
/**
 * 评价课程订单
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();
$title = "评价订单";
$evaluate_page = $common['evaluate_page']; //评价字体自定义
 
$uid = $_W['member']['uid'];
$orderid = intval($_GPC['orderid']); /* 课程订单id */

if($op=='display'){
	$order = pdo_fetch("SELECT a.id,a.ordersn,a.uid,a.lessonid,a.status,a.teacherid, b.bookname,b.images,b.price, c.nickname FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id LEFT JOIN " .tablename($this->table_mc_members). " c ON a.uid=c.uid WHERE a.uniacid=:uniacid AND a.id=:id AND a.uid=:uid", array(':uniacid'=>$uniacid,':id'=>$orderid,':uid'=>$uid));
	if(empty($order)){
		message("该订单不存在或已被删除", "", "warning");
	}

	if($order['status']==2){
		message("该订单已评价", $this->createMobileUrl('mylesson'), "warning");
	}

	/* 提交评价 */
	if(checksubmit('submit')){
		$data = array(
			'uniacid'			=> $uniacid,
			'orderid'			=> $orderid,
			'ordersn'			=> $order['ordersn'],
			'lessonid'			=> $order['lessonid'],
			'bookname'			=> $order['bookname'],
			'teacherid'			=> $order['teacherid'],
			'uid'				=> $order['uid'],
			'grade'				=> intval($_GPC['grade']),
			'global_score'		=> intval($_GPC['global_score']),
			'content_score'		=> intval($_GPC['content_score']),
			'understand_score'  => intval($_GPC['understand_score']),
			'content'			=> trim($_GPC['content']),
			'status'			=> $setting['audit_evaluate']==0 ? 1 : 0,
			'type'				=> 1,
			'addtime'			=> time(),
		);

		if(strlen($data['content'])<10){
			message("评论内容不得少于10个字符", "", "warning");
		}
		if(!$data['grade']){
			message("请对课程进行总体评价", "", "warning");
		}
		if(!$data['global_score'] || !$data['content_score'] || !$data['understand_score']){
			message("请点亮评分的每一行星星", "", "warning");
		}

		$result = pdo_insert($this->table_evaluate, $data);
		if($result){
			/* 更新订单状态 */
			pdo_update($this->table_order, array('status'=>2), array('id'=>$order['id']));

			/* 用户评价课程 */
			$site_common->memberEvaluate($data);

			$evaluate_word = $setting['audit_evaluate']==0 ? "评价成功" : "评价成功，等待管理员审核";
			message($evaluate_word, $this->createMobileUrl('mylesson'), "success");
		}else{
			message("评价失败", $this->createMobileUrl('mylesson'), "warning");
		}
	}

}elseif($op=='freeorder'){
	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id LIMIT 1", array(':uniacid'=>$uniacid,':id'=>$lessonid));
	if(empty($lesson)){
		message("该课程不存在或已被删除", "", "warning");
	}

	$already_evaluate = pdo_fetch("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uid=:uid AND lessonid=:lessonid AND orderid=:orderid", array(':uid'=>$uid,':lessonid'=>$lessonid,':orderid'=>0));
	if(!empty($already_evaluate)){
		message("该课程已评价", "", "warning");
	}

	$order = array(
		'images'   => $lesson['images'],
		'bookname' => $lesson['bookname'],
		'teacherid'=> $lesson['teacherid'],
		'price'    => $lesson['price'],
		'uid'	   => $uid,
	);

	/* 提交评价 */
	if(checksubmit('submit')){
		$data = array(
			'uniacid'			=> $uniacid,
			'orderid'			=> '',
			'ordersn'			=> '',
			'lessonid'			=> $lessonid,
			'bookname'			=> $order['bookname'],
			'teacherid'			=> $order['teacherid'],
			'uid'				=> $order['uid'],
			'grade'				=> intval($_GPC['grade']),
			'global_score'		=> intval($_GPC['global_score']),
			'content_score'		=> intval($_GPC['content_score']),
			'understand_score'  => intval($_GPC['understand_score']),
			'content'			=> trim($_GPC['content']),
			'status'			=> $setting['audit_evaluate']==0 ? 1 : 0,
			'type'				=> 1,
			'addtime'			=> time(),
		);

		if(strlen($data['content'])<10){
			message("评论内容不得少于10个字符", "", "warning");
		}
		if(!$data['grade']){
			message("请对课程进行总体评价", "", "warning");
		}
		if(!$data['global_score'] || !$data['content_score'] || !$data['understand_score']){
			message("请点亮评分的每一行星星", "", "warning");
		}

		$result = pdo_insert($this->table_evaluate, $data);
		if($result){
			/* 用户评价课程 */
			$site_common->memberEvaluate($data);
			
			$evaluate_word = $setting['audit_evaluate']==0 ? "评价成功" : "评价成功，等待管理员审核";
			message($evaluate_word, $this->createMobileUrl('lesson',array('id'=>$lessonid)), "success");
		}else{
			message("评价失败", $this->createMobileUrl('lesson',array('id'=>$lessonid)), "warning");
		}
	}
}

include $this->template("../mobile/{$template}/evaluate");

?>