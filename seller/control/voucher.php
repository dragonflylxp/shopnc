<?php

/**

 * 店铺

 *

 *

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class voucherControl extends mobileHomeControl

{

    public function __construct()

    {

        parent::__construct();

    }





	/**

     * 店铺优惠券列表

     */

    public function voucher_tpl_listOp(){

		$voucher_model = Model('voucher');

		$param = array();

		$param['voucher_t_store_id'] = $_POST["store_id"];

		$param['voucher_t_state'] = 1;

		$model_voucher = Model('voucher');

		$gettype_array = $model_voucher->getVoucherGettypeArray();

		$param['voucher_t_gettype'] = $gettype_array['free']['sign'];

		$voucher_list = $voucher_model->getVoucherTemplateList($param);

		if(!empty($voucher_list)){

			$model_voucher = Model('voucher');

			foreach($voucher_list as $key=>$value){

				$voucher_list[$key]['voucher_t_end_date_text'] = date('Y-m-d',$value['voucher_t_end_date']);

			}

		}



		output_data(array('voucher_list'=>$voucher_list));	

	}



		



}

