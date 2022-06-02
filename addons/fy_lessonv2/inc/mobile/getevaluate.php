<?php
/**
 * 获取课程评价列表
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
/* 课程id */
$id = intval($_GPC['id']);

$pindex =max(1,$_GPC['page']);
$psize = 10;

$evaluate_list = pdo_fetchall("SELECT a.*, b.nickname,b.avatar FROM " .tablename($this->table_evaluate). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.lessonid=:lessonid AND a.status=:status ORDER BY a.type DESC,a.addtime DESC, a.id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array('lessonid'=>$id,':status'=>1));
foreach($evaluate_list as $k=>$v){
	if($v['grade']==1){
		$v['grade'] = "好评";
		$v['grade_ico'] = MODULE_URL."static/mobile/{$template}/images/oc-h.png";
	}elseif($v['grade']==2){
		$v['grade'] = "中评";
		$v['grade_ico'] = MODULE_URL."static/mobile/{$template}/images/oc-z.png";
	}elseif($v['grade']==3){
		$v['grade'] = "差评";
		$v['grade_ico'] = MODULE_URL."static/mobile/{$template}/images/oc-c.png";
	}
	if($setting['show_evaluate_time']){
		$v['addtime'] = date('Y-m-d', $v['addtime']);
	}else{
		$v['addtime'] = '';
	}

	$v['nickname'] = $v['nickname'] ? $v['nickname'] : $v['virtual_nickname'];
	if(!$v['nickname']){
		$v['nickname'] = '匿名';
	}else{
		$v['nickname'] = $site_common->substrCut($v['nickname'], 1, 1);
	}

	if(!$v['avatar'] && $v['virtual_avatar']){
		$v['avatar'] = $v['virtual_avatar'];
	}
	if(empty($v['avatar'])){
		$v['avatar'] = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
		$v['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
	}

	$v['content'] = htmlspecialchars_decode($v['content']);

	$evaluate_list[$k] = $v;
}

$this->resultJson($evaluate_list);