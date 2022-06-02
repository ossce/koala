<?php

/* 推荐板块列表 */
$rec_list = pdo_fetchall("SELECT id,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
/* 课程分类列表 */
$category_list = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE uniacid=:uniacid AND parentid=:parentid", array(':uniacid'=>$uniacid,':parentid'=>0));

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$bookname = trim($_GPC['bookname']);
$pid	  = intval($_GPC['pid']);
$recid	  = trim($_GPC['recid']);
$is_free  = trim($_GPC['is_free']);

$condition = " uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
if(!empty($bookname)){
	$condition .= " AND bookname LIKE :bookname ";
	$params[':bookname'] = "%".$bookname."%";
}
if($pid>0){
	$condition .= " AND pid=:pid ";
	$params[':pid'] = $pid;
}
if($recid=='norec'){
	$condition .= " AND (recommendid=:recommendid OR recommendid IS NULL) ";
	$params[':recommendid'] = '';
}else{
	if(intval($recid)>0){
		$condition .= " AND ((recommendid='{$recid}') OR (recommendid LIKE '{$recid},%') OR (recommendid LIKE '%,{$recid}') OR (recommendid LIKE '%,{$recid},%')) ";
	}
}
if(in_array($is_free, array('0','1'))){
	if(in_array($is_free, array('0'))){
		$condition .= " AND price=:price ";
		$params[':price'] = 0;
	}elseif(in_array($is_free, array('1'))){
		$condition .= " AND price > :price ";
		$params[':price'] = 0;
	}
}

$lesson_list = pdo_fetchall("SELECT id,bookname,price,recommendid,status,addtime FROM" .tablename($this->table_lesson_parent). " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
foreach($lesson_list as $key=>$value){
	$recidarr = explode(",", $value['recommendid']);
	foreach($recidarr as $rid){
		$tmp_rec = pdo_fetch("SELECT rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid='{$uniacid}' AND id='{$rid}'");
		$rec_name .= $tmp_rec['rec_name']."<br/>";
	}
	$lesson_list[$key]['rec_name'] = trim($rec_name,"<br/>");
	unset($rec_name);
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}",$params);
$pager = pagination($total, $pindex, $psize);

?>