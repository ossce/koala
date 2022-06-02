<?php

ignore_user_abort(true);
set_time_limit(600);

/* 优惠券列表 */
$couponList = pdo_fetchall("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));
/* VIP等级 */
$levelList = pdo_fetchall("SELECT * FROM " .tablename($this->table_vip_level). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

if(checksubmit('submit')){
	$coupon_id = intval($_GPC['coupon_id']);
	$send_type = intval($_GPC['send_type']);
	$uids = explode(",", trim($_GPC['uids']));
	$level_id = $_GPC['level_id'];
	$startDate = strtotime($_GPC['time']['start'] . " 00:00:00");
	$endDate = strtotime($_GPC['time']['end'] . " 23:59:59");

	if(checksubmit('submit')){
		if(empty($coupon_id)){
			message("请选择优惠券", "", "error");
		}
		if(empty($send_type)){
			message("请选择发放范围", "", "error");
		}
		$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_mcoupon). " WHERE id=:id", array(':id'=>$coupon_id));
		if(empty($coupon)){
			message("优惠券不存在", "", "error");
		}

		$list = array();
		if($send_type==1){
			/*全部会员*/
			$list = pdo_fetchall("SELECT a.uid,b.openid,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_fans). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid", array(':uniacid'=>$uniacid));

		}elseif($send_type==2){
			if(empty($uids)){
				message("请输入指定会员uid", "", "error");
			}

			/*指定会员*/
			foreach($uids as $key=>$value){
				$item = pdo_fetch("SELECT a.uid,b.openid,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_fans). " b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$value));
				if(empty($item)) continue;

				$list[$key] = $item;
				unset($item);
			}

		}elseif($send_type==3){
			/*指定VIP等级*/
			if(empty($level_id)){
				message("请选择指定的会员VIP等级", "", "error");
			}
			$list = pdo_fetchall("SELECT a.uid,b.openid,b.nickname FROM " .tablename($this->table_member_vip). " a LEFT JOIN ".tablename($this->table_fans)." b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.level_id=:level_id", array(':uniacid'=>$uniacid,':level_id'=>$level_id));

		}elseif($send_type==4){
			/*指定注册日期*/
			if(empty($startDate) || empty($endDate)){
				message("请选择加入日期", "", "error");
			}
			$list = pdo_fetchall("SELECT a.uid,b.openid,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN ".tablename($this->table_fans)." b ON a.uid=b.uid WHERE a.uniacid=:uniacid AND a.addtime>=:startDate AND addtime<=:endDate", array(':uniacid'=>$uniacid,':startDate'=>$startDate,':endDate'=>$endDate));
		}

		$validity = $coupon['validity_type']==1 ? $coupon['days1'] : time()+ $coupon['days2']*86400;
		$now = time();

		$sql_head = "INSERT INTO ".tablename($this->table_member_coupon)." (`uniacid`, `uid`,`amount`,`conditions`,`validity`,`use_type`,`category_id`,`lesson_ids`,`status`,`source`,`coupon_id`,`addtime`) VALUES ";
		$sql = "";

		foreach($list as $k=>$v){
			$sql .= "('{$uniacid}','{$v[uid]}','{$coupon[amount]}','{$coupon[conditions]}','{$validity}','{$coupon[use_type]}','{$coupon[category_id]}','{$coupon[lesson_ids]}','0','7','$coupon[id]','{$now}'),";

			if(($k+1)%1000==0 || $k+1==count($list)){
				$sql = substr($sql, 0, strlen($sql)-1);
				pdo_query($sql_head.$sql);
				$sql = "";
			}
		}

		message("发放成功", $this->createWebUrl('market', array('op'=>'sendCoupon')), "success");
	}
}