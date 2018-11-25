<?php
/**
 * 支付宝返回地址
 *
 * 
 */
error_reporting(7);
$_GET['con']	= 'payment';
$_GET['fun']		= 'return';
$_GET['payment_code'] = 'alipay';
require_once(dirname(__FILE__).'/../../../index.php');
?>