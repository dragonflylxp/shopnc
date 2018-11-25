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
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');
class store_dis_orderControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 分销订单列表
     *
     */
    public function indexOp() {
        $model_dis_order = Model('dis_order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $order_list = $model_dis_order->getDisOrderList($condition, 10);

        Tpl::output('order_list', $order_list);
        Tpl::output('show_page', $model_dis_order->showpage());
        self::profile_menu('dis_order','index');
        Tpl::showpage('dis_order.index');
    }
    /**
     * 小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'dis_order':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销订单 ',  'menu_url'=>'index.php?con=store_dis_order&fun=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
