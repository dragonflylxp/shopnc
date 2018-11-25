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
class mb_video_settingControl extends SystemControl{
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
            if (!empty($_FILES['video_logo']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_COMMON);
                $result = $upload->upfile('video_logo');
                if ($result){
                    $_POST['video_logo'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $update_array = array();
            $update_array['video_isuse'] = intval($_POST['video_isuse'])==1?1:0;
            $update_array['video_modules_name'] = $_POST['video_modules_name'];
            if (!empty($_POST['video_logo'])){
                $update_array['video_modules_logo'] = $_POST['video_logo'];
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['video_modules_logo']) && !empty($_POST['video_modules_logo'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$list_setting['video_modules_logo']);
                }
                $this->log('编辑手机端视频模块设置',1);
                showDialog(L('nc_common_save_succ'));
            } else {
                showDialog(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::showpage('mb_video_setting');
    }
}
