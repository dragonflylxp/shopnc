<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
function getfiles($path, &$files = array())
{
	if (!is_dir($path)) {
		return NULL;
	}

	$handle = opendir($path);

	while (false !== ($file = readdir($handle))) {
		if (($file != '.') && ($file != '..')) {
			$path2 = $path . '/' . $file;

			if (is_dir($path2)) {
				getfiles($path2, $files);
			}
			else if (preg_match('/\\.(gif|jpeg|jpg|png|bmp)$/i', $file)) {
				$files[] = $path2;
			}
		}
	}

	return $files;
}

require 'config.php';

if (!$enable) {
	exit('没有显示权限');
}

$paths = array($root_path_relative . IMAGE_DIR . '/upload/');
$action = htmlspecialchars($_POST['action']);

if ($action == 'get') {
	$files = array();

	foreach ($paths as $path) {
		$tmp = getfiles($path);

		if ($tmp) {
			$files = array_merge($files, $tmp);
		}
	}

	if (!count($files)) {
		return NULL;
	}

	rsort($files, SORT_STRING);
	$str = '';

	foreach ($files as $file) {
		$str .= $file . 'ue_separate_ue';
	}

	echo $str;
}

?>
