<?php
/**
 * 收藏课程或讲师
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();

$ctype = trim($_GPC['ctype']);
$id = intval($_GPC['id']);
$uid = $_W['member']['uid'];

if($ctype=='lesson'){
	$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid=:uniacid AND uid=:uid AND outid=:outid AND ctype=:ctype LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':outid'=>$id,':ctype'=>1));
	if(empty($collect)){
		$insertdata = array(
			'uniacid' => $uniacid,
			'uid'	  => $uid,
			'outid'   => $id,
			'ctype'   => 1,
			'addtime' => time(),
		);
		pdo_insert($this->table_lesson_collect, $insertdata);
		echo '1';
	}else{
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'uid'=>$uid,'outid'=>$id,'ctype'=>1));
		echo '2';
	}

}elseif($ctype=='teacher'){
	$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid=:uniacid AND uid=:uid AND outid=:outid AND ctype=:ctype LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':outid'=>$id,':ctype'=>2));
	if(empty($collect)){
		$insertdata = array(
			'uniacid' => $uniacid,
			'uid'	  => $uid,
			'outid'   => $id,
			'ctype'   => 2,
			'addtime' => time(),
		);
		pdo_insert($this->table_lesson_collect, $insertdata);
		echo '1';
	}else{
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'uid'=>$uid,'outid'=>$id,'ctype'=>2));
		echo '2';
	}

}


?>