<?php
/**
 * 商家订单
 *
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

class seller_orderControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }
    /**
     * 统计信息
     *
     */
    public function get_statOp() {
        $no_payment = 0;        // 待付款
        $no_delivery = 0;       // 待发货
        $no_receive = 0;        // 待收货

        $model_order = Model('order');
        // 待付款
        $no_payment = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'NewCount');
        // 待发货
        $no_delivery = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'PayCount');
        // 待收货
        $no_receive = $model_order->getOrderCountByID('store',$this->store_info['store_id'],'SendCount');

        //统计数组
        $statistics = array(
            'no_payment' => $no_payment,
            'no_delivery' => $no_delivery,
            'no_receive' => $no_receive
        );
        output_data($statistics);
    }

    public function order_listOp() {
        $model_order = Model('order');

        $order_list = $model_order->getStoreOrderList(
            $this->store_info['store_id'],
            $_POST['order_sn'],
            $_POST['buyer_name'],
            $_POST['state_type'],
            $_POST['query_start_date'],
            $_POST['query_end_date'],
            $_POST['skip_off'],
            '*',
            array('order_goods'),
            $this->page
        );

        $page_count = $model_order->gettotalpage();

        output_data(array('order_list' => array_values($order_list)), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $order_id = intval($_POST['order_id']);
        $reason = $_POST['reason'];
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }

        if ($order_info['order_type'] == 2) {
            //预定订单
            $result = Logic('order_book')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason);
        } else {
            $cancel_condition = array();
            if ($order_info['payment_code'] != 'offline') {
                $cancel_condition['order_state'] = ORDER_STATE_NEW;
            }
            $result = Logic('order')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason, true, $cancel_condition);
        }

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 修改运费
     */
    public function order_ship_priceOp() {
        $order_id = intval($_POST['order_id']);
        $shipping_fee = ncPriceFormat($_POST['shipping_fee']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        $result = Logic('order')->changeOrderShipPrice($order_info, 'seller', $this->seller_info['seller_name'], $shipping_fee);

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 发货
     */
    public function order_deliver_sendOp() {
        $order_id = intval($_POST['order_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
        if ($if_allow_send) {
            output_error('无权操作');
        }

        $result = Logic('order')->changeOrderSend($order_info, 'seller', $this->seller_info['seller_name'], $_POST);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }

    /**
     * 订单详情
     */
    public function order_infoOp() {
        $logic_order = logic('order');
        $model_order = Model('order');
        $order_id = intval($_POST['order_id']);
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods','goods_common'));    //gongbo 修改，添加goods_common 获取商品上下架状态
        //print_r($order_info);
        if (empty($order_info)) {
            output_error('订单不存在');
        }
        $model_order->getOrderExtendInfo($order_info);
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);
        //显示调整费用
        $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);
        //显示取消订单
        $order_info['if_store_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);
        //显示发货
        $order_info['if_store_send'] = $model_order->getOrderOperateState('store_send',$order_info);
        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);
        $data = array();
        $data['order_id'] = $order_info['order_id'];
        $data['order_sn'] = $order_info['order_sn'];
        $data['add_time'] = date('Y-m-d H:i:s',$order_info['add_time']);
        $data['payment_time'] = $order_info['payment_time'] ? date('Y-m-d H:i:s',$order_info['payment_time']) : '';
        $data['shipping_time'] = $order_info['extend_order_common']['shipping_time'] ? date('Y-m-d H:i:s',$order_info['extend_order_common']['shipping_time']) : '';
        $data['finnshed_time'] = $order_info['finnshed_time'] ? date('Y-m-d H:i:s',$order_info['finnshed_time']): '';
        $data['order_amount'] = ncPriceFormat($order_info['order_amount']);
        $data['shipping_fee'] = ncPriceFormat($order_info['shipping_fee']);
        $data['real_pay_amount'] = ncPriceFormat($order_info['order_amount']);
        $data['order_state'] = $order_info['order_state'];
        $data['state_desc'] = $order_info['state_desc'];
        $data['payment_name'] = $order_info['payment_name'];
        $data['shipping_code'] = $order_info['shipping_code'];
        $data['express_code'] = '';
        $data['express_name'] = '';
        $data['order_message'] = $order_info['extend_order_common']['order_message'];
        $data['reciver_phone'] = $order_info['buyer_phone'];
        $data['reciver_name'] = $order_info['extend_order_common']['reciver_name'];
        $data['reciver_addr'] = $order_info['extend_order_common']['reciver_info']['address'];
        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }
        //显示系统自动收货时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }
        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        }
        $_tmp = $order_info['extend_order_common']['invoice_info'];
        $_invonce = '';
        if (is_array($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_k => $_v) {
                $_invonce .= $_k.'：'.$_v.' ';
            }
        }
        $_tmp = $order_info['extend_order_common']['promotion_info'];
        $data['promotion'] = array();
        if(!empty($_tmp)){
            $pinfo = unserialize($_tmp);
            if (is_array($pinfo) && $pinfo){
                foreach ($pinfo as $pk => $pv){
                    if (!is_array($pv) || !is_string($pv[1]) || is_array($pv[1])) {
                        $pinfo = array();
                        break;
                    }
                    $pinfo[$pk][1] = strip_tags($pv[1]);
                }
                $data['promotion'] = $pinfo;
            }
        }
        
        $data['invoice'] = rtrim($_invonce);
        $data['if_lock'] = $order_info['if_lock'];
        $data['if_modify_price'] = $order_info['if_modify_price'];
        $data['if_store_cancel'] = $order_info['if_store_cancel'];
        $data['if_store_send'] = $order_info['if_store_send'];
        $data['if_deliver'] = $order_info['if_deliver'];
        $data['goods_list'] = array();
        $data['zengpin_list'] = array();
        foreach ($order_info['extend_order_goods'] as $_k => $_v) {
            if ($value['goods_type'] == 5) {
                $value['goods_name'] = $_v['goods_name'];
                $value['goods_num'] = $_v['goods_num'];
                $data['zengpin_list'][] = $value;
            } else {
                $value['rec_id'] = $_v['rec_id'];
                $value['goods_id'] = $_v['goods_id'];
                $value['goods_name'] = $_v['goods_name'];
                $value['goods_price'] = ncPriceFormat($_v['goods_price']);
                $value['goods_num'] = $_v['goods_num'];
                $value['goods_spec'] = $_v['goods_spec'];
                $value['image_url'] = cthumb($_v['goods_image'], 240, $_v['store_id']);
                //gongbo 20161208  增加  商品上下架状态--------------------start-------------
                $value['goods_state'] = $_v['goods_state'];
                //gongbo 20161208  增加  商品上下架状态--------------------start-------------
                $data['goods_list'][] = $value;
            }
        }
        //取得订单操作日志
        $order_log_list = $model_order->getOrderLogList(array('order_id'=>$order_id),'log_id asc');
        foreach ($order_log_list as $k => $v){
            $order_log_list[$k]['log_time'] = date('Y-m-d H:i:s',$v['log_time']);
        }
        $data['order_log_list'] = $order_log_list;
        output_data($data);
    }

    /**
     * 物流跟踪
     */
    public function order_deliverOp(){
        $order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }

        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];

        $content = Model('express')->get_express($e_code, $order_info['shipping_code']);
        if (empty($content)) {
            output_error('物流信息查询失败');
        }
        output_data(array('express_name' => $e_name, 'shipping_code' => $order_info['shipping_code'], 'deliver_info' => $content));
    }
}
