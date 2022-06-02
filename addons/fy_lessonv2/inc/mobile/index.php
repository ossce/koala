<?php
/**
 * 微课堂首页
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if((!$userAgent && in_array('index', $login_visit)) || ($userAgent && !$comsetting['hidden_login'])){
	checkauth();
}

$uid = $_W['member']['uid'];
$site_common->check_black_list('visit', $uid);

if($uid && !$_GPC['uid']){
	header("Location:".$_W['siteurl'].'&uid='.$uid);
	exit();
}

/* 开屏广告 */
$avd = $this->readCommonCache('fy_lesson_'.$uniacid.'_start_adv');
if(empty($avd)){
	$avd = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND is_show=:is_show AND is_pc=:is_pc AND banner_type=:banner_type ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':is_show'=>1,':is_pc'=>0, 'banner_type'=>3));
	cache_write('fy_lesson_'.$uniacid.'_start_adv', $avd);
}
if(!empty($avd) && !$_GPC['t']){
	header("Location:".$this->createMobileUrl('startadv', array('uid'=>$_GPC['uid'])));
	exit();
}

/* 粉丝信息 */
load()->model('mc');
$fans = pdo_fetch("SELECT follow FROM " .tablename($this->table_fans). " WHERE uid=:uid", array(':uid'=>$uid));

/* 全局会员信息 */
$mc_member = pdo_fetch("SELECT mobile,salt FROM " .tablename($this->table_mc_members). " WHERE uid=:uid", array(':uid'=>$uid));

/* 引导关注 */
$isfollow = json_decode($setting['isfollow'], true);

/* 自定义字体 */
$index_page = $common['index_page'];

/* 绑定手机号码是否显示密码 */
$index_verify = json_decode($setting['index_verify'], true);

/* 新会员优惠券弹窗通知 */
if($uid){
	$lessonmember = pdo_get($this->table_member, array('uid'=>$uid), array('coupon_tip','addtime'));
	if(!$lessonmember['coupon_tip']){
		pdo_update($this->table_member, array('coupon_tip'=>1), array('uid'=>$uid));
		$market = pdo_get($this->table_market, array('uniacid'=>$uniacid), array('reg_give','reg_coupon_image'));
		$reg_give = json_decode($market['reg_give'], true);
		if(time()-$lessonmember['addtime']<86400 && !empty($reg_give)){
			$coupon_tip = true;
			$reg_coupon_image = $market['reg_coupon_image'] ? $_W['attachurl'].$market['reg_coupon_image'] : MODULE_URL."static/mobile/{$template}/images/reg_coupon_image.png?v=6";
		}
	}
}

/* 绑定手机号码 */
if(checksubmit('modify_mobile')){
	$data = array();

	$data['mobile'] = trim($_GPC['mobile']);
	if(empty($data['mobile'])){
		message("请输入您的手机号码", "", "warning");
	}
	if(!(preg_match("/1\d{10}/",$data['mobile']))){
		message("您输入的手机号码格式有误", "", "warning");
	}
	$exist = pdo_fetch("SELECT uid FROM " .tablename($this->table_mc_members). " WHERE uniacid=:uniacid AND mobile=:mobile", array(':uniacid'=>$uniacid,':mobile'=>$data['mobile']));
	if(!empty($exist) && $mc_member['mobile']!=$data['mobile']){
		message("该手机号码已存在，请重新输入其他手机号码", "", "warning");
	}

	$mobile_code = trim($_GPC['verify_code']);
	if(empty($mobile_code)){
		message("请输入的短信验证码", "", "warning");
	}
	if($mobile_code != $_SESSION['mobile_code']){
		message("短信验证码错误", "", "warning");
	}

	if(in_array('password', $index_verify)){
		if(empty($_GPC['pwd1'])){
			message("请输入登录密码", "", "warning");
		}
		if($_GPC['pwd1'] != $_GPC['pwd2']){
			message("两次密码不一致", "", "warning");
		}

		$data['password'] = md5($_GPC['pwd1'] . $mc_member['salt'] . $_W['config']['setting']['authkey']);
	}

	if(pdo_update($this->table_mc_members, $data, array('uid'=>$uid))){
		cache_build_memberinfo($uid);
		/* 销毁短信验证码 */
		unset($_SESSION['mobile_code']);
		message("绑定手机成功", $this->createMobileUrl('index'), "success");
	}
}

