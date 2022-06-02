<?php

$bannerType = $typeStatus->bannerType();
$banner_id = intval($_GPC['banner_id']);
if($banner_id){
	$picture = pdo_get($this->table_banner, array('banner_id' => $banner_id));
	if(empty($picture)){
		message('该广告位不存在', '', 'error');
	}
}

if (checksubmit('submit')) {
	$data = array(
		'uniacid'	   => $uniacid,
		'banner_name'  => trim($_GPC['banner_name']),
		'picture'	   => trim($_GPC['picture']),
		'link'		   => trim($_GPC['link']),
		'is_pc'		   => intval($_GPC['is_pc']),
		'is_show'	   => $_GPC['is_show'],
		'banner_type'  => $_GPC['banner_type'],
		'displayorder' => intval($_GPC['displayorder']),
		'update_time'  => time(),
	);

	if(empty($data['banner_name'])){
		message('请填写广告位名称', '', 'error');
	}
	if($data['banner_type']==''){
		message('请选择广告位类型', '', 'error');
	}
	if(empty($data['picture'])){
		message('请上传广告位图片', '', 'error');
	}

	if (empty($banner_id)) {
		$data['addtime'] = time();
		$result = pdo_insert($this->table_banner, $data);
	} else {
		$result = pdo_update($this->table_banner, $data, array('banner_id' => $banner_id));
	}

	if($result){
		$site_common->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->广告位管理", "添加/修改广告位");

		$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('setting', array('op' => 'picture'));
		message('更新成功', $refurl, 'success');
	}else{
		message('更新失败，请稍候重试 ', '', 'error');
	}  
}