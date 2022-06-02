<?php
/**
 * 短信发送
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */


require_once dirname(__FILE__).'/../common/SendSMS.php';


$mobile = trim($_GPC['mobile']);
$smsConfig = json_decode($setting['sms'], true);

$sendsms = new SendSMS($mobile, $smsConfig);
$sendsms->sendCode();