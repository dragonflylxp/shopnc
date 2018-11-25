<?php
/**
 * 前台登录 退出操作
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

class loginControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();

        //验证merchantId有效性
        $agencyid = $_POST['agencyid'];
        if (empty($agencyid)) {
            $agencyid= $_GET['agencyid'];
        }
        $merchantid = $_POST['merchantid'];
        if (empty($merchantid)) {
            $merchantid = $_GET['merchantid'];
        }
	if (!empty($agencyid) && $agencyid > 0 && !empty($merchantid) && $merchantid > 0) {
           if (!$this->_verify_merchantid($agencyid, $merchantid)){
               output_error('merchantId不合法');
           }
        }
    }

    /**
     * 登录
     */
    public function indexOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

        $model_member = Model('member');

        $login_info = array();
        $login_info['user_name'] = $_POST['username'];
        $login_info['password'] = $_POST['password'];
        $member_info = $model_member->login($login_info);
        if(isset($member_info['error'])) {
            output_error($member_info['error']);
        } else {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));
            } else {
                output_error('登录失败');
            }
        }
    }
    /**
     * 验证merchantid有效性 
     */
    private function _verify_merchantid($agencyid,$merchantid) {
        $inc_file = BASE_PATH.DS.'api'.DS.'merchantid'.DS.'verify.php';

        if(is_file($inc_file)) {
            require($inc_file);
            $verify = new MerchantIdVerify();
            return $verify->do_verify($agencyid,$merchantid);
        }
        return false; 
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

    /**
     * 注册
     */
    public function registerOp(){
        $model_member   = Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        $register_info['referral_code'] = $_POST['referral_code'];
        if(intval($_REQUEST['inviterid'])){
        	$inviter_id = $_REQUEST($_GET['inviterid']);
        }else{
        	$inviter_id = cookie('inviter_id');
        }        
              
		if (!empty($inviter_id)) {
			$_SESSION['inviter_id'] = $inviter_id;
			/**
			 * 邀请注册 20160906
			 */
			
			$model_member = Model('member');
			$inviter_info = $model_member -> getMemberInfoByID($inviter_id);
			$inviter_name = $inviter_info['member_name'];
			setNcCookie('inviter_id', $inviter_id);
			setNcCookie('inviter_name', $inviter_name);
		}  
                
		if (!empty($_SESSION['inviter_id'])) {
			$register_info['inviter_id'] = $_SESSION['inviter_id'];
		}                           
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));

                
            } else {
                output_error('注册失败');
            }
        } else {
            output_error($member_info['error']);
        }

    }

    /**
     * yifen sport联合注册/登录
     */
    public function comloginOp(){
        if(empty($_POST['agencyid']) || empty($_POST['merchantid']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('联合登录失败');
        } 
        $model_member = Model('member');
        $comlogin_info = array();
        $comlogin_info['agencyid'] = $_POST['agencyid'];
        $comlogin_info['merchantid'] = $_POST['merchantid'];
        $member_info = $model_member->comlogin($comlogin_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));
            } else {
                output_error('联合登录创建token失败');
            }
        } else {
            output_error($member_info['error']);
        }
        
    }
}
