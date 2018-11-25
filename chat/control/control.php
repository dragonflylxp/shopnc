<?php
/**
 * 前台control父类
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class BaseControl {
    public function __construct(){
        Language::read('common');
    }
}
