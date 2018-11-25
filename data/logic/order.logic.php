<?php
/**
 * 实物订单行为
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class orderLogic {

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额
     * @param array $cancel_condition 订单更新条件,目前只传入订单状态，防止并发下状态已经改变
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $user = '', $msg = '', $if_update_account = true, $cancel_condition = array()) {
        try {
            $model_order = Model('order');
            $model_order->beginTransaction();
            $order_id = $order_info['order_id'];

            //库存销量变更
            $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
            $data = array();
            $goods_sale = array();
            foreach ($goods_list as $goods) {
                $data[$goods['goods_id']] = $goods['goods_num'];
                $goods_sale[$goods['goods_id']] = $goods['goods_commonid'];
            }
            $result = Logic('queue')->cancelOrderUpdateStorage($data, $goods_sale);
            if (!$result['state']) {
                throw new Exception('还原库存失败');
            }
            if ($order_info['chain_id']) {
                $result = Logic('queue')->cancelOrderUpdateChainStorage($data,$order_info['chain_id']);
                if (!$result['state']) {
                    throw new Exception('还原门店库存失败');
                }
            }

            if ($if_update_account) {
                $model_pd = Model('predeposit');
                //解冻充值卡
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
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
            }

            //更新订单信息
            $update_order = array('order_state'=>ORDER_STATE_CANCEL);
            $cancel_condition['order_id'] = $order_id;
            $update = $model_order->editOrder($update_order,$cancel_condition);
            if (!$update) {
                throw new Exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = '取消了订单';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;
            $model_order->addOrderLog($data);
            $model_order->commit();

            Model('voucher')->returnVoucher($order_info['order_id']);

            return callback(true,'操作成功');

        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,'操作失败');
        }
    }

    /**
     * 收货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system,chain 分别代表买家、商家、管理员、系统、门店
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @return array
     */
    public function changeOrderStateReceive($order_info, $role, $user = '', $msg = '') {
        $model_order = Model('order');
        try {
            $order_id = $order_info['order_id'];
            $model_order->beginTransaction();

            //更新订单状态
            $update_order = array();
            $update_order['finnshed_time'] = TIMESTAMP;
            $update_order['order_state'] = ORDER_STATE_SUCCESS;
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = $msg;
            $data['log_user'] = $user;
            $data['log_orderstate'] = ORDER_STATE_SUCCESS;
            $model_order->addOrderLog($data);

            if ($order_info['buyer_id'] > 0 && $order_info['order_amount'] > 0) {
                //添加会员积分
                if (C('points_isuse') == 1){
                    Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                }
                //添加会员经验值
                Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                /**
                 * 邀请返利 20160906
                 */
                $model_member = Model('member');
                $inviter_info = $model_member->getInviterInfoByMemberId($order_info['buyer_id']);
                $rebate_amount = ceil(0.01 * $order_info['order_amount'] * C('points_rebate'));
                Model('points')->savePointsLog('rebate', array('pl_memberid' => $inviter_info['member_id'], 'pl_membername' => $order_info['buyer_name'], 'rebate_amount' => $rebate_amount), true);
                $this->addStoreMoney($order_info);
                $this->addCommision($order_info);            
            }
//          $insert = $this->addTrade($order_info);
//          if (!$insert) {
//              throw new Exception();
//          }
            $model_order->commit();
            return callback(true,'操作成功');
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,'操作失败');
        }
    }

    private function addTrade($order_info){
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
        $trade['BODY'] = '订单总额:'.strval($order_info['goods_amount']).'元\n缴纳利润率:'.strval($profit_rate).'%';
        $trade['BUYER_USERNAME'] = $order_info['buyer_name'];
        $trade['BUYER_MOBILE'] = 0;
        $store = $model_store->table('store')->field('member_name')->where(array('store_id' => $order_info['store_id']))->master(false)->find();
        $trade['SELLER_USERNAME'] = $store['member_name'];
        $partner = $model_trade->prefix('')->table('mstr_partner')->field('PARTNER')->where(array('TRADE_TYPE' => '02'))->master(false)->find();
        $trade['PARTNER'] = $partner['PARTNER'];
        $trade['TOTAL_FEE'] = $order_info['goods_amount'];
        $trade['PROFIT_RATE'] = $profit_rate;
        $trade['INPUT_CHARSET'] = 'UTF-8';
        $trade['STATE'] = '00';
        $trade['CREATE_TIME'] = $sysdate;
        $trade['LAST_UPDATE_TIME'] = $sysdate;
        $trade['SIGN'] = '';
        $trade['OPERATOR'] = '';
        $trade['TRADE_TYPE'] = '02';
        $member = $model_member->table('member')->field('r_code')->where(array('member_name' => $store['member_name']))->master(false)->find();
        $trade['REFERRAL_CODE'] = $member['r_code'];

        return $model_trade->prefix('')->table('secs_trade')->insert($trade);
    }

    /**
     * 更改运费
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param float $price 运费
     * @return array
     */
    public function changeOrderShipPrice($order_info, $role, $user = '', $price) {
        try {

            $order_id = $order_info['order_id'];
            $model_order = Model('order');

            $data = array();
            $data['shipping_fee'] = abs(floatval($price));
            $data['order_amount'] = array('exp','goods_amount+'.$data['shipping_fee']);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '修改了运费'.'( '.$price.' )';;
            $data['log_orderstate'] = $order_info['payment_code'] == 'offline' ? ORDER_STATE_PAY : ORDER_STATE_NEW;
            $model_order->addOrderLog($data);
            return callback(true,'操作成功');
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }

    /**
     * 回收站操作（放入回收站、还原、永久删除）
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $state_type 操作类型
     * @return array
     */
    public function changeOrderStateRecycle($order_info, $role, $state_type) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        //更新订单删除状态
        $state = str_replace(array('delete','drop','restore'), array(ORDER_DEL_STATE_DELETE,ORDER_DEL_STATE_DROP,ORDER_DEL_STATE_DEFAULT), $state_type);
        $update = $model_order->editOrder(array('delete_state'=>$state),array('order_id'=>$order_id));
        if (!$update) {
            return callback(false,'操作失败');
        } else {
            return callback(true,'操作成功');
        }
    }

    /**
     * 发货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderSend($order_info, $role, $user = '', $post = array()) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        try {
            $model_order->beginTransaction();
            $data = array();
            if (!empty($post['reciver_name'])) {
                $data['reciver_name'] = $post['reciver_name'];
            }
            if (!empty($post['reciver_info'])) {
                $data['reciver_info'] = $post['reciver_info'];
            }
            $data['deliver_explain'] = $post['deliver_explain'];
            $data['daddress_id'] = intval($post['daddress_id']);
            $data['shipping_express_id'] = intval($post['shipping_express_id']);
            $data['shipping_time'] = TIMESTAMP;

            $condition = array();
            $condition['order_id'] = $order_id;
            $condition['store_id'] = $order_info['store_id'];
            $update = $model_order->editOrderCommon($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }

            $data = array();
            $data['shipping_code']  = $post['shipping_code'];
            $data['order_state'] = ORDER_STATE_SEND;
            $data['delay_time'] = TIMESTAMP;
            $update = $model_order->editOrder($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }
            $model_order->commit();
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,$e->getMessage());
        }

        //更新表发货信息
        if ($post['shipping_express_id'] && $order_info['extend_order_common']['reciver_info']['dlyp']) {
            $data = array();
            $data['shipping_code'] = $post['shipping_code'];
            $data['order_sn'] = $order_info['order_sn'];
            $express_info = Model('express')->getExpressInfo(intval($post['shipping_express_id']));
            $data['express_code'] = $express_info['e_code'];
            $data['express_name'] = $express_info['e_name'];
            Model('delivery_order')->editDeliveryOrder($data,array('order_id' => $order_info['order_id']));
        }

        //添加订单日志
        $data = array();
        $data['order_id'] = $order_id;
        $data['log_role'] = 'seller';
        $data['log_user'] = $user;
        $data['log_msg'] = '发出货物(编辑信息)';
        $data['log_orderstate'] = ORDER_STATE_SEND;
        $model_order->addOrderLog($data);

        // 发送买家消息
        $param = array();
        $param['code'] = 'order_deliver_success';
        $param['member_id'] = $order_info['buyer_id'];
        $param['param'] = array(
            'order_sn' => $order_info['order_sn'],
            'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_id))
        );
        QueueClient::push('sendMemberMsg', $param);

        return callback(true,'操作成功');
    }

    /**
     * 收到货款
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderReceivePay($order_list, $role, $user = '', $post = array()) {
        $model_order = Model('order');

        try {
            $model_order->beginTransaction();
            $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$order_list[0]['pay_sn']));
            if ($pay_info) {
                if ($pay_info['api_pay_state'] == 1) {
                    return callback(true,'操作成功');
                }
                $pay_info = $model_order->getOrderPayInfo(array('pay_id'=>$pay_info['pay_id']), true,true);
                if ($pay_info['api_pay_state'] == 1) {
                    return callback(true,'操作成功');
                }
            }
            $model_pd = Model('predeposit');
            foreach($order_list as $order_info) {
                $order_id = $order_info['order_id'];
                if (!in_array($order_info['order_state'],array(ORDER_STATE_NEW))) continue;
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

                //更新订单相关扩展信息
                $result = $this->_changeOrderReceivePayExtend($order_info,$post);
                if (!$result['state']) {
                    throw new Exception($result['msg']);
                }

                //添加订单日志
                $data = array();
                $data['order_id'] = $order_id;
                $data['log_role'] = $role;
                $data['log_user'] = $user;
                $data['log_msg'] = '收到货款(外部交易号:'.$post['trade_no'].')';
                $data['log_orderstate'] = ORDER_STATE_PAY;
                $insert = $model_order->addOrderLog($data);
                if (!$insert) {
                    throw new Exception('操作失败');
                }

                //更新订单状态
                $update_order = array();
                $update_order['order_state'] = ORDER_STATE_PAY;
                $update_order['payment_time'] = ($post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP);
                $update_order['payment_code'] = $post['payment_code'];
                if ($post['trade_no'] != '') {
                    $update_order['trade_no'] = $post['trade_no'];
                }
                $condition = array();
                $condition['order_id'] = $order_info['order_id'];
                $condition['order_state'] = ORDER_STATE_NEW;
                $update = $model_order->editOrder($update_order,$condition);
                if (!$update) {
                    throw new Exception('操作失败');
                }
            }

            //更新支付单状态
            $data = array();
            $data['api_pay_state'] = 1;
            $update = $model_order->editOrderPay($data,array('pay_sn'=>$order_info['pay_sn']));
            if (!$update) {
                throw new Exception('更新支付单状态失败');
            }

            $model_order->commit();
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,$e->getMessage());
        }

        foreach($order_list as $order_info) {

            $order_id = $order_info['order_id'];
            //支付成功发送买家消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $param['member_id'] = $order_info['buyer_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_info['order_id']))
            );
            QueueClient::push('sendMemberMsg', $param);

            //非预定订单下单或预定订单全部付款完成
            if ($order_info['order_type'] != 2 || $order_info['if_send_store_msg_pay_success']) {
                //支付成功发送店铺消息
                $param = array();
                $param['code'] = 'new_order';
                $param['store_id'] = $order_info['store_id'];
                $param['param'] = array(
                        'order_sn' => $order_info['order_sn']
                );
                QueueClient::push('sendStoreMsg', $param);
                //门店自提发送提货码
                if ($order_info['order_type'] == 3) {
                    $_code = rand(100000,999999);
                    $result = $model_order->editOrder(array('chain_code'=>$_code),array('order_id'=>$order_info['order_id']));
                    if (!$result) {
                        throw new Exception('订单更新失败');
                    }
                    $param = array();
                    $param['chain_code'] = $_code;
                    $param['order_sn'] = $order_info['order_sn'];
                    $param['buyer_phone'] = $order_info['buyer_phone'];
                    QueueClient::push('sendChainCode', $param);
                }
            }
        }

        return callback(true,'操作成功');
    }

    /**
     * 更新订单相关扩展信息
     * @param unknown $order_info
     * @return unknown
     */
    private function _changeOrderReceivePayExtend($order_info, $post) {
        //预定订单收款
        if ($order_info['order_type'] == 2) {
            $result = Logic('order_book')->changeBookOrderReceivePay($order_info, $post);
        }
        if ($order_info['order_type'] == 4) {//拼团订单
            $model_pintuan = Model('p_pintuan');
            $model_pintuan->payOrder($order_info);
        }
        return callback(true);
    }

    /**
     * 买家订单详细
     */
    public function getMemberOrderInfo($order_id,$member_id) {
        $order_id = intval($order_id);
        $member_id = intval($member_id);
        if ($order_id <= 0) {
            return callback(false,'订单不存在');
        }

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $member_id;
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store','goods_common'));        //【修改】添加goods_common查询商品上下线状态  gongbo 20161208
        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            return callback(false,'订单不存在');
        }

        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
        } else {
            $refund_all = array();
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示取消订单
        $order_info['if_buyer_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示退款取消订单
        $order_info['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order_info);

        //显示投诉
        $order_info['if_complain'] = $model_order->getOrderOperateState('complain',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $order_info['close_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
        }
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            $value['refund'] = $value['refund'] ? 1 : 0;
            //处理消费者保障服务
            if (trim($value['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$value['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $value['contractlist'][] = $contract_item[$gcti_v];
                }
            }
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['zengpin_list'] = array();
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }

        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

        //卖家发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
        } else {
            $daddress_info = array();
        }
        return callback(true,'',array('order_info'=>$order_info,'refund_all'=>$refund_all,'daddress_info'=>$daddress_info));
    }
    //返佣 20160906
    private function _addCommision($member_id, $member_name, $money, $commision_level, $goods_num, $goods_id, $buyer_name, $order_sn, $order_amount, $add_time, $seller_id, $seller_name) {
        $dateline = TIMESTAMP;
        $model_mingxi = Model('mingxi');
        $model_member = Model('member');

        //会员返佣以及返佣明细
        $condition = array();
        $condition['member_id'] = $member_id;

        $model_member->where($condition)->setInc('available_predeposit', $money);
        if($money > 0){
           $this->_addCommisionPdLog($member_id, $member_name, 'add_commision', $money, $commision_level . '级返佣收入，订单号：' . $order_sn);
        }
        $mingxi_data = array(
            'user_id' => $member_id,
            'username' => $member_name,
            'shijian' => '获取佣金',
            'je' => $money,
            'commision_level' => $commision_level,
            'goods_num' => $goods_num,
            'goods_id' => $goods_id,
            'addtime' => $dateline,
            'memo' => "交易人" . $buyer_name . "，交易号" . $order_sn . ",消费金额：" . $order_amount,
            'buyer_name' => $buyer_name,
            'order_sn' => $order_sn,
            'order_time' => $add_time
        );
        $model_mingxi->insert($mingxi_data);        
        
        //商家支付佣金
        $condition = array();
        $condition['member_id'] = $seller_id;
        $model_member->where($condition)->setDec('available_predeposit', $money);
        if($money > 0){
          $this->_addCommisionPdLog($seller_id, $seller_name, 'pay_commision', -$money, $commision_level . '级返佣支出，订单号：' . $order_sn);
        }
    }

    //写日志
    private function _addCommisionPdLog($member_id, $member_name, $lg_type, $lg_av_amount, $lg_desc) {
        $data_log = array(
            'lg_member_id' => $member_id,
            'lg_member_name' => $member_name,
            'lg_type' => $lg_type,
            'lg_av_amount' => $lg_av_amount,
            'lg_freeze_amount' => 0,
            'lg_add_time' => TIMESTAMP,
            'lg_desc' => $lg_desc
        );
        $insert = Model('pd_log')->table('pd_log')->insert($data_log);
        if (!$insert) {
            throw new Exception('写日志操作失败');
        }
    }    
   /**
     * 生成返利
     * 推荐人获得推广佣金
     */
    public function addCommision($order_info) {
        $model_goods = Model('goods');
        $model_order = Model('order');
        $model_member = Model('member');
        $model_store = Model('store');

        //订单商品列表
        $order_goods_list = $model_order->getOrderGoodsList(array('order_id' => $order_info['order_id']));

        //推荐人佣金
        $first_money  = '0.00';
        $second_money = '0.00';
        $third_money  = '0.00';

        if($order_goods_list) {
            foreach ($order_goods_list as $goods) {
                $goods_info = $model_goods->getGoodsInfo(array('goods_id' => $goods['goods_id']));
                $condition = array('goods_commonid' => $goods_info['goods_commonid'], 'store_id' => $order_info['store_id']);
                $goods_common_info = $model_goods->getGoodsCommonInfo($condition);
                
                $first_money = ncPriceFormat($goods_common_info['fencheng1'] * $goods['goods_num']);
                
                $second_money = ncPriceFormat($goods_common_info['fencheng2'] * $goods['goods_num']);
               
                $third_money = ncPriceFormat($goods_common_info['fencheng3'] * $goods['goods_num']); 
                
                
                
                $store_info = $model_store->getStoreInfoByID($goods_common_info['store_id']);
                $seller_id = $store_info['member_id'];
                $seller_name = $store_info['member_name'];
                $buyer_info = $model_member->getMemberInfoByID($order_info['buyer_id']);
                
                //一级推荐人
                if ($buyer_info['inviter_id'] != 0) {
                    $first_level_referee = $model_member->getMemberInfoByID($buyer_info['inviter_id']);
                    if ($first_level_referee) {
                        $this->_addCommision($first_level_referee['member_id'], $first_level_referee['member_name'], $first_money, 1, $goods['goods_num'], $goods['goods_id'], $buyer_info['member_name'], $order_info['order_sn'], $order_info['order_amount'], $order_info['add_time'], $seller_id, $seller_name);
                        
                        //二级推荐人
                        if ($first_level_referee['inviter_id']) {
                            $second_level_referee = $model_member->getMemberInfoByID($first_level_referee['inviter_id']);
                            
                            if ($second_level_referee['member_id'] != 0) {
                                $this->_addCommision($second_level_referee['member_id'], $second_level_referee['member_name'], $second_money, 2, $goods['goods_num'], $goods['goods_id'], $buyer_info['member_name'], $order_info['order_sn'], $order_info['order_amount'], $order_info['add_time'], $seller_id, $seller_name);
                                
                                //三级推荐人
                                if($second_level_referee['inviter_id']) {
                                    $third_level_referee = $model_member->getMemberInfoByID($second_level_referee['inviter_id']); 
                                    if ($third_level_referee['member_id'] != 0) {
                                        $this->_addCommision($third_level_referee['member_id'], $third_level_referee['member_name'], $third_money, 3, $goods['goods_num'], $goods['goods_id'], $buyer_info['member_name'], $order_info['order_sn'], $order_info['order_amount'], $order_info['add_time'], $seller_id, $seller_name);
                                    }
                                }
                            }
                        }
                    }
                }
            }               
        }     
    }

   //增加商家金钱
    public function addStoreMoney($order_info) {
        $model_order = Model('order');
        $store_info = Model('store')->table('store')->where(array('store_id' => $order_info['store_id']))->find();
        $seller_info = Model('member')->table('member')->where(array('member_id' => $store_info['member_id']))->find();
        $refund = Model('refund_return')->table('refund_return')->where(array('order_id' => $order_info['order_id'], 'refund_state' => 3))->find();
        $seller_money = 0;
        if ($refund) {
            $seller_money = $order_info['order_amount'] - $refund['refund_amount'];
        } else {
            $seller_money = $order_info['order_amount'];
        }
        //取得佣金金额
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['buyer_id'] = $order_info['buyer_id'];
        $order_goods_info = $model_order->getOrderGoodsInfo($condition);

        //更新佣金比例
        if ($order_goods_info['commis_rate'] == 200) {
            $model_order->getOrderGoodsCommisRate($order_goods_info);
        }

        $fields = 'SUM(ROUND(goods_pay_price*commis_rate/100,2)) as commis_amount';
        $order_goods_info = $model_order->getOrderGoodsInfo($condition, $fields);
        $commis_rate_totals_array[] = $order_goods_info['commis_amount'];
        $commis_amount_sum = floatval(array_sum($commis_rate_totals_array)); 
        if ($commis_amount_sum > 0) {
            $seller_money = $seller_money - $commis_amount_sum;
        }

        //检测是否货到付款方式
        $is_offline = ($order_info['payment_code'] == "offline");

        if ($seller_money > 0 && $is_offline == false) {
            //变更会员预存款
            $model_pd = Model('predeposit');
            $data = array();
            $data['msg'] = "";
            if ($commis_amount_sum > 0) {
                $data['msg'] = $commis_amount_sum;
            }
            $data['member_id'] = $store_info['member_id'];
            $data['member_name'] = $store_info['member_name'];
            $data['amount'] = $seller_money;
            $data['pdr_sn'] = $order_info['order_sn'];
            $model_pd->changePd('seller_money', $data);
        }
    }
    
}
