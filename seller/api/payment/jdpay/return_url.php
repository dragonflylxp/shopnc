<?php
/**
 * 网银在线返回地址
 *
 
 */
error_reporting(7);
$_GET['con']	= 'payment';
$_GET['fun']		= 'return';
$_GET['payment_code'] = 'jdpay';

//赋值，方便后面合并使用支付宝验证方法
$_GET['out_trade_no'] = $_GET['tradeNum'];
$_GET['extra_common_param'] = 'real_order';
$_GET['trade_no'] = $_GET['tradeNum'];

require_once(dirname(__FILE__).'/../../../index.php');
?>