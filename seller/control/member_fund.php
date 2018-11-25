<?php

/**

 * 我的资金相关信息

 *

 */





defined('Inshopec') or exit('Access Invalid!');



class member_fundControl extends mobileMemberControl {

    public function __construct(){

        parent::__construct();

    }

    /*

    *我的资金相关信息首页

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'充值卡余额');

         Tpl::showpage('rechargecardlog_list');

    }



     /*

    *账户余额

    */

    public function predepositlog_listOp() {

         Tpl::output('web_seo',C('site_name').' - '.'账户余额');

         Tpl::showpage('predepositlog_list');

    }



    /*

    *充值明细

    */

    public function pdrecharge_listOp() {

         Tpl::output('web_seo',C('site_name').' - '.'充值明细');

         Tpl::showpage('pdrecharge_list');

    }



    /*

    *余额提现

    */

    public function pdcashlistOp() {

         Tpl::output('web_seo',C('site_name').' - '.'余额提现');

         Tpl::showpage('pdcashlist');

    }

    

    /*

    *在线充值

    */

    public function recharge_addOp() {

         Tpl::output('web_seo',C('site_name').' - '.'在线充值');

         Tpl::showpage('recharge_add');

    }



    /*

    *获取充值订单

    */

      

    public function ajax_recharge_orderOp(){

        $pdr_amount = abs(floatval($_POST['pdr_amount']));

        if ($pdr_amount <= 0) {

           output_error('充值金额有误');

        }

        $model_pdr = Model('predeposit');

        $data = array();

        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();

        $data['pdr_member_id'] = $_SESSION['member_id'];

        $data['pdr_member_name'] = $_SESSION['member_name'];

        $data['pdr_amount'] = $pdr_amount;

        $data['pdr_add_time'] = TIMESTAMP;

        $insert = $model_pdr->addPdRecharge($data);

        if($insert){

            output_data(array('pay_sn'=>$pay_sn));

        }

    }



    /*

    *预存款下单支付检验

    */



