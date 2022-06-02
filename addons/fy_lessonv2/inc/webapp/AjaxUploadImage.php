<?php
/**
 * Ajax异步上传图片
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

load()->func('file');


/* 以base64格式上传 */
if($_GPC['type']=='base64'){
	$path = "../attachment/images/{$uniacid}/";
	$this->checkdir($path);
	$path .= date('Y', time())."/";
	$this->checkdir($path);
	$path .= date('m', time())."/";
	$this->checkdir($path);

	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $_GPC['imageData'], $result)){
		$type = $result[2];
		$new_file = $path.random(30).".{$type}";

		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $_GPC['imageData'])))){
			$res['path'] = str_replace("../attachment/", "", $new_file);
		}else{
			$data = array(
				'code' => -1,
				'message' => '图片数据错误，请重新上传',
			);
			$this->resultJson($data);
		}
	}else{
		$data = array(
			'code' => -1,
			'message' => '图片数据错误，请重新上传',
		);
		$this->resultJson($data);
	}

/* 以图片文件上传 */
}else{
	$res = file_upload($_FILES['uploadFile']);

	/* 按比例压缩图片 */
	$imagePath = ATTACHMENT_ROOT."/".$res['path'];
	$site_common->resize($imagePath, $imagePath, "1024", "1024", "100");
}

if (!empty($_W['setting']['remote']['type'])) {
	$remotestatus = file_remote_upload($res['path']);
	if (is_error($remotestatus)) {
		$data = array(
			'code' => -1,
			'message' => '远程附件上传失败，请联系管理员检查配置',
		);
		$this->resultJson($data);
	}
}

$data = array(
	'code'	    => 0,
	'imagePath' => $res['path'],
	'message'   => '上传成功',
);
$this->resultJson($data);


?>