<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4/004
 * Time: 12:07
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

//以下是定义结算单状态
//默认
define('BILL_STATE_CREATE',1);
//店铺已确认
define('BILL_STATE_STORE_COFIRM',2);
//平台已审核
define('BILL_STATE_SYSTEM_CHECK',3);
//结算完成
define('BILL_STATE_SUCCESS',4);

class member_virtual_billControl extends SystemControl{
	/**
	 * 每次导出订单数量
	 * @var int
	 */
	const EXPORT_SIZE = 1000;	
	
	private $links = array(
			array('url'=>'con=virtual_bill&fun=index','lang'=>'nc_manage'),
	);
	
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 实物结算 会员列表
     */
    public function indexOp(){
        Tpl::showpage('member_virtual_bill.index');
    }
    
    /**
     * 新增会员虚拟结算管理
     */
    public function get_virtual_bill_xmlOp(){
    	$model_bill = Model('virtual_bill');
    	$condition = array();
    	list($condition,$order) = $this->_get_bill_condition($condition);
    	$bill_list = $model_bill->getOrderBillList2($condition,'*',$_POST['rp'],$order);
    	$data = array();
    	$data['now_page'] = $model_bill->shownowpage();
    	$data['total_num'] = $model_bill->gettotalnum();
    	foreach ($bill_list as $bill_info) {
    		$list = array();
//    		if (in_array($bill_info['member_ob_state'],array())) {
//    			$list['operation'] = "<a class=\"btn orange\" href=\"index.php?con=member_virtual_bill&fun=show_virtual_bill&member_ob_id={$bill_info['member_ob_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
//    		} else {
//    			$list['operation'] = "<a class=\"btn green\" href=\"index.php?con=member_virtual_bill&fun=show_virtual_bill&member_ob_id={$bill_info['member_ob_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
//    		}

			$list['operation'] = "--";
			$list['member_ob_id'] = $bill_info['member_ob_id'];       //结算单编号


			$list['member_ob_result_totals'] = ncPriceFormat($bill_info['member_ob_result_totals']);//应结金额
			$list['member_name'] = $bill_info['member_name'];//会员名
			$list['member_ob_member_id'] = $bill_info['member_ob_member_id'];//会员ID
			if($bill_info['member_ob_state'] == 1){
				$list['member_ob_state'] = '未转入余额';
			}
			if($bill_info['member_ob_state'] == 4){
				$list['member_ob_state'] = '已转入余额';
			}


			$list['member_ob_start_date'] = date('Y-m-d',$bill_info['member_ob_start_date']);//开始日期
			$list['member_ob_end_date'] = date('Y-m-d',$bill_info['member_ob_end_date']);//结束日期
    		$data['list'][$bill_info['member_ob_id']] = $list;
    	}
    	exit(Tpl::flexigridXML($data));
    }
    
/*
 * 新增会员虚拟结算详情  
 * */

    public function show_virtual_billOp(){
    	$member_ob_id = intval($_GET['member_ob_id']);
    	if ($member_ob_id <= 0) {
    		showMessage('参数错误','','html','error');
    	}
    	$model_bill = Model('virtual_bill');
    	$bill_info = $model_bill->getOrderBillInfo(array('member_ob_id'=>$member_ob_id));
    	if (!$bill_info){
    		showMessage('参数错误','','html','error');
    	}
    	
    	$order_condition = array();
    	$order_condition['order_state'] = ORDER_STATE_SUCCESS;
    	$order_condition['store_id'] = $bill_info['ob_store_id'];
    	$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
    	$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
    	$start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
    	$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
    	$end_unixtime = $if_end_date ? $end_unixtime+86400-1 : null;
    	if ($if_start_date || $if_end_date) {
    		$order_condition['finnshed_time'] = array('between',"{$start_unixtime},{$end_unixtime}");
    	} else {
    		$order_condition['finnshed_time'] = array('between',"{$bill_info['ob_start_date']},{$bill_info['ob_end_date']}");
    	}
        $sub_tpl_name = 'member_virtual_bill.show.code_list';
    	
    	Tpl::output('tpl_name',$sub_tpl_name);
    	Tpl::output('bill_info',$bill_info);
    	Tpl::showpage('member_virtual_bill.show');
    }
    
   /*审核  */
    public function bill_checkOp(){
    	$member_ob_id = intval($_GET['member_ob_id']);
    	if ($member_ob_id <= 0) {
    		showMessage('参数错误','','html','error');
    	}
    	$model_bill = Model('virtual_bill');
    	$condition = array();
    	$condition['member_ob_id'] = $member_ob_id;
    	$condition['member_ob_state'] = BILL_STATE_STORE_COFIRM;
    	$update = $model_bill->editOrderBill(array('member_ob_state'=>BILL_STATE_SYSTEM_CHECK),$condition);
    	if ($update){
    		$this->log('审核账单,账单号：'.$member_ob_id,1);
    		showMessage('审核成功，账单进入付款环节');
    	}else{
    		$this->log('审核账单，账单号：'.$member_ob_id,0);
    		showMessage('审核失败','','html','error');
    	}
    } 
    
