<?php
/**
 * 下载设置
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
class mb_appControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->mb_appOp();
    }

    /**
     * 设置下载地址
     *
     */
    public function mb_appOp() {
        $model_setting = Model('setting');
        $mobile_apk = $model_setting->getRowSetting('mobile_apk');
        $mobile_apk_version = $model_setting->getRowSetting('mobile_apk_version');
        $mobile_ios = $model_setting->getRowSetting('mobile_ios');
        if (chksubmit()) {
            $update_array = array();
            $update_array['mobile_apk'] = $_POST['mobile_apk'];
            $update_array['mobile_apk_version'] = $_POST['mobile_apk_version'];
            $update_array['mobile_ios'] = $_POST['mobile_ios'];
            $state = $model_setting->updateSetting($update_array);
            if ($state) {
                $this->log('设置手机端下载地址');
                showMessage(Language::get('nc_common_save_succ'),'index.php?con=mb_app&fun=mb_app');
                return;
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('mobile_apk',$mobile_apk);
        Tpl::output('mobile_version',$mobile_apk_version);
        Tpl::output('mobile_ios',$mobile_ios);
        Tpl::showpage('mb_app.edit');
    }

    /**
     * 生成二维码
     */
    public function mb_qrOp() {
        $url = urlShop('mb_app', 'index');
        $mobile_app = 'mb_app.png';
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS);
        $PhpQRCode->set('date',$url);
        $PhpQRCode->set('pngTempName', $mobile_app);
        $PhpQRCode->init();

        $this->log('生成手机端二维码');
        showMessage('生成二维码成功','index.php?con=mb_app&fun=mb_app');
    }

    /**
     * 生成对应系统app二维码
     * author liming
     */
    public function mb_and_ap_qrOp() {

        $mobile_apk_url = !empty(trim($_POST['mobile_apk'])) ? trim($_POST['mobile_apk']) : '';
        $mobile_ios_url = !empty(trim($_POST['mobile_ios'])) ? trim($_POST['mobile_ios']) : '';
        $mobile_android = 'mb_android_app.png';
        $mobile_apple = 'mb_apple_app.png';
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS);
        if(!empty($_POST['mobile_apk'])){
            $logo_url = BASE_UPLOAD_PATH.'/shop/store/goods/android_icon.jpg';
            $PhpQRCode->set('date',$mobile_apk_url);
            $PhpQRCode->set('pngTempName', $mobile_android);
            $PhpQRCode->init_logo_android_apple($logo_url);
        }

        if(!empty($_POST['mobile_ios'])){
            $logo_url = BASE_UPLOAD_PATH.'/shop/store/goods/apple_icon.jpg';
            $PhpQRCode->set('date',$mobile_ios_url);
            $PhpQRCode->set('pngTempName', $mobile_apple);
            $PhpQRCode->init_logo_android_apple($logo_url);
        }

        $this->log('生成手机端二维码');
        $data = array(
            'code'      =>'200',
            'status'    =>'success',
            'url'       =>'index.php?con=mb_app&fun=mb_app',
            'message'   =>'生成二维码成功'
        );
        echo json_encode($data);
    }
}
