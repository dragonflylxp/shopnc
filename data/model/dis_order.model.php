<?php
/**
 * 分销订单
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

class dis_orderModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 查询分销订单
     *
     * @param
     * @return array
     */
    public function getDisOrderList($condition = array(), $page = '', $limit = '', $order = 'order_id desc', $extend = array('order_common')) {
        $condition['is_dis'] = 1;
        $result = $this->table('orders')->where($condition)->page($page)->limit($limit)->order($order)->select();
        $order_list = array();
        if (!empty($result) && is_array($result)) {
            $order_ids = array();//订单编号数组
            $dis_member_ids = array();//分销会员编号数组
            foreach ($result as $order){
                $order_id = $order['order_id'];
                $order_ids[] = $order_id;
                $order['state_desc'] = orderState($order);
                $order['payment_name'] = orderPaymentName($order['payment_code']);
                $order['add_time_text'] = date('Y-m-d H:i:s',$order['add_time']);
                $order['goods_count'] = 0;
                $order['dis_order_amount'] = 0;//分销商品金额
                $order['dis_commis_amount'] = 0;//分销商品佣金
                $order_list[$order_id] = $order;
            }
            $order_goods_list = $this->table('order_goods')->where(array('is_dis'=>1,'order_id'=> array('in',$order_ids)))->select();
            foreach ($order_goods_list as $value) {
                $order_id = $value['order_id'];
                $dis_member_ids[] = $value['dis_member_id'];
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                $value['dis_commis_amount'] = ncPriceFormat($value['goods_pay_price']*$value['dis_commis_rate']/100);
                $order_list[$order_id]['extend_order_goods'][] = $value;
                $order_list[$order_id]['goods_count'] += 1;
                $dis_order_amount = $order_list[$order_id]['dis_order_amount']+$value['goods_pay_price'];
                $order_list[$order_id]['dis_order_amount'] = ncPriceFormat($dis_order_amount);
                $dis_commis_amount = $order_list[$order_id]['dis_commis_amount']+$value['dis_commis_amount'];
                $order_list[$order_id]['dis_commis_amount'] = ncPriceFormat($dis_commis_amount);
            }
            $member_list = $this->table('member')->where(array('member_id'=> array('in', $dis_member_ids)))->field('member_name,member_id')->key('member_id')->select();
            $dis_pay_list = $this->table('dis_pay')->field('order_id,max(dis_pay_time) as dis_pay_time')->group('order_id')->where(array('log_state'=>1,'order_id'=> array('in', $order_ids)))->key('order_id')->select();
            foreach ($order_list as $k => $v) {
                foreach ($v['extend_order_goods'] as $k_goods => $v_goods) {
                    $member_id = $v_goods['dis_member_id'];
                    $order_list[$k]['extend_order_goods'][$k_goods]['dis_member_name'] = $member_list[$member_id]['member_name'];
                }
                $order_id = $v['order_id'];
                $order_list[$order_id]['dis_pay_state'] = 0;
                $order_list[$order_id]['dis_pay_state_text'] = '未结算';
                $order_list[$order_id]['dis_pay_time'] = '';
                if (!empty($dis_pay_list[$order_id])) {
                    $order_list[$order_id]['dis_pay_state'] = 1;
                    $order_list[$order_id]['dis_pay_state_text'] = '已结算';
                    $order_list[$order_id]['dis_pay_time'] = date('Y-m-d H:i:s',$dis_pay_list[$order_id]['dis_pay_time']);
                }
            }
            if (in_array('order_common',$extend)) {
                $order_common_list = $this->table('order_common')->where(array('order_id'=> array('in',$order_ids)))->select();
                foreach ($order_common_list as $order_common) {
                    $order_id = $order_common['order_id'];
                    $order_list[$order_id]['extend_order_common'] = $order_common;
                    $order_list[$order_id]['extend_order_common']['reciver_info'] = @unserialize($order_common['reciver_info']);
                    $order_list[$order_id]['extend_order_common']['invoice_info'] = @unserialize($order_common['invoice_info']);
                }
            }
        }
        return $order_list;
    }

    /**
     * 分销员订单列表
     * @return array
     */
    public function getMeberDistriOrderList($condition = array(), $field = '*', $page = 0, $order = 'order_goods.rec_id desc', $limit = 0){
        return $this->table('order_goods,orders')->join('Left')->on('order_goods.order_id = orders.order_id')->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销员订单列表+结算
     * @return array
     */
    public function getMeberDistriOrderWithPayList($condition = array(), $field = '*', $page = 0, $order = 'order_goods.rec_id desc', $limit = 0,$group = ''){
        return $this->table('order_goods,orders,dis_pay')->join('Left')->on('order_goods.order_id = orders.order_id,order_goods.rec_id = dis_pay.order_goods_id')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销佣金结算单条记录
     *
     * @param array 
     * @return array
     */
    public function getDisPayInfo($condition = array(), $order = 'log_id desc') {
        return $this->table('dis_pay')->where($condition)->order($order)->find();
    }

    /**
     * 分销佣金结算记录
     *
     * @param
     * @return array
     */
    public function getDisPayList($condition = array(), $page = '', $fields = '*', $limit = '', $order = 'log_id desc') {
        $result = $this->table('dis_pay')->field($fields)->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }
}
