<?php
/**
 * 学习记录
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$pindex =max(1,$_GPC['page']);
$psize = 20;

if($op=='display'){

	include_once "record/display.php";

}elseif($op == 'studyDuration'){

	include_once "record/studyDuration.php";

}elseif($op == 'studyDurationDetails'){

	include_once "record/studyDurationDetails.php";

}elseif($op == 'test'){
/*
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid=:uniacid ORDER BY video_duration DESC LIMIT 0,10", array(':uniacid'=>$uniacid));
	foreach($list as $v){
		$playrecord = pdo_fetchall("SELECT SUM(duration*playcount) AS total FROM " .tablename($this->table_playrecord). " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$v['uid']));
		
		if($playrecord[0]['total']){
			pdo_update($this->table_member, array('video_duration'=>$playrecord[0]['total']), array('uniacid'=>$uniacid,'uid'=>$v['uid']));
		}
	}
*/
}


include $this->template('web/record');

?>