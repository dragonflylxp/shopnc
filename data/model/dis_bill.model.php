<?php
/**
 * 分销结算
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

class dis_billModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取结算列表
     */
    public function getDistriBillList($condition = array(), $field = '*', $page = 0,$order = 'log_id desc', $limit = 0, $group = ''){
        return $this->table('dis_pay')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->page($page)->select();
    }



    /**
     * 获取结算单数量
     */
    public function getDistriBillCount($condition){
        return $this->table('dis_pay')->where($condition)->count();
    }

    /**
     * 获取结算单详情
     */
    public function getDistriBillInfo($condition, $field = '*', $order = 'log_id desc'){
        return $this->table('dis_pay')->field($field)->where($condition)->find();
    }
}