<?php

/**

 * 用户登陆

 */







defined('Inshopec') or exit('Access Invalid!');



class memberControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

      

    }

    /*

    *用户中心首页

    */

    public function indexOp() {

 

         Tpl::output('web_seo',C('site_name').' - '.'用户中心');

         Tpl::showpage('member');

    }



    /*

    *获取用户中心信息

    */

    public function ajax_memberOp(){

        $model_order = Model('order');

        $member_info = array();

        $member_info = $this->getMemberAndGradeInfo(true);

        $member_info['user_name'] = $this->member_info['member_name'];

        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);

        $member_info['point'] = $this->member_info['member_points'];

        $member_info['predepoit'] = $this->member_info['available_predeposit'];

        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];

        $member_info['level_name'] = $this->member_info['level_name'];

        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'NewCount');

        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'SendCount');

        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'EvalCount');

        

        //获得会员升级进度

       

       if ($this->level_name['less_exppoints'] == 0){

            $member_info['tipinfo'] ="已达到最高会员级别，继续加油保持这份荣誉哦！";

            

         } else {

            $member_info['tipinfo'] ="再累积{$this->level_name['less_exppoints']}经验值可升级{$this->level_name['upgrade_name']}";

       } 

        $member_info['exppoints_rate'] = $this->level_name['exppoints_rate'];

       



        $favorites_model = Model('favorites');

        $member_info['favorites_store'] = $favorites_model->getStoreFavoritesCountByStoreId('',$this->member_info['member_id']);//店铺收藏数

        $member_info['favorites_goods'] = $favorites_model->getGoodsFavoritesCountByGoodsId('',$this->member_info['member_id']);//商品收藏数

        output_data(array('member_info' => $member_info));

    }



  



   

}

