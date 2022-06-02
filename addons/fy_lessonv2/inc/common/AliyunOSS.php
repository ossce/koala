<?php
/**
 * 阿里云OSS方法
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

require_once dirname(__FILE__).'/../../library/aliyunOSS/oss-php-sdk/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class AliyunOSS{
	public $accessKeyId;
	public $accessKeySecret;
	public $endpoint;

	function __construct($accessKeyId, $accessKeySecret, $endpoint) {
		$this->accessKeyId = $accessKeyId;
		$this->accessKeySecret = $accessKeySecret;
		$this->endpoint = $endpoint;
	}

	/* 获取播放链接 */
	public function getSignUrl($bucket, $object, $timeout=3600){
		$options = array();
		$suffix = substr(strrchr($object, '.'), 1);
		if(strtolower($suffix) == 'm3u8'){
			$options['x-oss-process'] = 'hls/sign';
		}

		try{
			$ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
			return $ossClient->signUrl($bucket, $object, $timeout, $method='GET', $options);

		} catch(OssException $e) {
			message($e->getMessage(), "", "error");
			return;
		}
	}

	public function delObject($bucket, $object){
		try{
			$ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);

			$ossClient->deleteObject($bucket, $object);
		} catch(OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
	}



	

}


