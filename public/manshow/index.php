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
define('APP_ID','manshow');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', MICROSHOP_SITE_URL);
define('MICROSHOP_IMG_URL',UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP);
define('TPL_NAME',TPL_MICROSHOP_NAME);
define('MICROSHOP_RESOURCE_SITE_URL',MICROSHOP_SITE_URL.'/resource');
define('MICROSHOP_TEMPLATES_URL',MICROSHOP_SITE_URL.'/templates/'.TPL_NAME);
define('MICROSHOP_BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

//define('MICROSHOP_SEO_KEYWORD',$config['seo_keywords']);
//define('MICROSHOP_SEO_DESCRIPTION',$config['seo_description']);

define('MICROSHOP_SEO_KEYWORD',C('seo_keywords'));
define('MICROSHOP_SEO_DESCRIPTION',C('seo_description'));

//微商城框架扩展
require(BASE_PATH.'/framework/function/function.php');

shopec\Core::runApplication();
