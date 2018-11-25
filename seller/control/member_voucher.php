<?php

/**

 * 我的代金券

 */



defined('Inshopec') or exit('Access Invalid!');



class member_voucherControl extends mobileMemberControl {



	public function __construct() {

		parent::__construct();

	}

    /*

    *我的代金券

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'我的代金券');

         Tpl::showpage('voucher_list');

    }

    /**

    * 代金券列表

    */

    public function voucher_listOp() {

		$model_voucher = Model('voucher');

        $voucher_list = $model_voucher->getMemberVoucherList($this->member_info['member_id'], $_POST['voucher_state'], $this->page);

        foreach ($voucher_list as &$value) {

            if($value['voucher_end_date']>time()){

                $value['voucher_end_date'] = date('Y-m-d',$value['voucher_end_date']);

            }else{

                $value['voucher_end_date'] = '已过期';

            }

           

        }

        $page_count = $model_voucher->gettotalpage();



        output_data(array('voucher_list' => $voucher_list), mobile_page($page_count));

    }

 

    /**

    * 领取免费代金券

    */

    public function voucher_freeexOp() {

        $t_id = intval($_POST['tid']);

        if($t_id <= 0){

            output_error('代金券信息错误');

        }

        $model_voucher = Model('voucher');

        //验证是否可领取代金券

        $data = $model_voucher->getCanChangeTemplateInfo($t_id, intval($this->member_info['member_id']), intval($this->member_info['store_id']));

        if ($data['state'] == false){

            output_error($data['msg']);

        }

        try {

            $model_voucher->beginTransaction();

            //添加代金券信息

            $data = $model_voucher->exchangeVoucher($data['info'], $this->member_info['member_id'], $this->member_info['member_name']);

            if ($data['state'] == false) {

                throw new Exception($data['msg']);

            }

            $model_voucher->commit();

            output_data('代金券领取成功');

        } catch (Exception $e) {

            $model_voucher->rollback();

            output_error($e->getMessage());

        }

        

    }

    /*

    *领取密码代金券视图

    */

    public function voucher_pwexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'领取免费代金券');

         Tpl::showpage('voucher_pwex');

    }

    /**

    * 领取密码代金券

    */

    public function get_voucher_pwexOp() {

        if($this->member_info['member_id']){

            if(!$this->check()){

                output_error('验证码错误！');

            }

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array(

                    "input" => $_POST["pwd_code"],

                    "require" => "true",

                    "message" => '请输入代金券卡密'

                )

            );

            $error = $obj_validate->validate();

            if ($error != '') {

                showDialog($error);

            }

            // 查询代金券

            $model_voucher = Model('voucher');

            $where = array();

            $where['voucher_pwd'] = md5($_POST["pwd_code"]);

            $voucher_info = $model_voucher->getVoucherInfo($where);

            if (! $voucher_info) {

                output_error('代金券卡密错误');

            }

            if (intval($_SESSION['store_id']) == $voucher_info['voucher_store_id']) {

                output_error('不能领取自己店铺的代金券');

            }

            if ($voucher_info['voucher_owner_id'] > 0) {

                output_error('该代金券卡密已被使用，不可重复领取');

            }

            $where = array();

            $where['voucher_id'] = $voucher_info['voucher_id'];

            $update_arr = array();

            $update_arr['voucher_owner_id'] = $this->member_info['member_id'];

            $update_arr['voucher_owner_name'] = $this->member_info['member_name'];

            $update_arr['voucher_active_date'] = time();

            $result = $model_voucher->editVoucher($update_arr, $where, $this->member_info['member_id']);

            if ($result) {

                // 更新代金券模板

                $update_arr = array();

                $update_arr['voucher_t_giveout'] = array(

                    'exp',

                    'voucher_t_giveout+1'

                );

                $model_voucher->editVoucherTemplate(array(

                    'voucher_t_id' => $voucher_info['voucher_t_id']

                ), $update_arr);

                output_data('代金券领取成功');

            } else {

                output_error('代金券领取失败');

            }

        }else{

            output_error('请登录！');

        }        

    }



    /**

     * AJAX验证

     *

     */

    protected function check(){

        if (checkSeccode($_POST['codekey'],$_POST['captcha'])){

            return true;

        }else{

            return false;

        }

    }

}

