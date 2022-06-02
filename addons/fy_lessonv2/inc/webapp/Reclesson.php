<?php
/**
 * 课程邀请
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$typeStatus = new TypeStatus();
$act_status = $typeStatus->activityStatus();

$pindex =max(1,$_GPC['page']);
$psize = 10;

if($op=='display'){
	$title = '我的课程邀请';

	$condition = " a.uniacid=:uniacid AND a.uid=:uid ";
	$params[':uniacid'] = $uniacid;
	$params[':uid'] = $uid;

	if(trim($_GPC['bookname'])){
		$condition .= " AND a.bookname LIKE :bookname";
		$params[':bookname'] = '%'.trim($_GPC['bookname']).'%';
	}
	if($_GPC['status']!=''){
		$condition .= " AND a.status = :status";
		$params[':status'] = $_GPC['status'];
	}

	$list = pdo_fetchall("SELECT a.*,b.recommend_free_limit,b.recommend_free_num FROM " . tablename($this->table_recommend_activity) . " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition} ORDER BY a.id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['endtime'] = $v['addtime'] + $v['recommend_free_limit']*86400;
		$v['invite_number'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_recommend_junior) . " WHERE  activity_id=:activity_id AND uid=:uid", array(':activity_id'=>$v['id'],':uid'=>$uid));
		$v['remain_number'] = $v['recommend_free_num'] - $v['invite_number'];

		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_recommend_activity). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='details'){
	$title = '课程邀请详情';

	$member = pdo_get($this->table_mc_members, array('uid'=>$uid), array('avatar','nickname'));
	if(empty($member['avatar'])){
		$avatar = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
		$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
	}

	$activity_id = intval($_GPC['activity_id']);
	$activity = pdo_fetch("SELECT a.*,b.bookname,b.images,b.share,b.recommend_free_limit,b.recommend_free_num,b.recommend_free_day FROM " . tablename($this->table_recommend_activity) . " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.id=:id AND a.uid=:uid" , array(':id'=>$activity_id,':uid'=>$uid));
	if(empty($activity)){
		message("该课程邀请活动不存在");
	}

	$lesson_wap_url = $_W['siteroot']."app/index.php?i={$uniacid}&c=entry&lessonid={$activity['lessonid']}&do=lessonqrcode&m=fy_lessonv2";

	if($activity['status']==0 && time() > $activity['addtime']+$activity['recommend_free_limit']*86400){
		pdo_update($this->table_recommend_activity, array('status'=>-1), array('id'=>$activity['id']));
		$activity['status'] = -1;
	}

	$invite_list = pdo_fetchall("SELECT a.*,b.nickname,b.avatar FROM " .tablename($this->table_recommend_junior). " a LEFT JOIN " .tablename($this->table_mc_members). " b on a.junior_uid=b.uid WHERE a.activity_id=:activity_id", array(':activity_id'=>$activity_id));
	$invite_number = count($invite_list);
	$remain_num = $activity['recommend_free_num'] - $invite_number;
	$enddate = date('Y-m-d H:i:s', $activity['addtime']+$activity['recommend_free_limit']*86400);

}


include $this->template("../webapp/{$template}/recLesson");


?>