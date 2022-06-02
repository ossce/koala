<?php

$remark = '退订本消息通知，请到“个人中心”里关闭订阅消息即可';
if(checksubmit()){
	$lesson_id = intval($_GPC['lesson_id']);
	$user_type = intval($_GPC['user_type']);
	$content = json_encode($_GPC['content']);

	$lesson = pdo_fetch("SELECT id,bookname,teacherid,status FROM " .tablename($this->table_lesson_parent). " WHERE id=:id", array(':id'=>$lesson_id));
	if(empty($lesson)){
		message("请选择课程", "", "error");
	}
	if($lesson['status']!=1){
		message("您选择的课程未上架", "", "error");
	}

	pdo_begin();
	try {
		if(empty($user_type)){
			message("请选择发送对象", "", "error");
		}

		/* 全部粉丝 */
		if($user_type==1){
			$list = pdo_fetchall("SELECT openid FROM " .tablename($this->table_member). " WHERE uniacid=:uniacid AND openid != :openid", array(':uniacid'=>$uniacid, ':openid'=>''));
		
		/* 全部VIP粉丝 */
		}elseif($user_type==2){
			$list = pdo_fetchall("SELECT distinct(b.openid) FROM " .tablename($this->table_member_vip). " a LEFT JOIN " .tablename($this->table_member). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.validity>:validity AND b.openid != :openid", array(':uniacid'=>$uniacid, ':validity'=>time(), ':openid'=>''));
		
		/* 购买该讲师任意课程的粉丝 */
		}elseif($user_type==3){
			$list = pdo_fetchall("SELECT distinct(b.openid) FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_member). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.teacherid=:teacherid AND b.openid != :openid", array(':uniacid'=>$uniacid, ':teacherid'=>$lesson['teacherid'], ':openid'=>''));
		
		/* 购买该课程的粉丝 */
		}elseif($user_type==4){
			$list = pdo_fetchall("SELECT distinct(b.openid) FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_member). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.lessonid=:lessonid AND a.status>:status AND b.openid != :openid AND a.is_delete=:is_delete", array(':uniacid'=>$uniacid, ':lessonid'=>$lesson['id'], ':status'=>0, ':openid'=>'', ':is_delete'=>0));
		
		/* 购买该讲师服务的粉丝 */
		}elseif($user_type==5){
			$list = pdo_fetchall("SELECT distinct(b.openid) FROM " .tablename($this->table_member_buyteacher). " a LEFT JOIN " .tablename($this->table_member). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.teacherid=:teacherid AND b.openid != :openid", array(':uniacid'=>$uniacid, ':teacherid'=>$lesson['teacherid'], ':openid'=>''));
		}

		/* 去掉取消订阅模板消息的粉丝 */
		$nosub_list = pdo_getall($this->table_subscribe_msg, array('uniacid'=>$uniacid,'subscribe'=>0), array('openid'));
		$lists = array_filter($list, function($i) use ($nosub_list) {
			return !in_array($i['openid'], array_column($nosub_list, 'openid'));
		});

		if(empty($list)){
			message("发送的粉丝为空，请重新选择发送对象", "", "error");
		}

		$inform = array(
			'uniacid'	=> $uniacid,
			'lesson_id' => $lesson_id,
			'book_name' => $lesson['bookname'],
			'content'	=> $content,
			'user_type' => $user_type,
			'inform_number' => count($list),
			'status'	=> 1, 
			'addtime'	=> time()
		);
		pdo_insert($this->table_inform, $inform);
		$inform_id = pdo_insertid();


		$now = time();
		$sql_head = "INSERT INTO ".tablename($this->table_inform_fans)." (`uniacid`, `inform_id`,`openid`,`addtime`) VALUES ";
		$sql = "";
		foreach($list as $k=>$v){
			$sql .= "('{$uniacid}','{$inform_id}','{$v[openid]}','{$now}'),";

			if(($k+1)%1000==0 || $k+1==count($list)){
				$sql = substr($sql, 0, strlen($sql)-1);
				pdo_query($sql_head.$sql);
				$sql = "";
			}
		}
		pdo_commit();
		message("添加成功", $this->createWebUrl('lesson', array('op'=>'inform')), "success");

	} catch (Exception $e) {
		load()->func('logging');
		logging_run('管理员后台添加课程通知失败(uniacid:'.$uniacid.')，原因：'.$e->getMessage(), 'trace', 'fylessonv2');
		pdo_rollback(); 
	}
}else{
	$lessonid = intval($_GPC['lessonid']);   /* 从课程管理携带过来的课程id */
	$sectionid = intval($_GPC['sectionid']); /* 从章节管理携带过来的章节id */
	if($lessonid){
		$lesson = pdo_get($this->table_lesson_parent, array('id'=>$lessonid));
		if(empty($lesson)){
			message('课程不存在');
		}
		
		$lessonUrl = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('lesson', array('id'=>$lesson['id'])));
	}
	if($sectionid){
		$section = pdo_get($this->table_lesson_son, array('id'=>$sectionid));
		if(empty($section)){
			message('章节不存在');
		}
		$lessonid = $section['parentid'];
		$lesson = pdo_get($this->table_lesson_parent, array('id'=>$lessonid));

		$first = '您订阅的课程《'.$lesson['bookname'].'》更新了章节“'.$section['title'].'”，快来学习吧！';
		$sectionUrl = $_W['siteroot'].'app/'.str_replace("./", "", $this->createMobileUrl('lesson', array('id'=>$section['parentid'],'sectionid'=>$section['id'])));
	}

	$teacher = pdo_get($this->table_teacher, array('id'=>$lesson['teacherid']), array('teacher'));
	if($lessonUrl){
		$first = '您订阅的【'.$teacher['teacher'].'】上新课了，快和我一起加入学习！';
	}
	
	$bookname = $lesson['bookname'];
	$link = $lessonUrl ? $lessonUrl : $sectionUrl;
	$today = date('Y年m月d日');
}