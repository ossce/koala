<?php
/**
 * 关注二维码
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$follow_page   = $common['follow_page'];
$title = $follow_page['title'] ? $follow_page['title'] : "公众号二维码";

$wxapp_qrcode = $_W['attachurl']."images/{$uniacid}/fy_lessonv2/wxapp_follow.png";
$wxapp_root = ATTACHMENT_ROOT."images/{$uniacid}/fy_lessonv2/wxapp_follow.png";
$wxapp_exist = is_file($wxapp_root);



include $this->template("../mobile/{$template}/follow");