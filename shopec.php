<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net/
 * @link       http://www.shopec.net/
 * @since      File available since Release v1.1
 */

error_reporting(E_ALL & ~E_NOTICE);

define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));

define('BASE_CORE_PATH',BASE_ROOT_PATH.'/core');

define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define("BASE_UPLOAD_PATH", BASE_ROOT_PATH . "/public/data/upload");
define("BASE_RESOURCE_PATH", BASE_ROOT_PATH . "/public/data/resource");

define("BASE_UPLOAD_PATH_CPRE", BASE_ROOT_PATH . "/public");

define('IS_AJAX',((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
///**
// * 安装判断
// */
//if (!is_file(BASE_ROOT_PATH."/shop/install/lock") && is_file(BASE_ROOT_PATH."/shop/install/index.php")){
//  if (ProjectName != 'mall'){
//      @header("location: ../shop/install/index.php");
//  }else{
//      @header("location: install/index.php");
//  }
//  exit;
//}
/**
 * 初始化
 */
require BASE_CORE_PATH . '/framework/core/base.php';
shopec\Core::initializeApplication(require BASE_DATA_PATH . '/config/config.ini.php');


define('DISTRIBUTE_SITE_URL',shopec\Core::getConfig('distribute_site_url'));
//by:511613932
define('EC_CHARSET','utf-8');

define('DIR_SHOP','mall');
define('DIR_CMS','news');
define('DIR_CIRCLE','bbs');
define('DIR_MICROSHOP','manshow');
define('DIR_ADMIN','myadmin');
define('DIR_API','api');
define('DIR_MOBILE','mob');
define('DIR_WAP','wap');
define('DIR_RESOURCE','data/resource');
define('DIR_UPLOAD','data/upload');

define('ATTACH_PATH','mall');
define('ATTACH_COMMON','mall/common');
define('ATTACH_AVATAR','mall/avatar');
define('ATTACH_EDITOR','mall/editor');
define('ATTACH_MEMBERTAG','mall/membertag');
define('ATTACH_STORE','mall/store');
define('ATTACH_GOODS','mall/store/goods');
define('ATTACH_STORE_DECORATION','mall/store/decoration');
define('ATTACH_LOGIN','mall/login');
define('ATTACH_ARTICLE','mall/article');
define('ATTACH_BRAND','mall/brand');
define('ATTACH_GOODS_CLASS','mall/goods_class');
define('ATTACH_ADV','mall/adv');
define('ATTACH_ACTIVITY','mall/activity');
define('ATTACH_WATERMARK','mall/watermark');
define('ATTACH_POINTPROD','mall/pointprod');
define('ATTACH_GROUPBUY','mall/groupbuy');
define('ATTACH_SLIDE','mall/store/slide');
define('ATTACH_VOUCHER','mall/voucher');
define('ATTACH_REDPACKET','mall/redpacket');
define('ATTACH_STORE_JOININ','mall/store_joinin');
define('ATTACH_REC_POSITION','mall/rec_position');
define('ATTACH_CONTRACTICON','mall/contracticon');
define('ATTACH_CONTRACTPAY','mall/contractpay');
define('ATTACH_WAYBILL','mall/waybill');
define('ATTACH_MOBILE','mob');
define('ATTACH_CIRCLE','bbs');
define('ATTACH_CMS','news');
define('ATTACH_LIVE','live');
define('ATTACH_MALBUM','mall/user');
define('ATTACH_MICROSHOP','manshow');
define('ATTACH_DELIVERY','service');
define('ATTACH_CHAIN', 'storeschain');
define('ATTACH_DISTRIBUTE','market');
define('ATTACH_ADMIN_AVATAR','myadmin/avatar');
define('TPL_SHOP_NAME','default');
define('TPL_CIRCLE_NAME', 'default');
define('TPL_MICROSHOP_NAME', 'default');
define('TPL_CMS_NAME', 'default');
define('TPL_ADMIN_NAME', 'default');
define('TPL_DELIVERY_NAME', 'default');
define('TPL_CHAIN_NAME', 'default');
define('TPL_MEMBER_NAME', 'default');
define('TPL_DISTRIBUTE_NAME','default');
define('ADMIN_MODULES_SYSTEM', 'modules/system');
define('ADMIN_MODULES_SHOP', 'modules/shop');
define('ADMIN_MODULES_CMS', 'modules/cms');
define('ADMIN_MODULES_CIECLE', 'modules/circle');
define('ADMIN_MODULES_MICEOSHOP', 'modules/microshop');
define('ADMIN_MODULES_MOBILE', 'modules/mobile');
define('ADMIN_MODULES_DISTRIBUTE', 'modules/distribute');
define('ADMIN_MODULES_DISTRIBUTE', 'modules/distribute');
define('SELLER_SITE_URL', $config['seller_site_url']);

/*
 * 商家入驻状态定义
 */
//新申请
define('STORE_JOIN_STATE_NEW', 10);
//完成付款
define('STORE_JOIN_STATE_PAY', 11);
//初审成功
define('STORE_JOIN_STATE_VERIFY_SUCCESS', 20);
//初审失败
define('STORE_JOIN_STATE_VERIFY_FAIL', 30);
//付款审核失败
define('STORE_JOIN_STATE_PAY_FAIL', 31);
//开店成功
define('STORE_JOIN_STATE_FINAL', 40);

//默认颜色规格id(前台显示图片的规格)
define('DEFAULT_SPEC_COLOR_ID', 1);


//会员登录注册发送短信间隔（单位为秒）
define('DEFAULT_CONNECT_SMS_TIME', 60);
//会员登录注册时每个手机号发送短信个数
define('DEFAULT_CONNECT_SMS_PHONE', 5);
//会员登录注册时每个IP发送短信个数
define('DEFAULT_CONNECT_SMS_IP', 20);

/**
 * 商品图片
 */
define('GOODS_IMAGES_WIDTH', '60,240,360,1280');
define('GOODS_IMAGES_HEIGHT', '60,240,360,12800');
define('GOODS_IMAGES_EXT', '_60,_240,_360,_1280');

/**
 *  订单状态
 */
//已取消
define('ORDER_STATE_CANCEL', 0);
//已产生但未支付
define('ORDER_STATE_NEW', 10);
//已支付
define('ORDER_STATE_PAY', 20);
//已发货
define('ORDER_STATE_SEND', 30);
//已收货，交易成功
define('ORDER_STATE_SUCCESS', 40);
//订单超过N小时未支付自动取消
define('ORDER_AUTO_CANCEL_TIME', 1);
//订单超过N天未收货自动收货
define('ORDER_AUTO_RECEIVE_DAY', 8);

//预订尾款支付期限(小时)
define('BOOK_AUTO_END_TIME', 72);

//门店支付订单支付提货期限(天)
define('CHAIN_ORDER_PAYPUT_DAY', 7);
/**
 * 订单删除状态
 */
//默认未删除
define('ORDER_DEL_STATE_DEFAULT', 0);
//已删除
define('ORDER_DEL_STATE_DELETE', 1);
//彻底删除
define('ORDER_DEL_STATE_DROP', 2);

/**
 * 文章显示位置状态,1默认网站前台,2买家,3卖家,4全站
 * @var unknown
 */
define('ARTICLE_POSIT_SHOP', 1);
define('ARTICLE_POSIT_BUYER', 2);
define('ARTICLE_POSIT_SELLER', 3);
define('ARTICLE_POSIT_ALL', 4);

//兑换码过期后可退款时间，15天
define('CODE_INVALID_REFUND', 15);

//用户退出分销后N天关闭其分销中心
define('MEMBER_DISTRIBUTE_CLOSE',30);

//商品退出分销后N天清理出商品分销表
define('GOODS_DISTRIBUTE_DEL',180);

//直播聊天记录表数量 默认为5
define('LIVE_MESSAGE_TABLE_NUM',5);

//直播访客记录 
define('LIVE_MEMBER_TABLE_NUM',5);


$_GET['con'] = is_string($_GET['con']) ? strtolower($_GET['con']) : (is_string($_POST['con']) ? strtolower($_POST['con']) : null);
$_GET['fun'] = is_string($_GET['fun']) ? strtolower($_GET['fun']) : (is_string($_POST['fun']) ? strtolower($_POST['fun']) : null);

if (empty($_GET['con'])){
    require_once(BASE_CORE_PATH.'/framework/core/route.php');
    new Route($config);
}
//统一ACTION
$_GET['con'] = preg_match('/^[\w]+$/i',$_GET['con']) ? $_GET['con'] : 'index';
$_GET['fun'] = preg_match('/^[\w]+$/i',$_GET['fun']) ? $_GET['fun'] : 'index';

//对GET POST接收内容进行过滤,$ignore内的下标不被过滤
$ignore = array('article_content','pgoods_body','doc_content','content','sn_content','g_body','store_description','p_content','groupbuy_intro','remind_content','note_content','adv_pic_url','adv_word_url','adv_slide_url','appcode','mail_content', 'message_content','member_gradedesc');
if (!class_exists('Security')) require(BASE_CORE_PATH.'/framework/libraries/security.php');
$_GET = !empty($_GET) ? Security::getAddslashesForInput($_GET,$ignore) : array();
$_POST = !empty($_POST) ? Security::getAddslashesForInput($_POST,$ignore) : array();
//$_REQUEST = !empty($_REQUEST) ? Security::getAddslashesForInput($_REQUEST,$ignore) : array();
$_SERVER = !empty($_SERVER) ? Security::getAddSlashes($_SERVER) : array();
//启用ZIP压缩
if (\shopec\Core::getConfig('gip') && function_exists('ob_gzhandler') && $_GET['inajax'] != 1){
    ob_start('ob_gzhandler');
}else {
    ob_start();
}
//by:511613932
if (!get_magic_quotes_gpc()) {

	$_REQUEST = addslashes_deep($_REQUEST);
}
$_CFG = array('image_width'=>'1200','image_height'=>'1200','thumb_width'=>'240','thumb_height'=>'240');
$GLOBALS['_CFG']= $_CFG;

require_once(BASE_CORE_PATH.'/framework/libraries/queue.php');



