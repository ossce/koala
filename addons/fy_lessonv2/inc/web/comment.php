<?php
/**
 * 评价管理
 * ============================================================================
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


/* 上一步URL */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('comment');

/* 评价字体自定义 */
$evaluate_page = $common['evaluate_page'];

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid=:uniacid ";
	$params[':uniacid'] = $uniacid;

	if ($_GPC['grade']) {
		$condition .= " AND grade=:grade ";
		$params[':grade'] = $_GPC['grade'];
	}
	if ($_GPC['reply'] != '') {
		if($_GPC['reply']==0){
			$condition .= " AND reply IS NULL ";
		}elseif($_GPC['reply']==1){
			$condition .= " AND reply IS NOT NULL ";
		}
	}
	if ($_GPC['status'] != '') {
		$condition .= " AND status=:status ";
		$params[':status'] = $_GPC['status'];
	}	
	if (!empty($_GPC['ordersn'])) {
		$condition .= " AND ordersn LIKE :ordersn ";
		$params[':ordersn'] = "%".$_GPC['ordersn']."%";
	}
	if (!empty($_GPC['bookname'])) {
		$condition .= " AND bookname LIKE :bookname ";
		$params[':bookname'] = "%".$_GPC['bookname']."%";
	}
	if (!empty($_GPC['nickname'])) {
		$condition .= " AND nickname LIKE :nickname ";
		$params[':nickname'] = "%".$_GPC['nickname']."%";
	}
	if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;

		$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_evaluate). " WHERE {$condition} ORDER BY id desc, addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_evaluate). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

	/* 临时功能，增加讲师id到评价表 */
	$evaluatelist = pdo_fetchall("SELECT id,lessonid FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND teacherid IS NULL ");
	foreach($evaluatelist as $value){
		$lesson = pdo_fetch("SELECT teacherid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id ", array(':uniacid'=>$uniacid,':id'=>$value['lessonid']));
		if(!empty($lesson['teacherid'])){
			pdo_update($this->table_evaluate, array('teacherid'=>$lesson['teacherid']), array('id'=>$value['id']));
		}
	}

}elseif($operation == 'reply'){
	$id = $_GPC['id'];
	$evaluate = pdo_fetch("SELECT * FROM " .tablename($this->table_evaluate). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($evaluate)){
		message("该评价不存");
	}

	if($evaluate['uid']){
		$member = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$evaluate['uid']), array('nickname','realname','mobile','avatar'));
	}else{
		$member['nickname'] = $evaluate['virtual_nickname'];
		$member['avatar'] = $evaluate['virtual_avatar'];
	}

	if(empty($member['avatar'])){
		$avatar = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$avatar = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://") ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
	}

	if(checksubmit('submit')){
		$evaluate_data = array(
			'grade'   => intval($_GPC['grade']),
			'content' => trim($_GPC['content']),
			'status'  => intval($_GPC['status']),
			'reply'   => trim($_GPC['reply']),
		);

		if(empty($evaluate_data['content'])){
			message("请输入评价内容");
		}

		$result = pdo_update($this->table_evaluate, $evaluate_data, array('uniacid'=>$uniacid,'id'=>$id));
		if($result){
			$site_common->memberEvaluate($evaluate);

			if(empty($evaluate['ordersn'])){
				$ordersn = "免费";
			}else{
				$ordersn = "订单编号:{$evaluate['ordersn']}的";
			}
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "评价管理", "回复{$ordersn}课程评价");
			
			message("回复成功", $refurl, "success");
		}else{
			message("系统繁忙，请稍候重试~", "", "error");
		}
	}

}elseif($operation == 'batchAudit'){
	$checkids = $_GPC['checkids'];
	$status	  = $_GPC['status'];
	$reply	  = $_GPC['reply'];

	if(!in_array($status, array('-1','0','1'))){
		$json_data = array(
			'code'	=> -1,
			'msg'	=> '处理状态有误，请刷新页面重试',
		);
		$this->resultJson($json_data);
	}

	$ids = explode(",", $checkids);
	foreach($ids as $id){
		$data = array(
			'status' => $status,
		);
		if($reply){
			$data['reply'] = $reply;
		}
		pdo_update($this->table_evaluate, $data, array('uniacid'=>$uniacid,'id'=>$id));
	}

	$json_data = array(
		'code'	=> 0,
		'msg'	=> '批量处理完成',
	);
	$this->resultJson($json_data);

}elseif ($operation == 'delete') {
	$id = $_GPC['id'];
	$evaluate = pdo_get($this->table_evaluate, array('uniacid'=>$uniacid, 'id'=>$id));
	
	if(empty($evaluate)){
		message("该评价不存在或已被删除！");
	}

	$result = pdo_delete($this->table_evaluate, array('uniacid'=>$uniacid,'id' => $id));
	if($result){
		$site_common->memberEvaluate($evaluate);

		if($evaluate['ordersn']){
			$log_remark = "删除订单编号：{$evaluate['ordersn']} 的评价内容";
		}else{
			$log_remark = "删除课程ID：{$evaluate['lessonid']} 的评价内容";
		}
		$site_common->addSysLog($_W['uid'], $_W['username'], 2, "评价管理", $log_remark);
	}

	itoast("删除成功", "", "success");

}elseif ($operation == 'addComment') {
	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid));
	if(empty($lesson)){
		message("该课程不存在");
	}

	if(checksubmit('submit')){
		$data = array(
			'uniacid'	=> $uniacid,
			'lessonid'	=> $lesson['id'],
			'bookname'	=> $lesson['bookname'],
			'teacherid'	=> $lesson['teacherid'],
			'virtual_nickname'	=> trim($_GPC['virtual_nickname']),
			'virtual_avatar'	=> trim($_GPC['virtual_avatar']),
			'grade'				=> intval($_GPC['grade']),
			'global_score'		=> intval($_GPC['global_score']),
			'content_score'		=> intval($_GPC['content_score']),
			'understand_score'	=> intval($_GPC['understand_score']),
			'content' => trim($_GPC['content']),
			'reply'   => trim($_GPC['reply']),
			'status'  => intval($_GPC['status']),
			'type'	  => 1,
			'addtime' => strtotime($_GPC['addtime']),
		);

		if(empty($data['virtual_nickname'])){
			message("请输入用户昵称");
		}
		if(empty($data['virtual_avatar'])){
			message("请上传用户头像");
		}
		if(empty($data['grade'])){
			message("请选择评价级别");
		}
		if(empty($data['content'])){
			message("请输入评价内容");
		}
		if($data['status'] == ''){
			message("请选择评价状态");
		}
		if($data['addtime'] > time()){
			message("评价时间不能大于当前时间");
		}

		$res = pdo_insert($this->table_evaluate, $data);
		if($res){
			$site_common->memberEvaluate($data);
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "评价管理", "新增评价，课程ID：{$lessonid}");
			
			message("操作成功", $this->createWebUrl('comment', array('op'=>'addComment','lessonid'=>$lessonid)), "success");
		}else{
			message("系统繁忙，请稍候重试~", "", "error");
		}
	}
}

include $this->template('web/comment');

?>