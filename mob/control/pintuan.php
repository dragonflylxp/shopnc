<?php
/**
 * 拼团
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
class pintuanControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function infoOp() {
        $pintuan_id = intval($_GET['pintuan_id']);
        $buyer_id = intval($_GET['buyer_id']);
        $data = array();
        
        $model_pintuan = Model('p_pintuan');
        $condition = array();
        $condition['log_id'] = $pintuan_id;
        $condition['buyer_id'] = $buyer_id;
        $condition['pay_time'] = array('gt',0);
        $_info = $model_pintuan->getOrderInfo($condition);
        if (!empty($_info) && is_array($_info)) {
            $order_id = $_info['order_id'];
            $goods_id = $_info['goods_id'];
            $_end_time = $_info['end_time']-TIMESTAMP;
            $data['pintuan_end_time'] = 0;
            
            $member_id = $this->getMemberIdIfExists();
            $data['member_id'] = $member_id;
            
            $model_order = Model('order');
            $order_goods = $model_order->getOrderGoodsInfo(array('order_id'=> $order_id));
            if ($buyer_id == $member_id) $data['order_id'] = $order_id;//当前登录会员订单编号
            
            $condition = array();
            $condition['goods_id'] = $goods_id;
            $pintuan_info = $model_pintuan->getGoodsInfo($condition);
            if ($_info['lock_state'] && $_end_time > 0) {
                $data['log_id'] = $_info['log_id'];
                $data['buyer_id'] = $_info['buyer_id'];
                $data['pintuan_end_time'] = $_end_time;
            }
            $data['goods_id'] = $_info['goods_id'];
            $data['min_num'] = $_info['min_num'];
            
            $data['goods_name'] = $order_goods['goods_name'];
            $data['pintuan_price'] = $order_goods['goods_price'];
            $data['goods_image_url'] = cthumb($order_goods['goods_image'], 240);
            
            $data['goods_price'] = $pintuan_info['goods_price'];
            
            $log_list = array();
            $buyer_type = $_info['buyer_type'];//参团类型:0为团长,其它为参团
            if ($buyer_type) {
                $_info = $model_pintuan->getOrderInfo(array('log_id'=> $buyer_type));
            }
            $log_id = $_info['log_id'];
            if (!empty($_info)) {
                $_array = array();
                $_array['buyer_id'] = $_info['buyer_id'];
                $_array['buyer_name'] = $_info['buyer_name'];
                $_array['buyer_type'] = $_info['buyer_type'];
                $_array['avatar'] = getMemberAvatarForID($_info['buyer_id']);
                $_array['time_text'] = date('Y-m-d H:i:s',$_info['pay_time']);
                $_array['type_text'] = '开团';
                $log_list[] = $_array;
            }
            $condition = array();
            $condition['buyer_type'] = $log_id;
            $condition['goods_id'] = $goods_id;
            $condition['pay_time'] = array('gt',0);
            $list = $model_pintuan->table('order_pintuan')->where($condition)->order('pay_time asc')->select();
            foreach ($list as $k => $_info) {
                $_array = array();
                $_array['buyer_id'] = $_info['buyer_id'];
                $_array['buyer_name'] = $_info['buyer_name'];
                $_array['buyer_type'] = $_info['buyer_type'];
                $_array['avatar'] = getMemberAvatarForID($_info['buyer_id']);
                $_array['time_text'] = date('Y-m-d H:i:s',$_info['pay_time']);
                $_array['type_text'] = '参团';
                $log_list[] = $_array;
            }
            $num = $_info['min_num']-count($log_list);
            $data['num'] = $num > 0 ? $num:0;
            $data['log_list'] = $log_list;
        }
        output_data(array('pintuan_info'=> $data));
    }

    public function agreementOp() {
        $doc = Model('document')->getOneByCode('pintuan_doc');
        output_data($doc);
    }

}
