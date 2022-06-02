<?php
/**
 * 推荐课程
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */


$pindex = max(1, intval($_GPC['page']));
$psize = 16;

$recid = intval($_GPC['recid']);
$recommend = pdo_fetch("SELECT * FROM " .tablename($this->table_recommend). " WHERE id=:id AND is_show=:is_show", array(':id'=>$recid, ':is_show'=>1));
if(empty($recommend)){
	message("该课程板块不存在", "", "error");
}

$condition = " uniacid=:uniacid AND status=:status AND (recommendid=:recid1 OR (recommendid LIKE :recid2) OR (recommendid LIKE :recid3) OR (recommendid LIKE :recid4))";
$params = array(
	':uniacid' => $uniacid,
	':status'  => 1,
	':recid1'  => $recid,
	':recid2'  => "{$recid},%",
	':recid3'  => "%,{$recid}",
	':recid4'  => "%,{$recid},%",
);

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach ($list as $k => $v) {
	$v['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_lesson_son) . " WHERE parentid=:parentid AND status=:status", array(':parentid'=>$v['id'],':status'=>1));

	$v['buyTotal'] = $v['buynum'] + $v['virtual_buynum'] + $v['vip_number'] + $v['teacher_number'];
	if($v['price']==0){
		$v['buyTotal'] += $v['visit_number'];
	}

	$v['vipview'] = json_decode($v['vipview'], true);

	if($v['score']>0){
		$v['score_rate'] = $v['score']*100;
	}else{
		$v['score_rate'] = '';
	}

	$v['discount'] = $webAppCommon->getLessonDiscount($v['id']);
	if($v['discount']<1){
		$v['ico_name'] = $v['ico_name'] ? $v['ico_name'] : 'ico-discount';
		$v['market_price'] = $v['price'];
	}
	$v['price'] = round($v['price']*$v['discount'], 2);

	$v['descript'] = strip_tags(html_entity_decode($v['descript']));

	if($v['lesson_type']==1){
		$buynow_info = json_decode($v['buynow_info'], true);
		if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
			$v['ico_name'] = 'ico-appointed';
		}

		/* 重新计算报名课程学习人数 */
		$v['buyTotal'] = $v['buynum']+$v['virtual_buynum'];
	}elseif($v['lesson_type']==3){
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

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " WHERE {$condition} ", $params);

$pager = $webAppCommon->pagination($total, $pindex, $psize);



include $this->template("../webapp/{$template}/recommend");


?>