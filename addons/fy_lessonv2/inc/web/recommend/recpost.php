<?php

$idarr = $_GPC['id'];
$recid = intval($_GPC['recid']);
$posttype = trim($_GPC['posttype']);

if(is_array($idarr) && !empty($idarr)){
	foreach($idarr as $value){
		$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$value));
		if($posttype=='cancel'){
			$recdata = array('recommendid'=>0);
		}else{
			if(!empty($lesson['recommendid'])){
				$already1 = strstr($lesson['recommendid'], $recid.',');
				$already2 = strstr($lesson['recommendid'], ','.$recid);
				if(!$already1 && !$already2 && $lesson['recommendid'] != $recid){
					$recdata = array('recommendid'=>$lesson['recommendid'].','.$recid);
				}
			}else{
				$recdata = array('recommendid'=>$recid);
			}
		}
		pdo_update($this->table_lesson_parent, $recdata, array('id'=>$value));
	}

	if($posttype=='cancel'){
		$succword = "批量取消课程成功！";
	}else{
		$succword = "批量推荐课程成功！";
	}

	message($succword, referer, "success");

}else{
	message("参数错误，系统已自动修复，请重试！", referer, "error");
}


?>