<?php
/**
 * 微信支付通知地址
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

$_GET['con'] = 'payment';
$_GET['fun'] = 'notify';
$_GET['payment_code'] = 'wxpay_jsapi';

require __DIR__ . '/../../../index.php';
