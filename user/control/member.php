<?php
/**
 * 会员中心——账户概览
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

class memberControl extends BaseMemberControl{

    /**
     * 我的商城
     */
    public function homeOp() {
        $model_consume = Model('consume');
        $consume_list = $model_consume->getConsumeList(array('member_id' => $_SESSION['member_id']), '*', 0, 10);
        Tpl::output('consume_list', $consume_list);
        Tpl::showpage('member_home');
    }
}
