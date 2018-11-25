<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','news');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', CMS_SITE_URL);
define('TPL_NAME',TPL_CMS_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

define('CMS_RESOURCE_SITE_URL',CMS_SITE_URL.'/resource');
define('CMS_TEMPLATES_URL',CMS_SITE_URL.'/templates/'.TPL_NAME);
define('CMS_BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
define('CMS_SEO_KEYWORD',$config['seo_keywords']);
define('CMS_SEO_DESCRIPTION',$config['seo_description']);
//news框架扩展
require(BASE_PATH.'/framework/function/function.php');

shopec\Core::runApplication();
