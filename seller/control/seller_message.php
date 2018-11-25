<?php

/**

 * 用户登陆

 */






use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');



class seller_messageControl extends mobileSellerControl {



    public function __construct() {

        parent::__construct();

      

    }

    /*

    *用户中心首页

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'消息管理');

         Tpl::showpage('seller_message_index');

    }

    /*

    *用户中心首页

    */

    public function systemOp() {

         Tpl::output('web_seo',C('site_name').' - '.'系统消息');

         Tpl::showpage('seller_message');

    }

    /*

    *获取用户中心信息

    */

    public function ajax_messageOp(){

        // output_data(array('member_info' => $member_info));

        $model_storemsg = Model('store_msg');

        $wheres['store_id'] = $_SESSION['store_id'];

        if (!$_SESSION['seller_is_admin']) {

            $wheres['smt_code'] = array('in', $_SESSION['seller_smt_limits']);

        }

        $article_total = Model()->table('store_msg')->where($wheres)->count();

       

        $page = intval($_GET['p'])-1;

        $pageSize = 10; //每页显示数 

        $totalPage = ceil($article_total/$pageSize); //总页数 

        $startPage = $page*$pageSize;

        $page ="{$startPage},{$pageSize}";

        $where['store_id'] = $_SESSION['store_id'];

        if (!$_SESSION['seller_is_admin']) {

            $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);

        }

    

          $msg_list = $model_storemsg->where($where)->limit($page)->select();

       

        // 整理数据

        if (!empty($msg_list)) {

            foreach ($msg_list as $key => &$val) {

                $msg_list[$key]['sm_readids'] = explode(',', $val['sm_readids']);

                $val['sm_addtime'] = date('Y-m-d h:i:s',$val['sm_addtime']);

            }

        

   

            $arrs = array();

      

            $arrs['status'] = 1;

            $arrs['pages'] = $totalPage;

            $arrs['datas']['nlists'] = $msg_list;

            echo json_encode($arrs);

        }else{

            $arrs['status'] = 0;

            $arrs['datas']['nlists'] = array();

            $arrs['info'] = '没有了,别点了...';

            echo json_encode($arrs);

        }

        

    

    }



  



   

}

