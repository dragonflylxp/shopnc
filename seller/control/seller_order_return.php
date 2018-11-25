<?php

/**

 * 商家销售统计

 *

 */






use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');



class seller_order_returnControl extends mobileSellerControl {



    public function __construct(){

        parent::__construct();

    }

    /*

    *商家订单首页

    */

    public function indexOp() {

        

         Tpl::output('web_seo',C('site_name').' - '.'退款记录');

         Tpl::showpage('seller_order_return');

    }



    /*

    *获取退款记录

    */

    public function get_returnOp(){

        $model_refund = Model('refund_return');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $order_lock = intval($_GET['lock']);

        if ($order_lock != 1) {

            $order_lock = 2;

        }

        $_GET['lock'] = $order_lock;

        $condition['order_lock'] = $order_lock;

        $page   = new Page();

        $refund_list = $model_refund->getReturnList($condition,$page);

        

        if($refund_list){

            $page_count = $model_refund->gettotalpage();  

            $state_array = $model_refund->getRefundStateArray('seller');

            $admin_array = $model_refund->getRefundStateArray('admin');

            foreach ($refund_list as &$value) {

                  $value['seller_zt'] = $state_array[$value['seller_state']];

                  $value['add_time'] = date('Y-m-d H:i:s',$value['add_time']);

                  if($value['return_type'] != 2){

                    $value['goods_num'] = '无';

                  }

              

                if($value['seller_state']==2){

                    $value['admin_zt'] = $admin_array[$value['refund_state']]; 

                }else{

                    $value['admin_zt'] = '无'; 

                }   

            }  



            $arrs['status'] = 1;

            $arrs['pages'] = $page_count;

            $arrs['datas']['refund_list'] = $refund_list;

            echo json_encode($arrs);

        }else{

            $arrs['status'] = 0;

            $arrs['datas']['refund_list'] = array();

            $arrs['info'] = '没有了,别点了...';

            echo json_encode($arrs);

        }

        

     

    }



    /*

    *商家退款详情

    */

    public function viewOp() {

         $model_refund = Model('refund_return');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_GET['return_id']);

        $return_list = $model_refund->getReturnList($condition);

        $return = $return_list[0];

        Tpl::output('return',$return);

        $express_list  = rkcache('express',true);

        if ($return['express_id'] > 0 && !empty($return['invoice_no'])) {

            Tpl::output('e_name',$express_list[$return['express_id']]['e_name']);

            Tpl::output('e_code',$express_list[$return['express_id']]['e_code']);

        }

        $info['buyer'] = array();

        if(!empty($return['pic_info'])) {

            $info = unserialize($return['pic_info']);

        }

        Tpl::output('pic_list',$info['buyer']);

        $model_member = Model('member');

        $member = $model_member->getMemberInfoByID($return['buyer_id']);

        Tpl::output('member',$member);

        $condition = array();

        $condition['order_id'] = $return['order_id'];

        $goodlist = $model_refund->getRightOrderList($condition, $return['order_goods_id']);

        Tpl::output('goodlist',$goodlist);

        $state_array = $model_refund->getRefundStateArray('seller');

        $admin_array = $model_refund->getRefundStateArray('admin');

        Tpl::output('admin_array',$admin_array);

         Tpl::output('state_array',$state_array);

        Tpl::output('web_seo',C('site_name').' - '.'退货记录');

