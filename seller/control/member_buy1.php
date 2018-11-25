<?php

/**

 * 购买

 *

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');



class member_buyControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

    }



    /**

     * 购物车、直接购买第一步:选择收获地址和配置方式

     */

    public function buy_step1Op() {

        $cart_id = explode(',', $_POST['cart_id']);



        $logic_buy = logic('buy');



        //得到会员等级

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);



        if ($member_info){

            $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));

            $member_discount = $member_gradeinfo['orderdiscount'];

            $member_level = $member_gradeinfo['level'];

        } else {

            $member_discount = $member_level = 0;

        }



        //得到购买数据

        $result = $logic_buy->buyStep1($cart_id, $_POST['ifcart'], $this->member_info['member_id'], $this->member_info['store_id'],null,$member_discount,$member_level);

       

		//print_R($result);

		if(!$result['state']) {

            output_error($result['msg']);

        } else {

            $result = $result['data'];

        }



        //整理数据

        $store_cart_list = array();

		$sum = 0;

        foreach ($result['store_cart_list'] as $key => $value) {

            $store_cart_list[$key]['goods_list'] = $value;

            $store_cart_list[$key]['store_goods_total'] = $result['store_goods_total'][$key];

            if(!empty($result['store_premiums_list'][$key])) {

                $result['store_premiums_list'][$key][0]['premiums'] = true;

                $result['store_premiums_list'][$key][0]['goods_total'] = 0.00;

                $store_cart_list[$key]['goods_list'][] = $result['store_premiums_list'][$key][0];

            }

            $store_cart_list[$key]['store_mansong_rule_list'] = $result['store_mansong_rule_list'][$key];

            $store_cart_list[$key]['store_voucher_list'] = $result['store_voucher_list'][$key];

			$store_cart_list[$key]['store_voucher_info'] = array();

			if($store_cart_list[$key]['store_voucher_list']){

				$store_cart_list[$key]['store_voucher_info'] = array('voucher_price'=>$store_cart_list[$key]['store_voucher_list']['1']['voucher_price']);

			}

            if(!empty($result['cancel_calc_sid_list'][$key])) {

                $store_cart_list[$key]['freight'] = '0';

                $store_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];

            } else {

                $store_cart_list[$key]['freight'] = '1';

            }

            $store_cart_list[$key]['store_name'] = $value[0]['store_name'];

			$sum += $store_cart_list[$key]['store_goods_total'];

        }



        $buy_list = array();

        $buy_list['store_cart_list'] = $store_cart_list;

        $buy_list['freight_hash'] = $result['freight_list'];

        $buy_list['address_info'] = $result['address_info'];

        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];

        $buy_list['vat_hash'] = $result['vat_hash'];

        $buy_list['inv_info'] = $result['inv_info'];

        $buy_list['available_predeposit'] = $result['available_predeposit'];

        $buy_list['available_rc_balance'] = $result['available_rc_balance'];

        if (is_array($result['rpt_list']) && !empty($result['rpt_list'])) {

            foreach ($result['rpt_list'] as $k => $v) {

                unset($result['rpt_list'][$k]['rpacket_id']);

                unset($result['rpt_list'][$k]['rpacket_end_date']);

                unset($result['rpt_list'][$k]['rpacket_owner_id']);

                unset($result['rpt_list'][$k]['rpacket_code']);

            }

        }

        $buy_list['rpt_list'] = $result['rpt_list'] ? $result['rpt_list'] : array();

        $buy_list['zk_list'] = $result['zk_list'];

		$buy_list['order_amount'] = $sum;

		$buy_list['rpt_info'] = '';

		$buy_list['address_api'] = logic('buy')->changeAddr($result['address_info'],$result['address_info']['city_id'],$result['address_info']['area_id'], $this->member_info['member_id']);



		$buy_list['store_final_total_list'] = array('1'=>ncPriceFormat($sum));



		

        output_data($buy_list);

    }



    /**

     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式

     *

     */

    public function buy_step2Op() {

        $param = array();

        $param['ifcart'] = $_POST['ifcart'];

        $param['cart_id'] = explode(',', $_POST['cart_id']);

        $param['address_id'] = $_POST['address_id'];

        $param['vat_hash'] = $_POST['vat_hash'];

        $param['offpay_hash'] = $_POST['offpay_hash'];

        $param['offpay_hash_batch'] = $_POST['offpay_hash_batch'];

        $param['pay_name'] = $_POST['pay_name'];

        $param['invoice_id'] = $_POST['invoice_id'];

        $param['rpt'] = $_POST['rpt'];



        //处理代金券

        $voucher = array();

        $post_voucher = explode(',', $_POST['voucher']);

        if(!empty($post_voucher)) {

            foreach ($post_voucher as $value) {

                list($voucher_t_id, $store_id, $voucher_price) = explode('|', $value);

                $voucher[$store_id] = $value;

            }

        }

        $param['voucher'] = $voucher;



        //手机端暂时不做支付留言，页面内容太多了

        //$param['pay_message'] = json_decode($_POST['pay_message']);

        $param['pd_pay'] = $_POST['pd_pay'];

        $param['rcb_pay'] = $_POST['rcb_pay'];

        $param['password'] = $_POST['password'];

        $param['fcode'] = $_POST['fcode'];

        $param['order_from'] = 2;

        $logic_buy = logic('buy');



        //得到会员等级

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        if ($member_info){

            $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));

            $member_discount = $member_gradeinfo['orderdiscount'];

            $member_level = $member_gradeinfo['level'];

        } else {

            $member_discount = $member_level = 0;

        }

        $result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email'],$member_discount,$member_level);

        if(!$result['state']) {

            output_error($result['msg']);

        }



        output_data(array('pay_sn' => $result['data']['pay_sn']));

    }



    /**

     * 验证密码

     */

    public function check_passwordOp() {

        if(empty($_POST['password'])) {

            output_error('参数错误');

        }



        $model_member = Model('member');



        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        if($member_info['member_paypwd'] == md5($_POST['password'])) {

            output_data('1');

        } else {

            output_error('密码错误');

        }

    }



    /**

     * 更换收货地址

     */

    public function change_addressOp() {

        $logic_buy = Logic('buy');

        if (empty($_POST['city_id'])) {

            $_POST['city_id'] = $_POST['area_id'];

        }

        

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);

        if(!empty($data) && $data['state'] == 'success' ) {

            output_data($data);

        } else {

            output_error('地址修改失败');

        }

    }

	



	/**

     * 支付方式

     */

    public function payOp() {

		$pay_sn = $_POST['pay_sn'];  

		$condition = array();

		$condition['pay_sn'] = $pay_sn;

		$order_info = Model('order')->getOrderInfo($condition);

		$payment_list = Model('mb_payment')->getMbPaymentList();

		

		$pay_info['pay_amount'] = $order_info['order_amount'];

		$pay_info['member_available_pd'] = $this->member_info['available_predeposit'];

		$pay_info['member_available_rcb'] = $this->member_info['available_rc_balance'];



		$pay_info['member_paypwd'] = true;

		if(empty($this->member_info['member_paypwd'])){

			$pay_info['member_paypwd'] = false;		

		}

		

		$pay_info['pay_sn'] = $order_info['pay_sn'];

		$pay_info['payed_amount'] = $order_info['pd_amount'];

		if($pay_info['payed_amount']>'0.00'){

			$pay_info['pay_amount'] = $pay_info['pay_amount']-$pay_info['payed_amount'];

		}



		$pay_in["pay_info"]=$pay_info;

		$pay_in["pay_info"]["payment_list"]=$payment_list;

		output_data($pay_in);

	}



	/**

     * 支付密码确认

     */

    public function check_pd_pwdOp() {

		if($this->member_info['member_paypwd'] != md5($_POST['password'])){

			output_error('支付密码错误');

		}else{

			output_data('OK');

		}

	}

	

}

