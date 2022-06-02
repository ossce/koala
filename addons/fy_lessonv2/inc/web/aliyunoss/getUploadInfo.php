<?php

$id= $aliyunoss['access_key_id'];          // 请填写您的AccessKeyId。
$key= $aliyunoss['access_key_secret'];     // 请填写您的AccessKeySecret。

// $host的格式为 bucketname.endpoint，请替换为您的真实信息。
if($aliyunoss['https']){
	$host = 'https://'.$aliyunoss['bucket'].'.'.$aliyunoss['endpoint'];
}else{
	$host = 'http://'.$aliyunoss['bucket'].'.'.$aliyunoss['endpoint'];
}

// $callbackUrl为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息。
$callbackUrl = '';

// 用户上传文件时指定的前缀。
$dir = 'admin/';

$callback_param = array('callbackUrl'=>$callbackUrl, 
			 'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}', 
			 'callbackBodyType'=>"application/x-www-form-urlencoded");
$callback_string = json_encode($callback_param);

$base64_callback_body = base64_encode($callback_string);
$now = time();
$expire = 30;  //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问。

$end = $now + $expire;
$dtStr = date("c", $end);
$mydatetime = new DateTime($dtStr);
$expiration = $mydatetime->format(DateTime::ISO8601);
$pos = strpos($expiration, '+');
$expiration = substr($expiration, 0, $pos);
$expiration .= "Z";

//最大文件大小.用户可以自己设置
$condition = array(0=>'content-length-range', 1=>0, 2=>5368709120);
$conditions[] = $condition; 

// 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
$start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
$conditions[] = $start; 


$arr = array('expiration'=>$expiration,'conditions'=>$conditions);
$policy = json_encode($arr);
$base64_policy = base64_encode($policy);
$string_to_sign = $base64_policy;
$signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

$response = array();
$response['accessid'] = $id;
$response['host'] = $host;
$response['policy'] = $base64_policy;
$response['signature'] = $signature;
$response['expire'] = $end;
$response['callback'] = $base64_callback_body;
$response['dir'] = $dir;  // 这个参数是设置用户上传文件时指定的前缀。


$this->resultJson($response);