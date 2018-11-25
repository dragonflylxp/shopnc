<?php

/**

 * 商品

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');

class member_assetControl extends mobileMemberControl{



    public function __construct() {

        parent::__construct();

    }

    public function indexOp(){

        Tpl::output('web_seo',C('site_name').' - '.'我的资产');

        Tpl::showpage('member_asset');

    }

    



  

}

