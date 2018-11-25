<?php

/**

 * 商家销售统计

 *

 */






use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');



class seller_order_refundControl extends mobileSellerControl {



    public function __construct(){

        parent::__construct();

    }

    /*

    *商家订单首页

    */

    public function indexOp() {

        

         Tpl::output('web_seo',C('site_name').' - '.'退款记录');

         Tpl::showpage('seller_order_refund');

    }



    /*

    *获取退款记录

    */

    public function get_refundOp(){

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

     

        $refund_list = $model_refund->getRefundList($condition,$page);

        

        if($refund_list){

            $page_count = $model_refund->gettotalpage();  

            $state_array = $model_refund->getRefundStateArray('seller');

            $admin_array = $model_refund->getRefundStateArray('admin');

            foreach ($refund_list as &$value) {

                  $value['seller_zt'] = $state_array[$value['seller_state']];

                  $value['add_time'] = date('Y-m-d H:i:s',$value['add_time']);

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

        $condition['refund_id'] = intval($_GET['refund_id']);

        $refund_list = $model_refund->getRefundList($condition);

        $refund = $refund_list[0];

        Tpl::output('refund',$refund);

        $info['buyer'] = array();

        if(!empty($refund['pic_info'])) {

            $info = unserialize($refund['pic_info']);

        }

        Tpl::output('pic_list',$info['buyer']);

        $model_member = Model('member');

        $member = $model_member->getMemberInfoByID($refund['buyer_id']);

        Tpl::output('member',$member);

        $condition = array();

        $condition['order_id'] = $refund['order_id'];

        $goodlist= $model_refund->getRightOrderList($condition, $refund['order_goods_id']);

        Tpl::output('goodlist',$goodlist);

        $state_array = $model_refund->getRefundStateArray('seller');

        $admin_array = $model_refund->getRefundStateArray('admin');

        Tpl::output('admin_array',$admin_array);

         Tpl::output('state_array',$state_array);

        Tpl::output('web_seo',C('site_name').' - '.'退款记录');

        Tpl::showpage('seller_order_refund_view');

    }



      /**

     * 退款审核页

     *

     */

    public function editOp() {

        $model_refund = Model('refund_return');

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_id'] = intval($_GET['refund_id']);

        $refund_list = $model_refund->getRefundList($condition);

        $refund = $refund_list[0];

        if (chksubmit()) {

           

            $reload = 'index.php?con=seller_order_refund&lock=1';

            if ($refund['order_lock'] == '2') {

                $reload = 'index.php?con=seller_order_refund&lock=2';

            }

            if ($refund['seller_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误

               

               showMessage('参数错误!',$reload,'error');

               

            }

            $order_id = $refund['order_id'];

            $refund_array = array();

            $refund_array['seller_time'] = time();

            $refund_array['seller_state'] = $_POST['seller_state']?2:3;//卖家处理状态:1为待审核,2为同意,3为不同意

            $refund_array['seller_message'] = $_POST['seller_message'];

            if ($refund_array['seller_state'] == '3') {

                $refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成

            } else {

                $refund_array['seller_state'] = '2';

                $refund_array['refund_state'] = '2';

            }

          

            $state = $model_refund->editRefundReturn($condition, $refund_array);

            if ($state) {

                if ($refund_array['seller_state'] == '3' && $refund['order_lock'] == '2') {

                    $model_refund->editOrderUnlock($order_id);//订单解锁

                }

                $this->recordSellerLog('退款处理，退款编号：'.$refund['refund_sn']);



                // 发送买家消息

                $param = array();

                $param['code'] = 'refund_return_notice';

                $param['member_id'] = $refund['buyer_id'];

                $param['param'] = array(

                    'refund_url'=> urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),

                    'refund_sn' => $refund['refund_sn']

                );

                QueueClient::push('sendMemberMsg', $param);



                showMessage('保存成功!',$reload,'succ');

            } else {

                showMessage('保存成功!',$reload,'error');

            }

        }

        Tpl::output('refund',$refund);

        $info['buyer'] = array();

        if(!empty($refund['pic_info'])) {

            $info = unserialize($refund['pic_info']);

        }

        Tpl::output('pic_list',$info['buyer']);

        $model_member = Model('member');

        $member = $model_member->getMemberInfoByID($refund['buyer_id']);

        Tpl::output('member',$member);

        $condition = array();

        $condition['order_id'] = $refund['order_id'];

        $model_refund->getRightOrderList($condition, $refund['order_goods_id']);

        Tpl::showpage('seller_order_refund_edit');

    }



}

