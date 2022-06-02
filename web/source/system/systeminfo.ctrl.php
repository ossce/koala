<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('system');

$dos = array('display', 'get_attach_size');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	$info = array(
		'os' => php_uname(),
		'php' => PHP_VERSION,
		'ims_version' => IMS_VERSION,
		'ims_release_date' => IMS_RELEASE_DATE,
		'sapi' => $_SERVER['SERVER_SOFTWARE'] ? $_SERVER['SERVER_SOFTWARE'] : php_sapi_name(),
	);
	$info['family'] = '您的产品是';
	switch(IMS_FAMILY) {
		case 'v':
			$info['family'] .= '开源版, 没有购买商业授权, 不能用于商业用途';
			break;
		case 's':
			$info['family'] .= '授权版';
			break;
		case 'x':
			$info['family'] .= '商业版';
			break;
		default:
			$info['family'] .= '单版';
	}

		$size = 0;
	$size = @ini_get('upload_max_filesize');
	if ($size) {
		$size = bytecount($size);
	}
	if ($size > 0) {
		$ts = @ini_get('post_max_size');
		if ($ts) {
			$ts = bytecount($size);
		}
		if ($ts > 0) {
			$size = min($size, $ts);
		}
		$ts = @ini_get('memory_limit');
		if ($ts) {
			$ts = bytecount($size);
		}
		if ($ts > 0) {
			$size = min($size, $ts);
		}
	}
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
	$info['limit'] = $size;

		$sql = 'SELECT VERSION();';
	$info['mysql']['version'] = pdo_fetchcolumn($sql);

		$tables = pdo_fetchall("SHOW TABLE STATUS LIKE '" . $_W['config']['db']['tablepre'] . "%'");
	$size = 0;
	foreach ($tables as &$table) {
		$size += $table['Data_length'] + $table['Index_length'];
	}
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
		$info['mysql']['size'] = $size;
		$info['attach']['url'] = empty($_W['setting']['remote_complete_info']['type']) ? $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' : $_W['setting']['remote_complete_info'][attachment_get_type($_W['setting']['remote_complete_info']['type'])]['url'];

	$info['company'] = '不要在意细节，用就完了';
	if ($_W['isajax']) {
		iajax(0, $info);
	}
	template('system/systeminfo');
}

if ('get_attach_size' == $do) {
		$path = IA_ROOT . '/' . $_W['config']['upload']['attachdir'];
	$size = dir_size($path);
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
	iajax(0, array('attach_size' => $size));
}
