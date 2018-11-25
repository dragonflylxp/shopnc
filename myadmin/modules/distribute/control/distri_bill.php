<?php
/**
 * 分销-结算管理
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class distri_billControl extends SystemControl
{
    const EXPORT_SIZE = 1000;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 分销佣金结算列表
     */
    public function indexOp()
    {
        Tpl::showpage('distri_bill.index');
    }

    /**
     * 获取分销佣金结算xml数据
     */
    public function get_bill_xmlOp()
    {
        $model_bill = Model('dis_bill');
        $condition = array();
        list($condition, $order) = $this->_get_bill_condition($condition);
        $bill_list = $model_bill->getDistriBillList($condition, '*', $_POST['rp'], $order);
        $data = array();
        $data['now_page'] = $model_bill->shownowpage();
        $data['total_num'] = $model_bill->gettotalnum();
        foreach ($bill_list as $bill_info) {
            $list = array();
            $list['operation'] = "--";
            $list['log_id'] = $bill_info['log_id'];
            $list['order_sn'] = $bill_info['order_sn'];
            $list['goods_name'] = $bill_info['goods_name'];
            $list['add_time'] = date('Y-m-d', $bill_info['add_time']);
            $list['pay_goods_amount'] = ncPriceFormat($bill_info['pay_goods_amount']);
            $list['refund_amount'] = ncPriceFormat($bill_info['refund_amount']);
            $list['dis_commis_rate'] = $bill_info['dis_commis_rate'].'%';
            $list['dis_pay_amount'] = ncPriceFormat($bill_info['dis_pay_amount']);
            $list['dis_pay_time'] = date('Y-m-d', $bill_info['dis_pay_time']);
            $list['log_state'] = $bill_info['log_state']?'已结':'未结';
            $list['store_id'] = $bill_info['store_id'];
            $list['dis_member_id'] = $bill_info['dis_member_id'];

            $data['list'][$bill_info['log_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 导出账单表
     *
     */
    public function export_billOp(){
        $model_bill = Model('dis_bill');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['ob_id'])) {
            $_GET['ob_id'] = explode(',',trim($_GET['ob_id'],','));
            $condition['log_id'] = array('in',$_GET['ob_id']);
        }
        list($condition,$order) = $this->_get_bill_condition($condition);

        if (!is_numeric($_GET['curpage'])){
            $count = $model_bill->getDistriBillCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE){
                //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','javascript:history.back(-1)');
                Tpl::showpage('export.excel');
                exit();
            }
            $limit = false;
        }else{
            //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = "{$limit1},{$limit2}";
        }
        $data = $model_bill->getDistriBillList($condition,'*','','log_id desc',$limit);

        $export_data = array();
        $export_data[0] = array('结算编号','订单编号','商品名称','添加时间','支付金额','退款金额','分销佣金比例','分销佣金','结算时间','结算状态','商家ID','分销员ID');

        foreach ($data as $k => $v) {
            $export_data[$k+1][] = $v['log_id'];
            $export_data[$k+1][] = $v['order_sn'];
            $export_data[$k+1][] = $v['goods_name'];
            $export_data[$k+1][] = date('Y-m-d', $v['add_time']);
            $export_data[$k+1][] = ncPriceFormat($v['pay_goods_amount']);
            $export_data[$k+1][] = ncPriceFormat($v['refund_amount']);
            $export_data[$k+1][] = $v['dis_commis_rate'].'%';
            $export_data[$k+1][] = ncPriceFormat($v['dis_pay_amount']);
            $export_data[$k+1][] = date('Y-m-d', $v['dis_pay_time']);
            $export_data[$k+1][] = $v['log_state']?'已结':'未结';
            $export_data[$k+1][] = $v['store_id'];
            $export_data[$k+1][] = $v['dis_member_id'];
        }
        $csv = new Csv();
        $export_data = $csv->charset($export_data,CHARSET,'gbk');
        $csv->filename = 'distri_bill';
        $csv->export($export_data);
    }

    /**
     * 合并相同代码
     */
    private function _get_bill_condition($condition) {
        if ($_REQUEST['query'] != '' && $_REQUEST['qtype'] == 'order_sn') {
            $condition[$_REQUEST['qtype']] = $_REQUEST['query'];
        }else{
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }

        if (is_numeric($_GET["log_state"])) {
            $condition['log_state'] = intval($_GET["log_state"]);
        }
        if (is_numeric($_GET["order_sn"])) {
            $condition['order_sn'] = intval($_GET["order_sn"]);
        }
        if ($_GET['goods_name'] != ''){
            if ($_GET['jq_query']) {
                $condition['goods_name'] = $_GET['goods_name'];
            } else {
                $condition['goods_name'] = array('like',"%{$_GET['goods_name']}%");
            }
        }
        $sort_fields = array('log_id','order_sn','store_id','dis_member_id','goods_commonid','goods_name','add_time','pay_goods_amount','refund_amount','dis_commis_rate','dis_commis_rate','dis_pay_amount','dis_pay_time','log_state');
        if (in_array($_REQUEST['sortorder'],array('asc','desc')) && in_array($_REQUEST['sortname'],$sort_fields)) {
            $order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
        } else {
            $order = 'log_id desc';
        }
        return array($condition,$order);
    }

}