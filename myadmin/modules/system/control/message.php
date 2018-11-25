<?php
/**
 * 消息通知
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
class messageControl extends SystemControl{
    private $links = array(
        array('url'=>'con=message&fun=email','lang'=>'email_set'),
        array('url'=>'con=message&fun=email_tpl','lang'=>'email_tpl')
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting,message');
    }

    public function indexOp() {
        $this->emailOp();
    }

    /**
     * 邮件设置
     */
    public function emailOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['email_host']     = $_POST['email_host'];
            $update_array['email_port']     = $_POST['email_port'];
            $update_array['email_addr']     = $_POST['email_addr'];
            $update_array['email_id']       = $_POST['email_id'];
            $update_array['email_pass']     = $_POST['email_pass'];

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,email_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,email_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        Tpl::output('top_link',$this->sublink($this->links,'email'));
        Tpl::showpage('message.email');
    }

    /**
     * 邮件模板列表
     */
    public function email_tplOp(){
        $model_templates = Model('mail_templates');
        $templates_list = $model_templates->getTplList();
        Tpl::output('templates_list',$templates_list);
        Tpl::output('top_link',$this->sublink($this->links,'email_tpl'));
        Tpl::showpage('message.email_tpl');
    }

    /**
     * 编辑邮件模板
     */
    public function email_tpl_editOp(){
        $model_templates = Model('mail_templates');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["code"], "require"=>"true", "message"=>L('mailtemplates_edit_no_null')),
                array("input"=>$_POST["title"], "require"=>"true", "message"=>L('mailtemplates_edit_title_null')),
                array("input"=>$_POST["content"], "require"=>"true", "message"=>L('mailtemplates_edit_content_null')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['code'] = $_POST["code"];
                $update_array['title'] = $_POST["title"];
                $update_array['content'] = $_POST["content"];
                $result = $model_templates->editTpl($update_array,array('code'=>$_POST['code']));
                if ($result === true){
                    $this->log(L('nc_edit,email_tpl'),1);
                    showMessage(L('mailtemplates_edit_succ'),'index.php?con=message&fun=email_tpl');
                }else {
                    $this->log(L('nc_edit,email_tpl'),0);
                    showMessage(L('mailtemplates_edit_fail'));
                }
            }
        }
        if (empty($_GET['code'])){
            showMessage(L('mailtemplates_edit_code_null'));
        }
        $templates_array = $model_templates->getTplInfo(array('code'=>$_GET['code']));
        Tpl::output('templates_array',$templates_array);
        Tpl::output('top_link',$this->sublink($this->links,'email_tpl'));
        Tpl::showpage('message.email_tpl.edit');
    }

    /**
     * 测试邮件发送
     *
     * @param
     * @return
     */
    public function email_testingOp(){
        /**
         * 读取语言包
         */
        $lang   = Language::getLangContent();

        $email_host = trim($_POST['email_host']);
        $email_port = trim($_POST['email_port']);
        $email_addr = trim($_POST['email_addr']);
        $email_id = trim($_POST['email_id']);
        $email_pass = trim($_POST['email_pass']);

        $email_test = trim($_POST['email_test']);
        $subject    = $lang['test_email'];
        $site_url   = SHOP_SITE_URL;

        $site_title = C('site_name');
        $message = '<p>'.$lang['this_is_to']."<a href='".$site_url."' target='_blank'>".$site_title.'</a>'.$lang['test_email_send_ok'].'</p>';

        try {
            $mailer = new \shopec\Lib\Messager\SmtpMailer(
                $email_host,
                $email_port,
                $email_id,
                $email_pass,
                $email_addr,
                $site_title
            );
            $mailer->send($email_test, $subject, $message);
            $message = $lang['test_email_send_ok'];
        } catch (\shopec\Lib\Messager\Exception $ex) {
            $message = $lang['test_email_send_fail'] . ': ' . $ex->getMessage();
        }

        if (strtoupper(CHARSET) == 'GBK') {
            $message = Language::getUTF8($message);
        }

        showMessage($message, '', 'json');
    }
}