        Tpl::showpage('seller_order_return_view');

    }

    /**

     * 退货审核页

     *

     */

    public function editOp() {

        $model_refund = Model('refund_return');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_GET['return_id']);

        $return_list = $model_refund->getReturnList($condition);

        $return = $return_list[0];

        if (chksubmit()) {

            

            $reload = 'index.php?con=seller_order_return&lock=1';

            if ($return['order_lock'] == '2') {

                $reload = 'index.php?con=seller_order_return&lock=2';

            }

            if ($return['seller_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误

                

                showMessage('参数错误!',$reload,'error');

            }

            $order_id = $return['order_id'];

            $_POST['return_type'] = $_POST['return_type']?1:2;

            $refund_array = array();

            $refund_array['seller_time'] = time();

            $refund_array['seller_state'] = $_POST['seller_state']?2:3;//卖家处理状态:1为待审核,2为同意,3为不同意

            $refund_array['seller_message'] = $_POST['seller_message'];



            if ($refund_array['seller_state'] == '2' && empty($_POST['return_type'])) {

                $refund_array['return_type'] = '2';//退货类型:1为不用退货,2为需要退货

            } elseif ($refund_array['seller_state'] == '3') {

                $refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成

            } else {

                $refund_array['seller_state'] = '2';

                $refund_array['refund_state'] = '2';

                $refund_array['return_type'] = '1';//选择弃货

            }

         

            $state = $model_refund->editRefundReturn($condition, $refund_array);

            if ($state) {

                if ($refund_array['seller_state'] == '3' && $return['order_lock'] == '2') {

                    $model_refund->editOrderUnlock($order_id);//订单解锁

                }

                $this->recordSellerLog('退货处理，退货编号：'.$return['refund_sn']);



                // 发送买家消息

                $param = array();

                $param['code'] = 'refund_return_notice';

                $param['member_id'] = $return['buyer_id'];

                $param['param'] = array(

                    'refund_url' => urlShop('member_return', 'view', array('return_id' => $return['refund_id'])),

                    'refund_sn' => $return['refund_sn']

                );

                QueueClient::push('sendMemberMsg', $param);

                showMessage('保存成功!',$reload,'succ');

            } else {

                showMessage('保存失败!',$reload,'error');

            }

        }

        Tpl::output('return',$return);

        $info['buyer'] = array();

        if(!empty($return['pic_info'])) {

            $info = unserialize($return['pic_info']);

        }

        Tpl::output('pic_list',$info['buyer']);

        $model_member = Model('member');

        $member = $model_member->getMemberInfoByID($return['buyer_id']);

        Tpl::output('member',$member);

        $condition = array();

        $condition['order_id'] = $return['order_id'];

        $model_refund->getRightOrderList($condition, $return['order_goods_id']);

        Tpl::showpage('seller_order_return_edit');

    }



    /**

     * 物流跟踪

     */

    public function search_deliverOp(){

         $model_refund = Model('refund_return');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_POST['return_id']);

        $return_list = $model_refund->getReturnList($condition);

   

        $return = $return_list[0];



        if (!$return) {

            output_error('订单不存在');

        }



        $express = rkcache('express',true);

        $e_code = $express_list[$return['express_id']]['e_code'];

        $e_name = $express_list[$return['express_id']]['e_name'];

        $deliver_info = array();

        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);

        output_data(array('express_name' => $e_name, 'shipping_code' => $order_info['shipping_code'], 'deliver_info' => $deliver_info));

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

     * 收货

     *

     */

    public function receiveOp() {

        $model_refund = Model('refund_return');

        $model_trade = Model('trade');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_POST['return_id']);

        $return_list = $model_refund->getReturnList($condition);

        $return = $return_list[0];

        $return_delay = $model_trade->getMaxDay('return_delay');//发货默认5天后才能选择没收到

        $delay_time = time()-$return['delay_time']-60*60*24*$return_delay;

 

        if (chksubmit()) {

            if ($return['seller_state'] != '2' || $return['goods_state'] != '2') {//检查状态,防止页面刷新不及时造成数据错误

                output_error('参数错误!');

            }

            $refund_array = array();

            if ($_POST['return_type'] == '3' && $delay_time > 0) {

                $refund_array['goods_state'] = '3';

            } else {

                $refund_array['receive_time'] = time();

                $refund_array['receive_message'] = '确认收货完成';

                $refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成

                $refund_array['goods_state'] = '4';

            }

            $state = $model_refund->editRefundReturn($condition, $refund_array);

            if ($state) {

                $this->recordSellerLog('退货确认收货，退货编号：'.$return['refund_sn']);



                // 发送买家消息

                $param = array();

                $param['code'] = 'refund_return_notice';

                $param['member_id'] = $return['buyer_id'];

                $param['param'] = array(

                    'refund_url' => urlShop('member_return', 'view', array('return_id' => $return['refund_id'])),

                    'refund_sn' => $return['refund_sn']

                );

                QueueClient::push('sendMemberMsg', $param);



                  output_data('保存成功!');

            } else {

                 output_error('保存失败!');

            }

        }

   

    

    }



    /**

    *收货信息查询

    */

    public function get_receiveOp(){

        $model_refund = Model('refund_return');

        $model_trade = Model('trade');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_POST['return_id']);

        $return_list = $model_refund->getReturnList($condition);

        $return = $return_list[0];

        $return_delay = $model_trade->getMaxDay('return_delay');//发货默认5天后才能选择没收到

        $delay_time = time()-$return['delay_time']-60*60*24*$return_delay;

        $express_list  = rkcache('express',true);

        if ($return['express_id'] > 0 && !empty($return['invoice_no'])) {

            $e_name = $express_list[$return['express_id']]['e_name'];

        }

        $arr['delay_time'] = date("Y-m-d H:i:s",$return['delay_time']);

        $arr['e_express'] = $e_name.','.$return['invoice_no'];

        $arr['return_delay']= $return_delay;

        $arr['return_confirm'] = $model_trade->getMaxDay('return_confirm');

        output_data($arr);



    }



}

