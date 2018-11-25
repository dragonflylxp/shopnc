<?php
/**
 * 分销订单
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

class distri_orderControl extends MemberDistributeControl{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 分销员分销订单管理
     */
    public function indexOp(){
        $this->order_listOp();
    }

    public function order_listOp(){
        $model_order = Model('dis_order');
        $condition = array('dis_member_id' => $_SESSION['member_id']);
        if(trim($_GET['goods_name'])){
            $condition['order_goods.goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        switch(intval($_GET['order_state'])){
            case 0:
                if(isset($_GET['order_state'])){
                    $condition['orders.order_state'] = 0;
                }
                break;
            case 10:
                $condition['orders.order_state'] = 10;
                $condition['orders.chain_code'] = 0;
                break;
            case 11:
                $condition['orders.order_state'] = 10;
                $condition['orders.chain_code'] = array('neq',0);
                break;
            case 20:
                $condition['orders.order_state'] = 20;
                $condition['orders.chain_code'] = 0;
                break;
            case 21:
                $condition['orders.order_state'] = 20;
                $condition['orders.chain_code'] = array('neq',0);
                break;
            case 30:
                $condition['orders.order_state'] = 30;break;
            case 40:
                $condition['orders.order_state'] = 40;break;
        }
        $condition['order_goods.is_dis'] = 1;
        $fields = '*';
        $list = $model_order->getMeberDistriOrderList($condition, $fields, 8);

        Tpl::output('order_list',$list);
        Tpl::output('show_page',$model_order->showpage(2));
        Tpl::showpage('distri_order.list');
    }

}