<?php
/**
 * 供货商管理
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
class store_supplierControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }

    public function sup_listOp() {
        $model_sup = Model('store_supplier');
        $condition = array();
        $condition['sup_store_id'] = $_SESSION['store_id'];
        if ($_GET['sup_name'] != '') {
            $condition['sup_name'] = array('like',"%{$_GET['sup_name']}%");
        }
        $sp_list = $model_sup->getStoreSupplierList($condition, 10, 'sup_id desc');
        Tpl::output('sp_list', $sp_list);
        Tpl::output('show_page', $model_sup->showpage());
        self::profile_menu('sup_list','sup_list');
        Tpl::showpage('store_supplier.list');
    }

    /**
     * 添加
     */
    public function sup_addOp() {
        $model_sup = Model('store_supplier');
        if($_GET['sup_id'] != '') {
            $sup_info = $model_sup->getStoreSupplierInfo(array('sup_id' => $_GET['sup_id'], 'sup_store_id' => $_SESSION['store_id']));
            if (empty($sup_info)){
                showMessage('参数错误','','html','error');
            }
            Tpl::output('sup_info',$sup_info);
        }
        Tpl::showpage('store_supplier.add','null_layout');
    }

    /**
     * 保存
     */
    public function sup_saveOp(){
        if (!chksubmit()) {
            showDialog('参数错误');
        }
        $model_sup = Model('store_supplier');
        $data = array();
        $data['sup_name'] = $_POST['sup_name'];
        $data['sup_desc'] = $_POST['sup_desc'];
        $data['sup_man'] = $_POST['sup_man'];
        $data['sup_phone'] = $_POST['sup_phone'];
        $data['sup_store_id'] = $_SESSION['store_id'];
        $data['sup_store_name'] = $_SESSION['store_name'];
        if ($_POST['sup_id']) {
            $condition = array();
            $condition['sup_id'] = intval($_POST['sup_id']);
            $condition['sup_store_id'] = $_SESSION['store_id'];
            $result = $model_sup->editStoreSupplier($data,$condition);
        } else {
            $result = $model_sup->addStoreSupplier($data);
        }
        if ($result){
            showDialog('保存成功','reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }else {
            showDialog('保存失败');
        }
    }

    /**
     * 删除
     */
    public function sup_delOp() {
        $model_sup    = Model('store_supplier');
        $sup_id       = intval($_GET['sup_id']);
        if ($sup_id > 0){
            $model_sup->delStoreSupplier(array('sup_id'=>$sup_id, 'sup_store_id' => $_SESSION['store_id']));
            showDialog(Language::get('nc_common_del_succ'),'index.php?con=store_supplier&fun=sup_list','succ');
        }else {
            showDialog(Language::get('nc_common_del_fail'));
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_type, $menu_key = '', $array = array()) {
        $menu_array     = array();
        switch ($menu_type) {
        	case 'sup_list':
        	    $menu_array = array(
        	    array('menu_key'=>'sup_list', 'menu_name'=>'供货商', 'menu_url'=>'index.php?con=store_supplier&fun=sup_list')
        	    );
        	    break;
        }
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
