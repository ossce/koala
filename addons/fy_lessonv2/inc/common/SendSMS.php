<?php
/**
 * 短信发送类，封装阿里云短信和腾讯云短信
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

session_start();
defined('IN_IA') or exit('Access Denied');

class SendSMS extends WeModuleSite {

	function __construct($mobile, $smsConfig) {
		$this->mobile = $mobile;
		$this->smsConfig = $smsConfig;
	}

	public function sendCode(){
		if(!(preg_match("/1\d{10}/",$this->mobile))){
			$data = array(
				'code' => -1,
				'msg'  => '您输入的手机号码有误'
			);
			$this->resultJson($data);
		}

		$param['code'] = strval(rand(1234,9999));
		$_SESSION['mobile_record'] = $this->mobile;
		$_SESSION['mobile_code'] = $param['code'];

		if($this->smsConfig['type']==1){
			require_once dirname(__FILE__).'/AliyunSMS.php';
			$aliyunSMS = new AliyunSMS();

			$sms = $this->smsConfig['aliyun'];
			$output = $aliyunSMS->sendSMS($sms, $this->mobile, $sms['template_id'], $param);
			$result = json_decode(json_encode($output), true);

			if($result['Code']=='OK'){
				$data = array(
					'code' => 0,
					'msg'  => '验证码发送成功',
					'result' => $result
				);
			}else{
				$data = array(
					'code' => -1,
					'msg'  => $result['Message'],
					
				);
			}

			$this->resultJson($data);

		}elseif($this->smsConfig['type']==2){
			require_once dirname(__FILE__).'/QcloudSMS.php';
			$qcloudSMS = new QcloudSMS();

			$sms = $this->smsConfig['qcloud'];
			$output = $qcloudSMS->sendSMS($sms, $this->mobile, $sms['template_id'], $param);
			$result = json_decode($output, true);

			if($result['result']=='0'){
				$data = array(
					'code' => 0,
					'msg'  => '验证码发送成功',
					'result' => $result
				);
			}else{
				$data = array(
					'code' => -1,
					'msg'  => $result['errmsg'],
				);
			}

			$this->resultJson($data);
		}
	}


	/* json输出 */
	private function resultJson($data){
		echo json_encode($data);
		exit();
	}

}


?>