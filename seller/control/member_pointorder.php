<?php

/**

 * 会员中心——积分兑换信息

 

 */







defined('Inshopec') or exit('Access Invalid!');



class member_pointorderControl extends mobileMemberControl{

    public function __construct() {

        parent::__construct();

        //读取语言包

        Language::read('member_member_points,member_pointorder');

        //判断系统是否开启积分和积分兑换功能

        if (C('points_isuse') != 1 || C('pointprod_isuse') != 1){

            showDialog(L('member_pointorder_unavailable'),urlShop('member', 'home'),'error');

        }

        Tpl::output('act', 'member_points');

    }

    public function indexOp() {

       Tpl::output('web_seo',C('site_name').' - '.'兑换记录');

       Tpl::showpage('member_pointorder');

    }

    /**

    *订单详情

    */

    public function order_infoOp() {

       Tpl::output('web_seo',C('site_name').' - '.'订单详情');

       Tpl::showpage('member_pointorder_info');

    }

    /*

    *物流跟踪视图

    */

    public function deliverOp(){

         Tpl::output('web_seo',C('site_name').' - '.'物流跟踪');

         Tpl::showpage('member_pointorder_delivery');

    }

    /**

     * 物流跟踪

     */

    public function search_deliverOp(){

     

        $order_id = intval($_POST['order_id']);



        if ($order_id <= 0){

            output_error('订单不存在');

        }

        $model_pointorder = Model('pointorder');

        //查询兑换订单信息

        $where = array();

        $where['point_orderid'] = $order_id;

        $where['point_buyerid'] = $_SESSION['member_id'];

        $order_info = $model_pointorder->getPointOrderInfo($where);

        if (!$order_info){

            output_error('订单不存在');

        }

          //物流公司信息

        if ($order_info['point_shippingcode'] != ''){

            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);

            if ($data['state']){

                $express_info = $data['data']['express_info'];

            }

            $deliver_info = array();

            $deliver_info = $this->_get_express($express_info['e_name'], $order_info['point_shippingcode']);

       

            output_data(array('express_name' => $express_info['e_name'], 'shipping_code' => $order_info['point_shippingcode'], 'deliver_info' => $deliver_info));

        }

    }

        /**

     * 订单详情

     */

    public function get_current_deliverOp(){

         $order_id = intval($_POST['order_id']);



        if ($order_id <= 0){

            output_error('订单不存在');

        }

        $model_pointorder = Model('pointorder');

        //查询兑换订单信息

        $where = array();

        $where['point_orderid'] = $order_id;

        $where['point_buyerid'] = $_SESSION['member_id'];

        $order_info = $model_pointorder->getPointOrderInfo($where);

        if (!$order_info){

            output_error('订单不存在');

        }

          //物流公司信息

        if ($order_info['point_shippingcode'] != ''){

            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);

            if ($data['state']){

                $express_info = $data['data']['express_info'];

            }

             $deliver_info = array();

            $deliver_info = $this->_get_express($express_info['e_name'], $order_info['point_shippingcode']);

      

            $data['deliver_info']['context'] = $e_name;

            $data['deliver_info']['time'] = $deliver_info['0'];

            output_data($data);

           

        }





       

    }

    /**

     * 兑换信息列表

     */

    public function order_listOp() {

        //兑换信息列表

        $where = array();

        $where['point_buyerid'] = $_SESSION['member_id'];

        $state_type = $_POST['state_type'];

        if(!empty($state_type)){

            $where['point_orderstate'] = $state_type;

        }

        $model_pointorder = Model('pointorder');

        $order_list = $model_pointorder->getPointOrderList($where, '*', 10, 0, 'point_orderid desc');

        $order_idarr = array();

        $order_listnew = array();

        if (is_array($order_list) && count($order_list)>0){

            foreach ($order_list as $k => $v){

                $order_listnew[$v['point_orderid']] = $v;

                $order_idarr[] = $v['point_orderid'];

            }

        }



        //查询兑换商品

        if (is_array($order_idarr) && count($order_idarr)>0){

            $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>array('in',$order_idarr)));

            if (is_array($prod_list) && count($prod_list)>0){

                foreach ($prod_list as $v){

                    if (isset($order_listnew[$v['point_orderid']])){

                        $order_listnew[$v['point_orderid']]['prodlist'][] = $v;

                    }

                }

            }

        }

        $list = array();

        foreach ($order_listnew as &$ve) {

            $list[] = $ve;

        }



        $page_count = $model_pointorder->gettotalpage();



        output_data(array('order_group_list' => $list), mobile_page($page_count));

      

    }

    /**

     *  取消兑换

     */

    public function cancel_orderOp(){

        $model_pointorder = Model('pointorder');

        //取消订单

        $data = $model_pointorder->cancelPointOrder($_POST['order_id'],$_SESSION['member_id']);

        if(!$data['state']) {

            output_error($data['msg']);

        } else {

            output_data('1');

        }

    }

    /**

     * 确认收货

     */

    public function receiving_orderOp(){

        $data = Model('pointorder')->receivingPointOrder($_POST['order_id']);

        

        if(!$data['state']) {

            output_error($data['msg']);

        } else {

            output_data('1');

        }

    }

       /**

     * 从第三方取快递信息

     *

     */

    public function _get_express($e_code, $shipping_code){

        $content = Model('express')->get_express($e_code, $shipping_code);        

        if (empty($content)) {

            output_error('物流信息查询失败');

        }

        $output = array();

        foreach ($content as $k=>$v) {

            if ($v['time'] == '') continue;

            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];

        }



        return $output;

    }

    /**

     * 兑换信息详细

     */

    public function get_order_infoOp(){

        $order_id = intval($_GET['order_id']);

        if ($order_id <= 0){

             output_error('订单不存在');

        }

        $model_pointorder = Model('pointorder');

        //查询兑换订单信息

        $where = array();

        $where['point_orderid'] = $order_id;

        $where['point_buyerid'] = $_SESSION['member_id'];

        $order_info = array();

        $order_info = $model_pointorder->getPointOrderInfo($where);

        if (!$order_info){

            output_error('订单不存在');

        }





        //查询兑换订单收货人地址

        $orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));

        $order_info['reciver_name'] = $orderaddress_info['point_truename'];

        $order_info['reciver_phone'] = $orderaddress_info['point_telphone'].','.$orderaddress_info['point_mobphone'];

        $order_info['reciver_addr'] =  $orderaddress_info['point_areainfo'];

        $order_info['point_addtime'] = $order_info['point_addtime'] ? date('Y-m-d H:i:s',$order_info['point_addtime']):'';

        $order_info['point_shippingtime'] = $order_info['point_shippingtime'] ? date('Y-m-d H:i:s',$order_info['point_shippingtime']):'';



        //兑换商品信息

        $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));

        $order_info['prod_list'] = $prod_list;



        output_data(array('order_info'=>$order_info));

       

       

    }

  

}

