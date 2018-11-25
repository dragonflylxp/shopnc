<?php

/**
 * 注销
 *
 *
 *
 *
 */







defined('Inshopec') or exit('Access Invalid!');



class logoutControl extends mobileMemberControl {



    public function __construct(){

        parent::__construct();

    }



    /**
     * 注销
     */

    public function indexOp(){



        if(!in_array($_POST['client'], $this->client_type_array)) {

            output_error('参数错误');

        }



        $model_mb_user_token = Model('mb_user_token');



        if($this->member_info['member_name'] ==  $_SESSION['member_name']) {

            $condition = array();

            $condition['member_id'] = $this->member_info['member_id'];

            $condition['client_type'] = $_POST['client'];

            $model_mb_user_token->delMbUserToken($condition);

            setCookie('key', '', -3600);

            setCookie('cart_count', '', -3600);

            session_unset();

            session_destroy();

           

            output_data('1');

        } else {

            output_error('参数错误');

        }

    }



}

