<?php
/**
 * 微信支付通知地址
 *
 * 

 */
$_GET['con']	= 'payment';
$_GET['fun']		= 'notify';
$_GET['payment_code'] = 'wxpay';
require_once(dirname(__FILE__).'/../../../index.php');
