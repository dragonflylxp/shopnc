<?php
/**
 * 一户一码入驻 
 *
 * 包括 商户基础信息登记、商户银行卡信息登记、开通支付平台业务 
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class MerchantControl extends SystemControl {

    /**
     * 商户基础信息登记 
     */
    public function basic_infoOp(){}

    /**
     * 商户银行卡信息登记 
     */
    public function bank_infoOp(){}

    /**
     * 开通支付平台业务 
     */
    public function busi_infoOp(){}
}
