<?php
/**
 * 前台登录
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

class loginControl extends BaseHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录操作
     *
     */
    public function indexOp(){
        Language::read("home_login_index,home_login_register");
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        //检查登录状态
        $model_member->checkloginMember();
        if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
            $script = "document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&nchash=".getNchash()."&t=' + Math.random();";
        }
        $result = chksubmit(false,C('captcha_status_login'),'num');
        if ($result !== false){
            if ($result === -11){
                showDialog($lang['login_index_login_illegal'],'','error',$script);
            }elseif ($result === -12){
                showDialog('验证码错误','','error',$script);
            }
            
            $login_info = array();
            $login_info['user_name'] = $_POST['user_name'];
            $login_info['password'] = $_POST['password'];
            $member_info = $model_member->login($login_info);
            if(isset($member_info['error'])) {
                showDialog($member_info['error'],'','error',$script);
            }

            // 自动登录
            $member_info['auto_login'] = $_POST['auto_login'];
            $model_member->createSession($member_info, true);
            if ($_GET['inajax'] == 1){
                showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
            } else {
                redirect($_POST['ref_url']);
            }
        }else{

            //登录表单页面
            $_pic = @unserialize(C('login_pic'));
            if ($_pic[0] != ''){
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
            }else{
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
            }

            if(empty($_GET['ref_url'])) {
                $ref_url = getReferer();
                if (!preg_match('/con=login&fun=logout/', $ref_url)) {
                 $_GET['ref_url'] = $ref_url;
                }
            }
            Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
            Tpl::showpage('login_inajax','null_layout');
        }
    }
}
