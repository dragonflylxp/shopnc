<?php

/**

 * QQ互联登录

 */







defined('Inshopec') or exit('Access Invalid!');



class connect_qqControl extends mobileHomeControl{

    public function __construct(){

        parent::__construct();

        //Language::read("home_login_register,home_login_index,home_qqconnect");

        /**

         * 判断qq互联功能是否开启

         */

        if (C('qq_isuse') != 1){

             showMessage('系统未开启QQ互联功能',urlMobile('login'),'html','error');//'系统未开启QQ互联功能'

        }

        if (!$_SESSION['openid']){

            showMessage('系统错误',urlMobile('login'),'html','error');//'系统错误'

        }
        $this->re_url= base64_decode($_GET['ref_url']);
        

        //Tpl::output('hidden_login', 1);

    }

    /**

     * 首页

     */

    public function indexOp(){

        /**

         * 检查登录状态

         */

        if(!empty($_SESSION['key'])){

            //qq绑定

            $this->bindqqOp();

        }else {

            $this->autologin();

            $this->registerOp();

        }

    }

    /**

     * qq绑定新用户

     */

    public function registerOp(){

        //实例化模型

        $model_member   = Model('member');

        if (chksubmit()){

            $update_info    = array();

            $update_info['member_passwd']= md5(trim($_POST["password"]));

            if(!empty($_POST["email"])) {

                $update_info['member_email']= $_POST["email"];

                $_SESSION['member_email']= $_POST["email"];

            }

            $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_info);

        }else {



            //获取qq账号信息

            require_once (BASE_PATH.'/api/qq/user/get_user_info.php');

            $qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);





            //处理qq账号信息

            $qquser_info['nickname'] = trim($qquser_info['nickname']);

            $user_passwd = rand(100000, 999999);

            /**

             * 会员添加

             */

            $user_array = array();

            $user_array['member_name']      = $qquser_info['nickname'];

            $user_array['member_passwd']    = $user_passwd;

            $user_array['member_email']     = '';

            $user_array['member_qqopenid']  = $_SESSION['openid'];//qq openid

            $user_array['member_qqinfo']    = serialize($qquser_info);//qq 信息

            $rand = rand(100, 899);

            if(strlen($user_array['member_name']) < 3) $user_array['member_name']       = $qquser_info['nickname'].$rand;

            $check_member_name  = $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));

            $result = 0;

            if(empty($check_member_name)) {

                $result = $model_member->addMember($user_array);

            }else {

                for ($i = 1;$i < 999;$i++) {

                    $rand += $i;

                    $user_array['member_name'] = trim($qquser_info['nickname']).$rand;

                    $check_member_name  = $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));

                    if(empty($check_member_name)) {

                        $result = $model_member->addMember($user_array);

                        break;

                    }

                }

            }

            if($result) {

                //$avatar = @copy($qquser_info['figureurl_qq_2'],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_$result.jpg");

                $update_info    = array();

                if($avatar) {

                    $update_info['member_avatar']   = "avatar_$result.jpg";

                    $model_member->editMember(array('member_id'=>$result),$update_info);

                }

                $member_info = $model_member->getMemberInfo(array('member_name'=>$user_array['member_name']));

                $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');

                $state_data['key'] = $token;

                $state_data['username'] = $member_info['member_name'];

                $state_data['userid'] = $member_info['member_id'];

                    $url = $this->re_url? $this->re_url:urlMobile('member','index',array('username'=>$member_info['username'],'key'=>$token));
                        if($this->re_url){
                        $model_mb_user_token = Model('mb_user_token');
                        $key = $token;
                        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
                        $model_member = Model('member');

                        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
                        if(empty($_SESSION['is_login'])){

                            $model_member->createSession($this->member_info);

                            $_SESSION['key']  = $key;
                            setcookie('key', $key); 

                        }
                    }
                    @header("location:$url;");exit;


            } else {

               showMessage('会员注册失败',urlMobile('login'),'html','error');//"会员注册失败"

            }

        }

    }

    /**

     * 已有用户绑定QQ

     */

    public function bindqqOp(){

        $model_member   = Model('member');

        //验证QQ账号用户是否已经存在

        $array  = array();

        $array['member_qqopenid']   = $_SESSION['openid'];

        $member_info = $model_member->getMemberInfo($array);

        if (is_array($member_info) && count($member_info)>0){

            unset($_SESSION['openid']);

           showMessage('该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定',urlMobile('login'),'html','error');//'该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定'

        }

        //获取qq账号信息

        require_once (BASE_PATH.'/api/qq/user/get_user_info.php');

        $qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);

        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']), array('member_qqopenid'=>$_SESSION['openid'], 'member_qqinfo'=>serialize($qquser_info)));

        if ($edit_state){

            

            showMessage('绑定QQ成功',urlMobile('login'),'html','succ');

        }else {

            

            showMessage('绑定QQ失败',urlMobile('login'),'html','error');

        }

    }

    /**

     * 绑定qq后自动登录

     */

    public function autologin(){

        //查询是否已经绑定该qq,已经绑定则直接跳转

        $model_member   = Model('member');

        $array  = array();

        $array['member_qqopenid']   = $_SESSION['openid'];

        $member_info = $model_member->getMemberInfo($array);

        if (is_array($member_info) && count($member_info)>0){

            if(!$member_info['member_state']){//1为启用 0 为禁用

                showMessage('您的账号已经被管理员禁用，请联系平台客服进行核实',urlMobile('login'),'html','error');

            } 

            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');

            $url = $this->re_url ? $this->re_url:urlMobile('member','index',array('username'=>$member_info['username'],'key'=>$token));
            if($this->re_url){
                        $model_mb_user_token = Model('mb_user_token');
                        $key = $token;
                        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
                        $model_member = Model('member');

                        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
                        if(empty($_SESSION['is_login'])){

                            $model_member->createSession($this->member_info);

                            $_SESSION['key']  = $key;
                            setcookie('key', $key); 

                        }
                    }

           @header("location:$url;");exit;


        }

    }

    /**

     * 登录生成token 

     */

    public function _get_token($member_id, $member_name, $client) {

        $model_mb_user_token = Model('mb_user_token');

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

