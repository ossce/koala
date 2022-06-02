<?php
/**
 * DIY页面
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if($userAgent){
	checkauth();
}

$uid = $_W['member']['uid'];
$site_common->check_black_list('visit', $uid);

$id = intval($_GPC['id']);
if(!$id){
	message("页面参数非法", "", "warning");
}

$page_data = $site_common->getDiyTemplate($id, $page_type=2);

$diy_page = pdo_get($this->table_diy_template, array('uniacid'=>$uniacid,'page_type'=>2,'id'=>$id), array('page_title','page_desc'));
$title = $diy_page['page_title'];
$descript = $diy_page['page_desc'];


include $this->template("../mobile/{$template}/diypage");

?>