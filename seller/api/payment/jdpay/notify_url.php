<?php
/**
 * 网银在线自动对账文件
 *
 
 */
error_reporting(7);
$_GET['con']	= 'payment';
$_GET['fun']		= 'notify';
$_GET['payment_code'] = 'jdpay';

//赋值，方便后面合并使用支付宝验证方法
$_POST['out_trade_no'] = $_POST['v_oid'];
$_POST['extra_common_param'] = $_POST['remark1'];
$_POST['trade_no'] = $_POST['v_idx'];

require_once(dirname(__FILE__).'/../../../index.php');
?>