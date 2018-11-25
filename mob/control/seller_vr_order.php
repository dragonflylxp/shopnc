<?php
/**
 * 商家虚拟订单
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

class seller_vr_orderControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 虚拟订单列表
     *
     */
    public function order_listOp() {
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (preg_match('/^\d{10,20}$/',$_POST['order_sn'])) {
            $condition['order_sn'] = $_POST['order_sn'];
        }
        if ($_POST['buyer_name'] != '') {
            $condition['buyer_name'] = $_POST['buyer_name'];
        }
        $allow_state_array = array('state_new','state_pay','state_success','state_cancel');
        if (in_array($_POST['state_type'],$allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_POST['state_type']);
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_POST['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_POST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_POST['skip_off'] == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }
        $order_list = $model_vr_order->getOrderList($condition, $this->page, '*', 'order_id desc');
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_list as $key => $order) {
            //处理消费者保障服务
            if (trim($order['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$order['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $order['contractlist'][] = $contract_item[$gcti_v];
                }
            }
            $order_list[$key] = $order;
            //显示取消订单
            $order_list[$key]['if_cancel'] = $model_vr_order->getOrderOperateState('store_cancel',$order);
            $order_list[$key]['goods_image_url'] = cthumb($order['goods_image'], 240, $order['store_id']);
            $order_list[$key]['add_time_text'] = date('Y-m-d',$order['add_time']);
        }
        $page_count = $model_vr_order->gettotalpage();

        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 取消虚拟订单
     */
    public function order_cancelOp() {
        $order_id = intval($_POST['order_id']);
        $reason = $_POST['reason'];
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_vr_order->getOrderInfo($condition);

        $if_allow = $model_vr_order->getOrderOperateState('store_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该虚拟订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }
        $logic_vr_order = Logic('vr_order');
        $result = $logic_vr_order->changeOrderStateCancel($order_info,'seller', $reason);

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }

    /**
     * 兑换码消费
     */
    public function exchangeOp() {
        if (!preg_match('/^[a-zA-Z0-9]{15,18}$/',$_POST['vr_code'])) {
            output_error('兑换码格式错误，请重新输入');
        }
        $model_vr_order = Model('vr_order');
        $vr_code_info = $model_vr_order->getOrderCodeInfo(array('vr_code' => $_POST['vr_code']));
        if (empty($vr_code_info) || $vr_code_info['store_id'] != $this->store_info['store_id']) {
            output_error('该兑换码不存在');
        }
        if ($vr_code_info['vr_state'] == '1') {
            output_error('该兑换码已被使用');
        }
        if ($vr_code_info['vr_indate'] < TIMESTAMP) {
            return array('该兑换码已过期，使用截止日期为： '.date('Y-m-d H:i:s',$vr_code_info['vr_indate']));
        }
        if ($vr_code_info['refund_lock'] > 0) {//退款锁定状态:0为正常,1为锁定(待审核),2为同意
            output_error('该兑换码已申请退款，不能使用');
        }

        //更新兑换码状态
        $update = array();
        $update['vr_state'] = 1;
        $update['vr_usetime'] = TIMESTAMP;
        $update = $model_vr_order->editOrderCode($update, array('vr_code' => $_POST['vr_code']));

        //如果全部兑换完成，更新订单状态
        Logic('vr_order')->changeOrderStateSuccess($vr_code_info['order_id']);

        if ($update) {
            //取得返回信息
            $order_info = $model_vr_order->getOrderInfo(array('order_id'=>$vr_code_info['order_id']));
            if ($order_info['use_state'] == '0') {
                $model_vr_order->editOrder(array('use_state' => 1), array('order_id' => $vr_code_info['order_id']));
            }
            $order_info['img_60'] = thumb($order_info,60);
            $order_info['img_240'] = thumb($order_info,240);
            output_data($order_info);
        }
    }

    /**
     * 订单详情
     */
    public function order_infoOp() {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_vr_order->getOrderInfo($condition);
        if (empty($order_info)) {
            output_error('订单不存在');
        }
        $order_list = array();
        $order_list[$order_id] = $order_info;

        //显示取消订单
        $order_info['if_cancel'] = $model_vr_order->getOrderOperateState('store_cancel',$order_info);
        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }
        $order_info['goods_image_url'] = cthumb($order_info['goods_image'], 240, $order_info['store_id']);

        $order_info['vr_indate'] = $order_info['vr_indate'] ? date('Y-m-d',$order_info['vr_indate']) : '';
        $order_info['add_time'] = date('Y-m-d',$order_info['add_time']);
        $order_info['payment_time'] = $order_info['payment_time'] ? date('Y-m-d',$order_info['payment_time']) : '';
        $order_info['finnshed_time'] = $order_info['finnshed_time'] ? date('Y-m-d',$order_info['finnshed_time']) : '';
        //取兑换码列表
        $vr_code_list = $model_vr_order->getOrderCodeList(array('order_id' => $order_info['order_id']));
        foreach ($vr_code_list as $k => $v){
            unset($vr_code_list[$k]['qr_pic_url']);
            if ($v['vr_state'] == '0' && $v['vr_indate'] < TIMESTAMP) {
                $vr_code_list[$k]['vr_code'] = encryptShow($v['vr_code'],7,12);
            }
        }
        $order_info['code_list'] = $vr_code_list ? $vr_code_list : array();

        output_data($order_info);   
    }
}
