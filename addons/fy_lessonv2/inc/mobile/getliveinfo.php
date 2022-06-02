<?php
/**
 * 获取直播课程信息
 * ============================================================================
 
 * 为维护开发者的合法权益，源码仅供学习，不可用于商业行为。

 * 牛牛网提供1000+源码和教程用于学习：www.niuphp.com

 * 精品资源、二开项目、技术问题，请联系QQ：353395558
 
 * ============================================================================
 */

if(!$_W['isajax']){
	$this->resultJson('Illegal Request');
}

$uid = $_W['member']['uid'];

/* 课程直播配置参数 */
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