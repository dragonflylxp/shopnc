<?php
/**
 * 商品库
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
class store_lib_goodsControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 商品列表
     *
     */
    public function indexOp() {
        $model_goods = Model('lib_goods');
        $is_all = checkPlatformStoreBindingAllGoodsClass();
        $store_id = $_SESSION['store_id'];
        $condition = array();
        $gc_id = 0;
        if (!empty($_GET['gc_id']) && is_array($_GET['gc_id'])) {
            $gc_id = intval(end($_GET['gc_id']));
            if ($gc_id) $condition['gc_id'] = $gc_id;
        }
        if (!$is_all) {
            $_class = array();
            $bind_class_list = Model('store_bind_class')->getStoreBindClassList(array('store_id'=> $store_id,'state'=>array('in',array(1,2))), 99);
            if (!empty($bind_class_list) && is_array($bind_class_list)) {
                foreach ($bind_class_list as $k => $v) {
                    $_id = $v['class_3'];
                    if (empty($_id)) $_id = $v['class_2'];
                    if (empty($_id)) $_id = $v['class_1'];
                    $_class[] = $_id;
                }
            }
            $condition['gc_id'] = array('in', $_class);
            if ($gc_id && in_array($gc_id,$_class)) $condition['gc_id'] = $gc_id;
        }
        if (trim($_GET['goods_name']) != ''){
            $condition['goods_name'] = array('like','%'.$_GET['goods_name'].'%');
        }
        $list = $model_goods->getGoodsList($condition);
        Tpl::output('list',$list);
        Tpl::output('show_page', $model_goods->showpage());
        $goods_class = Model('goods_class')->getGoodsClass($store_id);
        Tpl::output('goods_class',$goods_class);
        self::profile_menu('lib_goods','index');
        Tpl::showpage('lib_goods.index');
    }
    /**
     * 编辑
     *
     */
    public function edit_goodsOp() {
        $model_goods = Model('lib_goods');
        $model_transport = Model('transport');
        $gc_id = intval($_GET['gc_id']);
        $condition = array();
        $condition['goods_id'] = intval($_GET['goods_id']);
        $condition['gc_id'] = $gc_id;
        $goods = $model_goods->getGoodsInfo($condition);
        Tpl::output('goods',$goods);
        if (chksubmit() && !empty($goods)) {
            if (!checkPlatformStoreBindingAllGoodsClass()) {
                $where = array();
                $where['class_1|class_2|class_3'] = $gc_id;
                $where['store_id'] = $_SESSION['store_id'];
                $rs = Model('store_bind_class')->getStoreBindClassInfo($where);
                if (empty($rs)) {
                    showDialog('店铺没有绑定该分类，请重新选择。','reload','error','CUR_DIALOG.close();');
                }
            }
            $goods['goods_price'] = floatval($_POST['g_price']);
            $goods['goods_marketprice'] = floatval($_POST['g_marketprice']);
            $goods['g_storage'] = intval($_POST['g_storage']);
            $goods['goods_freight'] = floatval($_POST['g_freight']);
            $transport_id = intval($_POST['transport_id']);
            $goods['transport_id'] = $transport_id;
            $transport = $model_transport->getTransportInfo(array('id'=> $transport_id));
            if (!empty($transport)) {
                $goods['transport_title'] = $transport['title'];
                if ($transport['goods_trans_type'] == 2) $goods['goods_trans_v'] = $goods['goods_trans_kg'];
                if ($transport['goods_trans_type'] == 3) $goods['goods_trans_v'] = $transport['goods_trans_v'];
            }
            unset($goods['goods_id']);
            unset($goods['goods_trans_kg']);
            unset($goods['goods_edittime']);
            $logic_goods = Logic('goods');
            $result =  $logic_goods->saveLibGoods(
                $goods,
                $_SESSION['store_id'], 
                $_SESSION['store_name'], 
                $this->store_info['store_state'], 
                $_SESSION['seller_id'], 
                $_SESSION['seller_name'],
                $_SESSION['bind_all_gc']
            );
            if ($result['state']) {
                showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
            }
        }
        $trans_list = $model_transport->getTransportList(array('store_id'=> $_SESSION['store_id']),9);
        Tpl::output('trans_list',$trans_list);
        $goods_trans_array = array(
            '1' => '按件数',
            '2' => '按重量',
            '3' => '按体积'
            );//计费规则:1是按件数2是按重量3是按体积
        Tpl::output('goods_trans_array', $goods_trans_array);
        Tpl::showpage('lib_goods.edit','null_layout');
    }

    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classOp() {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);
        if ($gc_id <= 0 || $deep <= 0 || $deep >= 4) {
            exit('0');
        }
        $model_goodsclass = Model('goods_class');
        $list = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $gc_id, $deep);
        if (empty($list)) {
            exit('0');
        }
        echo json_encode($list);
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'lib_goods':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'商品库的商品 ',  'menu_url'=>'index.php?con=store_lib_goods&fun=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
