 

<?php defined('Inshopec') or exit('Access Invalid!');



class cartControl extends mobileHomeControl {



    public function __construct() {

        parent::__construct();

    }

    



 /*

    *我的购物车

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'我的购物车');

         Tpl::showpage('cart_list');

    }





 }