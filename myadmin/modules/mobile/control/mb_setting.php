<?php
/**
 * 手机端设置
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class mb_settingControl extends SystemControl{
    public function __construct(){
        parent::__construct();
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
            $update_array = array();
            $update_array['signin_isuse'] = intval($_POST['signin_isuse'])==1?1:0;
            $update_array['points_signin'] = intval($_POST['points_signin'])?$_POST['points_signin']:0;
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log('编辑手机端设置',1);
                showDialog(L('nc_common_save_succ'));
            } else {
                showDialog(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::showpage('mb_setting');
    }
}
