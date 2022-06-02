<?php

if(checksubmit()){
	$limit = intval($_GPC['upnumber']) ;
	$old_domain = trim($_GPC['old_domain']);
	$new_domain = trim($_GPC['new_domain']);
	
	if(empty($limit)){
		message("请选择更新数量", "", "error");
	}
	if(empty($old_domain)){
		message("请输入原音视频域名", "", "error");
	}
	if(empty($new_domain)){
		message("请输入新音视频域名", "", "error");
	}

	$t = 0;

	$section_list = pdo_fetchall("SELECT id,videourl FROM " .tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND videourl LIKE :videourl LIMIT 0, ".$limit, array(':uniacid'=>$uniacid, ':videourl'=>'%'.$old_domain.'%'));

	foreach($section_list as $item){
		$videourl = str_replace($old_domain, $new_domain, $item['videourl']);
		if(pdo_update($this->table_lesson_son, array('videourl'=>$videourl), array('id'=>$item['id']))){
			$t++;
		}
	}

	message("成功更新{$t}条数据", $this->createWebUrl('lesson', array('op'=>'updomain')), "success");
}