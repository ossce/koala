<?php
/**
 * 我的(课程)邀请好友
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];
$typeStatus = new TypeStatus();
$act_status = $typeStatus->activityStatus();

$pindex =max(1,$_GPC['page']);
$psize = 10;

if($op=='display'){
	$title = '我的课程邀请';

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_recommend_activity). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));

}elseif($op=='details'){
	$title = '课程邀请详情';

	$member = pdo_get($this->table_mc_members, array('uid'=>$uid), array('avatar','nickname'));
	if(empty($member['avatar'])){
		$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
		$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
	}

	$activity_id = intval($_GPC['activity_id']);
	$activity = pdo_fetch("SELECT a.*,b.bookname,b.images,b.share,b.recommend_free_limit,b.recommend_free_num,b.recommend_free_day FROM " . tablename($this->table_recommend_activity) . " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.id=:id AND a.uid=:uid" , array(':id'=>$activity_id,':uid'=>$uid));
	if(empty($activity)){
		message("该课程邀请活动不存在");
	}

	if($activity['status']==0 && time() > $activity['addtime']+$activity['recommend_free_limit']*86400){
		pdo_update($this->table_recommend_activity, array('status'=>-1), array('id'=>$activity['id']));
		$activity['status'] = -1;
	}

	$invite_list = pdo_fetchall("SELECT a.*,b.nickname,b.avatar FROM " .tablename($this->table_recommend_junior). " a LEFT JOIN " .tablename($this->table_mc_members). " b on a.junior_uid=b.uid WHERE a.activity_id=:activity_id", array(':activity_id'=>$activity_id));
	$invite_number = count($invite_list);
	$remain_num = $activity['recommend_free_num'] - $invite_number;
	$enddate = date('Y-m-d H:i:s', $activity['addtime']+$activity['recommend_free_limit']*86400);

	/* 构造分享信息开始 */
	$share_info = json_decode($lesson['share'], true);    /* 课程单独分享信息 */
	$sharelesson = unserialize($comsetting['sharelesson']);  /* 全局课程分享信息 */

	if(!empty($share_info['title'])){
		$sharelesson['title'] = $share_info['title'];
	}else{
		$sharelesson['desc'] = $sharelesson['title'];
		$sharelesson['title'] = $activity['bookname'];
	}

	$sharelesson['desc'] = $share_info['descript'] ? $share_info['descript'] : str_replace("【课程名称】","《".$title."》",$sharelesson['desc']);
	$sharelesson['images'] = $share_info['images'] ? $share_info['images'] : $sharelesson['images'];
	if(empty($sharelesson['images'])){
		$sharelesson['images'] = $activity['images'];
	}
	$sharelesson['link'] = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$activity['lessonid'],'uid'=>$uid));
	/* 构造分享信息结束 */

}elseif($op=='ajaxgetlist'){

	$list = pdo_fetchall("SELECT a.*,b.lesson_type,b.live_info,b.recommend_free_limit,b.recommend_free_num FROM " . tablename($this->table_recommend_activity) . " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.uid=:uid ORDER BY a.id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, array(':uid'=>$uid));
	foreach($list as $k=>$v){
		$v['statusname'] = $act_status[$v['status']];
		$v['endtime'] = date('Y-m-d H:i', $v['addtime']+$lesson['recommend_free_limit']*86400);
		$v['addtime'] = date('Y-m-d H:i', $v['addtime']);
		$v['update_time'] = date('Y-m-d H:i', $v['update_time']);
		$v['invite_number'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_recommend_junior) . " WHERE  activity_id=:activity_id AND uid=:uid", array(':activity_id'=>$v['id'],':uid'=>$uid));
		$v['remain_number'] = $v['recommend_free_num'] - $v['invite_number'];

		if($v['lesson_type']==3){
			$live_info = json_decode($v['live_info'], true);
			$starttime = strtotime($live_info['starttime']);
			$endtime = strtotime($live_info['endtime']);
			if(time() < $starttime){
				$v['icon_live_status'] = 'icon-live-nostart';
			}elseif(time() > $endtime){
				$v['icon_live_status'] = 'icon-live-ended';
			}elseif(time() > $starttime && time() < $endtime){
				$v['icon_live_status'] = 'icon-live-starting';
			}
		}

		$list[$k] = $v;
	}
	$this->resultJson($list);
}


include $this->template("../mobile/{$template}/reclesson");
