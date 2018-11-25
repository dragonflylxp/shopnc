<?php
/**
 * 手机短信记录
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

class sms_logModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 增加短信记录
     *
     * @param
     * @return int
     */
    public function addSms($log_array) {
        $log_id = $this->table('sms_log')->insert($log_array);
        return $log_id;
    }

    /**
     * 查询单条记录
     *
     * @param
     * @return array
     */
    public function getSmsInfo($condition) {
        if (empty($condition)) {
            return false;
        }
        $result = $this->table('sms_log')->where($condition)->order('log_id desc')->find();
        return $result;
    }

    /**
     * 查询记录
     *
     * @param
     * @return array
     */
    public function getSmsList($condition = array(), $page = '', $limit = '', $order = 'log_id desc') {
        $result = $this->table('sms_log')->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }
    
    /**
     * 取得记录数量
     *
     * @param
     * @return int
     */
    public function getSmsCount($condition) {
        return $this->table('sms_log')->where($condition)->count();
    }
}
