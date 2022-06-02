<?php
/**
 * 微课程模块定义
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */
defined('IN_IA') or exit('Access Denied');

class fy_lessonv2Module extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$dat = array(
				'buynow_name' => trim($_GPC['buynow_name']),
				'buynow_link' => trim($_GPC['buynow_link']),
				'buy_vip_name'=> trim($_GPC['buy_vip_name']),
				'buy_vip_link'=> trim($_GPC['buy_vip_link']),
				'study_name'  => trim($_GPC['study_name']),
				'study_link'  => trim($_GPC['study_link']),

				'share_name'  => trim($_GPC['share_name']),
				'service_url' => trim($_GPC['service_url']),
				'tel'		  => trim($_GPC['tel']),
				'teacher_qq'  => trim($_GPC['teacher_qq']),
				'teacher_qqgroup' => trim($_GPC['teacher_qqgroup']),
				'teacher_qqlink'  => trim($_GPC['teacher_qqlink']),
				'teacher_qrcode'  => trim($_GPC['teacher_qrcode']),

				//即将废弃start
				'mylesson_bg'  => $_GPC['mylesson_bg'],
				'ucenter_bg'   => $_GPC['ucenter_bg'],
				'vip_bg'	   => $_GPC['vip_bg'],
				'vip_bg_pc'	   => $_GPC['vip_bg_pc'],
				'myteacher_bg' => $_GPC['myteacher_bg'],
				'teacher_bg'   => $_GPC['teacher_bg'],
				'teacher_bg_pc'=> $_GPC['teacher_bg_pc'],
				//即将废弃end

				'index_slogan' => $_GPC['index_slogan'],
				'statis_code'  => $_GPC['statis_code'],
			);
			$this->saveSettings($dat);
			itoast("保存成功", "", "success");
		}

		include $this->template('web/settings');
	}

}

?>