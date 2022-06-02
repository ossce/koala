<?php

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_suggest_category). " WHERE uniacid=:uniacid ORDER BY displayorder DESC, id ASC ", array(':uniacid'=>$uniacid));

if(checksubmit()){
	foreach($_GPC['title'] as $k=>$v){
		$title = trim($v);
		$id = intval($_GPC['id'][$k]);

		if(empty($title)){
			continue;
		}

		$data = array(
			'uniacid'		=> $uniacid,
			'title'			=> $title,
			'displayorder'  => count($_GPC['title'])-$k,
		);

		if($id){
			pdo_update($this->table_suggest_category, $data, array('uniacid'=>$uniacid,'id'=>$id));
		}else{
			pdo_insert($this->table_suggest_category, $data);
		}
		$titles .= $data['title'].";";
	}

	cache_delete('fy_lesson_'.$uniacid.'_suggest_category');
	$site_common->addSysLog($_W['uid'], $_W['username'], 1, "其他管理->投诉建议", "修改投诉类型为：[{$titles}]");
	itoast("保存成功", "", "success");
}

?>