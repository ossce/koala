<?php
/**
 * 佣金明细
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$this->checkDistributorStatus($comsetting);

$title = $salefont['commission_log'] ? $salefont['commission_log'] : '佣金明细';
$commossion_award = $font['commossion_award'] ? $font['commossion_award'] : '分销奖励';

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid=:uniacid AND uid=:uid ";
$params[':uniacid'] = $uniacid;
$params[':uid'] = $uid;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_log). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize, $params);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_commission_log). " WHERE {$condition}", $params);
$pager = $webAppCommon->pagination($total, $pindex, $psize);


include $this->template("../webapp/{$template}/commissionLog");


?>