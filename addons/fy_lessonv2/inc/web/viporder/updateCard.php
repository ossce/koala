<?php

$cardids = trim($_GPC['cardids']);
$ids = explode(",", $cardids);

if($_GPC['type']=='set'){
	$own_uid = intval($_GPC['own_uid']);
	$own_user = pdo_get($this->table_mc_members, array('uniacid'=>$uniacid,'uid'=>$own_uid));
	if(empty($own_user)){
		$data = array(
			'code' => -1,
			'msg'  => '您选择的用户不存在，请刷新页面重新选择'
		);
		$this->resultJson($data);
	}


	if(!empty($ids) && is_array($ids)){
		$num = 0;
		$card = "";
		foreach($ids as $id){
			$vipCard = pdo_get($this->table_vipcard, array('uniacid'=>$uniacid,'id'=>$id));
			if(!empty($vipCard)){
				pdo_update($this->table_vipcard, array('own_uid'=>$own_uid), array('id'=>$id));
			}
		}

		$data = array(
			'code' => 0,
			'msg'  => '批量操作成功'
		);
		$this->resultJson($data);
	}else{
		$data = array(
			'code' => -1,
			'msg'  => '未选中任何服务卡'
		);
		$this->resultJson($data);
	}

}elseif($_GPC['type']=='cancel'){
	if(!empty($ids) && is_array($ids)){
		$num = 0;
		$card = "";
		foreach($ids as $id){
			$vipCard = pdo_get($this->table_vipcard, array('uniacid'=>$uniacid,'id'=>$id));
			if(!empty($vipCard)){
				pdo_update($this->table_vipcard, array('own_uid'=>0), array('id'=>$id));
			}
		}

		$data = array(
			'code' => 0,
			'msg'  => '批量取消分配成功'
		);
		$this->resultJson($data);
	}else{
		$data = array(
			'code' => -1,
			'msg'  => '未选中任何服务卡'
		);
		$this->resultJson($data);
	}
}

