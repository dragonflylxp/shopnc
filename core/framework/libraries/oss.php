<?php
/**
 * aliyun oss
 *
 * @package    library
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @author     shopec Team
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
final class oss {
    private static $oss_sdk_service;
    private static $bucket;
    private static function _init() {
        require_once(BASE_DATA_PATH.'/api/oss/sdk.class.php');
        self::$oss_sdk_service = new ALIOSS(null,null,C('oss.api_url'));
        //设置是否打开curl调试模式
        self::$oss_sdk_service->set_debug_mode(false);
        self::$bucket = C('oss.bucket');
    }

    //格式化返回结果
    private static function _format($response) {
        echo '|-----------------------Start---------------------------------------------------------------------------------------------------'."\n";
        echo '|-Status:' . $response->status . "\n";
        echo '|-Body:' ."\n";
        echo $response->body . "\n";
        echo "|-Header:\n";
        print_r ( $response->header );
        echo '-----------------------End-----------------------------------------------------------------------------------------------------'."\n\n";
    }

    /**
     * 
     * @param unknown $src_file
     * @param unknown $new_file
     */
    public static function upload($src_file,$new_file) {
        self::_init();
        try{
            $response = self::$oss_sdk_service->upload_file_by_file(self::$bucket,$new_file,$src_file);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
//             self::_format($response);exit;
        } catch (Exception $ex){
            return false;
            //die($ex->getMessage());
        }
    }

    public static function del($img_list = array()) {
        self::_init();
        try{
            $options = array(
                    'quiet' => false,
                    //ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
            );
            $response = self::$oss_sdk_service->delete_objects(self::$bucket,$img_list,$options);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex){
            return false;
            //die($ex->getMessage());
        }
    }
}
