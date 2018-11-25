<?php
/**
 * 佣金设置
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

class store_dis_setControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 佣金设置
     *
     */
    public function indexOp() {
        $model_store_ext = Model('store_extend');
        $store_ext = $model_store_ext->getStoreExtendInfo(array('store_id'=> $_SESSION['store_id']));
        Tpl::output('dis_commis_rate', $store_ext['dis_commis_rate']);
        if (chksubmit()) {
            $dis_commis_rate = intval($_POST['dis_commis_rate']);
            if ($dis_commis_rate && $dis_commis_rate <= 30) {
                $model_store_ext->editStoreExtend(array('dis_commis_rate'=> $dis_commis_rate), array('store_id'=> $_SESSION['store_id']));
            }
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        self::profile_menu('dis_set','index');
        Tpl::showpage('dis_set.index');
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
            case 'dis_set':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'默认佣金设置',  'menu_url'=>'index.php?con=store_dis_set&fun=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
