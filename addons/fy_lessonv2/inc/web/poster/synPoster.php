<?php

set_time_limit(0); 

$lesson_list = pdo_fetchall("SELECT id,poster_config,poster_setting,poster_bg FROM " .tablename($this->table_lesson_parent). " WHERE poster_config != '' AND (poster_bg = '' OR poster_bg IS NULL)");

if(!empty($lesson_list)){
	foreach($lesson_list as $lesson){
		$poster_config = json_decode($lesson['poster_config'], true);
		if(!$poster_config['images']){
			continue;
		}

		$poster_setting = array();
		if(!empty($poster_config['avatar_left']) || !empty($poster_config['avatar_top'])){
			$poster_setting[] = array(
				'left' => round($poster_config['avatar_left']/2).'px',
				'top' => round($poster_config['avatar_top']/2).'px',
				'type' => 'head',
				'width' => '50px',
				'height' => '50px',
			);
		}
		
		if(!empty($poster_config['qrcode_left']) || !empty($poster_config['qrcode_top'])){
			$poster_setting[] = array(
				'left' => round($poster_config['qrcode_left']/2).'px',
				'top' => round($poster_config['qrcode_top']/2).'px',
				'type' => 'qr',
				'width' => '80px',
				'height' => '80px',
			);
		}

		if(!empty($poster_config['qrcode_left']) || !empty($poster_config['nickname_top'])){
			$poster_setting[] = array(
				'left' => round($poster_config['nickname_left']/2).'px',
				'top' => (round($poster_config['nickname_top']/2)-22).'px',
				'type' => 'nickname',
				'width' => '60px',
				'height' => '30px',
				'size' => $poster_config['nickname_fontsize'],
				'color' => $poster_config['nickname_fontcolor'],
			);
		}

		$data = array(
			'poster_bg' => $poster_config['images'],
			'poster_setting' => json_encode($poster_setting),
		);
		pdo_update($this->table_lesson_parent, $data, array('id'=>$lesson['id']));
	}
}


$posterbg = pdo_fetch("SELECT id,uniacid,posterbg,poster_config FROM " .tablename($this->table_setting). " WHERE uniacid={$uniacid} AND posterbg != '' AND poster_config != '' ");
if(!empty($posterbg)){
	$poster = json_decode($posterbg['posterbg'], true);
	$poster_config = json_decode($posterbg['poster_config'], true);
	
	$poster_setting = array();
	if($poster_config['avatar_show']){
		$poster_setting[] = array(
			'left' => $poster_config['avatar_left'] ? round($poster_config['avatar_left']/2).'px' : '11px',
			'top' => $poster_config['avatar_top'] ? round($poster_config['avatar_top']/2).'px' : '349px',
			'type' => 'head',
			'width' => '50px',
			'height' => '50px',
		);
	}

	if($poster_config['nickname_show']){
		$poster_setting[] = array(
			'left' => $poster_config['nickname_left'] ? round($poster_config['nickname_left']/2).'px' : '105px',
			'top' => $poster_config['nickname_top'] ? round($poster_config['nickname_top']/2).'px' : '342px',
			'type' => 'nickname',
			'width' => '60px',
			'height' => '30px',
			'color'	=> $poster_config['nickname_fontcolor'] ? $poster_config['nickname_fontcolor'] : '#ffffff',
			'size'	=> $poster_config['nickname_fontsize'] ? $poster_config['nickname_fontsize'] : '22px',
		);
	}
	$poster_setting[] = array(
		'left' => $poster_config['qrcode_left'] ? round($poster_config['qrcode_left']/2).'px' : '236px',
		'top' => $poster_config['qrcode_top'] ? round($poster_config['qrcode_top']/2).'px' : '370px',
		'type' => 'qr',
		'width' => '80px',
		'height' => '80px',
	);

	if(!is_array($poster)){
		$data = array(
			'uniacid' => $uniacid,
			'poster_name' => '第1张',
			'poster_bg' => $posterbg['posterbg'],
			'poster_setting' => json_encode($poster_setting),
			'addtime' => time(),
			'update_time' => time(),
		);
		pdo_insert($this->table_poster, $data);
	}else{
		foreach($poster as $k=>$v){
			$data = array(
				'uniacid' => $uniacid,
				'poster_name' => '第'.($k+1).'张',
				'poster_bg' => $v,
				'poster_setting' => json_encode($poster_setting),
				'addtime' => time(),
				'update_time' => time(),
			);
			pdo_insert($this->table_poster, $data);
		}
	}
	pdo_update($this->table_setting, array('poster_config'=>''), array('uniacid'=>$uniacid));
}

message('同步成功', $this->createWebUrl('poster'), 'success');