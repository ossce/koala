<?php
/**
 * 日志管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if ($operation == 'display'){
	/* 所有操作员 */
	$admin_list = pdo_fetchall("SELECT uid,username FROM " .tablename($this->table_users));
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$condition = " uniacid=:uniacid ";
	$params = array(':uniacid' => $uniacid);
	if($_W['role']!='founder'){
		$condition .= " AND admin_uid=:admin_uid ";
		$params[':admin_uid'] = $_W['uid'];
	}
	if(!empty($_GPC['function'])){
		$condition .= " AND function LIKE :function ";
		$params[':function'] = "%".$_GPC['function']."%";
	}
	if($_GPC['log_type']>0){
		$condition .= " AND log_type=:log_type ";
		$params[':log_type'] = $_GPC['log_type'];
	}
	if($_W['role']=='founder' && $_GPC['admin_uid']>0){
		$condition .= " AND admin_uid=:admin_uid ";
		$params[':admin_uid'] = $_GPC['admin_uid'];
	}

	if (strtotime($_GPC['time']['start']) || strtotime($_GPC['time']['end'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;

		$condition .= " AND addtime>=:starttime AND addtime<=:endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_syslog). " WHERE {$condition} ORDER BY addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_syslog). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

}else if ($operation == 'delete'){
	
	if(checksubmit('submit')){
		$delete_condition = intval($_GPC['delete_condition']);

		if($delete_condition==1){
			$endTime = strtotime("-6 month");
		}elseif($delete_condition==2){
			$endTime = strtotime("-1 year");
		}else{
			message("请选择删除条件", "", "error");
		}

		pdo_query("DELETE FROM ".tablename($this->table_syslog)." WHERE addtime<:addtime", array(':addtime'=>$endTime));
		message("删除日志成功", $this->createWebUrl('syslog'), "success");
	}
	
}

include $this->template('web/syslog');

?>