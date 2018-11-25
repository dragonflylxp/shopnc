<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
require 'php/config.php';

if (!$enable) {
	exit('没有编辑器使用权限');
}

$lang = ($_CFG['lang'] == 'en_us' ? 'en' : 'zh-cn');
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>ecEditor - Powered by dm299.com</title>\r\n<script type=\"text/javascript\" src=\"ueditor.config.js\"></script>\r\n<script type=\"text/javascript\" src=\"ueditor.all.js\">/*ecmoban新睿社区 --zhuo*/</script> \r\n<script type=\"text/javascript\" src=\"lang/";
echo $lang;
echo '/';
echo $lang;
echo ".js\"></script>\r\n<script type=\"text/javascript\" src=\"third-party/jquery-1.10.2.min.js\"></script>\r\n<style type=\"text/css\">\r\nbody {margin:0px; padding:0px;}\r\n</style>\r\n</head>\r\n\r\n<body>\r\n<script type=\"text/plain\" name=\"content\" id=\"container\"></script>\r\n<script type=\"text/javascript\">\r\n";
$item = htmlspecialchars($_GET['item']);
echo 'var cBox = $(\'#';
echo $item;
echo "', parent.document);\r\nvar editor = UE.getEditor('container');\r\neditor.addListener('ready', function() {\r\n  \$('#detail-table', parent.document).hide();//先显示再隐藏编辑器，兼容部分浏览在display:none时无法创建的问题\r\n  var content = cBox.val();\r\n  editor.setContent(content);\r\n});\r\n//editor.addListener(\"contentChange\", function(){setSync()});//触发同步\r\n\$(function(){\r\n  window.setInterval(\"setSync()\",1000);//自动同步\r\n})\r\nfunction setSync(){\r\n  var content = editor.getContent();\r\n  cBox.val(content);\r\n}\r\n</script>\r\n</body>\r\n</html>";

?>
