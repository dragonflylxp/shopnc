<?php

//支付请求示例

require_once ("classes/GateWaySubmit.class.php");

/* 业务代码 */
$busi_code = "PAY";
/* 商户号 */
$merchant_no = "000000153990021";
/* 终端号 */
$terminal_no = "20001104";
/* 商户密钥KEY */
$key = "eea5b0d874053400283e00d09f788314"; 

//商户订单号，这里用当前时间毫秒数作为订单号，商户应当保持订单号在商户系统的唯一性
date_default_timezone_set('UTC');
$order_no = date("YmdHis");

/* 商品金额,以元为单位   */
$amount = '0.01';

/* 交易完成后页面即时通知跳转的URL  */
$return_url = 'http://localhost/returnUrl.php';

/* 接收后台通知的URL */
$notify_url = 'http://localhost/notifyUrl.php';

/* 货币代码，人民币：CNY    */
$currency_type = 'CNY';

/*创建订单的客户端IP（消费者电脑公网IP，用于防钓鱼支付）   */
//$client_ip = $_SERVER['REMOTE_ADDR'];
$client_ip = '';

/* 签名算法（暂时只支持MD5）   */
$sign_type = 'SHA256';

//直连银行参数
//$bank_code = "ICBC";  //直连招商银行参数值
$bank_code = "";

//订单备注，该信息使用64位编码提交服务器，并将在支付完成后随支付结果原样返回
$memo = "测试备注信息";
$base64_memo = base64_encode($memo);

/* 支付请求对象 */
$gatewaySubmit = new GatewaySubmit();
$gatewaySubmit->setKey($key);
$gatewaySubmit->setGateUrl("https://testpay.sicpay.com/entry.do");   //测试服务器

//设置支付参数 
$gatewaySubmit->setParameter("busi_code", $busi_code);		        //业务代码
$gatewaySubmit->setParameter("merchant_no", $merchant_no);		    //商户号
$gatewaySubmit->setParameter("terminal_no", $terminal_no);		    //终端号
$gatewaySubmit->setParameter("order_no", $order_no);	   			//商户订单号
$gatewaySubmit->setParameter("amount", $amount);			   		//商品金额,以元为单位
$gatewaySubmit->setParameter("return_url", $return_url);		   	//交易完成后页面即时通知跳转的URL
$gatewaySubmit->setParameter("notify_url", $notify_url);		  	//接收后台通知的URL
$gatewaySubmit->setParameter("currency_type", $currency_type);	   	//货币种类
$gatewaySubmit->setParameter("client_ip",$client_ip); 	//创建订单的客户端IP（消费者电脑公网IP，用于防钓鱼支付）
$gatewaySubmit->setParameter("sign_type", $sign_type);			   	//签名算法（暂时只支持SHA256）

//业务可选参数
$gatewaySubmit->setParameter("bank_code", $bank_code);	        	//直连银行参数，例子是直接转跳到招商银行时的参数
$gatewaySubmit->setParameter("base64_memo", $base64_memo);		   	//订单备注的BASE64编码

//请求的URL
$requestUrl = $gatewaySubmit->getRequestURL();

//获取调试信息
/*
$debugMsg = $gatewaySubmit->getDebugMsg();
echo "<br/>" . $requestUrl . "<br/>";
echo "<br/>" . $debugMsg . "<br/>";
*/

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>支付支付请求例子</title>
</head>
<body><br/><br/>
<a href="<?php echo $requestUrl ?>" target="_blank">支付</a>
</body>
</html>