    /**
     * 账单付款
     *
     */
    public function bill_payOp(){
    	$member_ob_id = intval($_GET['member_ob_id']);
    	if ($member_ob_id <= 0) {
    		showMessage('参数错误','','html','error');
    	}
    	$model_bill = Model('virtual_bill');
    	$condition = array();
    	$condition['member_ob_id'] = $member_ob_id;
    	$condition['member_ob_state'] = BILL_STATE_SYSTEM_CHECK;
    	$bill_info = $model_bill->getOrderBillInfo($condition);
    	if (!$bill_info){
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
    		$update = $model_bill->editOrderBill($input,$condition);
    		if ($update){
    			$model_store_cost = Model('store_cost');
    			$cost_condition = array();
    			$cost_condition['cost_store_id'] = $bill_info['ob_store_id'];
    			$cost_condition['cost_state'] = 0;
    			$cost_condition['cost_time'] = array('between',"{$bill_info['member_ob_start_date']},{$bill_info['member_ob_end_date']}");
    			$model_store_cost->editStoreCost(array('cost_state'=>1),$cost_condition);
    
    			// 发送店铺消息
    			$param = array();
    			$param['code'] = 'store_bill_gathering';
    			$param['store_id'] = $bill_info['member_ob_store_id'];
    			$param['param'] = array(
    					'bill_no' => $bill_info['member_ob_id']
    			);
    			QueueClient::push('sendStoreMsg', $param);
    
    			$this->log('账单付款,账单号：'.$member_ob_id,1);
    			showMessage('保存成功','index.php?con=bill');
    		}else{
    			$this->log('账单付款,账单号：'.$member_ob_id,1);
    			showMessage('保存失败','','html','error');
    		}
    	}else{
    		Tpl::showpage('member_virtual_bill.pay');
    	}
    }    
    
    
    /**
     * 打印结算单
     *
     */
    public function bill_printOp(){
    	$ob_id = intval($_GET['member_ob_id']);
    	if ($ob_id <= 0) {
    		showMessage('参数错误','','html','error');
    	}
    	$model_bill = Model('virtual_bill');
    	$condition = array();
    	$condition['member_ob_id'] = $ob_id;
    	$condition['member_ob_state'] = BILL_STATE_SUCCESS;
    	$bill_info = $model_bill->getOrderBillInfo($condition);
    	if (!$bill_info){
    		showMessage('参数错误','','html','error');
    	}
    
    	Tpl::output('bill_info',$bill_info);
    
    	Tpl::showpage('member_virtual_bill.print','null_layout');
    } 
    
    
    /**
     * 导出平台月出账单表
     *
     */
    public function export_billOp(){
    	$model_bill = Model('virtual_bill');
    	$condition = array();
    	if (preg_match('/^[\d,]+$/', $_GET['member_ob_id'])) {
    		$_GET['member_ob_id'] = explode(',',trim($_GET['member_ob_id'],','));
    		$condition['member_ob_id'] = array('in',$_GET['member_ob_id']);
    	}
    	list($condition,$order) = $this->_get_bill_condition($condition);
    
    	if (!is_numeric($_GET['curpage'])){
    		$count = $model_bill->getOrderBillCount($condition);
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
    	$data = $model_bill->getOrderBillList2($condition,'*','','member_ob_id desc',$limit);
    
    	$export_data = array();
    	$export_data[0] = array('账单ID','应结金额','会员名称','会员ID','账单状态','开始日期','结束日期');
		$ob_commis_totals = 0;
		$ob_commis_return_totals = 0;
		$member_ob_result_totals = 0;
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
//    	$count = count($export_data);
//    	$export_data[$count][] = '合计:';
//    	$export_data[$count][] = $ob_commis_totals;
//    	$export_data[$count][] = $ob_commis_return_totals;
//    	$export_data[$count][] = $member_ob_result_totals;
    	$csv = new Csv();
    	$export_data = $csv->charset($export_data,CHARSET,'gbk');
    	$csv->filename = 'virtual_bill';
    	$csv->export($export_data);
    }  
    
    /**
     * 合并相同代码
     */
    private function _get_bill_condition($condition) {
    	if ($_GET['query_year'] && $_GET['query_month']) {
    		$_GET['os_month'] = intval($_GET['query_year'].$_GET['query_month']);
    	} elseif ($_GET['query_year']) {
    		$condition['os_month'] = array('between',$_GET['query_year'].'01,'.$_GET['query_year'].'12');
    	}
    	if (!empty($_GET['os_month'])) {
    		$condition['os_month'] = intval($_GET['os_month']);
    	}
    	if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('member_store_ob_id','member_ob_id','member_ob_member_id'))) {
    		$condition[$_REQUEST['qtype']] = $_REQUEST['query'];
    	}
    	if (is_numeric($_GET["member_ob_state"])) {
    		$condition['member_ob_state'] = intval($_GET["member_ob_state"]);
    	}
    	if (is_numeric($_GET["member_ob_id"])) {
    		$condition['member_ob_id'] = intval($_GET["member_ob_id"]);
    	}
    	if (is_numeric($_GET["member_store_ob_id"])) {
    		$condition['member_store_ob_id'] = intval($_GET["member_store_ob_id"]);
    	}
		//搜索会员ID
    	if (is_numeric($_GET["member_ob_member_id"])){
    		$condition['member_ob_member_id'] =  intval($_GET["member_ob_member_id"]);
    	}

    	$sort_fields = array('member_ob_id','member_ob_store_id','member_ob_member_id');
    	if (in_array($_REQUEST['sortorder'],array('asc','desc')) && in_array($_REQUEST['sortname'],$sort_fields)) {
    		$order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
    	} else {
    		$order = 'member_ob_id desc';
    	}
    	return array($condition,$order);
    }    
    
}
