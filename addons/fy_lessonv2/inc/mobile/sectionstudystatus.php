<?php
/**
 * 检查用户学习章节资格
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$uid = $_W['member']['uid'];

if($op == 'display'){
	/* 非直播课程点击进入章节 */

	$id = intval($_GPC['id']);/* 课程id */
	$sectionid = intval($_GPC['sectionid']);/* 点播章节id */

	$lesson = pdo_fetch("SELECT price,teacherid,lesson_type,vipview FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id AND status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
	if(empty($lesson)){
		$data = array(
			'code' => -1,
			'msg'  => '课程不存在',
		);
		$this->resultJson($data);
	}
	if($lesson['price']==0){
		$data = array(
			'code' => 0,
			'msg'  => '免费课程',
		);
		$this->resultJson($data);
	}

	$section = pdo_get($this->table_lesson_son, array('parentid'=>$id,'id'=>$sectionid,'status'=>1), array('is_free'));
	if(empty($section)){
		$data = array(
			'code' => -2,
			'msg'  => '章节不存在',
		);
		$this->resultJson($data);
	}
	if($section['is_free']==1){
		$data = array(
			'code' => 0,
			'msg'  => '试听章节',
		);
		$this->resultJson($data);
	}

	if($uid){
		$isbuy = pdo_fetch("SELECT validity FROM " .tablename($this->table_order). " WHERE uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
		if($isbuy){
			if($isbuy['validity']==0){
				$data = array(
					'code' => 0,
					'msg'  => '已购买课程(永久有效)',
				);
				$this->resultJson($data);
			}else{
				if($isbuy['validity']>time()){
					$data = array(
						'code' => 0,
						'msg'  => '已购买课程(有效期内)',
					);
					$this->resultJson($data);
				}
			}
		}
	}

	/* 讲师自己课程免费 */
	$teacher = pdo_get($this->table_teacher, array('uid'=>$uid), array('id'));
	if($lesson['teacherid'] == $teacher['id']){
		$data = array(
			'code' => 0,
			'msg'  => '讲师自己的课程',
		);
		$this->resultJson($data);
	}

	/* 已购买讲师服务 */
	$buy_teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND teacherid=:teacherid AND validity>:validity", array(':uid'=>$uid, ':teacherid'=>$lesson['teacherid'], ':validity'=>time()));
	if(!empty($buy_teacher)){
		$data = array(
			'code' => 0,
			'msg'  => '已购买讲师服务',
		);
		$this->resultJson($data);
	}

	if($uid){
		$memberVip_list = pdo_fetchall("SELECT level_id FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
		if(!empty($memberVip_list)){
			foreach($memberVip_list as $v){
				if(in_array($v['level_id'], json_decode($lesson['vipview']))){
					$data = array(
						'code' => 0,
						'msg'  => 'VIP会员免费学习',
					);
					$this->resultJson($data);
					break;
				}
			}
		}
	}

	/* 课程页面字体 */
	$lesson_page = $common['lesson_page'];

	$data = array(
		'code' => -99,
		'msg'  => $lesson_page['nobuyTip'] ? $lesson_page['nobuyTip'] : '请先购买课程再学习',
	);
	$this->resultJson($data);

}elseif($op == 'live'){
	/* 直播课程直接进入直播间 */

	$id = intval($_GPC['id']);/* 课程id */

	$lesson = pdo_fetch("SELECT price,teacherid,lesson_type,vipview FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id AND status!=:status LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0));
	if(empty($lesson)){
		$data = array(
			'code' => -1,
			'msg'  => '课程不存在',
		);
		$this->resultJson($data);
	}
	if($lesson['price']==0){
		$data = array(
			'code' => 0,
			'msg'  => '免费课程',
		);
		$this->resultJson($data);
	}

	if($uid){
		$isbuy = pdo_fetch("SELECT validity FROM " .tablename($this->table_order). " WHERE uid=:uid AND lessonid=:lessonid AND status>=:status AND (validity>:validity OR validity=0) AND is_delete=:is_delete ORDER BY id DESC LIMIT 1", array(':uid'=>$uid,':lessonid'=>$id,':status'=>1,':validity'=>time(),':is_delete'=>0));
		if($isbuy){
			if($isbuy['validity']==0){
				$data = array(
					'code' => 0,
					'msg'  => '已购买课程(永久有效)',
				);
				$this->resultJson($data);
			}else{
				if($isbuy['validity']>time()){
					$data = array(
						'code' => 0,
						'msg'  => '已购买课程(有效期内)',
					);
					$this->resultJson($data);
				}
			}
		}
	}

	/* 讲师自己课程免费 */
	$teacher = pdo_get($this->table_teacher, array('uid'=>$uid), array('id'));
	if($lesson['teacherid'] == $teacher['id']){
		$data = array(
			'code' => 0,
			'msg'  => '讲师自己的课程',
		);
		$this->resultJson($data);
	}

	/* 已购买讲师服务 */
	$buy_teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_member_buyteacher). " WHERE uid=:uid AND teacherid=:teacherid AND validity>:validity", array(':uid'=>$uid, ':teacherid'=>$lesson['teacherid'], ':validity'=>time()));
	if(!empty($buy_teacher)){
		$data = array(
			'code' => 0,
			'msg'  => '已购买讲师服务',
		);
		$this->resultJson($data);
	}

	if($uid){
		$memberVip_list = pdo_fetchall("SELECT level_id FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));
		if(!empty($memberVip_list)){
			foreach($memberVip_list as $v){
				if(in_array($v['level_id'], json_decode($lesson['vipview']))){
					$data = array(
						'code' => 0,
						'msg'  => 'VIP会员免费学习',
					);
					$this->resultJson($data);
					break;
				}
			}
		}
	}

	/* 课程页面字体 */
	$lesson_page = $common['lesson_page'];

	$data = array(
		'code' => -99,
		'msg'  => $lesson_page['nobuyTip'] ? $lesson_page['nobuyTip'] : '请先购买课程再学习',
	);
	$this->resultJson($data);
}

