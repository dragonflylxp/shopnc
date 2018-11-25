<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
require 'config.php';

if (!$enable) {
	exit('{"url":"","fileType":"","original":"","state":"没有上传权限"}');
}

$config = array(
	'savePath'   => $root_path_relative . IMAGE_DIR . '/upload/',
	'allowFiles' => array('.rar', '.doc', '.docx', '.zip', '.pdf', '.txt', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.mov', '.wmv', '.mp4', '.webm', '.flv'),
	'maxSize'    => 1024 * 50
	);
$up = new Uploader('upfile', $config);
$info = $up->getFileInfo();
$info['url'] = str_replace($root_path_relative, $root_path, $info['url']);
if ($info['url'] && (substr($info['url'], 0, 1) != '/')) {
	$info['url'] = '/' . $info['url'];
}

if ($GLOBALS['_CFG']['open_oss'] == 1) {
	if ($info['url']) {
		$dir_url = explode(IMAGE_DIR, $info['url']);

		if (count($dir_url) == 2) {
			$desc_image = IMAGE_DIR . $dir_url[1];
			$urlip = get_ip_url($GLOBALS['ecs']->get_domain(), 1);
			$url_site = $urlip . $dir_url[0];
		}
		else {
			$desc_image = IMAGE_DIR . $dir_url;
			$url_site = get_ip_url($GLOBALS['ecs']->get_domain());
		}

		$bucket_info = get_bucket_info();
		$url = $url_site . 'oss.php?act=upload';
		$Http = new Http();
		$post_data = array(
			'bucket'    => $bucket_info['bucket'],
			'keyid'     => $bucket_info['keyid'],
			'keysecret' => $bucket_info['keysecret'],
			'is_cname'  => $bucket_info['is_cname'],
			'endpoint'  => $bucket_info['outside_site'],
			'object'    => array($desc_image)
			);
		$Http->doPost($url, $post_data);
	}
}

echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['originalName'] . '","state":"' . $info['state'] . '"}';

?>
