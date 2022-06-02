<?php
/**
 * 奥点云IM通讯
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

$im_config = json_decode($setting['im_config'], true);

$api = new TisApi($im_config['aodianyun']['accessId'], $im_config['aodianyun']['accessKey']);
$method = $_REQUEST['method'];


$rst = $api->$method($_REQUEST, $_GPC['tisId']);

$this->resultJson($rst);

?>