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

class manager_loginControl extends ManagerControl {

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

        $model_member   = Model('manager_index');
        //检查登录状态 查看是否已经登录过了
        $model_member->checkloginMember();
        //查看是否是post提交
        $result = chksubmit(false,true,'num');
        if ($result !== false){

            if ($result === -11){
                showDialog($lang['login_index_login_illegal'],'','error');
            }elseif ($result === -12){
                showDialog($lang['login_index_wrong_checkcode'],'','error');
            }

            $login_info = array();
            $login_info['user_name'] = $_POST['user_name'];
            $login_info['password'] = $_POST['password'];

            $member_info = $model_member->login($login_info);

            if(isset($member_info['error'])) {
                showDialog($member_info['error'],'','error');
            }
           /* 自动登录
           $member_info['auto_login'] = $_POST['auto_login'];*/
            $model_member->createSession($member_info, true);
            if($_POST['is_distri_login'] == 'yes' && in_array($member_info['distri_state'],array('0'))){
                redirect(urlDistribute('distri_join','index'));
            }
            if ($_GET['inajax'] == 1){
                showDialog('登录成功',urlMember('manager_index','index'),'succ');
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
            if ($_GET['inajax'] == 1){
                Tpl::showpage('login_inajax','null_layout');
            }else{
                Tpl::showpage('manager_login');
            }
        }
    }


    /**
     * 退出登录
     */
    public function logoutOp(){
        Language::read("home_login_index");
        $lang   = Language::getLangContent();
        // 清理COOKIE
        setNcCookie('details','',-3600);  //查看管理人详情
        session_unset();
        session_destroy();
        redirect(urlMember('manager_login','index'));
    }

}
