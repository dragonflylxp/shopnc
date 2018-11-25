<?php
/**
 * 接收微信支付异步通知回调地址
 *
 * 
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

error_reporting(7);
$_GET['con']	= 'payment';
$_GET['fun']		= 'wxpay_notify';

require_once(dirname(__FILE__).'/../../../index.php');
?>