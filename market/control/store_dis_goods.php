<?php
/**
 * 分销商品
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
class store_dis_goodsControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 分销商品列表
     *
     */
    public function indexOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['is_dis'] = 1;
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGoodsCommonList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        self::profile_menu('dis_goods','index');
        Tpl::showpage('dis_goods.index');
    }

    /**
     * 普通商品列表
     **/
    public function goods_listOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['is_dis'] = 0;
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsCommonList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        self::profile_menu('goods_list','goods_list');
        Tpl::showpage('dis_goods_list');
    }
    /**
     * 添加分销商品
     *
     */
    public function add_goodsOp() {
        $model_store_ext = Model('store_extend');
        $store_ext = $model_store_ext->getStoreExtendInfo(array('store_id'=> $_SESSION['store_id']));
        $model_dis_goods = Model('dis_goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_commonid'] = intval($_GET['id']);
        $state = $model_dis_goods->addGoods($condition, $store_ext['dis_commis_rate']);
        if ($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }
    /**
     * 编辑分销商品佣金比例
     *
     */
    public function edit_goodsOp() {
        $dis_commis_rate = intval($_GET['num']);
        if ($dis_commis_rate && $dis_commis_rate <= 30) {
            $model_dis_goods = Model('dis_goods');
            $condition = array();
            $condition['store_id'] = $_SESSION['store_id'];
            $condition['goods_commonid'] = intval($_GET['id']);
            $state = $model_dis_goods->editGoods($condition, array('dis_commis_rate'=> $dis_commis_rate));
        }
        if ($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }
    /**
     * 删除分销
     *
     */
    public function del_goodsOp() {
        $model_dis_goods = Model('dis_goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_commonid'] = intval($_GET['id']);
        $state = $model_dis_goods->delGoods($condition);
        if ($state) {
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_op_fail'), 'reload', 'error');
        }
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
            case 'dis_goods':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销商品 ',  'menu_url'=>'index.php?con=store_dis_goods&fun=index')
                );
                break;
            case 'goods_list':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销商品 ',  'menu_url'=>'index.php?con=store_dis_goods&fun=index'),
                    array('menu_key'=>'goods_list','menu_name'=>'添加商品 ',  'menu_url'=>'index.php?con=store_dis_goods&fun=goods_list')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
