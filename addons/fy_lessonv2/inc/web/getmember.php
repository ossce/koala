<?php
/**
 * 查找会员
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$select_type = intval($_GPC['select_type']);
$keyword = trim($_GPC['keyword']);

$condition = " a.uniacid=:uniacid ";
$params[':uniacid'] = $uniacid;
if(!empty($keyword)){
	if($select_type == '0'){
		$condition .= " AND b.uid=:uid ";
		$params[':uid'] = $keyword;
	}elseif($select_type == '1'){
		$condition .= " AND b.nickname LIKE :nickname ";
		$params[':nickname'] = "%".$keyword."%";
	}elseif($select_type == '2'){
		$condition .= " AND b.realname LIKE :realname ";
		$params[':realname'] = "%".$keyword."%";
	}elseif($select_type == '3'){
		$condition .= " AND b.mobile=:mobile ";
		$params[':mobile'] = $keyword;
	}
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$list = pdo_fetchall("SELECT b.uid,b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_member). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.uid ASC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
foreach($list as $k=>$v){
	if(empty($v['avatar'])){
		$v['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
		$v['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
	}

	$list[$k] = $v;
}

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_member). " a INNER JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition}", $params);
$total_page = ceil($total/$psize);

include $this->template('web/getMember');


?>