<?php
/**
 * 队列
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */


//if (empty($_SERVER['argv'][1])) exit('Access Invalid!');

define('APP_ID','crontab');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
define('TRANS_MASTER',true);
require __DIR__ . '/../shopec.php';

if (PHP_SAPI == 'cli') {
     $_GET['con'] = $_SERVER['argv'][1];
     $_GET['fun'] = empty($_SERVER['argv'][2]) ? 'index' : $_SERVER['argv'][2];
}

shopec\Core::runApplication();
