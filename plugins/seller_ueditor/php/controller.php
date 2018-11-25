<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
date_default_timezone_set('Asia/chongqing');
error_reporting(1);
header('Content-Type: text/html; charset=utf-8');
$CONFIG = json_decode(preg_replace('/\\/\\*[\\s\\S]+?\\*\\//', '', file_get_contents('config.json')), true);
$action = $_GET['action'];

switch ($action) {
case 'config':
	$result = json_encode($CONFIG);
	break;

case 'uploadimage':
case 'uploadscrawl':
case 'uploadvideo':
case 'uploadfile':
	$result = include 'action_upload.php';
	break;

case 'listimage':
	$result = include 'action_list.php';
	break;

case 'listfile':
	$result = include 'action_list.php';
	break;

case 'catchimage':
	$result = include 'action_crawler.php';
	break;

default:
	$result = json_encode(array('state' => '请求地址出错'));
	break;
}

if (isset($_GET['callback'])) {
	if (preg_match('/^[\\w_]+$/', $_GET['callback'])) {
		echo htmlspecialchars($_GET['callback']) . '(' . $result . ')';
	}
	else {
		echo json_encode(array('state' => 'callback参数不合法'));
	}
}
else {
	echo $result;
}

?>
