<?php
/**
 * 获取课程评价
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 课程id */
$id = intval($_GPC['id']);

$pindex =max(1,$_GPC['page']);
$psize = 10;

$evaluate_list = pdo_fetchall("SELECT a.*, b.nickname,b.avatar FROM " .tablename($this->table_evaluate). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.lessonid=:lessonid AND a.status=:status ORDER BY a.type DESC, a.addtime DESC, a.id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, array('lessonid'=>$id,':status'=>1));
foreach($evaluate_list as $k=>$v){
	if($v['grade']==1){
		$v['grade'] = "好评";
		$v['grade_ico'] = MODULE_URL.'static/webapp/default/images/oc-h.png';
	}elseif($v['grade']==2){
		$v['grade'] = "中评";
		$v['grade_ico'] = MODULE_URL.'static/webapp/default/images/oc-z.png';
	}elseif($v['grade']==3){
		$v['grade'] = "差评";
		$v['grade_ico'] = MODULE_URL.'static/webapp/default/images/oc-c.png';
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
		$v['avatar'] = MODULE_URL."static/webapp/default/images/default_avatar.jpg";
	}else{
		$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
		$v['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
	}

	$evaluate_list[$k] = $v;
}

$evaluate_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE lessonid=:lessonid AND status=:status", array(':lessonid'=>$id,':status'=>1));
$page_total = ceil($evaluate_total/$psize);


$range = array();
$range['start'] = max(1, $pindex - 2);
$range['end'] = min($page_total, $pindex + 2);

$html = '<ul class="hg-40 ql_pages m-auto text-c">';
$html .= ($pindex > $page_total || $pindex==1) ? '<li><a href="javascript:;" class="end">&lt;</a></li>' : '<li><a href="#content-info" data-page="'.($pindex-1).'">&lt;</a></li>';

if ($range['end'] - $range['start'] < 4) {
	$range['end'] = min($page_total, $range['start'] + 4);
	$range['start'] = max(1, $range['end'] - 4);
}
for ($i = $range['start']; $i <= $range['end']; $i++) {
	$html .= ($i == $pindex ? '<li><a href="#content-info" class="cur" data-page="'.$i.'">'.$i.'</a></li>' : '<li><a href="#content-info" data-page="'.$i.'">'.$i.'</a></li>');
}
$html .= $pindex >= $page_total ? '<li><a href="javascript:;" class="end">&gt;</a></li>' : '<li><a href="#content-info" data-page="'.($pindex+1).'">&gt;</a></li>';
$html .= '</ul>';


$data = array(
	'evaluate_list' => $evaluate_list,
	'pager_html'	=> $html,
);

$this->resultJson($data);


?>