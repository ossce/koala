<?php

$aid = intval($_GPC['aid']);
if($aid>0){
	$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$aid));
	if(empty($article)){
		message("该文章公告不存在！", "", "error");
	}
}

if(checksubmit('submit')){
	$data = array(
		'uniacid'		=> $uniacid,
		'title'			=> trim($_GPC['title']),
		'cate_id'		=> intval($_GPC['cate_id']),
		'author'		=> trim($_GPC['author']),
		'content'		=> $_GPC['content'],
		'linkurl'		=> trim($_GPC['linkurl']),
		'images'		=> $_GPC['images'],
		'describes'		=> $_GPC['describes'],
		'commend'		=> intval($_GPC['commend']),
		'is_vip'		=> intval($_GPC['is_vip']),
		'isshow'		=> intval($_GPC['isshow']),
		'virtual_view'	=> intval($_GPC['virtual_view']),
		'displayorder'  => intval($_GPC['displayorder']),
	);
	if(!$data['title']){
		message("请填写文章标题");
	}
	if(!$data['content']){
		message("请填写文章内容");
	}

	if($aid>0){
		$res = pdo_update($this->table_article, $data, array('uniacid'=>$uniacid, 'id'=>$aid));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "文章公告", "编辑ID:{$aid}的文章公告");
		}
		message("更新成功", $refurl, "success");
	}else{
		$data['addtime'] = time();
		pdo_insert($this->table_article, $data);
		$id = pdo_insertid();

		if($id>0){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "文章公告", "新增ID:{$id}的文章公告");
			
		}
		message("添加成功", $refurl, "success");
	}
}