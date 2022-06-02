<?php
/**
 * 查找课程或会员
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

$op = $_GPC['op'];
if($op=='getLesson'){
	$uniacid = intval($_GPC['uniacid']);
	$keyword = trim($_GPC['keyword']);

	$condition = " uniacid=:uniacid ";
	$param[':uniacid'] = $uniacid;
	if(!empty($keyword)){
		$condition .= " AND bookname LIKE :keyword ";
		$param[':keyword'] = "%".$keyword."%";
	}

	$list = pdo_fetchall("SELECT id,bookname,lesson_type,price,teacher_income,images,validity FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}", $param);

	include $this->template('web/getLesson');

}elseif($op=='getMember'){
	$uniacid = intval($_GPC['uniacid']);
	$keyword = trim($_GPC['keyword']);

	$condition = " a.uniacid=:uniacid ";
	$param[':uniacid'] = $uniacid;
	if(!empty($keyword)){
		$condition .= " AND (b.nickname LIKE :keyword OR b.realname LIKE :keyword OR b.mobile = :keyword2 OR b.uid=:keyword2 ) ";
		$param[':keyword'] = "%".$keyword."%";
		$param[':keyword2'] = $keyword;
	}

	$list = pdo_fetchall("SELECT b.uid,b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE {$condition} ORDER BY uid ASC", $param);
	foreach($list as $k=>$v){
		if(empty($v['avatar'])){
			$list[$k]['avatar'] = MODULE_URL."template/mobile/{$template}/images/default_avatar.jpg";
		}else{
			$inc = strstr($v['avatar'], "http://") || strstr($v['avatar'], "https://");
			$list[$k]['avatar'] = $inc ? $v['avatar'] : $_W['attachurl'].$v['avatar'];
		}
	}

	include $this->template('web/getMember');
}

?>