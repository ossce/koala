<?php
 
$id = intval($_GPC['id']); /* 当前讲师id */
$letter = array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","T","U","V","W","X","Y","Z");

if (!empty($id)) {
	$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teacher) . " WHERE uniacid=:uniacid AND id=:id ", array(':uniacid'=>$uniacid,':id'=>$id));
	if(empty($teacher)){
		message("该讲师不存在或已被删除！", "", "error");
	}
}

$teacher_price = pdo_get($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$id));
$commission = json_decode($teacher['commission'], true); /* 购买讲师一二三级佣金 */

if (checksubmit('submit')) {
	$data = array(
		'uniacid'			=> $_W['uniacid'],
		'teacher'			=> trim($_GPC['teacher']),
		'cate_id'			=> intval($_GPC['cate_id']),
		'idcard'			=> trim($_GPC['idcard']),
		'mobile'			=> trim($_GPC['mobile']),
		'teacher_income'	=> intval($_GPC['teacher_income']),
		'is_distribution'	=> intval($_GPC['is_distribution']),
		'commission'		=> json_encode($_GPC['commission']),
		'qq'				=> trim($_GPC['qq']),
		'qqgroup'			=> trim($_GPC['qqgroup']),
		'qqgroupLink'		=> trim($_GPC['qqgroupLink']),
		'online_url'		=> trim($_GPC['online_url']),
		'weixin_qrcode'		=> trim($_GPC['weixin_qrcode']),
		'teacher_bg'		=> trim($_GPC['teacher_bg']),
		'teacher_bg_pc'		=> trim($_GPC['teacher_bg_pc']),
		'first_letter'		=> trim($_GPC['first_letter']),
		'teacherdes'		=> trim($_GPC['teacherdes']),
		'digest'			=> preg_replace('/[\r\n]+/', "\n", $_GPC['digest']),
		'teacherphoto'		=> trim($_GPC['teacherphoto']),
		'status'			=> intval($_GPC['status']),
		'is_recommend'		=> intval($_GPC['is_recommend']),
		'upload'			=> intval($_GPC['upload']),
		'addlive'			=> intval($_GPC['addlive']),
		'avoid_audit'		=> intval($_GPC['avoid_audit']),
		'teacher_home_show'	=> intval($_GPC['teacher_home_show']),
		'addexam'			=> intval($_GPC['addexam']),
		'displayorder'		=> intval($_GPC['displayorder']),
		'addtime'			=> time(),
		'update_time'		=> time(),
	);
	if (empty($data['teacher'])) {
		message("请输入讲师名称");
	}
	if (!is_numeric($_GPC['teacher_income'])) {
		message("讲师分成必须为整数");
	}
	if ($data['teacher_income']<0 || $data['teacher_income']>100) {
		message("讲师分成必须介于0~100之间");
	}
	if (empty($data['status'])) {
		message("请选择讲师状态");
	}

	/* 处理讲师帐号密码 */
	$account = trim($_GPC['account']);
	$password = $_GPC['password'];
	if($account){
		if(empty($teacher['account'])){
			if(strlen($account)<6 || strlen($account)>16){
				message("微讲师账号长度必须介于6~16位", "", "error");
			}
			$isExist = pdo_get($this->table_teacher, array('uniacid'=>$uniacid, 'account'=>$account));
			if($isExist){
				message("微讲师账号已被占用，请重新输入", "", "error");
			}
			$data['account'] = $account;

			if(empty($password)){
				message("请输入微讲师密码", "", "error");
			}
		}
		if($password){
			if(strlen($password)<6 || strlen($password)>16){
				message("微讲师密码长度必须介于6~16位", "", "error");
			}
			$data['password'] =md5($password.$_W['config']['setting']['authkey']);
		}
		
	}

	if($setting['company_income']){
		$data['company_uid'] = intval($_GPC['company_uid']);
	}

	if (!empty($id)) {
		unset($data['addtime']);
		$res = pdo_update($this->table_teacher, $data, array('id' => $id));
		if($res){
			$site_common->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理", "编辑ID:{$id}的讲师");
		}
	} else {
		$res = pdo_insert($this->table_teacher, $data);
		$id = pdo_insertid();
		if($id){
			$site_common->addSysLog($_W['uid'], $_W['username'], 1, "讲师管理", "新增ID:{$id}的讲师");
		}
	}

	if($res){
		/* 处理购买讲师价格START */
		$price_info = $_GPC['price_info'];
		if(!$price_info['validity_time'] || !intval($price_info['price'])){
			pdo_delete($this->table_teacher_price, array('uniacid'=>$uniacid, 'teacherid'=>$id));
		}else{
			$price_data = array(
				'uniacid'		 => $uniacid,
				'teacherid'		 => $id,
				'price'			 => $price_info['price'],
				'integral'		 => $price_info['integral'],
				'validity_time'  => $price_info['validity_time'],
				'teacher_income' => $price_info['income'],
				'update_time'	 => time(),
			);
			if($teacher_price){
				pdo_update($this->table_teacher_price, $price_data, array('id'=>$teacher_price['id']));
			}else{
				$price_data['addtime'] = time();
				pdo_insert($this->table_teacher_price, $price_data);
			}
		}
		/* 处理购买讲师价格END */


		if($teacher['status'] != $data['status']){
			if($data['status']==1 || $data['status']==-1){
				$fans = pdo_get($this->table_fans, array('uid'=>$teacher['uid']), array('openid'));
				$tplmessage = pdo_get($this->table_tplmessage, array('uniacid'=>$uniacid), array('teacher_notice','teacher_notice_format'));
				$teacher_notice_format = json_decode($tplmessage['teacher_notice_format'], true);

				if($fans['openid'] && $tplmessage['teacher_notice']){
					if($data['status']==1){
						$first = $teacher_notice_format['first'] ? $teacher_notice_format['first'] : '恭喜您，您的讲师申请已审核通过。';
						$keyword2 = '已通过';
						$remark = $teacher_notice_format['remark'] ? $teacher_notice_format['remark'] : '点击详情进入讲师中心。';
						$url = $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=teachercenter&m=fy_lessonv2";
					}elseif($data['status']==-1){
						$first = $teacher_notice_format['first'] ? $teacher_notice_format['first'] : '很抱歉，您的讲师申请未通过审核。';
						$keyword2 = '未通过';
						if(trim($_GPC['reason'])){
							$keyword2 .= '，原因：'.$_GPC['reason'];
						}
						$remark = $teacher_notice_format['remark'] ? $teacher_notice_format['remark'] : '点击详情进入讲师中心修改后重新提交申请。';
						$url = $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=applyteacher&m=fy_lessonv2";
					}
					$keyword1 = $teacher_notice_format['keyword1'] ? $teacher_notice_format['keyword1'] : '讲师申请';

					$sendmessage = array(
						'touser' => $fans['openid'],
						'template_id' => $tplmessage['teacher_notice'],
						'url' => $url,
						'topcolor' => "#7B68EE",
						'data' => array(
							'first' => array(
								'value' => $first,
								'color' => "",
							),
							'keyword1' => array(
								'value' => $keyword1,
								'color' => "",
							),
							'keyword2' => array(
								'value' => $keyword2,
								'color' => "",
							),
							'keyword3' => array(
								'value' => date('Y年m月d日 H:i', time()),
								'color' => "",
							),
							'remark' => array(
								'value' => $remark,
								'color' => "",
							),
						)
					);
					$this->send_template_message($sendmessage);
				}
			}
		}

		//清除讲师相关缓存
		cache_delete('fy_lesson_'.$uniacid.'_recommend_teacher');
		cache_delete('fy_lessonv2_'.$uniacid.'_teacher_bg_'.$id);
		cache_delete('fy_lessonv2_'.$uniacid.'_teacher_bg_pc_'.$id);

		message("操作成功", $refurl, "success");
	}else{
		message("操作失败，请稍候重试", "", "error");
	}
}