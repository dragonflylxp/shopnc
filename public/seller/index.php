<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 */
define('APP_ID','seller');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';
$wapurl = WAP_SITE_URL;
$agent = $_SERVER['HTTP_USER_AGENT'];
//  if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
// header("Location:$wapurl"); 
define('TPL_MOBILE_NAME','default');
define('APP_SITE_URL',SELLER_SITE_URL);
define('TPL_NAME',TPL_MOBILE_NAME);
define('MOBILE_RESOURCE_SITE_URL',SELLER_SITE_URL.DS.'resource');
define('MOBILE_TEMPLATES_URL',SELLER_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
require(BASE_PATH.'/framework/function/function.php');
shopec\Core::runApplication();
