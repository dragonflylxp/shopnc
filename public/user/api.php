<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net/
 * @link       http://www.shopec.net/
 * @since      File available since Release v1.1
 */
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
require __DIR__ . '/../shopec.php';

$config = shopec\Core::getConfigs();
$site_url = $config['shop_site_url'];
$version = $config['version'];
$setup_date = $config['setup_date'];
$gip = $config['gip'];
$dbtype = $config['dbdriver'];
$dbcharset = $config['db']['master']['dbcharset'];
$dbserver = $config['db']['master']['dbhost'];
$dbserver_port = $config['db']['master']['dbport'];
$dbname = $config['db']['master']['dbname'];
$db_pre = $config['tablepre'];
$dbuser = $config['db']['master']['dbuser'];
$dbpasswd = $config['db']['master']['dbpwd'];
$lang_type = $config['lang_type'];
$cookie_pre = $config['cookie_pre'];
unset($config);

if ($_GET['con'] == 'sharebind'){
    if ($_GET['type'] == 'sinaweibo'){
        include BASE_DATA_PATH.DS.'api/snsapi/sinaweibo/index.php';
    }
}
