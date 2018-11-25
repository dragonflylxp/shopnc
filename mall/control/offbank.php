<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
if(preg_match("/mobile|nokia|iphone|ipad|android|samsung|htc|blackberry/i", $_SERVER['HTTP_USER_AGENT']) && empty($_GET['pc'])) {
    @header('Location: '.C('wap_site_url'));exit;
}
class offbankControl extends BaseHomeControl{
	public function indexOp(){
		$logic_payment = Logic('payment');
		$order_id = $_REQUEST['order_id']?$_REQUEST['order_id']:0;
		$url = $_REQUEST['url']?$_REQUEST['url']:'';
		$result = $logic_payment->getPaymentInfo('offbank');
		if(!$result['state']) {
			showMessage($result['msg'], "index.php", 'html', 'error');
		}
		$payment_info = $result['data'];
		preg_match("/con=member_vr_order/",$url,$arr);
		if($arr){
			$order_info = Model()->table('vr_order')->where(array('order_id'=>$order_id))->find();
		}else{
			$order_info = Model()->table('orders')->where(array('order_id'=>$order_id))->find();
		}
		Tpl::output('order_info', $order_info);
		Tpl::output('payment_info', $payment_info);
		Tpl::output('url', $url);
		Tpl::output('order_id', $order_id);
		Tpl::showpage('offbank',"null_layout");
	}
	
}
