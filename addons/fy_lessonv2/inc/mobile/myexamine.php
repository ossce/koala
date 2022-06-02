<?php
/**
 * 我的考试
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

checkauth();
$uid = $_W['member']['uid'];


$title = "我的考试";

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " a.uniacid=:uniacid AND a.uid=:uid";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;

if($_GPC['finished'] != ''){
	$condition .= " AND a.finished=:finished ";
	$params[':finished'] = $_GPC['finished'];
}

$isMobile = $site_common->isMobile();

if($_W['isajax']){
	$list = pdo_fetchall("SELECT a.id,a.examine_id,a.submit_time,a.finished,a.corrected,a.score,b.title,b.cert_info,b.lesson_ids FROM " .tablename($this->table_exam_examine_record). " a LEFT JOIN " .tablename($this->table_exam_examine). " b ON a.examine_id=b.id WHERE {$condition} ORDER BY a.id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		if(!$v['finished']){
			$v['status_name'] = '未交卷';
		}else{
			if($v['corrected']){
				$v['status_name'] = '已批阅';
			}else{
				$v['status_name'] = '未批阅';
			}
		}

		$cert_info = json_decode($v['cert_info'], true);
		if($v['corrected'] && $cert_info['switch'] && $v['score'] >= $cert_info['score']){
			$v['cert'] = 1;
		}else{
			$v['cert'] = 0;
		}

		$lesson_ids = json_decode($v['lesson_ids'], true);
		if(!empty($lesson_ids)){
			foreach($lesson_ids as $item){
				$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$item), array('bookname'));
				$v['lesson'] .= $lesson['bookname']."；";
			}
			$v['lesson'] = "关联课程：".$v['lesson'];
		}else{
			$v['lesson'] = "暂未关联课程";
		}

		$list[$k] = $v;
	}

	$this->resultJson($list);
}else{
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_exam_examine_record). " a LEFT JOIN " .tablename($this->table_exam_examine). " b ON a.examine_id=b.id WHERE {$condition} ", $params);
}



include $this->template("../mobile/{$template}/myexamine");


?>