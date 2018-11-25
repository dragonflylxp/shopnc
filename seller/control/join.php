<?php

/**

 * 商家入住

 *

 *

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');



class joinControl extends mobileHomeControl {

   public function __construct(){

        parent::__construct();

        Tpl::setDir('seller');

        Tpl::setLayout('seller_layout');

        

        $model_seller = Model('seller');

        $seller_info = $model_seller->getSellerInfo(array('member_id' => $_SESSION['member_id']));

        if(!empty($seller_info)) {

            @header('location: index.php?con=seller_login');

        }



      

    }

    public function indexOp() {

       Tpl::output('web_seo',C('site_name').' - '.'商家入驻');

       Tpl::showpage('joinin');

    }

    // 注册协议

    public function ajax_zcxyOp(){

        $model_document = Model('document');

        $document_info = $model_document->getOneByCode('open_store');

        $doc_content = $document_info['doc_content'];

        @output_data($doc_content);

 

    }



    





   

 

}

