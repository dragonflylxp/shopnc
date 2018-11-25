<?php
/**
 * 微信支付通知地址
 *
 *

 */

$_GET['con'] = 'payment';
$_GET['fun'] = 'notify';
$_GET['payment_code'] = 'wxpay_jsapi';

require __DIR__ . '/../../../index.php';
