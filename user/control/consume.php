<?php
/**
 * 消费记录
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

class consumeControl extends BaseMemberControl {
    public function __construct() {
        parent::__construct();
    }
    
    public function indexOp() {
        $model_consume = Model('consume');
        $consume_list = $model_consume->getConsumeList(array('member_id' => $_SESSION['member_id']), '*', 20);
        Tpl::output('show_page', $model_consume->showpage());
        Tpl::output('consume_list', $consume_list);
        Tpl::output('consume_type', $this->type);
        $this->profile_menu('consume', 'consume');
        Tpl::showpage('consume.list');
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'consume':
                $menu_array = array(
                1=>array('menu_key'=>'consume','menu_name'=>'消费记录',   'menu_url'=>'index.php?con=consume'));
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
