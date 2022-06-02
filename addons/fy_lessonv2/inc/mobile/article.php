<?php
/**
 * 文章公告
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
if((!$userAgent && in_array('article', $login_visit)) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}

$aid = intval($_GPC['aid']);
$uid = $_W['member']['uid'];
if($uid && !$_GPC['uid']){
	header("Location:".$_W['siteurl'].'&uid='.$uid);
}

if($op=='display'){
	$aid = intval($_GPC['aid']);
	$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE id=:id", array(':id'=>$aid));
	if(empty($article)){
		message("该文章公告不存在", "", "warning");
	}
	$title = $article['title'];

	if($article['is_vip']){
		$member_vip = pdo_fetch("SELECT level_id FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
		if(empty($member_vip)){
			message("您不是VIP用户，无权限访问该页面");
		}
	}

	/* 增加访问量 */
	pdo_update($this->table_article, array('view'=>$article['view']+1), array('uniacid'=>$uniacid,'id'=>$aid));

	/* 分享设置 */
	$sharelink = unserialize($comsetting['sharelink']);
	$article['describes'] = $article['describes'] ? $article['describes'] : trim(str_replace("&nbsp;","",substr(strip_tags(htmlspecialchars_decode($article['content'])), 0, 240)));
	$article['images'] = $article['images']?$article['images']:$sharelink['images'];
	$shareurl = $uid ? $_W['siteroot'] .'app/'. $this->createMobileUrl('article', array('aid'=>$aid,'uid'=>$uid)) : '';

}elseif($op=='list'){
	$title = $common['page_title']['article'] ? $common['page_title']['article'] : '全部公告';
	
	/* 文章分类 */
	$category_list = $site_common->getArticleCategory();
	
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND isshow=:isshow ";
	$params = array(
		':uniacid'	=> $uniacid,
		':isshow'	=> 1,
	);
	if($_GPC['cate_id']){
		$condition .= " AND cate_id=:cate_id";
		$params[':cate_id'] = $_GPC['cate_id'];
	}

	/* 非VIP用户仅能查看非VIP公告 */
	$member_vip = pdo_fetch("SELECT level_id FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
	if(empty($member_vip)){
		$condition .= " AND is_vip=:is_vip";
		$params[':is_vip'] = 0;
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_article) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['images'] = $v['images'] ? $_W['attachurl'].$v['images'] : MODULE_URL."static/mobile/{$template}/images/nopic_350_350.png?v=3";
		$v['addtime'] = date("Y-m-d H:i:s", $v['addtime']);

		$list[$k] = $v;
	}

	if($_GPC['method']=='ajaxgetlist'){
		$this->resultJson($list);
	}
}

include $this->template("../mobile/{$template}/article");

?>