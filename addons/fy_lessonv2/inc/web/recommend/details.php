<?php

$id = intval($_GPC['recid']);
$recommend = pdo_fetch("SELECT id,rec_name FROM " . tablename($this->table_recommend) . " WHERE uniacid = '{$uniacid}' AND id = '{$id}'");
if (empty($recommend)) {
	message("抱歉，板块不存在或是已经被删除！", $this->createWebUrl('recommend', array('op' => 'display')), "error");
}

$condition = " b.uniacid='{$uniacid}' AND ((b.recommendid='{$id}') OR (b.recommendid LIKE '{$id},%') OR (b.recommendid LIKE '%,{$id}') OR (b.recommendid LIKE '%,{$id},%')) ";

$list = pdo_fetchall("SELECT a.teacher, b.id,b.bookname,b.price,b.status FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.id=b.teacherid LEFT JOIN " .tablename($this->table_recommend). " c ON b.recommendid=c.id WHERE {$condition} ORDER BY b.displayorder DESC, b.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " b LEFT JOIN " .tablename($this->table_recommend). " c ON b.recommendid=c.id WHERE {$condition}");
$pager = pagination($total, $pindex, $psize);

/* 批量取消推荐课程 */
if($_GPC['cancleRec']==1){
	$idarr = $_GPC['id'];
	$recid = intval($_GPC['recid']);

	if(is_array($idarr) && !empty($idarr)){
		foreach($idarr as $value){
			$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$value));
			$recdata = array('recommendid'=>trim(str_replace($recid,"",$lesson['recommendid']),","));
			pdo_update($this->table_lesson_parent, $recdata, array('id'=>$value));
		}
		message("批量取消课程成功！", referer, "success");
	}
}

?>