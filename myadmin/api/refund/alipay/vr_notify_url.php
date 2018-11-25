<?php
/**
 * 支付宝服务器异步通知页面
 *
 * 
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
$_GET['con']	= 'notify_refund';
$_GET['fun']		= 'alipay';
$_GET['refund']		= 'vr';//虚拟订单退款
require_once(dirname(__FILE__).'/../../../index.php');
?>