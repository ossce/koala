<?php
/**
 * 开屏广告
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */


$avd = $this->readCommonCache('fy_lesson_'.$uniacid.'_start_adv');
if(empty($avd)){
	$avd = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND is_show=:is_show AND is_pc=:is_pc AND banner_type=:banner_type ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':is_show'=>1,':is_pc'=>0, 'banner_type'=>3));
	cache_write('fy_lesson_'.$uniacid.'_start_adv', $avd);
}
if(!empty($avd)){
	$advs = array_rand($avd,1);
	$advs = $avd[$advs];
}else{
	header("Location:".$this->createMobileUrl('index',array('uid'=>$_GPC['uid'],'t'=>time())));
}


include $this->template("../mobile/{$template}/startadv");

?>