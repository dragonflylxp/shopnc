<?php

/**

 * 佣金 20160906

 */







defined('Inshopec') or exit('Access Invalid!');



class distribution_commisionControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

      

    }
	    public function indexOp() {

 

         Tpl::output('web_seo',C('site_name').' - '.'我的佣金');

         Tpl::showpage('distribution_commision');

    }
	
	
	
	
	
	
}	