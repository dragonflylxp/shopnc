<?php
/**
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','storeschain');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);


require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', CHAIN_SITE_URL);
define('CHAIN_TEMPLATES_URL', CHAIN_SITE_URL.'/templates/'.TPL_CHAIN_NAME);
define('BASE_CHAIN_TEMPLATES_URL', dirname(__FILE__).'/templates/'.TPL_CHAIN_NAME);
define('CHAIN_RESOURCE_SITE_URL',CHAIN_SITE_URL.'/resource');
define('TPL_NAME', TPL_CHAIN_NAME);
define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);
shopec\Core::runApplication();
