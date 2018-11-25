<?php
/**
 * 网站设置
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
class operationControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->settingOp();
    }

    /**
     * 基本设置
     */
    public function settingOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(

            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['promotion_allow'] = $_POST['promotion_allow'];
                $update_array['groupbuy_allow'] = $_POST['groupbuy_allow'];
                $update_array['pointshop_isuse'] = $_POST['pointshop_isuse'];
                $update_array['voucher_allow'] = $_POST['voucher_allow'];
                $update_array['pointprod_isuse'] = $_POST['pointprod_isuse'];
                $update_array['redpacket_allow'] = $_POST['redpacket_allow'];
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    $this->log(L('nc_edit,nc_operation,nc_operation_set'),1);
                    showMessage(L('nc_common_save_succ'));
                }else {
                    showMessage(L('nc_common_save_fail'));
                }
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::showpage('operation.setting');
    }
}
