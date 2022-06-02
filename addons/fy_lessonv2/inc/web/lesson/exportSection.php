<?php

$token = base64_decode("ZXhwb3J0");
if($token == $_GPC['token']){
	$lesson_list = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND status=:status ORDER BY displayorder DESC,id DESC", array(':uniacid'=>$uniacid,':status'=>1));
	
	$export_list = array();
	foreach($lesson_list as $lesson){
		$section_list = pdo_fetchall("SELECT parentid,title,sectiontype,savetype,videourl,videotime,content,displayorder,is_free,test_time,password,status FROM ".tablename($this->table_lesson_son). " WHERE uniacid=:uniacid AND parentid=:parentid", array(':uniacid'=>$uniacid,':parentid'=>$lesson['id']));
		foreach($section_list as $section){
			$export_list[] = $section;
		}
	}

	if($export_list){
		foreach ($export_list as $k=>$v) {
			$arr[$k]['parentid']		= $v['parentid'];
			$arr[$k]['title_id']		= '';
			$arr[$k]['title']			= $v['title'];
			$arr[$k]['images']			= '';
			$arr[$k]['sectiontype']		= $v['sectiontype'];
			$arr[$k]['savetype']		= $v['savetype'];
			$arr[$k]['is_live']			= 0;
			$arr[$k]['videourl']		= $v['videourl'];
			$arr[$k]['videotime']		= $v['videotime'];
			$arr[$k]['content']			= $v['content'];
			$arr[$k]['displayorder']	= $v['displayorder'];
			$arr[$k]['is_free']			= $v['is_free'];
			$arr[$k]['test_time']		= $v['test_time'];
			$arr[$k]['password']		= $v['password'];
			$arr[$k]['status']			= $v['status'];
		}

		$title = array("课程id（填写课程id后，章节记录将自动导入对应的课程。否则，将导入当前课程）", "课程目录id","章节名称(必填)", "章节封面(留空)","章节类型(必填，1视频章节，2图文章节，3音频章节，4外链章节)", "存储方式(必填，0其他存储，1七牛云存储，2内嵌代码方式，3腾讯云存储，4阿里云点播，5腾讯云点播，6阿里云OSS)", "视频类型(必填，0录播，1直播)", "链接URL(点播VideoId)(必填)", "视频/音频时长(格式：'02:18)", "章节内容", "排序","试听章节  (0否，1是)","试听时间(秒)","密码访问(仅对音频和视频章节生效)","章节状态(必填，0下架，1上架，2审核中)");
		$site_common->exportCSV($arr, $title, $fileName="章节列表");
	}
}

