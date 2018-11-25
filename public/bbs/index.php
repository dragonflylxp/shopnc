<?php
/**
 * 圈子板块初始化文件
 *
 * 圈子板块初始化文件，引用框架初始化文件
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','bbs');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', CIRCLE_SITE_URL);
define('TPL_NAME', TPL_CIRCLE_NAME);
define('CIRCLE_TEMPLATES_URL', CIRCLE_SITE_URL.'/templates/'.TPL_NAME);
define('CIRCLE_RESOURCE_SITE_URL',CIRCLE_SITE_URL.'/resource');
require(BASE_PATH.'/framework/function/function.php');

shopec\Core::runApplication();
