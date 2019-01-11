<?php

// ID验证接口配置
$config_api = array();
$config_api['idverify']['version']     = '2.0.0';
$config_api['idverify']['merchant_no'] = '549034554110002';
$config_api['idverify']['tranCode']    = '100022';
$config_api['idverify']['msgType']     = '01';
$config_api['idverify']['reqUrl']      = 'https://testpay.sicpay.com/interfaceWeb/qryUser';

// 混合支付接口配置
$config_api['ghtmixpay']['version']            = '2.0.0';
$config_api['ghtmixpay']['appid']              = 'CCFHC001';
$config_api['ghtmixpay']['merchant_no']        = '549034554110002';
$config_api['ghtmixpay']['terminal_no']        = '20003935';
$config_api['ghtmixpay']['tranCode']           = '111111';
$config_api['ghtmixpay']['msgType']            = '01';
$config_api['ghtmixpay']['currency_type']      = 'HKD';
$config_api['ghtmixpay']['sett_currency_type'] = 'HKD';
$config_api['ghtmixpay']['bank_code']          = '';
$config_api['ghtmixpay']['balanceUrl']         = 'https://testpay.sicpay.com/interfaceWeb/appUserCustomer/queryIntegral';
$config_api['ghtmixpay']['preorderUrl']        = 'https://testpay.sicpay.com/interfaceWeb/appUserCustomer/preYwOrder';
$config_api['ghtmixpay']['mixpayUrl']          = 'https://testpay.sicpay.com/interfaceWeb/appUserCustomer/admixPay';
$config_api['ghtmixpay']['gateUrl']            = 'https://testpay.sicpay.com/preEntry.do';
$config_api['ghtmixpay']['sha256_key']         = '3232eb533414bb8fd43bdb262c4a9b37';
$config_api['ghtmixpay']['split_type']         = '2';

// 商户入驻接口配置
$config_api['register']['version']           = '2.0.0';
$config_api['register']['merchant_no']       = '549034554110002';
$config_api['register']['basicInfotranCode'] = '100001';
$config_api['register']['bankInfotranCode']  = '100002';
$config_api['register']['busiInfotranCode']  = '100003';
$config_api['register']['qryCardtranCode']   = '100006';
$config_api['register']['qryBusitranCode']   = '100007';
$config_api['register']['addImagetranCode']  = '100011';
$config_api['register']['msgType']           = '01';
$config_api['register']['basicInfoUrl']      = 'https://testpay.sicpay.com/interfaceWeb/basicInfo';
$config_api['register']['bankInfoUrl']       = 'https://testpay.sicpay.com/interfaceWeb/bankInfo';
$config_api['register']['busiInfoUrl']       = 'https://testpay.sicpay.com/interfaceWeb/busiInfo';
$config_api['register']['qryCardUrl']        = 'https://testpay.sicpay.com/interfaceWeb/qryCardInfo';
$config_api['register']['qryBusiUrl']        = 'https://testpay.sicpay.com/interfaceWeb/qryBusi';
$config_api['register']['addImageUrl']       = 'https://testpay.sicpay.com/interfaceWeb/addImageInfo';
