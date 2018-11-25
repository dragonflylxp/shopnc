<?php
/**
 * mobile父类
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class mobileControl{

    //客户端类型
    protected $client_type_array = array('android', 'wap', 'wechat', 'ios', 'windows');
    //列表默认分页数
    protected $page = 5;


    public function __construct() {
        Language::read('mobile');

        //分页数处理
        $page = intval($_GET['page']);
        if($page > 0) {
            $this->page = $page;
        }
    }
}

class mobileHomeControl extends mobileControl{
    public function __construct() {
        parent::__construct();
    }

    protected function getMemberIdIfExists()
    {
        $key = $_POST['key'];
        if (empty($key)) {
            $key = $_GET['key'];
        }

        $model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if (empty($mb_user_token_info)) {
            return 0;
        }

        return $mb_user_token_info['member_id'];
    }
}

class mobileMemberControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];
        }
    }

    public function getOpenId()
    {
        return $this->member_info['openid'];
    }

    public function setOpenId($openId)
    {
        $this->member_info['openid'] = $openId;
        Model('mb_user_token')->updateMemberOpenId($this->member_info['token'], $openId);
    }
}

class mobileSellerControl extends mobileControl{

    protected $seller_info = array();
    protected $seller_group_info = array();
    protected $member_info = array();
    protected $store_info = array();
    protected $store_grade = array();

    public function __construct() {
        parent::__construct();

        $model_mb_seller_token = Model('mb_seller_token');

        $key = $_POST['key']?$_POST['key']:$_GET['key'];
        if(empty($key)) {
            output_error('请登录', array('login' => '0'));
        }
        //print_r($key);
        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($key);
        if(empty($mb_seller_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_seller = Model('seller');
        $model_member = Model('member');
        $model_store = Model('store');
        $model_seller_group = Model('seller_group');

        $this->seller_info = $model_seller->getSellerInfo(array('seller_id' => $mb_seller_token_info['seller_id']));
        $this->member_info = $model_member->getMemberInfoByID($this->seller_info['member_id']);
        $this->store_info = $model_store->getStoreInfoByID($this->seller_info['store_id']);
        $this->seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $this->seller_info['seller_group_id']));

        // 店铺等级
        if (intval($this->store_info['is_own_shop']) === 1) {
            $this->store_grade = array(
                'sg_id' => '0',
                'sg_name' => '自营店铺专属等级',
                'sg_goods_limit' => '0',
                'sg_album_limit' => '0',
                'sg_space_limit' => '999999999',
                'sg_template_number' => '6',
                'sg_price' => '0.00',
                'sg_description' => '',
                'sg_function' => 'editor_multimedia',
                'sg_sort' => '0',
            );
        } else {
            $store_grade = rkcache('store_grade', true);
            $this->store_grade = $store_grade[$this->store_info['grade_id']];
        }

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $this->seller_info['client_type'] = $mb_seller_token_info['client_type'];
        }
    }
}

class mobileDistributeControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            if(!in_array($this->member_info['distri_state'],array(2,4,5))) {
                output_error('请先认证成为分销员', array('is_distri' => '0'));
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            if($this->member_info['distri_state'] == 2){
                if($this->member_info['trad_amount'] >= C('distribute_bill_limit')){
                    $freeze_trad += C('distribute_bill_limit');
                    $available_trad -= C('distribute_bill_limit');
                }else{
                    $freeze_trad += $this->member_info['trad_amount'];
                    $available_trad = 0;
                }
            }
            $this->member_info['available_distri_trad'] = $available_trad;
            $this->member_info['freeze_distri_trad'] = $freeze_trad;
        }
    }

    public function getOpenId()
    {
        return $this->member_info['openid'];
    }

    public function setOpenId($openId)
    {
        $this->member_info['openid'] = $openId;
        Model('mb_user_token')->updateMemberOpenId($this->member_info['token'], $openId);
    }
}
