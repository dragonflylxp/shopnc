<?php
/* *
 * 高汇通支付接口配置文件
 */

/* 支付网关 */
$ghtpay_config['gate_url']       = 'https://epay.gaohuitong.com/entry.do';  
 
/* 版本号 */
$ghtpay_config['version']    = '2.0.0';

/* 机构号 */
$ghtpay_config['merchant_no']    = '549440179410002';     

/* 子商户号*/
$ghtpay_config['child_merchant_no']    = '00458107';     

/* 终端号 */
$ghtpay_config['terminal_no']    = '21570381';          

/* 商户密钥KEY */
$ghtpay_config['key']	         = 'b313cf46091c05db39439cc0e133ba6d'; 

/* 签名算法（暂时只支持MD5）   */
$ghtpay_config['sign_type']      = 'SHA256';

/* 交易完成后页面即时通知跳转的URL */
$ghtpay_config['return_url']     = MOBILE_SITE_URL.'/return_url.php';

/* 接收后台通知的URL */
$ghtpay_config['notify_url']     = MOBILE_SITE_URL.'/notify_url.php';

/* 货币代码，人民币：CNY    */
$ghtpay_config['currency_type']  = 'CNY';

/* 清算币种，人民币：CNY    */
$ghtpay_config['sett_currency_type']  = 'CNY';

/* 直连银行参数  */
$ghtpay_config['bank_code']      = '';

?>
