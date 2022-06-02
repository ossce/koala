<?php
/**
 * 异步上传图片
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
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
			exit(json_encode('图片数据错误，请重试'));
		}
	}else{
		exit(json_encode('图片数据错误，请重试'));
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
		exit(json_encode("远程附件上传失败，请联系管理员检查配置"));
	}
}

exit(json_encode($res));