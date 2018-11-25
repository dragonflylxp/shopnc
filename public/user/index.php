<?php
/**
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','user');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', MEMBER_SITE_URL);
define('MEMBER_TEMPLATES_URL', MEMBER_SITE_URL.'/templates/'.TPL_MEMBER_NAME);
define('BASE_MEMBER_TEMPLATES_URL', dirname(__FILE__).'/templates/'.TPL_MEMBER_NAME);
define('MEMBER_RESOURCE_SITE_URL',MEMBER_SITE_URL.'/resource');
define('LOGIN_RESOURCE_SITE_URL',LOGIN_SITE_URL.'/resource');
define('LOGIN_TEMPLATES_URL', LOGIN_SITE_URL.'/templates/'.TPL_MEMBER_NAME);

shopec\Core::runApplication();
