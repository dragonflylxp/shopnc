<?php
/**
 * 分销板块初始化文件
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','market');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', DISTRIBUTE_SITE_URL);
define('TPL_NAME',TPL_DISTRIBUTE_NAME);
define('DISTRIBUTE_RESOURCE_SITE_URL',DISTRIBUTE_SITE_URL.DS.'resource');
define('DISTRIBUTE_TEMPLATES_URL',DISTRIBUTE_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

shopec\Core::runApplication();