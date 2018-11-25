<?php

require_once ("classes/GateWaySubmit.class.php");

/* 业务代码 */
$busi_code = "SEARCH";
/* 商户号 */
$merchant_no = "000000153990021";
/* 终端号 */
$terminal_no = "20001104";
/* 商户密钥KEY */
$key = "eea5b0d874053400283e00d09f788314"; 
/* 签名算法（暂时只支持MD5）   */
$sign_type = 'SHA256';

/* 支付请求对象 */
$gatewaySubmit = new GatewaySubmit();
$gatewaySubmit->setKey($key);
$gatewaySubmit->setGateUrl("https://testpay.sicpay.com/entry.do");   //测试服务器

//业务可选参数
$order_no = "20181110035006";

//设置支付参数 
$gatewaySubmit->setParameter("busi_code", $busi_code);		        //业务代码
$gatewaySubmit->setParameter("merchant_no", $merchant_no);		    //商户号
$gatewaySubmit->setParameter("terminal_no", $terminal_no);		    //终端号
$gatewaySubmit->setParameter("order_no", $order_no);	   			//商户订单号
$gatewaySubmit->setParameter("sign_type", $sign_type);			   	//签名算法（暂时只支持SHA256）

//请求的URL
$requestUrl = $gatewaySubmit->getRequestURL();

echo $requestUrl;

?>
