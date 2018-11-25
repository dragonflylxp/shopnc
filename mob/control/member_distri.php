<?php
/**
 * 分销管理
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class member_distriControl extends mobileDistributeControl {
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取分销商品列表
     */
    public function distri_goodsOp(){
        $model_goods = Model('dis_goods');
        $condition = array('member_id'=>$this->member_info['member_id']);
        $condition['dis_goods.distri_goods_state'] = 1;
        if(trim($_POST['goods_name']) != ''){
            $condition['goods_common.goods_name|goods_common.goods_jingle'] = array('like', '%' . $_POST['goods_name'] . '%');
        }
        $goods_tmp_list = $model_goods->getDistriGoodsCommonList($condition,'*',$this->page);
        $goods_list = array();
        foreach($goods_tmp_list as $k => $value){
            $tmp = array();
            $tmp['distri_id'] = $value['distri_id'];
            $tmp['goods_commonid'] = $value['goods_commonid'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['goods_price'] = $value['goods_price'];
            $tmp['distri_time'] = $value['distri_time'];
            $tmp['store_name'] = $value['store_name'];
            $tmp['store_id'] = $value['store_id'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);

            $goods_list[$k] = $tmp;
        }
        $page_count = $model_goods->gettotalpage();
        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 删除分销商品
     */
    public function drop_goodsOp(){
        $distri_id = intval($_POST['distri_id']);
        if($distri_id <= 0){
            output_error('参数错误');
        }
        $model_goods = Model('dis_goods');
        $condition = array('distri_id' => $distri_id);
        $condition['member_id'] = $this->member_info['member_id'];
        $stat = $model_goods->delDistriGoods($condition);
        if($stat){
            output_data('1');
        }else{
            output_error('删除失败');
        }
    }

    /**
     * 获取分销订单列表
     */
    public function distri_orderOp(){
        $model_order = Model('dis_order');
        $condition = array('dis_member_id' => $this->member_info['member_id']);
        if(trim($_POST['goods_name'])){
            $condition['order_goods.goods_name'] = array('like', '%' . $_POST['goods_name'] . '%');
        }

        if ($_POST['state_type'] != '') {
            $condition['order_state'] = str_replace(
                array('state_new','state_send','state_noeval'),
                array(ORDER_STATE_NEW,ORDER_STATE_SEND,ORDER_STATE_SUCCESS), $_POST['state_type']);
        }
        if ($_POST['state_type'] == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($_POST['state_type'] == 'state_noeval') {
            $condition['evaluation_state'] = 0;
            $condition['order_state'] = ORDER_STATE_SUCCESS;
        }
        if ($_POST['state_type'] == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        $condition['order_goods.is_dis'] = 1;
        $fields = '*';
        $list = $model_order->getMeberDistriOrderList($condition, $fields, $this->page);
        $order_list = array();

        foreach($list as $k => $value){
            $tmp = array();
            $tmp['goods_id'] = $value['goods_id'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['goods_price'] = $value['goods_price'];
            $tmp['goods_num'] = $value['goods_num'];
            $tmp['goods_pay_price'] = $value['goods_pay_price'];
            $tmp['dis_commis_rate'] = $value['dis_commis_rate'];
            $tmp['add_time'] = $value['add_time'];
            $tmp['store_id'] = $value['store_id'];
            $tmp['store_name'] = $value['store_name'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);
            $tmp['dis_commis_amount'] = $value['goods_pay_price']*$value['dis_commis_rate']*0.01;
            $tmp['order_state'] = $value['order_state'];
            $tmp['order_state_txt'] = orderState($value);

            $order_list[$k] = $tmp;
        }

        $page_count = $model_order->gettotalpage();
        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 获取分销结算列表
     */
    public function distri_billOp(){
        $model_bill = Model('dis_bill');
        $condition = array('dis_member_id' => $this->member_info['member_id']);
        if(trim($_POST['goods_name'])){
            $condition['goods_name'] = array('like', '%' . $_POST['goods_name'] . '%');
        }
        if(is_numeric($_POST['bill_state']) && intval($_POST['bill_state']) >= 0){
            $condition['log_state'] = intval($_POST['bill_state']);
        }
        $fields = '*';
        $list = $model_bill->getDistriBillList($condition, $fields, $this->page);

        $bill_list = array();
        foreach($list as $k => $value){
            $tmp = array();
            $tmp['order_sn'] = $value['order_sn'];
            $tmp['goods_id'] = $value['goods_id'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['pay_goods_amount'] = $value['pay_goods_amount'];
            $tmp['refund_amount'] = $value['refund_amount'];
            $tmp['dis_commis_rate'] = $value['dis_commis_rate'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);
            $tmp['dis_pay_amount'] = $value['dis_pay_amount'];
            $tmp['dis_pay_time'] = $value['dis_pay_time'];
            $tmp['bill_state'] = $value['log_state'];
            $tmp['bill_state_txt'] = str_replace(array(0,1), array('未结算','已结算'), $value['log_state']);

            $bill_list[$k] = $tmp;
        }

        $page_count = $model_bill->gettotalpage();
        output_data(array('bill_list' => $bill_list), mobile_page($page_count));
    }

    /**
     * 获取分销提现列表
     */
    public function distri_cashOp(){
        $condition = array();
        if (preg_match('/^\d+$/',$_POST['sn_search'])) {
            $condition['tradc_sn'] = $_POST['sn_search'];
        }
        if (isset($_POST['paystate_search']) && is_numeric($_POST['paystate_search'])){
            $condition['tradc_payment_state'] = intval($_POST['paystate_search']);
        }
        $condition['tradc_member_id'] = $this->member_info['member_id'];
        $model_tard = Model('dis_trad');
        $list = $model_tard->getDistriTradCashList($condition, '*' , $this->page);

        $cash_list = array();
        foreach($list as $k => $value){
            $tmp = array();
            $tmp['tradc_sn'] = $value['tradc_sn'];
            $tmp['tradc_add_time'] = $value['tradc_add_time'];
            $tmp['tradc_amount'] = $value['tradc_amount'];
            $tmp['tradc_payment_state'] = $value['tradc_payment_state'];
            $tmp['tradc_payment_state_txt'] = str_replace(array('0','1'),array('未支付','已支付'),$value['tradc_payment_state']);
            $tmp['tradc_id'] = $value['tradc_id'];

            $cash_list[$k] = $tmp;
        }

        $page_count = $model_tard->gettotalpage();
        output_data(array('cash_list' => $cash_list,'available_trad'=>$this->member_info['available_distri_trad'],'freeze_trad'=>$this->member_info['freeze_distri_trad']), mobile_page($page_count));
    }

    /**
     * 提现记录详情
     */
    public function cash_infoOp(){
        $tradc_id = intval($_POST['tradc_id']);
        if ($tradc_id <= 0){
            output_error('参数错误');
        }
        $model_tard = Model('dis_trad');
        $condition = array();
        $condition['tradc_member_id'] = $this->member_info['member_id'];
        $condition['tradc_id'] = $tradc_id;
        $info = $model_tard->getDistriTradCashInfo($condition);
        if (empty($info)){
            output_error('记录不存在或已删除');
        }
        output_data($info);
    }

    /**
     * 佣金提现申请
     */
    public function cash_applyOp(){
        $obj_validate = new Validate();
        $tradc_amount = abs(floatval($_POST['cash_amount']));
        $validate_arr[] = array("input"=>$tradc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>'请输入正确的提现金额');
        $validate_arr[] = array("input"=>$_POST["pay_pwd"], "require"=>"true","message"=>'请输入支付密码');
        $obj_validate -> validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $model_tard = Model('dis_trad');

        //验证支付密码
        if (md5($_POST['pay_pwd']+'XMzDdG7D94CKm1IxIWQw6g==') != $this->member_info['member_paypwd']) {
            output_error('支付密码错误');
        }
        //验证金额是否足够
        $available_trad = $this->member_info['available_distri_trad'];

        if (floatval($available_trad) < $tradc_amount){
            output_error('请输入正确的提现金额');
        }
        try {
            $model_tard->beginTransaction();
            $tradc_sn = $model_tard->makeSn();
            $data = array();
            $data['tradc_sn'] = $tradc_sn;
            $data['tradc_member_id'] = $this->member_info['member_id'];
            $data['tradc_member_name'] = $this->member_info['member_name'];
            $data['tradc_amount'] = $tradc_amount;
            $data['tradc_bank_name'] = $this->member_info['bill_bank_name'];
            $data['tradc_bank_no'] = $this->member_info['bill_type_number'];
            $data['tradc_bank_user'] = $this->member_info['bill_user_name'];
            $data['tradc_add_time'] = TIMESTAMP;
            $data['tradc_payment_state'] = 0;
            $insert = $model_tard->addDistriTradCash($data);
            if (!$insert) {
                throw new Exception('提现申请失败');
            }
            //增加冻结分校佣金
            $data = array();
            $data['member_id'] = $this->member_info['member_id'];
            $data['member_name'] = $this->member_info['member_name'];
            $data['amount'] = $tradc_amount;
            $data['order_sn'] = $tradc_sn;
            $model_tard->changeDirtriTrad('cash_apply',$data);
            $model_tard->commit();
            output_data('1');
        } catch (Exception $e) {
            $model_tard->rollback();
            output_error($e->getMessage());
        }
    }

}