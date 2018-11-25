<?php
/**
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','service');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', DELIVERY_SITE_URL);
define('DELIVERY_TEMPLATES_URL', DELIVERY_SITE_URL.'/templates/'.TPL_DELIVERY_NAME);
define('BASE_DELIVERY_TEMPLATES_URL', dirname(__FILE__).'/templates/'.TPL_DELIVERY_NAME);
define('DELIVERY_RESOURCE_SITE_URL',DELIVERY_SITE_URL.'/resource');

shopec\Core::runApplication();
