<?php
/**
 * 文章公告
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$login_visit = json_decode($setting_pc['login_visit'], true);
if(in_array('article', $login_visit) && empty($_SESSION['fy_lessonv2_'.$uniacid.'_uid'])){
	header('Location:/'.$uniacid.'/login.html?refurl='.urlencode($_W['siteurl']));
}

$article_avd = $webAppCommon->getArticleAdv();

$new_notice_setting = json_decode($setting_pc['new_notice'], true);
$notice_title = $new_notice_setting['font'] ? $new_notice_setting['font'] : '最新通知';

if($op=='display'){

	/* 文章分类 */
	$category_list = $site_common->getArticleCategory();

	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$condition = " uniacid=:uniacid AND isshow=:isshow ";
	$params[':uniacid'] = $uniacid;
	$params[':isshow'] = 1;

	if($_GPC['cate_id']){
		$condition .= " AND cate_id=:cate_id";
		$params[':cate_id'] = $_GPC['cate_id'];
	}

	/* 非VIP用户仅能查看非VIP公告 */
	if($uid){
		$member_vip = pdo_fetch("SELECT level_id FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
		if(empty($member_vip)){
			$condition .= " AND is_vip=:is_vip";
			$params[':is_vip'] = 0;
		}
	}else{
		$condition .= " AND is_vip=:is_vip";
		$params[':is_vip'] = 0;
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_article) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);
	foreach($list as $k=>$v){
		$v['images'] = $v['images'] ? $_W['attachurl'].$v['images'] : MODULE_URL."static/webapp/{$template}/images/nopic_350_350.png?v=3";
		$v['content'] = mb_substr(strip_tags(htmlspecialchars_decode($v['content'])), 0, 100);

		$list[$k] = $v;
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_article). " WHERE {$condition}", $params);
	$pager = $webAppCommon->pagination($total, $pindex, $psize);

}elseif($op=='details'){
	$aid = intval($_GPC['aid']);
	$article = pdo_get($this->table_article, array('uniacid'=>$uniacid, 'id'=>$aid));
	if(!$article){
		message("该文章公告不存在！", "", "error");
	}
	$title = $article['title'];

	if($article['is_vip']){
		if(!$uid){
			header("Location:". $_W['siteroot']."/{$uniacid}/login.html?refurl=".urlencode($_W['siteroot']."/{$uniacid}/article.html?op=details&aid={$aid}"));
		}else{
			if(!$_SESSION['fy_lessonv2_'.$uniacid.'_vip']){
				message("您不是VIP用户，无权限访问该页面");
			}
		}
	}

	/* 增加访问量 */
	pdo_update($this->table_article, array('view'=>$article['view']+1), array('uniacid'=>$uniacid,'id'=>$aid));
}




include $this->template("../webapp/{$template}/article");


?>