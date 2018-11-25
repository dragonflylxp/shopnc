<?php
/**
 * 快递接口设置
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
class express_apiControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['express_api']   = $_POST['express_api'];
            $update_array['express_kuaidi100_id']   = $_POST['express_kuaidi100_id'];
            $update_array['express_kuaidi100_key']  = $_POST['express_kuaidi100_key'];
            $update_array['express_kdniao_id']   = $_POST['express_kdniao_id'];
            $update_array['express_kdniao_key']  = $_POST['express_kdniao_key'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('快递接口设置');
                showMessage(Language::get('nc_common_save_succ'));
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::showpage('express_api.edit');
    }

}
