<?php

require_once dirname(__FILE__).'/../../library/TencentSDK/autoload.php';
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\VodClient;
use TencentCloud\Vod\V20180717\Models\DescribeMediaInfosRequest;
try {

	$cred = new Credential('AKIDzB1pZfwDXoNyWKGuYqP0hjlnHoOCrCTN', 'JkAgMap2FQteDg5jHmBfPMVIlHd7gAcv');
	$httpProfile = new HttpProfile();
	$httpProfile->setEndpoint("vod.tencentcloudapi.com");
	  
	$clientProfile = new ClientProfile();
	$clientProfile->setHttpProfile($httpProfile);
	$client = new VodClient($cred, "", $clientProfile);

	$req = new DescribeMediaInfosRequest();
	
	$params = array(
		"FileIds" => array('5285890815654948050')
	);
	$req->fromJsonString(json_encode($params));

	$resp = $client->DescribeMediaInfos($req);

	print_r($resp->toJsonString());
}
catch(TencentCloudSDKException $e) {
	echo $e;
}


exit();