if(empty($diy_data)){
	/* 传统首页 */
	/* 焦点图 */
	$banner = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_banner');
	if(empty($banner)){
		$banner = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND is_show=:is_show AND is_pc=:is_pc AND banner_type=:banner_type ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':is_show'=>1,':is_pc'=>0, 'banner_type'=>0));
		cache_write('fy_lesson_'.$uniacid.'_index_banner', $banner);
	}

	/* 课程分类 */
	$category_row_number = $common['category_row_number'] ? $common['category_row_number'] : 5;
	$category_row = $common['category_row'] ? $common['category_row'] : 2;
	if(!empty($setting['category_ico'])){
		$allCategoryIco = $_W['attachurl'].$setting['category_ico'];
		$cat_num = $category_row_number * $category_row - 1;
	}else{
		$allCategoryIco = "";
		$cat_num = $category_row_number * $category_row;
	}
	$category_list = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_category');
	if(empty($category_list)){
		$category_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_category). " WHERE uniacid=:uniacid AND is_show=:is_show ORDER BY displayorder DESC,id ASC LIMIT {$cat_num}", array(':uniacid'=>$uniacid,':is_show'=>1));
		cache_write('fy_lesson_'.$uniacid.'_index_category', $category_list);
	}

	/* 文章公告 */
	$article_condition = " uniacid=:uniacid AND commend=:commend AND isshow=:isshow ";
	$article_params = array(
		':uniacid'	=> $uniacid,
		':commend'	=> 1,
		':isshow'	=> 1,
	);
	if($uid){
		/* 非VIP用户仅能查看非VIP公告 */
		$member_vip = pdo_fetch("SELECT level_id FROM " .tablename($this->table_member_vip). " WHERE uniacid=:uniacid AND uid=:uid AND validity>:validity LIMIT 1", array(':uniacid'=>$uniacid,':uid'=>$uid,':validity'=>time()));
		if(empty($member_vip)){
			$article_condition .= " AND is_vip=:is_vip";
			$article_params[':is_vip'] = 0;
		}
	}else{
		/* 未登录用户仅能查看非VIP公告 */
		$article_condition .= " AND is_vip=:is_vip";
		$article_params[':is_vip'] = 0;
	}
	$articlelist = pdo_fetchall("SELECT id,title,is_vip,addtime FROM " .tablename($this->table_article). " WHERE {$article_condition} ORDER BY displayorder DESC,id DESC", $article_params);

	/* 限时折扣 */
	$discount_banner = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_discount_banner');
	if(empty($discount_banner)){
		$discount_banner = pdo_fetchall("SELECT * FROM " .tablename($this->table_banner). " WHERE uniacid=:uniacid AND is_show=:is_show AND is_pc=:is_pc AND banner_type=:banner_type ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':is_show'=>1,':is_pc'=>0, 'banner_type'=>2));
		cache_write('fy_lesson_'.$uniacid.'_index_discount_banner', $discount_banner);
	}

	/* 推荐讲师 */
	$recommend_teacher = $this->readCommonCache('fy_lesson_'.$uniacid.'_recommend_teacher');
	if(empty($recommend_teacher)){
		$recommend_teacher = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher). " WHERE uniacid=:uniacid AND status=:status AND is_recommend=:is_recommend ORDER BY displayorder DESC", array(':uniacid'=>$uniacid,':status'=>1,':is_recommend'=>1));
		cache_write('fy_lesson_'.$uniacid.'_recommend_teacher', $recommend_teacher);
	}

	/* 最新课程 */
	if($setting['show_newlesson']){
		$newlesson = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_newlesson');
		if(empty($newlesson)){
			$newlesson = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND status=:status ORDER BY update_time DESC LIMIT 0,{$setting['show_newlesson']}", array(':uniacid'=>$uniacid, ':status'=>1));
			foreach($newlesson as $k=>$v){
				$v['tran_time'] = $site_common->tranTime($v['update_time']);
				$v['section'] = pdo_fetch("SELECT title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid AND status=:status ORDER BY id DESC LIMIT 0,1", array(':parentid'=>$v['id'],':status'=>1));
				if($v['price']>0){
					$v['study_number'] = $v['buynum']+$v['virtual_buynum']+$v['vip_number']+$v['teacher_number'];
				}else{
					$v['study_number'] = $v['buynum']+$v['virtual_buynum']+$v['vip_number']+$v['teacher_number']+$v['visit_number'];
				}
				$v['vipview'] = json_decode($v['vipview'], true);

				$v['discount'] = $site_common->getLessonDiscount($v['id']);
				$v['price'] = round($v['price']*$v['discount'], 2);
				if($v['discount']<1 && !$v['ico_name']){
					$v['ico_name'] = 'ico-discount';
				}

				if($v['lesson_type']==1){
					$buynow_info = json_decode($v['buynow_info'], true);
					if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
						$v['ico_name'] = 'ico-appointed';
					}
					/* 重新计算报名课程学习人数 */
					$v['study_number'] = $v['buynum']+$v['virtual_buynum'];
				}elseif($v['lesson_type']==3){
					$live_info = json_decode($v['live_info'], true);
					$starttime = strtotime($live_info['starttime']);
					$endtime = strtotime($live_info['endtime']);
					if(time() < $starttime){
						$v['icon_live_status'] = 'icon-live-nostart';
					}elseif(time() > $endtime){
						$v['icon_live_status'] = 'icon-live-ended';
					}elseif(time() > $starttime && time() < $endtime){
						$v['icon_live_status'] = 'icon-live-starting';
					}
					unset($v['section']);
				}
				
				$newlesson[$k] = $v;
			}
			cache_write('fy_lesson_'.$uniacid.'_index_newlesson', $newlesson);
		}
	}

	/* 板块课程 */
	$list = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_recommend');
	if(empty($list)){
		$list = pdo_fetchall("SELECT id AS recid,rec_name,icon,show_style,limit_number FROM " .tablename($this->table_recommend). " WHERE uniacid=:uniacid AND is_show=:is_show ORDER BY displayorder DESC,id DESC", array(':uniacid'=>$uniacid,':is_show'=>1));
		foreach($list as $key=>$rec){
			$list[$key]['lesson'] = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND status=1 AND (recommendid='{$rec['recid']}' OR (recommendid LIKE '{$rec['recid']},%') OR (recommendid LIKE '%,{$rec['recid']}') OR (recommendid LIKE '%,{$rec['recid']},%')) ORDER BY displayorder DESC, id DESC LIMIT ".$rec['limit_number']);
			foreach($list[$key]['lesson'] as $k=>$val){
				$val['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid=:parentid AND status=:status ", array(':parentid'=>$val['id'], ':status'=>1));

				$val['vipview'] = json_decode($val['vipview'], true);
				if($val['ico_name']=='ico-vip' && (!empty($val['vipview']) && $val['vipview']!='null')){
					$val['ico_name'] = 'ico-vip';
				}
				if($val['price']>0){
					$val['study_number'] = $val['buynum']+$val['virtual_buynum']+$val['vip_number']+$val['teacher_number'];
				}else{
					$val['study_number'] = $val['buynum']+$val['virtual_buynum']+$val['vip_number']+$val['teacher_number']+$val['visit_number'];
				}

				$val['discount'] = $site_common->getLessonDiscount($val['id']);
				$val['price'] = round($val['price']*$val['discount'], 2);
				if($val['discount']<1 && !$val['ico_name']){
					$val['ico_name'] = 'ico-discount';
				}

				$val['section'] = pdo_fetch("SELECT title FROM " .tablename($this->table_lesson_son). " WHERE parentid=:parentid ORDER BY id DESC LIMIT 0,1", array(':parentid'=>$val['id']));
				$val['teacher'] = pdo_get($this->table_teacher, array('id'=>$val['teacherid']), array('teacher'));

				if($val['lesson_type']==1){
					$buynow_info = json_decode($val['buynow_info'], true);
					if($buynow_info['appoint_validity'] && time() > strtotime($buynow_info['appoint_validity'])){
						$val['ico_name'] = 'ico-appointed';
					}

					/* 重新计算报名课程学习人数 */
					$val['study_number'] = $val['buynum']+$val['virtual_buynum'];
				}elseif($val['lesson_type']==3){
					$live_info = json_decode($val['live_info'], true);
					$starttime = strtotime($live_info['starttime']);
					$endtime = strtotime($live_info['endtime']);
					if(time() < $starttime){
						$val['icon_live_status'] = 'icon-live-nostart';
					}elseif(time() > $endtime){
						$val['icon_live_status'] = 'icon-live-ended';
					}elseif(time() > $starttime && time() < $endtime){
						$val['icon_live_status'] = 'icon-live-starting';
					}
					unset($val['count']);
				}
			
				$list[$key]['lesson'][$k] = $val;
			}
			if(empty($list[$key]['lesson'])){
				unset($list[$key]);
			}
		}
		cache_write('fy_lesson_'.$uniacid.'_index_recommend', $list);
	}

	/* 首页模版 */
	$index_html = $this->readCommonCache('fy_lesson_'.$uniacid.'_index_html');
	if(empty($index_html)){
		$module_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_index_module). " WHERE uniacid=:uniacid AND is_show=:is_show ORDER BY displayorder DESC,index_id ASC", array('uniacid'=>$uniacid, ':is_show'=>1));
		if(empty($module_list)){
			$index_html = 'index_default';
		}else{
			$index_html = $module_list;
		}
		cache_write('fy_lesson_'.$uniacid.'_index_html', $index_html);
	}
}


include $this->template("../mobile/{$template}/index");

?>