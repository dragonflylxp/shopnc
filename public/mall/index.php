<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
define('APP_ID','mall');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)).'/../../'.APP_ID);

require __DIR__ . '/../../shopec.php';

define('APP_SITE_URL', SHOP_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('SHOP_RESOURCE_SITE_URL',SHOP_SITE_URL.DS.'resource');
define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
	$wapurl = WAP_SITE_URL;
	
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")){
		global $config;

        if(!empty($config['wap_site_url'])){
            $url = $config['wap_site_url'];
            switch ($_GET['con']){
			case 'login':
			  $url .= '/tmpl/member/register.html?&inviterid=' . $_REQUEST['inviterid'];
			  break;
			case 'goods':
			  $url .= '/tmpl/product_detail.html?goods_id=' . $_GET['goods_id'];
			  break;
			case 'show_store':
			  $url .= '/tmpl/store.html?store_id=' . $_GET['store_id'];
			  break;
//			case 'store_snshome':
//			  $url .= '/tmpl/store.html?store_id=' . $_GET['sid'];
//			  break;	
			}
        } else {
            header("Location:" . $wapurl);
        }
	
			 header('Location:' . $url);
			exit();	
	

		
        
	}
shopec\Core::runApplication();
