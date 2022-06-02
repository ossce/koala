<?php 
// V 1.2
class TisApi{
	private $accessId;
	private $host = "http://api.dms.aodianyun.com:80";
	private $accessKey;
	public function __construct($accessId,$accessKey) {
		$this->accessKey = $accessKey;
		$this->accessId = $accessId;
	}
	public function Sign($str){
		$signature = $this->accessId.":".base64_encode(hash_hmac("sha1", $str, $this->accessKey, true));
		return $signature;
	}

	public function history($param,$instId){
		if(empty($instId)){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
    	$skip = 0;
		$num = 100;
    	if(!empty($param['skip'])){
			$skip=$param['skip'];
		}
		if(!empty($param['num'])){
			$num=$param['num'];
		}
		return $this->curl("/v1/tis/words/".$instId."/history?skip=".$skip."&num=".$num);
	}
	public function sendMsg($param,$instId){
		if(empty($param['content'])){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
		return $this->curl("/v1/tis/words/".$instId."/auto","POST",
			json_encode(array('content' => $param['content'])));
	}
	public function getFacesByGroup($param){
		if(empty($param['groupId'])){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
		$skip = 0;
		$num = 100;
		if(!empty($param['skip'])){
			$skip=$param['skip'];
		}
		if(!empty($param['num'])){
			$num=$param['num'];
		}
		return $this->curl("/v1/tis/faces?groupId=".$param['groupId']."&skip=".$skip."&num=".$num);
	}
	public function getFaceGroups($param,$instId){
		$skip = 0;
		$num = 100;
		if(empty($instId)){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
		if(!empty($param['skip'])){
			$skip=$param['skip'];
		}
		if(!empty($param['num'])){
			$num=$param['num'];
		}
		return $this->curl("/v1/tis/faceGroups?skip=".$skip."&num=".$num."&instId=".$instId);
	}
	public function getTisInst($param,$instId){
		if(empty($instId) ){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
		return $this->curl("/v1/tis/inst/".$instId);
	}
  	public function getPackage($param){
		if(empty($param['pkgId']) ){
			return array(
				'Flag'=>101,
				'FlagString'=>'参数错误'
			);
		}
		return $this->curl("/v1/tis/packages/".$param['pkgId']);
	}
	public function createTisRoom($param){

		$content = array(
			'dmsSecKey'	=> $param['s_key'],
			'filterKeys' => $param['filterKeys'],
			'description' => $param['description'],
		);
		if(empty($param['filterKeys'])){
			$content['auditEnable'] = 0;
			$content['filterType'] = 0;
		}else{
			$content['auditEnable'] = 1;
			$content['filterType'] = 3;
		}

		return $this->curl("/v1/tis/inst/auto", "POST", json_encode($content));
	
	}
	private function curl($path,$method="GET",$content="",$expire=3600,$timeout=60){
		$ch = curl_init();
		$expire = time() + $expire;
		if(($method == "POST" || $method == "PUT") && !empty($content)){
			$contentMd5 = md5($content);
			$str = $method."\n".$path."\n".$expire."\n".$contentMd5."\n";
		}else{
			$str = $method."\n".$path."\n".$expire."\n";
		}
		$query_url = $this->host.$path;
   
		curl_setopt($ch,CURLOPT_URL,$query_url);
		curl_setopt($ch,CURLOPT_HEADER,false);
		
		curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch,CURLOPT_FRESH_CONNECT,true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
		//	curl_setopt($ch,CURLOPT_USERAGENT,$useragent);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
	       // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		if($method == 'POST') {
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$content);
		} else if($method == 'PUT') {
			curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
            curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
		}
		$authorization = 'Authorization: tis '.$this->Sign($str);
		//print_r($authorization);exit();
		$header = array($authorization,'AD-Expire: '.$expire,'Content-Type: application/json');
		curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
		//execute post
		$response = curl_exec($ch);
		//get response code
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//close connection 
		curl_close($ch);
		//return result
		
		if($response_code == 200 || $response_code == 201 || $response_code == 204) {
			$rst = json_decode(trim($response),true);
		} else {
			$rr = json_decode(trim($response),true);
			if($rr) {
				$rst = $rr;
			} else {
				$rst = array(
					'Flag'=>$response_code,
					'FlagString'=>trim($response)
				);
			}
		}
		return $rst;
	}
}
?>