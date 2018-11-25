<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4/004
 * Time: 12:07
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class member_real_billControl extends SystemControl{

    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 实物结算 会员列表
     */
    public function indexOp()
    {
        Tpl::showpage('member_real_bill.index');
    }

    /**
     * 某店铺某月订单列表
     *
     */
    public function show_member_real_billOp(){
        $member_ob_id = intval($_GET['member_ob_id']);
        if ($member_ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_member_real_bill = Model('member_real_bill');
        $member_real_bill_info = $model_member_real_bill->getMemberRealBillInfo(array('member_ob_id'=>$member_ob_id));
        if (!$member_real_bill_info){
            showMessage('参数错误','','html','error');
        }

        //查询对应的开户行
        $model_member = Model('member');
        $condition_id= array('member_id'=>$member_real_bill_info['member_ob_member_id']);
        $bank_info = $model_member->get_bill_bank_infoOp($condition_id,'bill_type_number,bill_bank_name');
        Tpl::output('bank_info',$bank_info);

        $order_condition = array();
        $order_condition['order_state'] = ORDER_STATE_SUCCESS;
        $order_condition['store_id'] = $member_real_bill_info['ob_store_id'];//店铺ID
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
        $end_unixtime = $if_end_date ? $end_unixtime+86400-1 : null;
        if ($if_start_date || $if_end_date) {
            $order_condition['finnshed_time'] = array('between',"{$start_unixtime},{$end_unixtime}");
        } else {
            $order_condition['finnshed_time'] = array('between',"{$member_real_bill_info['ob_start_date']},{$member_real_bill_info['ob_end_date']}");
        }
        if ($_GET['query_type'] == 'refund') {
            $sub_tpl_name = 'bill_order_bill.show.refund_list';
        } elseif ($_GET['query_type'] == 'cost') {
            $sub_tpl_name = 'bill_order_bill.show.cost_list';
        } elseif ($_GET['query_type'] == 'dis') {
            $sub_tpl_name = 'bill_order_bill.show.dis_list';
        } elseif ($_GET['query_type'] == 'book') {
            $sub_tpl_name = 'bill_order_bill.show.order_book_list';
        } else {
            //订单列表
            $sub_tpl_name = 'member_real_bill_order_bill.show.order_list';
        }
        Tpl::output('tpl_name',$sub_tpl_name);
        Tpl::output('bill_info',$member_real_bill_info);
        Tpl::showpage('member_real_bill.show');
    }

    /*
     * 获取结算列表
     */
    public function get_member_real_bill_xmlOp(){
        //实例化模型
        $model_member_real_bill = Model('member_real_bill');
        $condition = array();
        //处理查询条件
        list($condition,$order) = $this->_get_member_real_bill_condition($condition);
        //获取显示的数据
//        $member_real_bill_list = $model_member_real_bill->getMemberRealBillList($condition,'*',$_POST['rp'],$order);
        $member_real_bill_list = $model_member_real_bill->getMemberRealBillList2($condition,'*',$_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_member_real_bill->shownowpage();
        $data['total_num'] = $model_member_real_bill->gettotalnum();
        //循环处理取得的数据
        foreach ($member_real_bill_list as $member_real_bill_info) {
            $list = array();
         /*   if (in_array($member_real_bill_info['member_ob_state'],array())) {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?con=member_real_bill&fun=show_member_real_bill&member_ob_id={$member_real_bill_info['member_ob_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
            } else {
                $list['operation'] = "<a class=\"btn green\" href=\"index.php?con=member_real_bill&fun=show_member_real_bill&member_ob_id={$member_real_bill_info['member_ob_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }*/

            //ncPriceFormat()函数  处理价格等数据
            $list['operation'] = "--";
            $list['member_ob_id'] = $member_real_bill_info['member_ob_id'];       //结算单编号


            $list['member_ob_result_totals'] = ncPriceFormat($member_real_bill_info['member_ob_result_totals']);//应结金额
            $list['member_name'] = $member_real_bill_info['member_name'];//会员名
            $list['member_ob_member_id'] = $member_real_bill_info['member_ob_member_id'];//会员ID
            if($member_real_bill_info['member_ob_state'] == 1){
                $list['member_ob_state'] = '未转入余额';
            }
            if($member_real_bill_info['member_ob_state'] == 4){
                $list['member_ob_state'] = '已转入余额';
            }


            $list['member_ob_start_date'] = date('Y-m-d',$member_real_bill_info['member_ob_start_date']);//开始日期
            $list['member_ob_end_date'] = date('Y-m-d',$member_real_bill_info['member_ob_end_date']);//结束日期
            $data['list'][$member_real_bill_info['member_ob_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 合并相同代码
     */
    private function _get_member_real_bill_condition($condition) {
        if ($_GET['query_year'] && $_GET['query_month']) {
            $_GET['member_os_month'] = intval($_GET['query_year'].$_GET['query_month']);
        } elseif ($_GET['query_year']) {
            $condition['member_os_month'] = array('between',$_GET['query_year'].'01,'.$_GET['query_year'].'12');
        }
        if (!empty($_GET['member_os_month'])) {
            $condition['member_os_month'] = intval($_GET['member_os_month']);
        }
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('member_ob_id','member_ob_member_id'))) {
            $condition[$_REQUEST['qtype']] = $_REQUEST['query'];
        }
        if (is_numeric($_GET["member_ob_state"])) {
            $condition['member_ob_state'] = intval($_GET["member_ob_state"]);
        }
        if (is_numeric($_GET["member_ob_id"])) {
            $condition['member_ob_id'] = intval($_GET["member_ob_id"]);
        }
        //搜索会员ID
        if (is_numeric($_GET["member_ob_member_id"])) {
            $condition['member_ob_member_id'] = intval($_GET["member_ob_member_id"]);
        }

        if ($_GET['ob_store_name'] != ''){
            if ($_GET['jq_query']) {
                $condition['ob_store_name'] = $_GET['ob_store_name'];
            } else {
                $condition['ob_store_name'] = array('like',"%{$_GET['ob_store_name']}%");
            }
        }
        $sort_fields = array('member_ob_id','ob_start_date','ob_end_date','member_ob_member_id','ob_order_totals','ob_shipping_totals','ob_commis_totals','ob_order_return_totals','ob_commis_return_totals','ob_store_cost_totals','ob_result_totals','ob_create_date','member_ob_state','ob_store_id');
        if (in_array($_REQUEST['sortorder'],array('asc','desc')) && in_array($_REQUEST['sortname'],$sort_fields)) {
            $order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
        } else {
            $order = 'member_ob_id desc';
        }
        return array($condition,$order);
    }

    /**
     * 平台审核 修改状态
     */
    public function member_real_bill_checkOp(){
        $ob_id = intval($_GET['member_ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_member_real_bill = Model('member_real_bill');
        $condition = array();
        $condition['member_ob_id'] = $ob_id;
        $condition['member_ob_state'] = BILL_STATE_STORE_COFIRM;
        $update = $model_member_real_bill->editMemberRealBill(array('member_ob_state'=>BILL_STATE_SYSTEM_CHECK),$condition);
        if ($update){
            $this->log('审核账单,账单号：'.$ob_id,1);
            showMessage('审核成功，账单进入付款环节');
        }else{
            $this->log('审核账单，账单号：'.$ob_id,0);
            showMessage('审核失败','','html','error');
        }
    }

    /**
     * 账单付款
     *
     */
    public function member_real_bill_payOp(){
        $ob_id = intval($_GET['member_ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_member_real_bill = Model('member_real_bill');
        $condition = array();
        $condition['member_ob_id'] = $ob_id;
        $condition['member_ob_state'] = BILL_STATE_SYSTEM_CHECK;
        $member_real_bill_info = $model_member_real_bill->getMemberRealBillInfo($condition);
        if (!$member_real_bill_info){
            showMessage('参数错误','','html','error');
        }
        if (chksubmit()){
            if (!preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['pay_date'])) {
                showMessage('参数错误','','html','error');
            }
            $input = array();
            $input['member_ob_pay_content'] = $_POST['pay_content'];
            $input['member_ob_pay_date'] = strtotime($_POST['pay_date']);
            $input['member_ob_state'] = BILL_STATE_SUCCESS;
            //修改状态
            $update = $model_member_real_bill->editMemberRealBill($input,$condition);
            if ($update){
                $model_store_cost = Model('store_cost');
                $cost_condition = array();
                $cost_condition['cost_store_id'] = $member_real_bill_info['ob_store_id'];
                $cost_condition['cost_state'] = 0;
                $cost_condition['cost_time'] = array('between',"{$member_real_bill_info['ob_start_date']},{$member_real_bill_info['ob_end_date']}");
                $model_store_cost->editStoreCost(array('cost_state'=>1),$cost_condition);
                // 记录日志
                $param = array();
                $param['code'] = 'store_bill_gathering';
                $param['store_id'] = $member_real_bill_info['ob_store_id'];
                $param['param'] = array(
                    'bill_no' => $member_real_bill_info['ob_id']
                );
                QueueClient::push('sendStoreMsg', $param);

                $this->log('账单付款,账单号：'.$ob_id,1);
                showMessage('保存成功','index.php?con=member_real_bill');
            }else{
                $this->log('账单付款,账单号：'.$ob_id,1);
                showMessage('保存失败','','html','error');
            }
        }else{
            Tpl::showpage('member_real_bill.pay');
        }
    }

    /**
     * 打印结算单
     *
     */
    public function member_real_bill_printOp(){
        $ob_id = intval($_GET['member_ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_member_real_bill = Model('member_real_bill');
        $condition = array();
        $condition['member_ob_id'] = $ob_id;
        $condition['member_ob_state'] = BILL_STATE_SUCCESS;
        $member_real_bill_info = $model_member_real_bill->getMemberRealBillInfo($condition);
        if (!$member_real_bill_info){
            showMessage('参数错误','','html','error');
        }

        Tpl::output('bill_info',$member_real_bill_info);

        Tpl::showpage('member_real_bill.print','null_layout');
    }

    /**
     * 导出平台月出账单表
     *
     */
    public function export_billOp(){
        $model_member_real_bill = Model('member_real_bill');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['member_ob_id'])) {
            $_GET['member_ob_id'] = explode(',',trim($_GET['member_ob_id'],','));
            $condition['member_ob_id'] = array('in',$_GET['member_ob_id']);
        }
        list($condition,$order) = $this->_get_member_real_bill_condition($condition);
        if (!is_numeric($_GET['curpage'])){
            $count = $model_member_real_bill->getOrderBillCount($condition);
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
        $data = $model_member_real_bill->getMemberRealBillList2($condition,'*','','member_ob_id desc',$limit);
//        var_dump($data);
//        exit;
        $export_data = array();
        $export_data[0] = array('账单ID','应结金额','会员名称','会员ID','账单状态','开始日期','结束日期');
        if(C('distribute_isuse') == 1) {
            $export_data[0] = array('账单ID','应结金额','会员名称','会员ID','账单状态','开始日期','结束日期');
        }
        $ob_order_totals = 0;
        $ob_shipping_totals = 0;
        $ob_commis_totals = 0;
        $ob_order_return_totals = 0;
        $ob_commis_return_totals = 0;
        $ob_store_cost_totals = 0;
        $ob_dis_pay_amount = 0;
        $ob_result_totals = 0;
        foreach ($data as $k => $v) {
            $export_data[$k+1][] = $v['member_ob_id'];         //账单ID
            $export_data[$k+1][] = $v['member_ob_result_totals'];         //应结金额
            $export_data[$k+1][] = $v['member_name'];         //会员名称
            $export_data[$k+1][] = $v['member_ob_member_id'];         //会员ID
            //账单状态
            if(intval($v['member_ob_state']) == 1){
                $export_data[$k+1][] = '未转入余额';
            }
            if(intval($v['member_ob_state']) == 4){
                $export_data[$k+1][] = '已转入余额';
            }

            $export_data[$k+1][] = date('Y-m-d',$v['member_ob_start_date']);       //开始日期
            $export_data[$k+1][] = date('Y-m-d',$v['member_ob_end_date']);         //结束日期




        }
      /*  $count = count($export_data);
        $export_data[$count][] = '';
        $export_data[$count][] = '';
        $export_data[$count][] = '合计';
        $export_data[$count][] = $ob_commis_totals;
        $export_data[$count][] = $ob_commis_return_totals;
        $export_data[$count][] = $ob_result_totals;*/
        $csv = new Csv();
        $export_data = $csv->charset($export_data,CHARSET,'gbk');
        $csv->filename = 'member_real_bill';
        $csv->export($export_data);
    }
}