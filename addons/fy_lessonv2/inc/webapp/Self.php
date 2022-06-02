<?php
/**
 * 个人中心
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

/* 访问权限 */
$site_common->check_black_list('visit', $uid);

$title = "个人中心";
$self_item = $common['self_item'];
$self_diy = unserialize($setting['self_diy']);
$font = json_decode($comsetting['font'], true);


$memberinfo = pdo_fetch("SELECT uid,mobile,credit1,credit2,nickname,avatar FROM " .tablename($this->table_mc_members). " WHERE uid=:uid LIMIT 1", array(':uid'=>$uid));

/* 订阅模板消息判断 */
$subscribe_msg = pdo_get($this->table_subscribe_msg, array('uid'=>$uid));
$is_subscribe = empty($subscribe_msg) || $subscribe_msg['subscribe'] ? 1 : 0;

/* VIP等级数量 */
$memberListCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid AND is_show=:is_show", array(':uniacid'=>$uniacid,':is_show'=>1));

/* 已购VIP数量 */
$memberVipCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));

/* 检查会员是否讲师身份 */
$teacher = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uid=:uid", array(':uid'=>$uid));

/* 关注的课程数量 */
$collect_lesson = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uid=:uid AND ctype=:ctype", array(':uid'=>$uid, ':ctype'=>1));

/* 关注的讲师数量 */
$collect_teacher = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_collect) . " WHERE uid=:uid AND ctype=:ctype", array(':uid'=>$uid, ':ctype'=>2));

/* 可用优惠券数量 */
$coupon_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member_coupon). " WHERE uniacid=:uniacid AND uid=:uid AND status=:status", array(':uniacid'=>$uniacid,':uid'=>$uid,':status'=>0));

/* 待付款订单 */
$nopay_list = pdo_fetchall("SELECT a.id,a.ordersn,a.lesson_type,a.spec_name,a.spec_day,a.price,a.lessonid,a.bookname,a.addtime,b.images FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id WHERE a.uniacid=:uniacid AND a.uid=:uid AND a.status=:status AND a.is_delete=:is_delete ORDER BY a.id DESC LIMIT 0,3", array(':uniacid'=>$uniacid, ':uid'=>$uid, ':status'=>0, ':is_delete'=>0));
$nopay_count = count($nopay_list);

/* 已付款订单数量 */
$payed_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND uid=:uid AND status>:status AND is_delete=:is_delete", array(':uniacid'=>$uniacid,':uid'=>$uid, ':status'=>0, ':is_delete'=>0));

/* 可核销订单数量 */
$verify_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND uid=:uid AND lesson_type=:lesson_type AND status>:status AND is_verify<:is_verify AND is_delete=:is_delete", array(':uniacid'=>$uniacid,':uid'=>$uid, ':lesson_type'=>1, ':status'=>0, ':is_verify'=>2, ':is_delete'=>0));

/* 待评价订单数量 */
$noevaluate_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND uid=:uid AND status=:status AND is_delete=:is_delete", array(':uniacid'=>$uniacid, ':uid'=>$uid, ':status'=>1, ':is_delete'=>0));



include $this->template("../webapp/{$template}/self");


?>