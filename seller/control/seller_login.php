<?php

/**

 * 商家登录

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');



class seller_loginControl extends mobileHomeControl {



    public function __construct(){



    parent::__construct();

         Tpl::setDir('seller');

        Tpl::setLayout('seller_layout');

    }

    /*

    *用户中心首页

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'商家登录');

         Tpl::showpage('login');

    }



    /**

     * 登录

     */

    public function runloginOp(){

        if(empty($_POST['seller_name']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {

            output_error('用户名密码不能为空');

        }



        $model_seller = Model('seller');

        $seller_info = $model_seller->getSellerInfo(array('seller_name' => $_POST['seller_name']));



        if(!$seller_info) {

            output_error('登录失败');

        }



        //店铺所有人或者授权的子账号可以从客户端登录 

        if(!($seller_info['is_admin'] || $seller_info['is_client'])) {

            output_error('权限验证失败');

        }



        //验证身份

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfo(

            array(

                'member_id' => $seller_info['member_id'],

                'member_passwd' => md5($_POST['password'])

            )

        );

  

        if(!$member_info) {

            output_error('用户名密码错误');

        }



        //读取店铺信息

        $model_store = Model('store');

        $store_info = $model_store->getStoreInfoByID($seller_info['store_id']);

         //存储session

         $_SESSION['is_login'] = '1';

        $_SESSION['member_id'] = $member_info['member_id'];

        $_SESSION['member_name'] = $member_info['member_name'];

        $_SESSION['member_email'] = $member_info['member_email'];

        $_SESSION['is_buy'] = $member_info['is_buy'];

        $_SESSION['avatar'] = $member_info['member_avatar'];



        $_SESSION['grade_id'] = $store_info['grade_id'];

        $_SESSION['seller_id'] = $seller_info['seller_id'];

        $_SESSION['seller_name'] = $seller_info['seller_name'];

        $_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);

        $_SESSION['store_id'] = intval($seller_info['store_id']);

        $_SESSION['store_name'] = $store_info['store_name'];

        $_SESSION['store_avatar'] = $store_info['store_avatar'];

        $_SESSION['is_own_shop'] = (bool) $store_info['is_own_shop'];

        $_SESSION['bind_all_gc'] = (bool) $store_info['bind_all_gc'];

        $_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);

        $_SESSION['seller_group_id'] = $seller_info['seller_group_id'];

        $_SESSION['seller_gc_limits'] = $seller_group_info['gc_limits'];

        if($seller_info['is_admin']) {

            $_SESSION['seller_group_name'] = '管理员';

            $_SESSION['seller_smt_limits'] = false;

        } else {

            $_SESSION['seller_group_name'] = $seller_group_info['group_name'];

            $_SESSION['seller_smt_limits'] = explode(',', $seller_group_info['smt_limits']);

        }

        if(!$seller_info['last_login_time']) {

            $seller_info['last_login_time'] = TIMESTAMP;

        }

        $_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);

     

        setNcCookie('auto_login', '', -3600);



        

        //更新卖家登陆时间

        $model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));



        //生成登录令牌

        $token = $this->_get_token($seller_info['seller_id'], $seller_info['seller_name'], $_POST['client']);

        if($token) {

             $_SESSION['sellerkey']  = $token;

            output_data(array('seller_name' => $seller_info['seller_name'], 'store_name' => $store_info['store_name'], 'sellerkey' => $token));

        } else {

            output_error('登录失败');

        }

    }



    /**

     * 登录生成token

     */

    private function _get_token($seller_id, $seller_name, $client) {

        $model_mb_seller_token = Model('mb_seller_token');



        //重新登录后以前的令牌失效

        $condition = array();

        $condition['seller_id'] = $seller_id;

        $model_mb_seller_token->delSellerToken($condition);



        //生成新的token

        $mb_seller_token_info = array();

        $token = md5($seller_name. strval(TIMESTAMP) . strval(rand(0,999999)));

        $mb_seller_token_info['seller_id'] = $seller_id;

        $mb_seller_token_info['seller_name'] = $seller_name;

        $mb_seller_token_info['token'] = $token;

        $mb_seller_token_info['login_time'] = TIMESTAMP;

        $mb_seller_token_info['client_type'] = $client;



        $result = $model_mb_seller_token->addSellerToken($mb_seller_token_info);



        if($result) {

            return $token;

        } else {

            return null;

        }

    }

}

