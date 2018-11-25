<?php
/**
 * 分销商品统计
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
class store_dis_staControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 分销商品统计列表
     *
     */
    public function indexOp() {
        $model_goods = Model('goods');
        $model_dis_goods = Model('dis_goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['is_dis'] = 1;
        $goods_list = $model_goods->getGoodsCommonList($condition, '*', 10);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        // 统计数据
        $sta_list = $model_dis_goods->getDisStaList($goods_list);
        Tpl::output('sta_list', $sta_list);
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        self::profile_menu('dis_sta','index');
        Tpl::showpage('dis_sta.index');
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
            case 'dis_sta':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'商品统计 ',  'menu_url'=>'index.php?con=store_dis_sta&fun=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
