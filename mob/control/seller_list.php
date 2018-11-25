<?php
/**
 * 商家列表
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

//class seller_listControl extends mobileSellerControl {
class seller_listControl extends mobileHomeControl{

    public function __construct(){
        parent::__construct();
    }

    public function listOp() {
        $model_seller = Model('seller');
        $seller_list = $model_seller->getSellerList($condition, '', 'seller_id asc', 'seller_id,seller_name');//账号列表
        output_data($seller_list);
    }

}
