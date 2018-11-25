<?php
/**
 * 分销-分销设置
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
class manageControl extends SystemControl{

    function __construct()
    {
        parent::__construct();
    }

    public function indexOp() {
        $this->manageOp();
    }

    /**
     * 分销设置
     */
    public function manageOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::showpage('distribute_manage');
    }

    /**
     * 保存分销设置
     */
    public function manage_saveOp(){
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['distribute_isuse'] = intval($_POST['distribute_isuse']);
        $update_array['distribute_check'] = intval($_POST['distribute_check']);
        $old_image = '';
        if(!empty($_FILES['distribute_logo']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_DISTRIBUTE);
            $result = $upload->upfile('distribute_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['distribute_logo'] = $upload->file_name;
            $old_image = BASE_UPLOAD_PATH.DS.ATTACH_DISTRIBUTE.DS.C('distribute_logo');
        }
        $update_array['distribute_bill_limit'] = intval($_POST['distribute_bill_limit']);

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            if(!empty($old_image) && is_file($old_image)) {
                unlink($old_image);
            }
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            showMessage(Language::get('nc_common_save_fail'));
        }
    }
}