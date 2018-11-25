<?php
/**
 * 物流自提服务站首页
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class indexControl extends BaseDeliveryControl{
    public function __construct(){
        parent::__construct();
        @header('location: index.php?con=login');die;
    }
}
