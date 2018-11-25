<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

define('BASE_PATH',str_replace('\\','/',dirname(dirname(dirname(__FILE__)))).'/../../myadmin');
define('MODULES_BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../../../myadmin/modules/circle');
require __DIR__ . '/../../../../shopec.php';

define('APP_SITE_URL', ADMIN_SITE_URL.'/modules/circle');
define('TPL_NAME',TPL_ADMIN_NAME);
define('ADMIN_TEMPLATES_URL',ADMIN_SITE_URL.'/templates/'.TPL_NAME);
define('ADMIN_RESOURCE_URL',ADMIN_SITE_URL.'/resource');
define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',MODULES_BASE_PATH.'/templates/'.TPL_NAME);
define('MODULE_NAME', 'circle');

shopec\Core::runApplication(MODULES_BASE_PATH);
