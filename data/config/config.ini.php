<?php


$config = array();
$config['shop_site_url']        = 'http://manpay.sicpay.com/mall';
$config['cms_site_url']         = 'http://manpay.sicpay.com/news';
$config['microshop_site_url']   = 'http://manpay.sicpay.com/manshow';
$config['circle_site_url']      = 'http://manpay.sicpay.com/bbs';
$config['admin_site_url']       = 'http://manpay.sicpay.com/myadmin';
$config['mobile_site_url']      = 'http://manpay.sicpay.com/mob';
$config['seller_site_url']      = 'http://manpay.sicpay.com/seller';
$config['wap_site_url']         = 'http://manpay.sicpay.com/wap';
$config['chat_site_url']        = 'http://manpay.sicpay.com/chat';
$config['node_site_url']        = 'http://manpay.sicpay.com/:8090';
$config['delivery_site_url']    = 'http://manpay.sicpay.com/service';
$config['chain_site_url']       = 'http://manpay.sicpay.com/storeschain';
$config['member_site_url']      = 'http://manpay.sicpay.com/user';
$config['distribute_site_url']  = 'http://manpay.sicpay.com/market';
$config['data_site_url']      = 'http://manpay.sicpay.com';//by:511613932
$config['upload_site_url']      = 'http://manpay.sicpay.com/data/upload';
$config['resource_site_url']    = 'http://manpay.sicpay.com/data/resource';
$config['version']              = '2016112212413';
$config['setup_date']           = '2016-10-11 20:48:01';
$config['gip']                  = 0;
$config['dbdriver']             = 'mysql';
$config['tablepre']             = 'shop_';
$config['db']['master']['dbhost']       = '192.168.100.54';
$config['db']['master']['dbport']       = '3306';
$config['db']['master']['dbuser']       = 'root';
$config['db']['master']['dbpwd']        = 'mysql2017';
$config['db']['master']['dbname']       = 'gouwujie';
$config['db']['master']['dbcharset']    = 'UTF-8';
$config['db']['slave']                  = $config['db']['master'];
$config['session_expire']   = 3600;
$config['lang_type']        = 'zh';
$config['cookie_pre']       = '74BC_';
$config['cache_open'] = false;
#$config['redis']['prefix']        = 'nc_';
#$config['redis']['master']['port']        = 6379;
#$config['redis']['master']['host']        = '192.168.100.57';
#$config['redis']['master']['pconnect']    = 0;
#$config['redis']['master']['auth']	='';
#$config['redis']['slave']             = $config['redis']['master'];
$config['fullindexer']['open']      = false;
$config['fullindexer']['appname']   = 'shopec';
$config['fullindexer']['appstorename']   = 'shopecstore';
$config['debug']            = false;
$config['url_model'] = false;
$config['subdomain_suffix'] = '';
//$config['session_type'] = 'redis';
//$config['session_save_path'] = 'tcp://127.0.0.1:6779?auth='.$config['redis']['master']['auth'];
$config['node_chat'] = false;
//流量记录表数量，为1~10之间的数字，默认为3，数字设置完成后请不要轻易修改，否则可能造成流量统计功能数据错误
$config['flowstat_tablenum'] = 3;
$config['sms']['gwUrl'] = '';

$config['sms']['serialNumber'] = '';
$config['sms']['password'] = '';
$config['sms']['sessionKey'] = '';
$config['queue']['open'] = false;
$config['queue']['host'] = '127.0.0.1';
$config['queue']['port'] = 6779;
$config['oss']['open'] = false;
//$config['oss']['img_url'] = '';
//$config['oss']['api_url'] = '';
//$config['oss']['bucket'] = '';
//$config['oss']['access_id'] = '';
//$config['oss']['access_key'] = '';
$config['https'] = false;
//开店数量限制，0为不限
$config['store_limit'] = 0;
//发商品数量限制，0为不限
$config['sg_goods_limit'] = 0;
$config['sys_log'] = false;
//直播
// $config['live']['open'] = false;
// $config['live']['liveUrl'] = '';
// $config['live']['accUrl'] = '';
// $config['live']['AppName'] = '';
// $config['live']['AccessKeyId'] = '';
// $config['live']['AccessKeySecret'] = '';


$config['sms']['smsNumber'] = 3;//1为阿里大鱼 2为亿美 3为优易
//阿里大鱼短信接口
$config['dysms']['accessKeyId'] = 'LTAIEWPBWFgYBFgC';
$config['dysms']['accessKeySecret'] = 'yIPOnYmzcIRj87MPieltdhJ22Y5kYn';
//会员注册模板
$config['dysms']['registered'] = 'SMS_120115130';
//会员登录模板
$config['dysms']['login'] = 'SMS_120120109';
//会员重置密码模板
$config['dysms']['reset'] = 'SMS_120125115';
//会员虚拟兑换码模板
$config['dysms']['code'] = 'SMS_120120110';
//会员提货码模板
$config['dysms']['pickup'] = 'SMS_120130111';
//会员门店提货码模板
$config['dysms']['chaincode'] = 'SMS_120130112';
//会员提交账户安全验证
$config['dysms']['verify'] = 'SMS_120115131';
//会员绑定手机号
$config['dysms']['binding'] = 'SMS_120130110';
//短信签名
$config['dysms']['signature'] = '微慎';

//优易短信接口
$config['uesms']['gwUrl'] = 'http://www.uehyt.com/sms.aspx?action=send';
$config['uesms']['userid'] = '1289';
$config['uesms']['account'] = 'yunlifangtz';
$config['uesms']['password'] = 'yunlifangtz';
$config['uesms']['extno'] = '';

//主播佣金 511613932  赠送积分*佣金
$config['store_zb_yongjin'] = "0.8";
return $config;
