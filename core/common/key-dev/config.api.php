<?php

// ID验证接口配置
$config_api = array();
$config_api['idverify']['version']     = '2.0.0';
$config_api['idverify']['merchant_no'] = '549034555110003';
$config_api['idverify']['tranCode']    = '100022';
$config_api['idverify']['msgType']     = '01';
$config_api['idverify']['reqUrl']      = 'https://devpay.sicpay.com/interfaceWeb/qryUser';

// 混合支付接口配置
$config_api['ghtmixpay']['version']            = '2.0.0';
$config_api['ghtmixpay']['appid']              = 'CCFHC001';
$config_api['ghtmixpay']['merchant_no']        = '549034555110003';
$config_api['ghtmixpay']['terminal_no']        = '20002825';
$config_api['ghtmixpay']['tranCode']           = '111111';
$config_api['ghtmixpay']['msgType']            = '01';
$config_api['ghtmixpay']['currency_type']      = 'CNY';
$config_api['ghtmixpay']['sett_currency_type'] = 'CNY';
$config_api['ghtmixpay']['bank_code']          = '';
$config_api['ghtmixpay']['balanceUrl']         = 'https://devpay.sicpay.com/interfaceWeb/appUserCustomer/queryIntegral';
$config_api['ghtmixpay']['preorderUrl']        = 'https://devpay.sicpay.com/interfaceWeb/appUserCustomer/preYwOrder';
$config_api['ghtmixpay']['mixpayUrl']          = 'https://devpay.sicpay.com/interfaceWeb/appUserCustomer/admixPay';
$config_api['ghtmixpay']['gateUrl']            = 'https://devpay.sicpay.com/preEntry.do';
$config_api['ghtmixpay']['sha256_key']         = '86e95511065149220b43b618e7f6725d';
