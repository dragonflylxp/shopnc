<?php
/**
 * 分销会员结算管理
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

class distri_billControl extends MemberDistributeControl{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * 分销员分销订单管理
     */
    public function indexOp(){
        $this->bill_listOp();
    }

    public function bill_listOp(){
        $model_bill = Model('dis_bill');
        $condition = array('dis_member_id' => $_SESSION['member_id']);
        if(trim($_GET['goods_name'])){
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if(is_numeric($_GET['bill_state']) && intval($_GET['bill_state']) >= 0){
            $condition['log_state'] = intval($_GET['bill_state']);
        }
        $fields = '*';
        $list = $model_bill->getDistriBillList($condition, $fields, 15);

        Tpl::output('bill_list',$list);
        Tpl::output('show_page',$model_bill->showpage(2));
        Tpl::showpage('distri_bill.list');
    }

}