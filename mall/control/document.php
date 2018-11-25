<?php
/**
 * 系统文章
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

class documentControl extends BaseHomeControl {
    public function indexOp(){
        $lang   = Language::getLangContent();
        if($_GET['code'] == ''){
            showMessage($lang['para_error'],'','html','error');//'缺少参数:文章标识'
        }
        $model_doc  = Model('document');
        $doc    = $model_doc->getOneByCode($_GET['code']);
        Tpl::output('doc',$doc);
        /**
         * 分类导航
         */
        $nav_link = array(
            array(
                'title'=>$lang['homepage'],
                'link'=>SHOP_SITE_URL
            ),
            array(
                'title'=>$doc['doc_title']
            )
        );
        Tpl::output('nav_link_list',$nav_link);
        Tpl::showpage('document.index');
    }
}
