<?php
set_time_limit(0);

if ($op == "display") {
    $upset = pdo_get($this->table_upset, array('uniacid'=>$_W['uniacid']));
    $dingshi = $_W['siteroot'] . 'addons/fy_lessonv2_plugin_supersync/tongbu.php?opt=auto&uniacid='.$_W['uniacid'].'&pagesize=10';
    
    if(checksubmit()){
    	$data = array(
    		'uniacid'			=> $uniacid,
    		'domain'		=> $_GPC['domain'],
    		'auto_tongbu'		=> $_GPC['auto_tongbu'],
    		'tongbu_uniacid'		=> $_GPC['tongbu_uniacid']
    	);
    
    	if ($upset) {
    	    $result = pdo_update($this->table_upset, $data, array('uniacid' => $uniacid));
    	} else {
    		$result = pdo_insert($this->table_upset, $data);
    	}
    
    	if($result){
    		message('操作成功', $this->createWebUrl('upset', array('op' => 'display')), 'success');
    	}else{
    		message('操作失败，请稍候重试', "", 'error');
    	}
    }
    include $this->template("web/upset");
}
