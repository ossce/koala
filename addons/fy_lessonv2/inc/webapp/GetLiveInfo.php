<?php
/**
 * 获取直播课程信息
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

if(!$_W['isajax']){
	$this->resultJson('Illegal Request');
}

$live_info = cache_load('fy_lesson_'.$uniacid.'_'.$lessonid.'_lessonLiveInfo');
if(empty($live_info) || $live_info['time']+15 < time()){
	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_get($this->table_lesson_parent, array('uniacid'=>$uniacid,'id'=>$lessonid,'lesson_type'=>3), array('live_info','like_goods_ids'));
	
	$live_info = json_decode($lesson['live_info'], true);
	$live_info['time'] = time();
	cache_write('fy_lesson_'.$uniacid.'_'.$lessonid.'_lessonLiveInfo', $live_info);

	/* 直播商品查询 */
	$lesson['like_goods_ids'] = json_decode($lesson['like_goods_ids'], true);
	if($lesson['like_goods_ids']){
		/* 商品二维码保存路径 */
		$dirpath = "../attachment/images/{$uniacid}/";
		$this->checkdir($dirpath);
		$dirpath .="fy_lessonv2/";
		$this->checkdir($dirpath);
		$dirpath .="lessonGoods/";
		$this->checkdir($dirpath);

		$like_goods_list = array();
		foreach($lesson['like_goods_ids'] as $v){
			$goods = pdo_get($this->table_shop_goods, array('uniacid'=>$uniacid,'id'=>$v,'status'=>1), array('id','title','cover','sell_type','price','integral'));
			if($goods){
				if($goods['price']*100 == intval($goods['price'])*100){
					$goods['price'] = intval($goods['price']);
				}elseif($goods['price']*10 == round($goods['price'],1)*10){
					$goods['price'] = round($goods['price'],1);
				}

				if($goods['sell_type'] == 1){
					$goods['show_price'] = $goods['integral'].'积分';
				}elseif($goods['sell_type'] == 2){
					$goods['show_price'] = '￥'.$goods['price'];
				}elseif($goods['sell_type'] == 3){
					$goods['show_price'] = '￥'.$goods['price'].'+'.$goods['integral'].'积分';
				}

				$imagepath = $dirpath."goods_{$v}.jpg";
				if((!file_exists($imagepath) || time() > filectime($imagepath)+7*86400)){
					$goods_url = $setting_pc['mobile_site_root']."/app/index.php?i={$uniacid}&c=entry&id={$v}&do=shopgoods&m=fy_lessonv2_plugin_shop";

					include_once IA_ROOT."/framework/library/qrcode/phpqrcode.php";
					QRcode::png($goods_url, $imagepath, 'L', '8', 2);
				}

				$like_goods_list[] = $goods;
				unset($goods);
			}
		}

		$live_goods = array_reverse($like_goods_list);
		cache_write('fy_lesson_'.$uniacid.'_'.$lessonid.'_liveGoodsList', $live_goods);
	}
}

/* 获取直播在线人数 */
if($op=='getNumber'){
	$type = intval($_GPC['type']);
	if($type==1){
		//获取直播设置在线人数
		$number = intval($live_info['virtual_num']);
		$this->resultJson(array('number'=>$number));
	}

/* 获取直播黑白名单和直播商品 */
}elseif($op=='getChat'){
	$allow_chat = true;
	$chatroom_uids = $live_info['chatroom_uids'];			
	if($live_info['chatroom_type']==0 && !in_array($uid,$chatroom_uids)){
		$allow_chat = false; /* 不在白名单之内 */
	}
	if($live_info['chatroom_type']==1 && in_array($uid,$chatroom_uids)){
		$allow_chat = false; /* 在黑名单之内 */
	}

	//直播结束时间往后60秒
	$endtime = strtotime($live_info['endtime'])+60;

	$data = array(
		'allow_chat' => $allow_chat,
		'live_goods' => $live_goods,
		'is_end'	 => time() > $endtime ? 1 : 0,
	);
	$this->resultJson($data);
}


exit();
?>