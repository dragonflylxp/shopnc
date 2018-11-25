<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
require 'config.php';

if (!$enable) {
	exit('{\'url\':\'\',\'title\':\'\',\'original\':\'\',\'state\':\'没有上传权限\'}');
}

$config = array(
	'savePath'   => $root_path_relative . IMAGE_DIR . '/upload/',
	'maxSize'    => 3000,
	'allowFiles' => array('.gif', '.png', '.jpg', '.jpeg', '.bmp')
	);
$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);

if (isset($_GET['fetch'])) {
	header('Content-Type: text/javascript');
	echo 'updateSavePath(["upload"]);';
	return NULL;
}

$up = new Uploader('upfile', $config);
$info = $up->getFileInfo();
$info['url'] = str_replace($root_path_relative, $root_path, $info['url']);
if ($info['url'] && (substr($info['url'], 0, 1) != '/')) {
	$info['url'] = '/' . $info['url'];
}



echo '{\'url\':\'' . $info['url'] . '\',\'title\':\'' . $title . '\',\'original\':\'' . $info['originalName'] . '\',\'state\':\'' . $info['state'] . '\'}';

?>
