<?php
/**
 * 虚拟订单行为
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class vr_orderLogic {

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $msg 操作备注
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $msg, $if_queue = true) {

        try {

            $model_vr_order = Model('vr_order');
            $model_vr_order->beginTransaction();

            //库存、销量变更
            //为保证数据准确，不使用队列
            $result = Logic('queue')->cancelOrderUpdateStorage(array($order_info['goods_id'] => $order_info['goods_num']));
            if (!$result['state']) {
                throw new Exception('还原库存失败');
            }

            $model_pd = Model('predeposit');

            //解冻充值卡
            $pd_amount = floatval($order_info['rcb_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changeRcb('order_cancel',$data_pd);
            }

            //解冻预存款
            $pd_amount = floatval($order_info['pd_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changePd('order_cancel',$data_pd);
            }

            //更新订单信息
            $update_order = array(
                    'order_state' => ORDER_STATE_CANCEL,
                    'pd_amount' => 0,
                    'close_time' => TIMESTAMP,
                    'close_reason' => $msg
            );
            $update = $model_vr_order->editOrder($update_order,array('order_id'=>$order_info['order_id']));
            if (!$update) {
                throw new Exception('保存失败');
            }

            $model_vr_order->commit();
            return callback(true,'更新成功');

        } catch (Exception $e) {
            $model_vr_order->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 支付订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $post
     * @return array
     */
    public function changeOrderStatePay($order_info, $role, $post) {
        try {

            $model_vr_order = Model('vr_order');
            $model_vr_order->beginTransaction();
            $_info = $model_vr_order->getOrderInfo(array('order_id'=>$order_info['order_id']), 'order_state', true,true);
            if ($_info['order_state'] == ORDER_STATE_PAY) {
                return callback(true,'更新成功');
            }
            $model_pd = Model('predeposit');
            //下单，支付被冻结的充值卡
            $rcb_amount = floatval($order_info['rcb_amount']);
            if ($rcb_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $rcb_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changeRcb('order_comb_pay',$data_pd);
            }

            //下单，支付被冻结的预存款
            $pd_amount = floatval($order_info['pd_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changePd('order_comb_pay',$data_pd);
            }

            //更新订单状态
            $update_order = array();
            $update_order['order_state'] = ORDER_STATE_PAY;
            $update_order['payment_time'] = $post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP;
            $update_order['payment_code'] = $post['payment_code'];
            $update_order['trade_no'] = $post['trade_no'];
            $update = $model_vr_order->editOrder($update_order,array('order_id'=>$order_info['order_id'],'order_state'=>ORDER_STATE_NEW));
            if (!$update) {
                throw new Exception(L('nc_common_save_fail'));
            }
            //发放兑换码
            $insert = $model_vr_order->addOrderCode($order_info);
            if (!$insert) {
                throw new Exception('兑换码生成失败');
            }

            // 支付成功发送买家消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $param['member_id'] = $order_info['buyer_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_vr_order', 'show_order', array('order_id' => $order_info['order_id']))
            );
            QueueClient::push('sendMemberMsg', $param);

            // 支付成功发送店铺消息
            $param = array();
            $param['code'] = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn']
            );
            QueueClient::push('sendStoreMsg', $param);

            //发送兑换码到手机
            $param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$order_info['buyer_id'],'buyer_phone'=>$order_info['buyer_phone'],'goods_name'=>$order_info['goods_name']);
            QueueClient::push('sendVrCode', $param);

            $model_vr_order->commit();
            return callback(true,'更新成功');

        } catch (Exception $e) {
            $model_vr_order->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 完成订单(如果全部兑换完成)
     * @param int $order_id
     * @return array
     */
    public function changeOrderStateSuccess($order_id) {
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['vr_state'] = 0;
        $condition['refund_lock'] = array('in',array(0,1));
        $condition['order_id'] = $order_id;
        $condition['vr_indate'] = array('gt',TIMESTAMP);
        $order_code_info = $model_vr_order->getOrderCodeInfo($condition,'*',true);
        if (empty($order_code_info)) {
            $update = $model_vr_order->editOrder(array('order_state' => ORDER_STATE_SUCCESS,'finnshed_time' => TIMESTAMP), array('order_id' => $order_id));
            if (!$update) {
                callback(false,'更新失败');
            }
            $order_info = $model_vr_order->getOrderInfo(array('order_id'=>$order_id));
            //添加会员积分
            if (C('points_isuse') == 1){
                Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            }

            //添加会员经验值
            Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
        }

        return callback(true,'更新成功');
    }


    /**
     * 虚拟兑换立即返现
     * by suijiaolong
     * 20161228
     * @param $vr_code_info  虚拟
     * @param $order_info
     * @return mixed
     */
    public function addTrade($vr_code_info,$order_info){
        $model_trade = Model('secs_trade');
        $model_member = Model('member');
        $model_store = Model('store');
        $sysdate = date("Y-m-d H:i:s",time());
        $sn = 'TR'.date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 1, 13), 1))), 0, 12);
        $trade = array();
        $profit_rate = 10;
        $trade['SN'] = $sn;
        $trade['SIGN_TYPE'] = '';
        $trade['NOTIFY_URL'] = '';
        $trade['ORDER_NO'] = $order_info['order_sn'];
        $trade['SUBJECT'] = '订单号:'.$order_info['order_sn'];
        $trade['BODY'] = '订单总额:'.strval($vr_code_info['pay_price']).'元\n缴纳利润率:'.strval($profit_rate).'%';
        $trade['BUYER_USERNAME'] = $order_info['buyer_name'];
        $trade['BUYER_MOBILE'] = 0;
        $store = $model_store->table('store')->field('member_name')->where(array('store_id' => $order_info['store_id']))->master(false)->find();
        $trade['SELLER_USERNAME'] = $store['member_name'];
        $partner = $model_trade->prefix('')->table('mstr_partner')->field('PARTNER')->where(array('TRADE_TYPE' => '02'))->master(false)->find();
        $trade['PARTNER'] = $partner['PARTNER'];
        $trade['TOTAL_FEE'] = $vr_code_info['pay_price'];
        $trade['PROFIT_RATE'] = $profit_rate;
        $trade['INPUT_CHARSET'] = 'UTF-8';
        $trade['STATE'] = '00';
        $trade['CREATE_TIME'] = $sysdate;
        $trade['LAST_UPDATE_TIME'] = $sysdate;
        $trade['SIGN'] = '';
        $trade['OPERATOR'] = '';
        $trade['TRADE_TYPE'] = '02';  //02表示购买商品
        $member = $model_member->table('member')->field('r_code')->where(array('member_name' => $store['member_name']))->master(false)->find();
        $trade['REFERRAL_CODE'] = $member['r_code']; //推荐人的推荐码
        $trade['IS_VR'] = intval(1); //虚拟
        return $model_trade->prefix('')->table('secs_trade')->insert($trade);
    }
}
