<?php
/**
 * Created by PhpStorm.
 * User: suijiaolong
 * Date: 2016/11/11
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class member_real_billControl extends BaseMemberControl{

    public function __construct() {
        parent::__construct();
        Language::read('member_member_index');
    }
    /**
     * 收益列表显示
     */
    public function indexOp(){
        //获取当前用户的id
        $condition = array();
        $condition['member_ob_member_id'] = $_SESSION['member_id'];
        $model_bill = Model('member_real_bill');
        //搜索
         if ($_GET['keyword'] != '') {
             $condition['ob_store_name'] = array('like','%'.$_GET['keyword'].'%');
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['ob_end_date'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['state_type'] == 'state_new') {
            $condition['member_ob_state'] = 1;
        }
        if ($_GET['state_type'] == 'state_check') {
            $condition['member_ob_state'] = array('in',array(2,3));
        }
        if ($_GET['state_type'] == 'state_payout') {

            $condition['member_ob_state'] = 4;
        }

        $bill = $model_bill->getMemberRealBillList($condition);
        self::profile_menu('real_bill','real_bill');
        Tpl::output('member_real_bill',$bill);
        Tpl::output('show_page',$model_bill->showpage());
        Tpl::showpage('member_real_bill.index');
    }
    /**
     * 点击审核改变状态
     * @throws Exception
     */
    public function chageStateOp(){
        $length = count($_POST['id']);
        for($i=0;$i<$length;$i++){
            if(!is_array($_POST['id'])){
                $condition['member_ob_id'] = $_POST['id'];
            }else{
                $condition['member_ob_id'] = $_POST['id'][$i];
            }
            $condition['member_ob_member_id'] = $_SESSION['member_id'];
            $condition['member_ob_state'] = 1;
            $model_bill = Model('member_real_bill');
            $result =$model_bill->getMemberRealBillInfo($condition);
            if($result){
                $data['member_ob_state'] = 4;
                $model_bill->editOrderBill($condition,$data);
                $this->toBalanceOp($condition['member_ob_id'],$result['member_ob_result_totals']);
                echo 1;
            }else{
                throw new Exception('未找到');
            }
        }
    }
    /**
     * 实物 分享人收益转入余额中
     * @param $id 获取当前需要存入余额的收益账单的id
     * @param $amount 充值金额
     * @return multitype
     */
    public function toBalanceOp($id,$amount){
        $condition['member_ob_id'] = $id;
        //存入预存款
        $model_pdr = Model('predeposit');
        try {
            $model_pdr->beginTransaction();
            $data = array();
            $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();//生成充值编号
            $data['pdr_member_id'] = $_SESSION['member_id'];//用户id
            $data['pdr_member_name'] = $_SESSION['member_name'];//用户名字
            $data['pdr_amount'] = $amount;//充值金额
            $data['pdr_payment_code'] = 'fx_pay';//支付方式
            $data['pdr_payment_name'] = '实物分享收益转入';//支付方式
            $data['pdr_payment_state'] = 1;//支付状态 0 未支付 1 已经支付
            $data['pdr_payment_time'] = TIMESTAMP;//支付时间
            $data['pdr_add_time'] = TIMESTAMP;//当前时间
            $insert = $model_pdr->addPdRecharge($data); //存入预存款充值表
            if(!$insert){
                throw new Exception('转入余额失败');
            }
            //变更会员预存款 及预存款变更日志
            $data = array();
            $data['member_id'] = $_SESSION['member_id'];;
            $data['member_name'] = $_SESSION['member_name'];
            $data['amount'] = $amount;
            $data['pdr_sn'] = $pay_sn;
            $insert = $model_pdr->changePd('share',$data);//分享人收益转入余额
            if(!$insert){
                throw new Exception('转入余额失败');
            }
            $model_pdr->commit();
        } catch (Exception $e) {
            $model_pdr->rollback();
            return callback(false,$e->getMessage());
        }
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        /**
         * 读取语言包
         */
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'real_bill':
                $menu_array = array(
                    1=>array('menu_key'=>'real_bill','menu_name'=>'实物结算',   'menu_url'=>'index.php?con=member_real_bill&fun=index'));
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}