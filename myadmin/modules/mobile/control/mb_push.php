<?php
/**
 * 推送通知--百度云推送
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
class mb_pushControl extends SystemControl{
    private $links = array(
        array('url'=>'con=mb_push&fun=index','text'=>'推送列表'),
        array('url'=>'con=mb_push&fun=set','text'=>'设置')
    );
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        Tpl::output('top_link',$this->sublink($this->links, 'index'));
        Tpl::showpage('mb_push.list');
    }

    /**
     * 推送通知列表
     */
    public function get_list_xmlOp() {
        $model_push = Model('mb_push');
        $type_array = array('1'=>'关键字','2'=>'专题编号','3'=>'商品编号');
        $condition = array();
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        $list = $model_push->getPushList($condition,$page);
        $out_list = array();
        if (!empty($list) && is_array($list)){
            $fields_array = array('msg_tag','log_type','log_type_v','log_msg','add_time');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                $out_array['log_type'] = $type_array[$v['log_type']];
                if ($v['msg_tag'] == 'default') {
                    $out_array['msg_tag'] = '全部';
                } else {
                    $out_array['msg_tag'] = strtoupper($v['msg_tag']);
                }
                $out_array['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
                $out_list[$v['log_id']] = $out_array;
            }
        }
        $data = array();
        $data['now_page'] = $model_push->shownowpage();
        $data['total_num'] = $model_push->gettotalnum();
        $data['list'] = $out_list;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 推送设置
     */
    public function setOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['baidu_push_ios']   = $_POST['baidu_push_ios'];
            $update_array['baidu_push_ios_key']   = $_POST['baidu_push_ios_key'];
            $update_array['baidu_push_ios_secret']  = $_POST['baidu_push_ios_secret'];
            $update_array['baidu_push_android_key']   = $_POST['baidu_push_android_key'];
            $update_array['baidu_push_android_secret']  = $_POST['baidu_push_android_secret'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('手机端推送通知设置');
                showMessage(Language::get('nc_common_save_succ'));
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'set'));
        Tpl::showpage('mb_push.edit');
    }

    /**
     * 新增通知
     */
    public function addOp() {
        $model_push = Model('mb_push');
        $ios_sdk = array();
        $_key = C('baidu_push_ios_key');
        $_secret = C('baidu_push_ios_secret');
        if (!empty($_key) && !empty($_secret)) {
            $ios_sdk = $model_push->getPushSDK($_key,$_secret);
        }
        $android_sdk = array();
        $_key = C('baidu_push_android_key');
        $_secret = C('baidu_push_android_secret');
        if (!empty($_key) && !empty($_secret)) {
            $android_sdk = $model_push->getPushSDK($_key,$_secret);
        }
        if (chksubmit()) {
            $_array = array();
            $_array['msg_tag'] = $_POST['msg_tag'];
            $_array['log_type'] = $_POST['log_type'];
            $_array['log_type_v'] = $_POST['log_type_v'];
            $_array['log_msg'] = $_POST['log_msg'];
            $_array['ios_status'] = intval(C('baidu_push_ios'));
            $_array['add_time'] = time();

            $log_id = $model_push->addPush($_array);
            if ($log_id) {
                $this->log('新增手机端推送通知，编号'.$log_id);
                if (!empty($ios_sdk)) {
                    $msg = array();
                    $msg['aps']['alert'] = $_array['log_msg'];
                    $msg['type'] = $_array['log_type'];
                    $msg['type_v'] = $_array['log_type_v'];
                    $opts = array();
                    $opts['msg_type'] = 1;
                    $opts['deploy_status'] = $_array['ios_status'];
                    $rs = array();
                    if ($_array['msg_tag'] == 'default') {
                        $rs = $ios_sdk->pushMsgToAll($msg,$opts);
                    } else {
                        $rs = $ios_sdk->pushMsgToTag($_array['msg_tag'],$msg,$opts);
                    }
                    $model_push->editPush(array('log_id'=> $log_id), array('msg_ios_id'=> $rs['msg_id']));
                }
                if (!empty($android_sdk)) {
                    $msg = array();
                    $msg['description'] = $_array['log_msg'];
                    $msg['custom_content']['type'] = $_array['log_type'];
                    $msg['custom_content']['type_v'] = $_array['log_type_v'];
                    $opts = array();
                    $opts['msg_type'] = 1;
                    $rs = array();
                    if ($_array['msg_tag'] == 'default') {
                        $rs = $android_sdk->pushMsgToAll($msg,$opts);
                    } else {
                        $rs = $android_sdk->pushMsgToTag($_array['msg_tag'],$msg,$opts);
                    }
                    $model_push->editPush(array('log_id'=> $log_id), array('msg_id'=> $rs['msg_id']));
                }
                showMessage(Language::get('nc_common_save_succ'),'index.php?con=mb_push&fun=index');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::showpage('mb_push.add');
    }
}
