<?php

/**
 * mobile父类
 *
 *
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



        /**
     * 输出会员等级
     * @param bool $is_return 是否返回会员信息，返回为true，输出会员信息为false
     */

    protected function getMemberAndGradeInfo($is_return = false){

        $member_info = array();

        //会员详情及会员级别处理

        if($_SESSION['member_id']) {

            $model_member = Model('member');

            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);

            if ($member_info){

                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));

                $member_info = array_merge($member_info,$member_gradeinfo);

                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);

            }

        }

        if ($is_return == true){//返回会员信息

            return $member_info;

        } else {//输出会员信息

            Tpl::output('member_info',$member_info);

        }

    }

}



class mobileHomeControl extends mobileControl{

    public function __construct() {

        parent::__construct();

         //输出会员信息

        $this->getMemberAndGradeInfo(false);

        Tpl::setDir('home');

        Tpl::setLayout('home_layout');

        if(!C('site_status')) halt(C('closed_reason'));

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



class BaseLoginControl extends mobileControl{

    /**
     * 构造函数
     */

    public function __construct(){

      

        if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('member');

        Tpl::setLayout('login_layout');

    }



}





class mobileMemberControl extends mobileControl{



    protected $member_info = array();



    public function __construct() {



        parent::__construct();

         if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('member');

        Tpl::setLayout('member_layout');



        $agent = $_SERVER['HTTP_USER_AGENT']; 

       

        if (strpos($agent, "MicroMessenger") && $_GET["con"]=='auto') { 

            $this->appId = C('app_weixin_appid');

            $this->appSecret = C('app_weixin_secret');;         

        }else{

            $model_mb_user_token = Model('mb_user_token');
            $key1 = $_SESSION['key'];
            $key2 = $_COOKIE["key"]?$_COOKIE["key"]:$_GET['key'];
            if(!empty($key1)) {
                $key = $key1;
            }else{
                $key =  $key2;
            }
            $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
            if(empty($mb_user_token_info)) {
                session_destroy();
                unset($_COOKIE);
                //showMessage('请登录',urlMobile('login'),'html','error');
                if(IS_AJAX){
                    echo json_encode(array('nologin'=>1));exit;
                }else{
                   $login_url = WAP_SITE_URL +"/tmpl/member/login.html";
                   @header("Location:$login_url"); 
                }
            }



            $model_member = Model('member');

            $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);



            



            if(empty($this->member_info)) {

                    session_destroy();

                    unset($_COOKIE);

                 //showMessage('请登录',urlMobile('login'),'html','error');

                   if(IS_AJAX){

                        echo json_encode(array('nologin'=>1));exit;

                    }else{

                       $login_url = WAP_SITE_URL ."/tmpl/member/login.html";

                       @header("Location:$login_url"); 

                    }

            } else {

                if(empty($_SESSION['is_login'])){

                    $model_member->createSession($this->member_info);

                    $_SESSION['key']  = $key;

                }

                //输出会员信息

                $this->getMemberAndGradeInfo(false);

                $this->member_info['client_type'] = $mb_user_token_info['client_type'];

                $this->member_info['openid'] = $mb_user_token_info['openid'];

                $this->member_info['token'] = $mb_user_token_info['token'];

                $this->level_name = $model_member->getOneMemberGrade( $this->member_info['member_exppoints'],'true');

                $this->member_info['level_name'] = $this->level_name['level_name'];

                //读取卖家信息

                $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));

                $this->member_info['store_id'] = $seller_info['store_id'];

            }

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



        if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('seller');

        Tpl::setLayout('seller_layout');

        $model_mb_seller_token = Model('mb_seller_token');
       $key1 = $_SESSION['sellerkey'];

        if(!empty($key1)) {

            $key = $key1;

        }

        if(empty($key)) {
            
            showMessage('请登录',urlMobile('seller_login'),'html','error');



        }



        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($key);

        if(empty($mb_seller_token_info)) {

            showMessage('请登录',urlMobile('seller_login'),'html','error');



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
                'sg_name' => '自营店铺',
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

     /**
     * 记录卖家日志
     *
     * @param $content 日志内容
     * @param $state 1成功 0失败
     */

    protected function recordSellerLog($content = '', $state = 1){

        $seller_info = array();

        $seller_info['log_content'] = $content;

        $seller_info['log_time'] = TIMESTAMP;

        $seller_info['log_seller_id'] = $_SESSION['seller_id'];

        $seller_info['log_seller_name'] = $_SESSION['seller_name'];

        $seller_info['log_store_id'] = $_SESSION['store_id'];

        $seller_info['log_seller_ip'] = getIp();

        $seller_info['log_url'] = $_GET['con'].'&'.$_GET['fun'];

        $seller_info['log_state'] = $state;

        $model_seller_log = Model('seller_log');

        $model_seller_log->addSellerLog($seller_info);

    }

}

/********************************** 前台control父类 **********************************************/



/**

 * 积分中心control父类

 */

class BasePointShopControl extends mobileControl {

    protected $member_info;

    public function __construct(){

        Language::read('common,home_layout');

   

        //输出会员信息

        $this->member_info = $this->getMemberAndGradeInfo(true);

        Tpl::output('member_info',$this->member_info);





        Tpl::setDir('home');

        Tpl::setLayout('home_layout');



        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){

            $_GET = Language::getGBK($_GET);

        }

        if(!C('site_status')) halt(C('closed_reason'));



        //判断系统是否开启积分和积分中心功能

        if (C('points_isuse') != 1 || C('pointshop_isuse') != 1){

            showMessage(Language::get('pointshop_unavailable'),urlShop('index','index'),'html','error');

        }

        Tpl::output('index_sign','pointshop');

    }

    /**

     * 获得积分中心会员信息包括会员名、ID、会员头像、会员等级、经验值、等级进度、积分、已领代金券、已兑换礼品、礼品购物车

     */

    public function pointshopMInfo($is_return = false){

        if($_SESSION['is_login'] == '1'){

            $model_member = Model('member');

            if (!$this->member_info){

                //查询会员信息

                $member_infotmp = $model_member->getMemberInfoByID($_SESSION['member_id']);

            } else {

                $member_infotmp = $this->member_info;

            }

            $member_infotmp['member_exppoints'] = intval($member_infotmp['member_exppoints']);



            //当前登录会员等级信息

            $membergrade_info = $model_member->getOneMemberGrade($member_infotmp['member_exppoints'],true);

            $member_info = array_merge($member_infotmp,$membergrade_info);

            Tpl::output('member_info',$member_info);



            //查询已兑换并可以使用的代金券数量

            $model_voucher = Model('voucher');

            $vouchercount = $model_voucher->getCurrentAvailableVoucherCount($_SESSION['member_id']);

            Tpl::output('vouchercount',$vouchercount);



            //购物车兑换商品数

            $pointcart_count = Model('pointcart')->countPointCart($_SESSION['member_id']);

            Tpl::output('pointcart_count',$pointcart_count);



            //查询已兑换商品数(未取消订单)

            $pointordercount = Model('pointorder')->getMemberPointsOrderGoodsCount($_SESSION['member_id']);

            Tpl::output('pointordercount',$pointordercount);

            if ($is_return){

                return array('member_info'=>$member_info,'vouchercount'=>$vouchercount,'pointcart_count'=>$pointcart_count,'pointordercount'=>$pointordercount);

            }

        }

    }

}

