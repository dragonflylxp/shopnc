<?php
/**
 * 前台品牌分类
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
class documentControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function agreementOp() {
        $doc = Model('document')->getOneByCode('agreement');
        output_data($doc);
    }
}
