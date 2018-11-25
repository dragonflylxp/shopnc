<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
function getRemoteImage($uri, $config)
{
	global $root_path_relative;
	global $root_path;
	set_time_limit(0);
	$imgUrls = explode('ue_separate_ue', $uri);
	$tmpNames = array();

	foreach ($imgUrls as $imgUrl) {
		if (strpos($imgUrl, 'http') !== 0) {
			array_push($tmpNames, 'error');
			continue;
		}

		$heads = get_headers($imgUrl);
		if (!(stristr($heads[0], '200') && stristr($heads[0], 'OK'))) {
			array_push($tmpNames, 'error');
			continue;
		}

		$fileType = strtolower(strrchr($imgUrl, '.'));
		if (!in_array($fileType, $config['allowFiles']) || stristr($heads['Content-Type'], 'image')) {
			array_push($tmpNames, 'error');
			continue;
		}

		ob_start();
		$context = stream_context_create(array(
	'http' => array('follow_location' => false)
	));
		readfile($imgUrl, false, $context);
		$img = ob_get_contents();
		ob_end_clean();
		$uriSize = strlen($img);
		$allowSize = 1024 * $config['maxSize'];

		if ($allowSize < $uriSize) {
			array_push($tmpNames, 'error');
			continue;
		}

		$savePath = $config['savePath'];

		if (!file_exists($savePath)) {
			mkdir($savePath, 511);
		}

		$tmpName = $savePath . rand(1, 10000) . time() . strrchr($imgUrl, '.');

		try {
			$fp2 = @fopen($tmpName, 'a');
			fwrite($fp2, $img);
			fclose($fp2);
			array_push($tmpNames, $tmpName);
		}
		catch (Exception $e) {
			array_push($tmpNames, 'error');
		}
	}

	if (is_array($tmpNames)) {
		foreach ($tmpNames as $key => $vo) {
			$tmpNames[$key] = str_replace($root_path_relative, $root_path, $vo);
		}
	}

	echo '{\'url\':\'' . implode('ue_separate_ue', $tmpNames) . '\',\'tip\':\'远程图片抓取成功！\',\'srcUrl\':\'' . $uri . '\'}';
}

require 'config.php';

if (!$enable) {
	exit('{\'url\':\'\',\'tip\':\'没有抓取权限\',\'srcUrl\':\'\'}');
}

$config = array(
	'savePath'   => $root_path_relative . IMAGE_DIR . '/upload/',
	'allowFiles' => array('.gif', '.png', '.jpg', '.jpeg', '.bmp'),
	'maxSize'    => 3000
	);
$uri = htmlspecialchars($_POST['upfile']);
$uri = str_replace('&amp;', '&', $uri);
getRemoteImage($uri, $config);

?>
