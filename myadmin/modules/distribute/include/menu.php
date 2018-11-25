<?php
/**
 * 菜单
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

$_menu['distribute'] = array(
    'name' => '分销',
    'child' => array(
        array(
            'name' => $lang['nc_config'],
            'child' => array(
                'manage' => '分销设置',
                'web_config' => '首页配置'
            )
        ),
        array(
            'name' => '分销',
            'child' => array(
                'distri_member' => '分销商管理',
                'distri_goods' => '商品管理',
                'distri_order' => '订单管理',
                'distri_bill' => '结算管理',
                'distri_cash' => '提现管理'
            )
        ),
    )
);