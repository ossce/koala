<?php
/**
 * 文章公告管理
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 文章分类 */
$category_list = $site_common->getArticleCategory();
$catename_list = array();
foreach($category_list as $v){
	$catename_list[$v['id']] = $v['name'];
}

/* 上一步URL */
$refurl = $_GPC['refurl'] ? './index.php?'.base64_decode($_GPC['refurl']) : $this->createWebUrl('article');

if ($op == 'display'){
	
	include_once "article/display.php";

}elseif ($op == 'post'){
	
	include_once "article/post.php";
	
}else if ($op == 'delete'){
	
	include_once "article/delete.php";

}elseif ($op == 'category'){
	
	include_once "article/category.php";
	
}elseif ($op == 'postCategory'){
	
	include_once "article/postCategory.php";
	
}elseif ($op == 'delCategory'){
	
	include_once "article/delCategory.php";
	
}

include $this->template('web/article');

?>