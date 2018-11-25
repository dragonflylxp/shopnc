<?php

/**

 * 商家注销

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');



class seller_albumControl extends mobileSellerControl {



    public function __construct(){

        parent::__construct();

    }



    public function image_uploadOp() {

        $logic_goods = Logic('goods');



        $result =  $logic_goods->uploadGoodsImage(

            $_POST['name'],

            $this->seller_info['store_id'],

            $this->store_grade['sg_album_limit']

        );



        if(!$result['state']) {

            output_error($result['msg']);

        }



        output_data(array('image_name' => $result['data']['name']));

    }



}

