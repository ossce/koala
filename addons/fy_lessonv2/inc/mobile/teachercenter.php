<?php
/**
 * 讲师中心
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */
 
checkauth();
$uid = $_W['member']['uid'];

$teacher = pdo_fetch("SELECT a.*,b.avatar,b.nickname FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$uid));
if($op=='display'){
	$title = "讲师中心";
	$self_item = $common['self_item'];	/* 显示菜单 */
	$font = $common['teacher_page'];	/* 页面文字 */

	if(empty($teacher)){
		if(!in_array('teachercenter', $self_item)){
			message("系统没有开启讲师入驻", "", "warning");
		}else{
			header("Location:".$this->createMobileUrl('applyteacher'));
		}
	}elseif($teacher['status']==-1){
		header("Location:".$this->createMobileUrl('applyteacher'));
	}elseif($teacher['status']==2){
		message("您已申请讲师入驻，请等待审核", $this->createMobileUrl('index', array('t'=>1)), "warning");
	}

	$member = pdo_fetch("SELECT a.*,b.avatar,b.nickname AS mc_nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid,':uid'=>$uid));
	if(empty($member['avatar'])){
		$avatar = MODULE_URL."static/mobile/{$template}/images/default_avatar.jpg";
	}else{
		$inc = strstr($member['avatar'], "http://") || strstr($member['avatar'], "https://");
		$avatar = $inc ? $member['avatar'] : $_W['attachurl'].$member['avatar'];
	}

	/* 我的课程 */
	$mylessoncount = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " WHERE uniacid=:uniacid AND teacherid=:teacherid ", array(':uniacid'=>$uniacid, ':teacherid'=>$teacher['id']));

	include $this->template("../mobile/{$template}/teachercenter");

}elseif($op=='account'){
	$title = "讲师平台帐号管理";

	if(checksubmit('submit')){
		$account = trim($_GPC['account']);
		$password = $_GPC['password'];
		if(empty($teacher['account']) && (strlen($account)<6 || strlen($account)>16)){
			message("登陆账号长度必须介于6~16位", $this->createMobileUrl('teachercenter', array('op'=>'account')), "warning");
		}
		if(strlen($password)<6 || strlen($password)>16){
			message("登陆密码长度必须介于6~16位", $this->createMobileUrl('teachercenter', array('op'=>'account')), "warning");
		}

		$isExist = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND account=:account LIMIT 1", array(':uniacid'=>$uniacid, ':account'=>$account));
		if($isExist && $account!=$teacher['account']){
			message("该登录帐号已被占用，请重新输入登录帐号", $this->createMobileUrl('teachercenter', array('op'=>'account')), "warning");
		}

		$update = array('password'=>md5($password.$_W['config']['setting']['authkey']));
		if(empty($teacher['account'])){
			$update['account'] = $account;
		}
		$update['update_time'] = time();

		$res = pdo_update($this->table_teacher, $update, array('uniacid'=>$uniacid,'uid'=>$uid));
		if($res){
			message("保存成功", $this->createMobileUrl('teachercenter'), "success");
		}else{
			message("保存失败", $this->createMobileUrl('teachercenter', array('op'=>'account')), "warning");
		}
	}

	include $this->template("../mobile/{$template}/teacheraccount");
}
	

?>