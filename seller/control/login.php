<?php

/**

 * 店铺卖家登录

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');



class loginControl extends BaseLoginControl {



    public function __construct() {

        parent::__construct();

       

        if (!empty($_SESSION['is_login'])) {

        // if (!empty($_SESSION['is_login'])) {

            $_COOKIE["key"] = $_SESSION['key'];

           @header('location: index.php?con=member&key='. $_COOKIE["key"]);die;

        }

    }

    /*

    *登陆页面

    */

    public function indexOp() {

        Tpl::output('web_seo',C('site_name').' - '.'用户登录');

        Tpl::showpage('login');

    }



    /*

    *提交验证码

    */

    public function find_password_codeOp() {

        Tpl::output('web_seo',C('site_name').' - '.'提交验证码');

        Tpl::showpage('find_password_code');

    }



     /*

    *找回密码

    */

    public function find_password_passwordOp() {

        Tpl::output('web_seo',C('site_name').' - '.'找回密码');

        Tpl::showpage('find_password_password');

    }



    



    /*

    *登陆处理

    */

    public function runloginOp(){

        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {

            output_error('登录失败');

        }

        $model_member = Model('member');

        $array = array();

        $array['member_name']   = $_POST['username'];

        $array['member_passwd'] = md5($_POST['password']);

        $member_info = $model_member->getMemberInfo($array);



        if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $_POST['username'])) {//根据会员名没找到时查手机号

            $array = array();

            $array['member_mobile']   = $_POST['username'];

            $array['member_passwd'] = md5($_POST['password']);

            $member_info = $model_member->getMemberInfo($array);

        }

        if(empty($member_info) && (strpos($_POST['username'], '@') > 0)) {//按邮箱和密码查询会员

            $array = array();

            $array['member_email']   = $_POST['username'];

            $array['member_passwd'] = md5($_POST['password']);

            $member_info = $model_member->getMemberInfo($array);

        }



        if(is_array($member_info) && !empty($member_info)) {

            $model_member->createSession($member_info);

            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);

            if($token) {

                $_SESSION['key']  = $token;

                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));

            } else {

                output_error('登录失败');

            }

        } else {

            output_error('用户名密码错误');

        }

    }



  



    /*

    *找回密码find_password

    */

    public function find_passwordOp(){

        Tpl::output('web_seo',C('site_name').' - '.'找回密码');

        Tpl::showpage('find_password');

    }



    /**

     * 登录生成token

     */

    private function _get_token($member_id, $member_name, $client) {

        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效

        //暂时停用

        //$condition = array();

        //$condition['member_id'] = $member_id;

        //$condition['client_type'] = $client;

        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token

        $mb_user_token_info = array();

        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));

        $mb_user_token_info['member_id'] = $member_id;

        $mb_user_token_info['member_name'] = $member_name;

        $mb_user_token_info['token'] = $token;

        $mb_user_token_info['login_time'] = TIMESTAMP;

        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {

            return $token;

        } else {

            return null;

        }

    }



   

}

