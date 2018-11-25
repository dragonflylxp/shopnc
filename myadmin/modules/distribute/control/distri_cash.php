<?php
/**
 * 分销-提现管理
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

class distri_cashControl extends SystemControl{

    const EXPORT_SIZE = 2000;
    function __construct()
    {
        parent::__construct();
    }
    
    public function indexOp(){
        $this->cash_listOp();
    }

    /**
     * 提现列表
     */
    public function cash_listOp(){
        Tpl::showpage('distri_cash.list');
    }

    /**
     * 删除提现记录
     */
    public function cash_delOp(){
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_trad = Model('dis_trad');
            $condition = array();
            $condition['tradc_id'] = $id;
            $condition['tradc_payment_state'] = 0;
            $info = $model_trad->getDistriTradCashInfo($condition);
            if (!$info) {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
            try {
                $model_trad->beginTransaction();
                $result = $model_trad->delDistriTradCash($condition);
                if (!$result) {
                    throw new Exception(Language::get('admin_predeposit_cash_del_fail'));
                }
                //退还冻结的预存款
                $model_member = Model('member');
                $member_info = $model_member->getMemberInfo(array('member_id'=>$info['tradc_member_id']));
                //扣除冻结的预存款
                $admininfo = $this->getAdminInfo();
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $info['tradc_amount'];
                $data['order_sn'] = $info['tradc_sn'];
                $data['admin_name'] = $admininfo['name'];
                $model_trad->changeDirtriTrad('cash_del',$data);
                $model_trad->commit();

                $this->log('佣金提现申请删除[ID:'.$id.']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } catch (Exception $e) {
                $model_trad->rollback();
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 更改提现为支付状态
     */
    public function cash_payOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage('参数错误','index.php?con=distri_cash&fun=cash_list','','error');
        }
        $model_trad = Model('dis_trad');
        $condition = array();
        $condition['tradc_id'] = $id;
        $condition['tradc_payment_state'] = 0;
        $info = $model_trad->getDistriTradCashInfo($condition);
        if (!is_array($info) || count($info)<0){
            showMessage('记录不存在或已付款','index.php?con=distri_cash&fun=cash_list','','error');
        }

        //查询用户信息
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('member_id'=>$info['tradc_member_id']));

        $update = array();
        $admininfo = $this->getAdminInfo();
        $update['tradc_payment_state'] = 1;
        $update['tradc_payment_admin'] = $admininfo['name'];
        $update['tradc_payment_time'] = TIMESTAMP;
        $log_msg = '佣金提现付款完成，提现单号：'.$info['tradc_sn'];

        try {
            $model_trad->beginTransaction();
            $result = $model_trad->updateDistriTradCash($update,$condition);
            if (!$result) {
                throw new Exception('付款失败');
            }
            //扣除冻结的预存款
            $data = array();
            $data['member_id'] = $member_info['member_id'];
            $data['member_name'] = $member_info['member_name'];
            $data['amount'] = $info['tradc_amount'];
            $data['order_sn'] = $info['tradc_sn'];
            $data['admin_name'] = $admininfo['name'];
            $model_trad->changeDirtriTrad('cash_pay',$data);
            $model_trad->commit();
            $this->log($log_msg,1);
            showMessage('付款成功','index.php?con=distri_cash&fun=cash_list');
        } catch (Exception $e) {
            $model_trad->rollback();
            $this->log($log_msg,0);
            showMessage($e->getMessage(),'index.php?con=distri_cash&fun=cash_list','html','error');
        }
    }

    /**
     * 查看提现信息
     */
    public function cash_viewOp(){
        $id = intval($_GET['id']);
        $model_trad = Model('dis_trad');
        $condition = array();
        $condition['tradc_id'] = $id;
        $info = $model_trad->getDistriTradCashInfo($condition);
        Tpl::output('info',$info);
        Tpl::showpage('distri_cash.view', 'null_layout');
    }

    /**
     * 导出预存款提现记录
     *
     */
    public function export_cash_step1Op(){
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['tradc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['member_name'])){
            $condition['tradc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if (!empty($_GET['member_id'])){
            $condition['tradc_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if (!empty($_GET['user_name'])){
            $condition['tradc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }
        if ($_GET['payment_state'] != ''){
            $condition['tradc_payment_state'] = $_GET['payment_state'];
        }
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['tradc_id'] = array('in', $id_array);
        }

        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('tradc_id', 'tradc_sn', 'tradc_member_id', 'tradc_member_name', 'tradc_amount', 'tradc_add_time', 'tradc_bank_name', 'tradc_bank_no'
        ,'tradc_bank_user','tradc_payment_state','tradc_payment_time','tradc_payment_admin'
        );
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        $model_trad = Model('dis_trad');


        if (!is_numeric($_GET['curpage'])){
            $count = $model_trad->getDistriCashCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?con=distri_cash&fun=cash_list');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_trad->getDistriTradCashList($condition,'*','',$order,self::EXPORT_SIZE);

                $cashpaystate = array(0=>'未支付',1=>'已支付');
                foreach ($data as $k=>$v) {
                    $data[$k]['tradc_payment_state'] = $cashpaystate[$v['tradc_payment_state']];
                }
                $this->createCashExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_trad->getDistriTradCashList($condition,'*','',$order,"{$limit1},{$limit2}");
            $cashpaystate = array(0=>'未支付',1=>'已支付');
            foreach ($data as $k=>$v) {
                $data[$k]['tradc_payment_state'] = $cashpaystate[$v['tradc_payment_state']];
            }
            $this->createCashExcel($data);
        }
    }

    /**
     * 生成导出预存款提现excel
     *
     * @param array $data
     */
    private function createCashExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现用户编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现用户名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现金额（元）');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'申请时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收款银行');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收款账号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'开户姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'付款状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'支付时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理员');
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['tradc_id']);
            $tmp[] = array('data'=>$v['tradc_sn']);
            $tmp[] = array('data'=>$v['tradc_member_id']);
            $tmp[] = array('data'=>$v['tradc_member_name']);
            $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['tradc_amount']));
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['tradc_add_time']));
            $tmp[] = array('data'=>$v['tradc_bank_name']);
            $tmp[] = array('data'=>$v['tradc_bank_no']);
            $tmp[] = array('data'=>$v['tradc_bank_user']);
            $tmp[] = array('data'=>$v['tradc_payment_state'] == '0' ? '未支付' : '已支付');
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['tradc_payment_time']));
            $tmp[] = array('data'=>$v['tradc_payment_admin']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('分销佣金提现',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('分销佣金提现',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }


    /**
     * 获取提现列表xml
     */
    public function get_xmlOp(){
        $model_trad = Model('dis_trad');
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['tradc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['member_name'])){
            $condition['tradc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if (!empty($_GET['member_id'])){
            $condition['tradc_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if (!empty($_GET['user_name'])){
            $condition['tradc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }
        if ($_GET['payment_state'] != ''){
            $condition['tradc_payment_state'] = $_GET['payment_state'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('tradc_id', 'tradc_sn', 'tradc_member_id', 'tradc_member_name', 'tradc_amount', 'tradc_add_time', 'tradc_bank_name', 'tradc_bank_no'
        ,'tradc_bank_user','tradc_payment_state','tradc_payment_time','tradc_payment_admin'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $cash_list = $model_trad->getDistriTradCashList($condition,'*',$page,$order);
        $data = array();
        $data['now_page'] = $model_trad->shownowpage();
        $data['total_num'] = $model_trad->gettotalnum();
        foreach ($cash_list as $value) {
            $param = array();
            $param['operation'] = "";
            if ($value['tradc_payment_state'] == 0) {
                $param['operation'] .= "<a class='btn red' href=\"javascript:void(0)\" onclick=\"fg_delete('" . $value['tradc_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }
            $param['operation'] .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('cash_info','查看提现编号“". $value['tradc_sn'] ."”的明细', 'index.php?con=distri_cash&fun=cash_view&id=". $value['tradc_id'] ."', 640)\" ><i class='fa fa-list-alt'></i>查看</a>";
            $param['tradc_id'] = $value['tradc_id'];
            $param['tradc_sn'] = $value['tradc_sn'];
            $param['tradc_member_id'] = $value['tradc_member_id'];
            $param['tradc_member_name'] = "<img src=".getMemberAvatarForID($value['tradc_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['tradc_member_id']).">\")'>" .$value['tradc_member_name'];
            $param['tradc_amount'] = ncPriceFormat($value['tradc_amount']);
            $param['tradc_add_time'] = date('Y-m-d', $value['tradc_add_time']);
            $param['tradc_bank_name'] = $value['tradc_bank_name'];
            $param['tradc_bank_no'] = $value['tradc_bank_no'];
            $param['tradc_bank_user'] = $value['tradc_bank_user'];
            $param['tradc_payment_state'] = $value['tradc_payment_state'] == '0' ? '未支付' : '已支付';
            $param['tradc_payment_time'] = $value['tradc_payment_time'] > 0 ? date('Y-m-d', $value['tradc_payment_time']) : '';
            $param['tradc_payment_admin'] = $value['tradc_payment_admin'];
            $data['list'][$value['tradc_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();

    }
}