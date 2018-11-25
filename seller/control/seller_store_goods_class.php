<?php

/**

 * 商家店铺商品分类

 *

 */






use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');

class seller_store_goods_classControl extends mobileSellerControl{



    public function __construct() {

        parent::__construct();

    }



    public function indexOp() {

        $this->class_listOp();

    }



    /**

     * 返回商家店铺商品分类列表

     */

    public function class_listOp() {

        $store_goods_class = Model('store_goods_class')->getStoreGoodsClassPlainList($this->store_info['store_id']);

        output_data(array('class_list' => $store_goods_class));

    }

}

