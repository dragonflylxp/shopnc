<?php
/**
 * 注销
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

class logoutControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 注销
     */
    public function indexOp(){
        if(empty($_GET['username']) || !in_array($_GET['client'], $this->client_type_array)) {
            output_error('参数错误1');
        }

        $model_mb_user_token = Model('mb_user_token');

        if($this->member_info['member_name'] == $_GET['username']) {
            $condition = array();
            $condition['member_id'] = $this->member_info['member_id'];
            $condition['client_type'] = $_GET['client'];
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

    /**
     * yifen sport联合注销
     */
    public function comlogoutOp(){
        if(empty($_POST['agencyid']) || empty($_POST['merchantid']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('联合注销参数错误');
        } 

        $model_mb_user_token = Model('mb_user_token');

        if($this->member_info['member_agencyid'] == $_POST['agencyid'] && $this->member_info['member_merchantid'] == $_POST['merchantid']) {
            $condition = array();
            $condition['member_id'] = $this->member_info['member_id'];
            $condition['client_type'] = $_POST['client'];
            $model_mb_user_token->delMbUserToken($condition);
            setCookie('key', '', -3600);
            setCookie('cart_count', '', -3600);
            session_unset();
            session_destroy();            
        
            output_data('联合注销成功');
        } else {
            output_error('联合注销参数错误');
        }
    }
}
