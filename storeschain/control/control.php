<?php
/**
 * 物流自提服务站父类
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class BaseChainControl{
    /**
     * 构造函数
     */
    public function __construct(){
        /**
         * 读取通用、布局的语言包
         */
        Language::read('common');
        /**
         * 设置布局文件内容
         */
        Tpl::setLayout('chain_layout');
        /**
         * SEO
         */
        $this->SEO();
        /**
         * 获取导航
         */
        Tpl::output('nav_list', rkcache('nav',true));
    }
    /**
     * SEO
     */
    protected function SEO() {
        Tpl::output('html_title','门店系统      ' . C('site_name') . '- Powered by shopec');
        Tpl::output('seo_keywords','');
        Tpl::output('seo_description','');
    }
}
/**
 * 操作中心
 * @author Administrator
 *
 */
class BaseChainCenterControl extends BaseChainControl{
    public function __construct() {
        parent::__construct();
        if ($_SESSION['chain_login'] != 1) {
            @header('location: index.php?con=login');die;
        }
    }
}
/**
 * 操作中心
 * @author Administrator
 *
 */
class BaseAccountCenterControl extends BaseChainControl{
    public function __construct() {
        parent::__construct();

        Tpl::setLayout('login_layout');
    }
}
