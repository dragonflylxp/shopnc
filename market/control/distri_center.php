<?php
/**
 * 分销商会员页面
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

class distri_centerControl extends MemberDistributeControl{
    function __construct()
    {
        parent::__construct();
    }

    public function homeOp(){
        Tpl::showpage('home');
    }
}