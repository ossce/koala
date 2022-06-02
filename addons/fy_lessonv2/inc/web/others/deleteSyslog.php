<?php

if(checksubmit('submit')){
	$delete_condition = intval($_GPC['delete_condition']);

	if($delete_condition==1){
		$endTime = strtotime("-6 month");
		$endTime_tips = "半年前";
	}elseif($delete_condition==2){
		$endTime = strtotime("-1 year");
		$endTime_tips = "一年前";
	}else{
		message("请选择删除条件", "", "error");
	}

	pdo_query("DELETE FROM ".tablename($this->table_syslog)." WHERE addtime<:addtime", array(':addtime'=>$endTime));

	$site_common->addSysLog($_W['uid'], $_W['username'], 2, "其他管理->日志列表", "删除{$endTime_tips}操作日志");
	itoast("删除日志成功", $this->createWebUrl('others',array('op'=>'syslog')), "success");
}

?>