    public function pd_payOp() {

        $pay_sn = $_POST['pay_sn'];

        if (!preg_match('/^\d{18}$/',$pay_sn)){

            output_error('参数错误');

        }



        //查询支付单信息

        $model_order= Model('predeposit');

        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$this->member_info['member_id']));

        if(empty($pd_info)){

            output_error('订单出错!');

        }

        if (intval($pd_info['pdr_payment_state'])) {

            output_error('您的订单已经支付，请勿重复支付');

        }

        //显示支付接口列表

        $model_payment = Model('payment');

        $condition = array();

        $condition['payment_code'] = array('not in',array('offline','predeposit','wxpay'));

        $condition['payment_state'] = 1;

        $payment_list = $model_payment->getPaymentList($condition);

        if (empty($payment_list)) {

            output_error('暂未找到合适的支付方式');

        }



 

        $pay_info['pay_amount'] = $pd_info['pdr_amount'] ;

        $pay_info['pay_sn'] = $pd_info['pdr_sn'];

        $pay_in["pay_info"]=$pay_info;

        $pay_in["pay_info"]["payment_list"]=$payment_list;

        if(!empty($pay_in) && is_array($pay_in)){

             output_data($pay_in);

        }

       



     

    }





    /**

     * 充值列表

     */

    // public function indexOp(){

    //     $condition = array();

    //     $condition['pdr_member_id'] = $this->member_info['member_id'];

    //     if (!empty($_GET['pdr_sn'])) {

    //         $condition['pdr_sn'] = $_GET['pdr_sn'];

    //     }



    //     $model_pd = Model('predeposit');

    //     $list = $model_pd->getPdRechargeList($condition,20,'*','pdr_id desc');

    //     foreach($list as $key=>$value){

    //         $list[$key]['pdr_add_time_text'] = date('Y-m-d H:i:s',$value['pdr_add_time']);

    //     }

    //     $page_count = $model_pd->gettotalpage();

    //     output_data(array('list' => $list),mobile_page($page_count));

    // }

    /**

     * 预存款日志列表

     */

    public function predepositlogOp(){

        $model_predeposit = Model('predeposit');

        $where = array();

        $where['lg_member_id'] = $this->member_info['member_id'];

        $where['lg_av_amount'] = array('neq',0);

        $list = $model_predeposit->getPdLogList($where, $this->page, '*', 'lg_id desc');

        $page_count = $model_predeposit->gettotalpage();

        if ($list) {

            foreach($list as $k=>$v){

                $v['lg_add_time_text'] = @date('Y-m-d H:i:s',$v['lg_add_time']);

                $list[$k] = $v;

            }

        }

        output_data(array('list' => $list), mobile_page($page_count));

    }

    /**

     * 充值卡余额变更日志

     */

    public function rcblogOp()

    {

        $model_rcb_log = Model('rcb_log');

        $where = array();

        $where['member_id'] = $this->member_info['member_id'];

        $where['available_amount'] = array('neq',0);

        $log_list = $model_rcb_log->getRechargeCardBalanceLogList($where, $this->page, '', 'id desc');

        $page_count = $model_rcb_log->gettotalpage();

        if ($log_list) {

            foreach($log_list as $k=>$v){

                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);

                $log_list[$k] = $v;

            }

        }

        output_data(array('log_list' => $log_list), mobile_page($page_count));

    }

    /**

     * 充值明细

     */

    public function get_pdrechargelistOp(){

        $where = array();

        $where['pdr_member_id'] = $this->member_info['member_id'];

        $model_pd = Model('predeposit');

        $list = $model_pd->getPdRechargeList($where, $this->page,'*','pdr_id desc');

        $page_count = $model_pd->gettotalpage();

        if ($list) {

            foreach($list as $k=>$v){

                $v['pdr_add_time_text'] = @date('Y-m-d H:i:s',$v['pdr_add_time']);

                $v['pdr_payment_state_text'] = $v['pdr_payment_state']==1?'已支付':'未支付';

                $list[$k] = $v;

            }

        }

        output_data(array('list' => $list), mobile_page($page_count));

    }

    /**

     * 提现记录

     */

    public function get_pdcashlistOp(){

        $where = array();

        $where['pdc_member_id'] =  $this->member_info['member_id'];

        $model_pd = Model('predeposit');

        $list = $model_pd->getPdCashList($where, $this->page, '*', 'pdc_id desc');

        $page_count = $model_pd->gettotalpage();

        if ($list) {

            foreach($list as $k=>$v){

                $v['pdc_add_time_text'] = @date('Y-m-d H:i:s',$v['pdc_add_time']);

                $v['pdc_payment_time_text'] = @date('Y-m-d H:i:s',$v['pdc_payment_time']);

                $v['pdc_payment_state_text'] = $v['pdc_payment_state']==1?'已支付':'未支付';

                $list[$k] = $v;

            }

        }

        output_data(array('list' => $list), mobile_page($page_count));

    }

    /**

     * 充值卡充值视图

     */

    public function  rechargecard_addOp(){

         Tpl::output('web_seo',C('site_name').' - '.'充值卡充值');

         Tpl::showpage('rechargecard_add');

    }

    /**

     * 充值卡充值

     */

    public function run_rechargecard_addOp()

    {

        $param = $_POST;

        $rc_sn = trim($param["rc_sn"]);



        if (!$rc_sn) {

            output_error('请输入平台充值卡号');

        }

   //     if(!$this->check()){

			// output_error('验证码错误');

   //     }

        try {

            Model('predeposit')->addRechargeCard($rc_sn, array('member_id'=>$this->member_info['member_id'],'member_name'=>$this->member_info['member_name']));

            output_data('1');

        } catch (Exception $e) {

            output_error($e->getMessage());

        }

    }

    /**

     * 预存款提现记录详细

     */

    public function pdcashinfoOp(){

        $param = $_GET;

        $pdc_id = intval($param["pdc_id"]);

        if ($pdc_id <= 0){

            output_error('参数错误');

        }

        $where = array();

        $where['pdc_member_id'] =  $this->member_info['member_id'];

        $where['pdc_id'] = $pdc_id;

        $info = Model('predeposit')->getPdCashInfo($where);

        if (!$info){

            output_error('参数错误');

        }

        $info['pdc_add_time_text'] = $info['pdc_add_time']?@date('Y-m-d H:i:s',$info['pdc_add_time']):'';

        $info['pdc_payment_time_text'] = $info['pdc_payment_time']?@date('Y-m-d H:i:s',$info['pdc_payment_time']):'';

        $info['pdc_payment_state_text'] = $info['pdc_payment_state']==1?'已支付':'未支付';

        output_data(array('info' => $info));

    }

	

	

	

	

	

	

	

	

	

	

	

	



	/**

     * 我的积分 我的余额

     */

    public function my_assetOp() {

		$point = $this->member_info['member_points'];

		output_data(array('point' => $point));

	}

	protected function getMemberAndGradeInfo($is_return = false){

        $member_info = array();

        //会员详情及会员级别处理

        if($this->member_info['member_id']) {

            $model_member = Model('member');

            $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

            if ($member_info){

                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));

                $member_info = array_merge($member_info,$member_gradeinfo);

                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);

            }

        }

        if ($is_return == true){//返回会员信息

            return $member_info;

        } else {//输出会员信息

            Tpl::output('member_info',$member_info);

        }

    }

	

	/**

     * AJAX验证

     *

     */

	protected function check(){

        if (checkSeccode($_POST['nchash'],$_POST['captcha'])){

            return true;

        }else{

            return false;

        }

    }

}