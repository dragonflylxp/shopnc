<?php
/**
 * 店铺消息接收设置模型
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class store_msg_settingModel extends Model{
    public function __construct() {
        parent::__construct('store_msg_setting');
    }

    /**
     * 店铺消息接收设置列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getStoreMsgSettingList($condition, $field = '*', $key = '', $page = 0, $order = 'smt_code asc') {
        return $this->field($field)->where($condition)->key($key)->order($order)->page($page)->select();
    }

    /**
     * 店铺消息接收设置详细
     * @param array $condition
     * @param string $field
     */
    public function getStoreMsgSettingInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 编辑店铺模板接收设置
     * @param array $insert
     */
    public function addStoreMsgSetting($insert) {
        return $this->pk(array('smt_code', 'store_id'))->insert($insert, true);
    }
}
