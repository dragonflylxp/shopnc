<?php
/**
 * 分销-订单管理
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
class distri_orderControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取分销订单列表
     */
    public function indexOp(){
        $payment_list = Model('payment')->getPaymentOpenList();
        $payment_list['wxpay'] = array(
            'payment_code' => 'wxpay',
            'payment_name' => '微信支付'
        );
        Tpl::output('payment_list',$payment_list);
        Tpl::showpage('distri_order.index');
    }

    /**
     * 获取订单xml数据
     */
    public function get_xmlOp(){
        $condition = array();
        $model_dis_order = Model('dis_order');
        $model_order = Model('order');


        $this->_get_condition($condition);

        $sort_fields = array('buyer_name','store_name','order_id','payment_code','order_state','order_amount','order_from','pay_sn','rcb_amount','pd_amount','payment_time','finnshed_time','evaluation_state','refund_amount','buyer_id','store_id');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $page = $_POST['rp'];

        $order_list = $model_dis_order->getDisOrderList($condition,$page,'',$order);

        $data = array();
        $data['now_page'] = $model_dis_order->shownowpage();
        $data['total_num'] = $model_dis_order->gettotalnum();

        foreach ($order_list as $order_id => $order_info) {
            $order_info['if_system_cancel'] = $model_order->getOrderOperateState('system_cancel',$order_info);
            $order_info['if_system_receive_pay'] = $model_order->getOrderOperateState('system_receive_pay',$order_info);
            $order_info['state_desc'] = orderState($order_info);

            //取得订单其它扩展信息
            $model_order->getOrderExtendInfo($order_info);

            $list = array();
            //$list['operation'] = "<a class=\"btn green\" href=\"index.php?con=distri_order&fun=show_order&order_id={$order_info['order_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            $list['operation'] = '--';
            $list['order_sn'] = $order_info['order_sn'].str_replace(array(1,2,3,4), array(null,' [预定]','[门店自提]',' [拼团]'), $order_info['order_type']);
            $list['add_times'] = date('Y-m-d H:i:s',$order_info['add_time']);
            $list['order_amount'] = ncPriceFormat($order_info['order_amount']);
            if ($order_info['shipping_fee']) {
                $list['order_amount'] .= '(含运费'.ncPriceFormat($order_info['shipping_fee']).')';
            }
            $list['order_state'] = $order_info['state_desc'];
            $list['refund_amount'] = ncPriceFormat($order_info['refund_amount']);
            $list['finnshed_time'] = !empty($order_info['finnshed_time']) ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
            $list['store_name'] = $order_info['store_name'];
            $list['buyer_name'] = $order_info['buyer_name'];
            $list['dis_order_amount'] = ncPriceFormat($order_info['dis_order_amount']);

            $data['list'][$order_info['order_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 查看订单
     *
     */
    public function show_orderOp(){
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0 ){
            showMessage(L('miss_order_number'));
        }
        $model_order    = Model('order');
        $order_info = $model_order->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common','store'));

        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }

        //取得订单其它扩展信息
        $model_order->getOrderExtendInfo($order_info);

        //订单变更日志
        $log_list   = $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']));
        Tpl::output('order_log',$log_list);

        //退款退货信息
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['seller_state'] = 2;
        $condition['admin_time'] = array('gt',0);
        $return_list = $model_refund->getReturnList($condition);
        Tpl::output('return_list',$return_list);

        //退款信息
        $refund_list = $model_refund->getRefundList($condition);
        Tpl::output('refund_list',$refund_list);

        //商家信息
        $store_info = Model('store')->getStoreInfo(array('store_id'=>$order_info['store_id']));
        Tpl::output('store_info',$store_info);

        //商家发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $order_info['close_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id'],'log_orderstate'=>ORDER_STATE_CANCEL),'log_id desc');
        }

        //如果订单已支付，取支付日志信息(主要是第三方平台支付单号)
        if ($order_info['order_state'] == ORDER_STATE_PAY) {
            $order_info['pay_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id'],'log_orderstate'=>ORDER_STATE_PAY),'log_id desc');
        }

        Tpl::output('order_info',$order_info);
        Tpl::showpage('order.view');
    }

    /**
     * 导出
     *
     */
    public function export_step1Op(){
        $lang   = Language::getLangContent();

        $condition = array();
        $model_dis_order = Model('dis_order');
        $model_order = Model('order');

        $condition  = array();
        if (preg_match('/^[\d,]+$/', $_GET['order_id'])) {
            $_GET['order_id'] = explode(',', trim($_GET['order_id'], ','));
            $condition['order_id'] = array('in', $_GET['order_id']);
        }
        $this->_get_condition($condition);

        $sort_fields = array('buyer_name','store_name','order_id','payment_code','order_state','order_amount','order_from','pay_sn','rcb_amount','pd_amount','payment_time','finnshed_time','evaluation_state','refund_amount','buyer_id','store_id');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        } else {
            $order = 'order_id desc';
        }

        if (!is_numeric($_GET['curpage'])){
            $count = $model_order->getOrderCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?con=distri_order&fun=index');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_dis_order->getDisOrderList($condition,'',self::EXPORT_SIZE,$order);
                $this->createExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_dis_order->getDisOrderList($condition,'',"{$limit1},{$limit2}",$order);
            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'下单时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'退款金额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单完成时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家账号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单分销金额');
        //data
        foreach ((array)$data as $k=>$order_info){
            $order_info['state_desc'] = orderState($order_info);
            $list = array();
            $list['order_sn'] = $order_info['order_sn'].str_replace(array(1,2,3,4), array(null,' [预定]','[门店自提]',' [拼团]'), $order_info['order_type']);
            $list['add_time'] = date('Y-m-d H:i:s',$order_info['add_time']);
            $list['order_amount'] = ncPriceFormat($order_info['order_amount']);
            if ($order_info['shipping_fee']) {
                $list['order_amount'] .= '(含运费'.ncPriceFormat($order_info['shipping_fee']).')';
            }
            $list['order_state'] = $order_info['state_desc'];
            $list['refund_amount'] = ncPriceFormat($order_info['refund_amount']);
            $list['finnshed_time'] = !empty($order_info['finnshed_time']) ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
            $list['store_name'] = $order_info['store_name'];
            $list['buyer_name'] = $order_info['buyer_name'];
            $list['dis_order_amount'] = ncPriceFormat($order_info['dis_order_amount']);

            $tmp = array();
            $tmp[] = array('data'=>$list['order_sn']);
            $tmp[] = array('data'=>$list['add_time']);
            $tmp[] = array('data'=>$list['order_amount']);
            $tmp[] = array('data'=>$list['order_state']);
            $tmp[] = array('data'=>$list['refund_amount']);
            $tmp[] = array('data'=>$list['finnshed_time']);
            $tmp[] = array('data'=>$list['store_name']);
            $tmp[] = array('data'=>$list['buyer_name']);
            $tmp[] = array('data'=>$list['dis_order_amount']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
        $excel_obj->generateXML('distri_order-'.$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }


    /**
     * 处理搜索条件
     */
    private function _get_condition(& $condition) {
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('order_sn','store_name','buyer_name','pay_sn'))) {
            $condition[$_REQUEST['qtype']] = array('like',"%{$_REQUEST['query']}%");
        }
        if ($_GET['keyword'] != '' && in_array($_GET['keyword_type'],array('order_sn','store_name','buyer_name','pay_sn','shipping_code'))) {
            if ($_GET['jq_query']) {
                $condition[$_GET['keyword_type']] = $_GET['keyword'];
            } else {
                $condition[$_GET['keyword_type']] = array('like',"%{$_GET['keyword']}%");
            }
        }
        if (!in_array($_GET['qtype_time'],array('add_time','payment_time','finnshed_time'))) {
            $_GET['qtype_time'] = null;
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
        if ($_GET['qtype_time'] && ($start_unixtime || $end_unixtime)) {
            $condition[$_GET['qtype_time']] = array('time',array($start_unixtime,$end_unixtime));
        }
        if($_GET['payment_code']) {
            if ($_GET['payment_code'] == 'wxpay') {
                $condition['payment_code'] = array('in',array('wxpay','wx_saoma','wx_jsapi'));
            } elseif($_GET['payment_code'] == 'alipay') {
                $condition['payment_code'] = array('in',array('alipay','ali_native'));
            } else {
                $condition['payment_code'] = $_GET['payment_code'];
            }
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
            $condition['order_state'] = $_GET['order_state'];
        }
        if (!in_array($_GET['query_amount'],array('order_amount','shipping_fee','refund_amount'))) {
            $_GET['query_amount'] = null;
        }
        if (floatval($_GET['query_start_amount']) > 0 && floatval($_GET['query_end_amount']) > 0 && $_GET['query_amount']) {
            $condition[$_GET['query_amount']] = array('between',floatval($_GET['query_start_amount']).','.floatval($_GET['query_end_amount']));
        }
        if(in_array($_GET['order_from'],array('1','2'))){
            $condition['order_from'] = $_GET['order_from'];
        }
    }




}